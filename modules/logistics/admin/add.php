<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */
		
 
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// LẤY DANH SÁCH KHÁCH HÀNG QUA SỐ ĐIỆN THOẠI customer_phone
if($nv_Request->isset_request('customer_phone', 'post'))
{
	$customer_phone = $nv_Request->get_title('customer_phone','post', '');
	$customer_userid = $nv_Request->get_int('customer_userid','post',0);
	
	if(!empty($customer_phone) and $customer_userid > 0)
	{
		$list_customer = $db->query("SELECT * FROM ".$db_config['prefix'] . "_" . $module_data . "_customer WHERE status = 1 AND userid = ". $customer_userid ." AND phone like '%". $customer_phone ."%'")->fetchAll();
		
		if(!empty($list_customer))
		{
			$xtpl = new XTemplate( 'result_ajax.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
			$xtpl->assign( 'LANG', $lang_module );
			
			foreach($list_customer as $cus)
			{
				$xtpl->assign( 'cus', $cus );
				$xtpl->parse( 'main.loop' );
			}
			$xtpl->parse( 'main' );
			$contents = $xtpl->text( 'main' );

			echo $contents;
			
		}
	}
	die;
	
}

if($nv_Request->isset_request('id_service_a', 'post'))
{
	$id_service = $nv_Request->get_int('id_service_a','post', 0);
	$id_document = $nv_Request->get_int('id_document_a','post', 0);
	$send_city = $nv_Request->get_int('send_city_a','post', 0);
	$send_district = $nv_Request->get_int('send_district_a','post', 0);
	$send_wards = $nv_Request->get_int('send_wards_a','post', 0);
	$weight_document = $nv_Request->get_float('weight_document_a','post', 0);
	//$surcharge = $nv_Request->get_title('surcharge_a','post', '');
	$surcharge = array_unique($nv_Request->get_typed_array('surcharge_a', 'post', 'int', array()));
	
	$price = array();
	$price['gia_tam_thu'] = 0;
	$price['phu_thu'] = 0;
		
	if($id_service > 0 and $id_document > 0 and $send_city > 0 and $send_district > 0 and $weight_document > 0)
	{
		$id_khuvuc = 0;
		$flag = false;
		
		// LẤY KHU VỰC RA
		if($send_wards > 0)
		{
			
			$list_kv_tam = $db->query("SELECT id_zone, district, wards FROM ".$db_config['prefix'] . "_" . $module_data . "_zone_address WHERE status = 1 AND city =".$send_city." AND district like '%".$send_district."%' AND wards like '%". $send_wards ."%'")->fetchAll();
			
			// KIỂM TRA QUẬN HUYỆN CÓ TỒN TẠI TRONG DANH SÁCH QUẬN KHÔNG
			foreach($list_kv_tam as $kv_tam)
			{
				$mang_tam = explode(',',$kv_tam['district']);
				
				foreach($mang_tam as $tam)
				{
					if($tam == $send_district)
						$flag = true;
				}
			}
			
			if($flag)
			{
				// KIỂM TRA XÃ PHƯỜNG CÓ TỒN TẠI TRONG DANH SÁCH XÃ PHƯỜNG KHÔNG
				foreach($list_kv_tam as $kv_tam)
				{
					$mang_tam = explode(',',$kv_tam['wards']);
					foreach($mang_tam as $tam)
					{
						if($tam == $send_wards)
						{
							$flag = true;
							$id_khuvuc = $kv_tam['id_zone'];
						}
					}
				}
			
			}
		}
		
		if($id_khuvuc == 0)
		{
			$list_kv_tam = $db->query("SELECT id_zone, district FROM ".$db_config['prefix'] . "_" . $module_data . "_zone_address WHERE status = 1 AND city =".$send_city." AND district like '%".$send_district."%' AND wards like ''")->fetchAll();
			
			// KIỂM TRA QUẬN HUYỆN CÓ TỒN TẠI TRONG DANH SÁCH QUẬN KHÔNG
			foreach($list_kv_tam as $kv_tam)
			{
				$mang_tam = explode(',',$kv_tam['district']);
				
				foreach($mang_tam as $tam)
				{
					if($tam == $send_district)
						$id_khuvuc = $kv_tam['id_zone'];
				}
			}
		}
		
		
		
		if($id_khuvuc > 0)
		{
			$price_json = $db->query('SELECT price FROM '.$db_config['prefix'] . '_' . $module_data . '_price WHERE id_document ='.$id_document.' AND id_service ='.$id_service.' AND id_zone ='.$id_khuvuc)->fetchColumn();
			
			$array_data = json_decode($price_json,true);
			$price['array_data'] = $array_data;
			
			foreach($array_data as $row)
			{
				if(!empty($row['quantity']))
				{
					$mang_khoiluong = explode('-',$row['quantity']);
					if($weight_document >= $mang_khoiluong[0] and $weight_document <= $mang_khoiluong[1])
					{
						$price['gia_tam_thu'] = $row['price'];
					}
					
				}
			}
			
			// GIÁ NẰM NGOÀI KHUNG 
			if($price['gia_tam_thu'] == 0 and !empty($array_data))
			{
			
				$dem_mang_data = count($array_data) - 1;
				if($dem_mang_data > 0)
				{
					$khoi_luong_cuoi = explode('-',$array_data[$dem_mang_data - 1]['quantity']);
					
					if($khoi_luong_cuoi[0] > 0 and $khoi_luong_cuoi[1] > 0)
					{
						$giatang = explode('-',$array_data[$dem_mang_data]['quantity']);
						
						if($giatang[0] > 0 and empty($giatang[1]))
						{
							// TÍNH GIÁ CƯỚC
							$price['gia_tam_thu'] = $array_data[$dem_mang_data - 1]['price'] + (($weight_document - $khoi_luong_cuoi[1])/$giatang[0] * $array_data[$dem_mang_data]['price']) ;
							
							$price['aaaaaaaaaa'] = $giatang[0];
							$price['bbbbbbbbbb'] = $giatang[1];
						
						}
					}
				}
			}
		}
	}
	
	if(!empty($surcharge))
	{
		// print_r($surcharge);die;
		foreach($surcharge as $phuthu)
		{
			$gia_phuthu = $db->query('SELECT price FROM '.$db_config['prefix'] . '_' . $module_data . '_surcharge WHERE status = 1 AND id ='.$phuthu)->fetchColumn();
			$price['phu_thu'] = $price['phu_thu'] + $gia_phuthu;
		}
	}
	
	
	$myJSON = json_encode($price);

	echo $myJSON;
	die;
	
	
}


if($nv_Request->isset_request('id_store_ajax', 'post'))
{
	$id_store = $nv_Request->get_int('id_store_ajax','post', 0);
	$tt_store = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_store WHERE status = 1 AND id ='.$id_store)->fetch();
	
	
	if($tt_store['city'] > 0)
	{
		$tinhthanh = $db->query('SELECT title, type FROM tms_location_province WHERE status = 1 AND provinceid ='.$tt_store['city'])->fetch();
		$tinhthanh_k = $tinhthanh['type'] . ' ' . $tinhthanh['title'];
	}
	
	
	if($tt_store['district'] > 0)
	{
		$quanhuyen = $db->query('SELECT title, type FROM tms_location_district WHERE status = 1 AND districtid ='.$tt_store['district'])->fetch();
		$quanhuyen_k = $quanhuyen['type'] . ' ' . $quanhuyen['title'];
	}
	
	if($tt_store['wards'] > 0)
	{
		$wards = $db->query('SELECT wardid, title ,type FROM tms_location_ward WHERE wardid = '. $tt_store['wards'] .' and status = 1')->fetch();
		$wards_k = $wards['type'] . ' ' . $wards['title'];
	}
	
	
	$tt_store['dia_chi_day_du'] = $tt_store['address'] . ' ' . $wards_k . ' ' . $quanhuyen_k . ' ' . $tinhthanh_k;
	
	$myJSON = json_encode($tt_store);

	echo $myJSON;
	die;
}

if($nv_Request->isset_request('userid_ajax', 'get'))
{
	$userid = $nv_Request->get_int('userid_ajax','get', 0);
	if($userid > 0)
	{
		$list_store = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_store WHERE status = 1 AND userid ='. $userid .' ORDER BY id ASC')->fetchAll();
		$html = '<option value=0>-- Chọn kho --</option>';
					foreach($list_store as $l)
					{
						$html .= '<option value='.$l['id'].'>'. $l['title'].'</option>';
					}
		print $html;die;
	}

}

if($nv_Request->isset_request('userid_ajax_admin', 'get'))
{
	$userid = $nv_Request->get_int('userid_ajax_admin','get', 0);
	if($userid > 0)
	{
		// TẠO MÃ BILL
					
		$stt_lonnhat = $db->query('SELECT bill FROM '.$db_config['prefix'] . '_' . $module_data . '_rows WHERE userid_add ='.$userid.' ORDER BY id DESC')->fetchColumn();
					
		if($stt_lonnhat == 0)
			$bill = sprintf(strtoupper($row['userid_add'])."%'06d", ($stt_lonnhat+1));
		else
			$bill =sprintf("%'07d", ($stt_lonnhat+1));
			
		print $bill;die;
	}

}

// CHECK BILL

if($nv_Request->isset_request('check_bill', 'get'))
{
	$bill = $nv_Request->get_title('check_bill','get', '');
	$id_bill = $nv_Request->get_int('id_bill','get', 0);
	if(!empty($bill))
	{
		
		if($id_bill > 0)
		{
			$count = $db->query("SELECT count(id) FROM ".$db_config['prefix'] . "_" . $module_data . "_rows WHERE id =". $id_bill ." AND bill like '". $bill ."' ")->fetchColumn();	
		}
		else
		{
			
			$count = $db->query("SELECT count(id) FROM ".$db_config['prefix'] . "_" . $module_data . "_rows WHERE bill like '". $bill ."' ")->fetchColumn();	
		}
		
		if($count > 0)
		{
			print($lang_module['exits_bill']);
		}
		die;
	}

}



if($nv_Request->isset_request('id_tinhthanh', 'get'))
{
	$id_tinhthanh = $nv_Request->get_int('id_tinhthanh','get', 0);
	if($id_tinhthanh > 0)
	{
		$list_quan = $db->query('SELECT * FROM tms_location_district WHERE status = 1 and provinceid = '. $id_tinhthanh .' ORDER BY weight ASC')->fetchAll();
		$html = '<option value=0>-- Chọn quận huyện --</option>';
					foreach($list_quan as $l)
					{
						$html .= '<option value='.$l['districtid'].'>'.$l['type'] . ' '. $l['title'].'</option>';
					}
		print $html;die;
	}

}

if($nv_Request->isset_request('id_quanhuyen', 'get'))
{
	$id_quanhuyen = $nv_Request->get_int('id_quanhuyen','get', 0);
	if($id_quanhuyen > 0)
	{//print($id_quanhuyen);die;
		$list_quan = $db->query('SELECT * FROM tms_location_ward WHERE status = 1 and districtid = '. $id_quanhuyen .' ORDER BY title ASC')->fetchAll();
		$html = '<option value=0>-- Chọn xã phường --</option>';
					foreach($list_quan as $l)
					{
						$html .= '<option value='.$l['wardid'].'>'.$l['type'] . ' '. $l['title'].'</option>';
					}
		print $html;die;
	}

}

//change status
if( $nv_Request->isset_request( 'change_status', 'post, get' ) )
{
	$id = $nv_Request->get_int( 'id', 'post, get', 0 );
	$content = 'NO_' . $id;

	$query = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( isset( $row['status'] ) )
	{
		$status = ( $row['status'] ) ? 0 : 1;
		$query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET status=' . intval( $status ) . ' WHERE id=' . $id;
		$db->query( $query );
		$content = 'OK_' . $id;
	}
	$nv_Cache->delMod( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $id;
	if( $new_vid > 0 )
	{
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET weight=' . $new_vid . ' WHERE id=' . $id;
		$db->query( $sql );
		$content = 'OK_' . $id;
	}
	$nv_Cache->delMod( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
if ( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ))
{
	$id = $nv_Request->get_int( 'delete_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$weight=0;
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id =' . $db->quote( $id );
		$result = $db->query( $sql );
		list( $weight) = $result->fetch( 3 );
		
		$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows  WHERE id = ' . $db->quote( $id ) );
		if( $weight > 0)
		{
			$sql = 'SELECT id, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while(list( $id, $weight) = $result->fetch( 3 ))
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET weight=' . $weight . ' WHERE id=' . intval( $id ));
			}
		}
		$nv_Cache->delMod( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
	
	
	
$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['id_store'] = $nv_Request->get_int( 'id_store', 'post', 0 );
	$row['bill'] = $nv_Request->get_title( 'bill', 'post', '' );
	$row['send_name'] = $nv_Request->get_title( 'send_name', 'post', '' );
	$row['send_phone'] = $nv_Request->get_title( 'send_phone', 'post', '' );
	$row['send_address'] = $nv_Request->get_title( 'send_address', 'post', '' );
	$row['send_city'] = $nv_Request->get_int( 'send_city', 'post', 0 );
	$row['send_district'] = $nv_Request->get_int( 'send_district', 'post', 0 );
	$row['send_wards'] = $nv_Request->get_int( 'send_wards', 'post', 0 );
	$row['receive_name'] = $nv_Request->get_title( 'receive_name', 'post', '' );
	$row['receive_phone'] = $nv_Request->get_title( 'receive_phone', 'post', '' );
	$row['receive_address'] = $nv_Request->get_title( 'receive_address', 'post', '' );
	$row['id_document'] = $nv_Request->get_int( 'id_document', 'post', 0 );
	$row['document_name'] = $nv_Request->get_title( 'document_name', 'post', '' );
	$row['amount'] = $nv_Request->get_int( 'amount', 'post', 0 );
	$row['value_goods'] = $nv_Request->get_title( 'value_goods', 'post', '' );
	$row['weight_document'] = $nv_Request->get_title( 'weight_document', 'post', '' );
	$row['long_document'] = $nv_Request->get_title( 'long_document', 'post', '' );
	$row['wide'] = $nv_Request->get_title( 'wide', 'post', '' );
	$row['height'] = $nv_Request->get_title( 'height', 'post', '' );
	$row['id_service'] = $nv_Request->get_int( 'id_service', 'post', 0 );
	//$row['id_surcharge'] = $nv_Request->get_title( 'id_surcharge', 'post', '' );
	$row['id_surcharge'] = array_unique($nv_Request->get_typed_array('surcharge', 'post', 'int', array()));
//	print_r($row['id_surcharge']);die;
	$row['id_surcharge'] = implode(',',$row['id_surcharge']);
	$row['other_requirements'] = $nv_Request->get_title( 'other_requirements', 'post', '' );
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'delivery_date', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['delivery_date'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['delivery_date'] = 0;
	}
	$row['delivery_time'] = $nv_Request->get_title( 'delivery_time', 'post', '' );
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'received_date', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['received_date'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['received_date'] = 0;
	}
	$row['received_time'] = $nv_Request->get_title( 'received_time', 'post', '' );
	$row['pay'] = $nv_Request->get_int( 'pay', 'post', 0 );
	$row['money_collection'] = $nv_Request->get_title( 'money_collection', 'post', '' );
	$row['service_charge'] = $nv_Request->get_title( 'service_charge', 'post,get', 0 );
	$row['pays'] = $nv_Request->get_title( 'pays', 'post,get', 0 );
	$row['charge_for_collection'] = $nv_Request->get_title( 'charge_for_collection', 'post', '' );
	$row['total_charge'] = $nv_Request->get_title( 'total_charge', 'post', '' );
	$row['vat'] = $nv_Request->get_title( 'vat', 'post', '' );
	$row['total_money'] = $nv_Request->get_title( 'total_money', 'post', '' );
	$row['total_receivable'] = $nv_Request->get_title( 'total_receivable', 'post', '' );
	$row['seller_payments'] = $nv_Request->get_title( 'seller_payments', 'post', '' );
	
	$row['userid_add'] = $nv_Request->get_int( 'userid', 'post', 0 );
	
	
	$row['value_goods'] = floatval(preg_replace('/[^0-9\.]/', '', $row['value_goods']));
	$row['money_collection'] = floatval(preg_replace('/[^0-9\.]/', '', $row['money_collection']));
	$row['service_charge'] = floatval(preg_replace('/[^0-9\.]/', '', $row['service_charge']));
	$row['pays'] = floatval(preg_replace('/[^0-9\.]/', '', $row['pays']));
	$row['charge_for_collection'] = floatval(preg_replace('/[^0-9\.]/', '', $row['charge_for_collection']));
	$row['total_charge'] = floatval(preg_replace('/[^0-9\.]/', '', $row['total_charge']));
	$row['vat'] = floatval(preg_replace('/[^0-9\.]/', '', $row['vat']));
	$row['total_money'] = floatval(preg_replace('/[^0-9\.]/', '', $row['total_money']));
	$row['total_receivable'] = floatval(preg_replace('/[^0-9\.]/', '', $row['total_receivable']));
	
	// KIỂM TRA MÃ BILL CÓ TỒN TẠI HAY KHÔNG
	if(!empty($row['bill']))
	{
	
		if($row['id'] > 0)
		{
			$count = $db->query("SELECT count(id) FROM ".$db_config['prefix'] . "_" . $module_data . "_rows WHERE id !=". $row['id'] ." AND bill like '". $row['bill'] ."' ")->fetchColumn();	
		}
		else
		{
			
			$count = $db->query("SELECT count(id) FROM ".$db_config['prefix'] . "_" . $module_data . "_rows WHERE bill like '". $row['bill'] ."' ")->fetchColumn();	
		}
		
		if($count == 1)
			$error[] = $lang_module['exits_bill'];
	}
	
	// KẾT THÚC KIỂM TRA TỒN TẠI BILL
	
	if( empty($row['bill']) )
	{
		$error[] = $lang_module['error_required_bill'];
	}
	elseif( $row['userid_add'] == 0 )
	{
		$error[] = $lang_module['error_required_userid'];
	}
	elseif( empty( $row['id_store'] ) )
	{
		$error[] = $lang_module['error_required_id_store'];
	}
	elseif( empty( $row['send_name'] ) )
	{
		$error[] = $lang_module['error_required_send_name'];
	}
	elseif( empty( $row['send_phone'] ) )
	{
		$error[] = $lang_module['error_required_send_phone'];
	}
	elseif( empty( $row['send_address'] ) )
	{
		$error[] = $lang_module['error_required_send_address'];
	}
	elseif( empty( $row['receive_name'] ) )
	{
		$error[] = $lang_module['error_required_receive_name'];
	}
	elseif( empty( $row['receive_address'] ) )
	{
		$error[] = $lang_module['error_required_receive_address'];
	}
	elseif( empty( $row['id_document'] ) )
	{
		$error[] = $lang_module['error_required_id_document'];
	}
	elseif( empty( $row['document_name'] ) )
	{
		$error[] = $lang_module['error_required_document_name'];
	}
	elseif( empty( $row['id_service'] ) )
	{
		$error[] = $lang_module['error_required_id_service'];
	}
	elseif( empty( $row['pay'] ) )
	{
		$error[] = $lang_module['error_required_pay'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{

				$row['add_date'] = NV_CURRENTTIME;
				
				
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_rows (weight, id_store, send_name, send_phone, send_address, send_city, send_district, send_wards, receive_name, receive_phone, receive_address, id_document, document_name, bill, amount, value_goods, weight_document, long_document, wide, height, id_service, id_surcharge, other_requirements, delivery_date, delivery_time, received_date, received_time, pay, money_collection, service_charge, pays, charge_for_collection, total_charge, vat, total_money, total_receivable, seller_payments, add_date, userid_add, status) VALUES (:weight, :id_store, :send_name, :send_phone, :send_address, :send_city, :send_district, :send_wards, :receive_name, :receive_phone, :receive_address, :id_document, :document_name, :bill, :amount, :value_goods, :weight_document, :long_document, :wide, :height, :id_service, :id_surcharge, :other_requirements, :delivery_date, :delivery_time, :received_date, :received_time, :pay, :money_collection, :service_charge, :pays, :charge_for_collection, :total_charge, :vat, :total_money, :total_receivable, :seller_payments, :add_date, :userid_add, :status)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

				$stmt->bindParam( ':add_date', $row['add_date'], PDO::PARAM_INT );
				$stmt->bindParam( ':userid_add', $row['userid_add'], PDO::PARAM_INT );
				$stmt->bindValue( ':status', 1, PDO::PARAM_INT );
				
				$stmt->bindParam( ':bill', $row['bill'], PDO::PARAM_STR );


			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET id_store = :id_store, send_name = :send_name, send_phone = :send_phone, send_address = :send_address, send_city = :send_city, send_district = :send_district, send_wards = :send_wards, receive_name = :receive_name, receive_phone = :receive_phone, receive_address =:receive_address, id_document = :id_document, document_name = :document_name, amount = :amount, value_goods = :value_goods, weight_document = :weight_document, long_document = :long_document, wide = :wide, height = :height, id_service = :id_service, id_surcharge = :id_surcharge, other_requirements = :other_requirements, delivery_date = :delivery_date, delivery_time = :delivery_time, received_date = :received_date, received_time = :received_time, pay = :pay, money_collection = :money_collection, service_charge =:service_charge, pays = :pays, charge_for_collection = :charge_for_collection, total_charge = :total_charge, vat = :vat, total_money = :total_money, total_receivable = :total_receivable, seller_payments = :seller_payments, userid_add =:userid_add WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':id_store', $row['id_store'], PDO::PARAM_INT );
			$stmt->bindParam( ':send_name', $row['send_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':send_phone', $row['send_phone'], PDO::PARAM_STR );
			$stmt->bindParam( ':send_address', $row['send_address'], PDO::PARAM_STR );
			$stmt->bindParam( ':send_city', $row['send_city'], PDO::PARAM_INT );
			$stmt->bindParam( ':send_district', $row['send_district'], PDO::PARAM_INT );
			$stmt->bindParam( ':send_wards', $row['send_wards'], PDO::PARAM_INT );
			$stmt->bindParam( ':receive_name', $row['receive_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':receive_phone', $row['receive_phone'], PDO::PARAM_STR );
			$stmt->bindParam( ':receive_address', $row['receive_address'], PDO::PARAM_STR );
			$stmt->bindParam( ':id_document', $row['id_document'], PDO::PARAM_INT );
			$stmt->bindParam( ':document_name', $row['document_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':amount', $row['amount'], PDO::PARAM_INT );
			$stmt->bindParam( ':value_goods', $row['value_goods'], PDO::PARAM_STR );
			$stmt->bindParam( ':weight_document', $row['weight_document'], PDO::PARAM_STR );
			$stmt->bindParam( ':long_document', $row['long_document'], PDO::PARAM_STR );
			$stmt->bindParam( ':wide', $row['wide'], PDO::PARAM_STR );
			$stmt->bindParam( ':height', $row['height'], PDO::PARAM_STR );
			$stmt->bindParam( ':id_service', $row['id_service'], PDO::PARAM_INT );
			$stmt->bindParam( ':id_surcharge', $row['id_surcharge'], PDO::PARAM_STR );
			$stmt->bindParam( ':other_requirements', $row['other_requirements'], PDO::PARAM_STR );
			$stmt->bindParam( ':delivery_date', $row['delivery_date'], PDO::PARAM_INT );
			$stmt->bindParam( ':delivery_time', $row['delivery_time'], PDO::PARAM_STR );
			$stmt->bindParam( ':received_date', $row['received_date'], PDO::PARAM_INT );
			$stmt->bindParam( ':received_time', $row['received_time'], PDO::PARAM_STR );
			$stmt->bindParam( ':pay', $row['pay'], PDO::PARAM_INT );
			$stmt->bindParam( ':money_collection', $row['money_collection'], PDO::PARAM_STR );
			$stmt->bindParam( ':service_charge', $row['service_charge'], PDO::PARAM_STR );
			$stmt->bindParam( ':pays', $row['pays'], PDO::PARAM_STR );
			$stmt->bindParam( ':charge_for_collection', $row['charge_for_collection'], PDO::PARAM_STR );
			$stmt->bindParam( ':total_charge', $row['total_charge'], PDO::PARAM_STR );
			$stmt->bindParam( ':vat', $row['vat'], PDO::PARAM_STR );
			$stmt->bindParam( ':total_money', $row['total_money'], PDO::PARAM_STR );
			$stmt->bindParam( ':total_receivable', $row['total_receivable'], PDO::PARAM_STR );
			$stmt->bindParam( ':seller_payments', $row['seller_payments'], PDO::PARAM_STR );
			$stmt->bindParam( ':userid_add', $row['userid_add'], PDO::PARAM_INT );

			$exc = $stmt->execute();
			if( $exc )
			{
				// KIỂM TRA KHÁCH HÀNG ĐÃ TỒN TẠI CHƯA -> NẾU CHƯA THÊM KHÁCH HÀNG VÀO USERID HIỆN TẠI
		$tontai_customer = $db->query("SELECT COUNT(id) FROM ". $db_config['prefix'] . "_" . $module_data . "_customer WHERE phone like '". $row['receive_phone'] ."'")->fetchColumn();
		//print($tontai_customer);die;
		if($tontai_customer == 0)
		{
			// THÊM KHÁCH HÀNG VÀO
			$row['userid'] = $row['userid_add'];

			$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_customer (weight, title, phone, name, city, district, wards, address, note, userid, status) VALUES (:weight, :title, :phone, :name, :city, :district, :wards, :address, :note, :userid, :status)' );

			$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_customer' )->fetchColumn();
			$weight = intval( $weight ) + 1;
			$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

			$stmt->bindParam( ':userid', $row['userid'], PDO::PARAM_INT );
			$stmt->bindValue( ':status', 1, PDO::PARAM_INT );
			
			$stmt->bindParam( ':title', $row['receive_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':phone', $row['receive_phone'], PDO::PARAM_STR );
			$stmt->bindParam( ':name', $row['name'], PDO::PARAM_STR );
			$stmt->bindParam( ':city', $row['send_city'], PDO::PARAM_INT );
			$stmt->bindParam( ':district', $row['send_district'], PDO::PARAM_INT );
			$stmt->bindParam( ':wards', $row['send_wards'], PDO::PARAM_INT );
			$stmt->bindParam( ':address', $row['receive_address'], PDO::PARAM_STR );
			$stmt->bindParam( ':note', $row['note'], PDO::PARAM_STR, strlen($row['note']) );
			
			try{
			$exc = $stmt->execute();
			}
			catch( PDOException $e )
			{
			  die( $e->getMessage() );
			}
			
		}
		
		// KẾT THÚC THÊM KHÁCH HÀNG
		
				if( empty( $row['id'] ) )
				{
					// THÊM LỊCH TRÌNH CHO VẬN ĐƠN NÀY
					// LẤY id trình trạng đầu tiên ra
					$tinhtrang_lucdau = $db->query( 'SELECT min(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_schedule' )->fetchColumn();
					
					// LẤY ID BILL USER NÀY MỚI TẠO RA
					$id_moi_tao = $db->query( 'SELECT max(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE userid_add ='.$row['userid_add'] )->fetchColumn();
					
					$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_schedule_bill (id_bill, add_date, status, active) VALUES (:id_bill, :add_date, :status, :active)' );

					$stmt->bindParam( ':id_bill', $id_moi_tao, PDO::PARAM_STR );
					$stmt->bindValue( ':add_date', NV_CURRENTTIME, PDO::PARAM_INT );
					$stmt->bindValue( ':status', $tinhtrang_lucdau, PDO::PARAM_INT );
					$stmt->bindValue( ':active', 1, PDO::PARAM_INT );
					

					$exc = $stmt->execute();
					
					// GỬI MAIL ĐẾN TÀI KHOẢN TẠO BILL
				}
				else
				{
					$status_bill = $nv_Request->get_int( 'status_bill', 'post', 0 );
					if($status_bill > 0)
					{
						// LẤY TRẠNG THÁI MỚI NHẤT CỦA VẬN ĐƠN RA
						$status_moinhat = $db->query('SELECT status FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill WHERE id_bill ='. $row['id'] .' ORDER BY id DESC LIMIT 0,1')->fetchColumn();
						// KIỂM TRA TRẠNG THÁI MỚI NHẤT < $status_bill
						if($status_moinhat < $status_bill)
						{
							// THÊM TRẠNG THÁI MỚI VÀO VẬN ĐƠN
							
							$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_schedule_bill (id_bill, add_date, status, active) VALUES (:id_bill, :add_date, :status, :active)' );

							$stmt->bindParam( ':id_bill', $row['id'], PDO::PARAM_STR );
							$stmt->bindValue( ':add_date', NV_CURRENTTIME, PDO::PARAM_INT );
							$stmt->bindValue( ':status', $status_bill, PDO::PARAM_INT );
							$stmt->bindValue( ':active', 1, PDO::PARAM_INT );
							

							$exc = $stmt->execute();
							
							// GỬI MAIL ĐẾN TÀI KHOẢN TẠO BILL
							
							
						}
						
					
					
					}
				
				
				}
				$nv_Cache->delMod( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );
				die();
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
			die( $e->getMessage() ); //Remove this line after checks finished
		}
	}
}
elseif( $row['id'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	else
	{
		$row['value_goods'] = number_format($row['value_goods'],0,",",",");
		$row['money_collection'] = number_format($row['money_collection'],0,",",",");
		$row['service_charge'] = number_format($row['service_charge'],0,",",",");
		$row['pays'] = number_format($row['pays'],0,",",",");
		$row['charge_for_collection'] = number_format($row['charge_for_collection'],0,",",",");
		$row['total_charge'] = number_format($row['total_charge'],0,",",",");
		$row['vat'] = number_format($row['vat'],0,",",",");
		$row['total_money'] = number_format($row['total_money'],0,",",",");
		$row['total_receivable'] = number_format($row['total_receivable'],0,",",",");
		
		
	}
}
else
{
	
				
	$row['id'] = 0;
	$row['id_store'] = 0;
	$row['send_name'] = '';
	$row['send_phone'] = '';
	$row['send_address'] = '';
	$row['send_city'] = 0;
	$row['send_district'] = 0;
	$row['send_wards'] = 0;
	$row['receive_name'] = '';
	$row['receive_phone'] = '';
	$row['receive_address'] = '';
	$row['id_document'] = 0;
	$row['document_name'] = '';
	$row['amount'] = 1;
	$row['value_goods'] = '';
	$row['weight_document'] = '';
	$row['long_document'] = '';
	$row['wide'] = '';
	$row['height'] = '';
	$row['id_service'] = 0;
	$row['id_surcharge'] = '';
	$row['other_requirements'] = '';
	$row['delivery_date'] = 0;
	$row['delivery_time'] = '';
	$row['received_date'] = 0;
	$row['received_time'] = '';
	$row['bill'] = '';
	$row['pay'] = 1;
	$row['money_collection'] = '0';
	$row['service_charge'] = '0';
	$row['pays'] = '0';
	$row['charge_for_collection'] = '0';
	$row['total_charge'] = '0';
	$row['vat'] = '0';
	$row['total_money'] = '0';
	$row['total_receivable'] = '0';
	$row['seller_payments'] = '0';
}

if( empty( $row['delivery_date'] ) )
{
	$row['delivery_date'] = '';
}
else
{
	$row['delivery_date'] = date( 'd/m/Y', $row['delivery_date'] );
}

if( empty( $row['received_date'] ) )
{
	$row['received_date'] = '';
}
else
{
	$row['received_date'] = date( 'd/m/Y', $row['received_date'] );
}

$q = $nv_Request->get_title( 'q', 'post,get' );

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_rows' );

	if( ! empty( $q ) )
	{
		$db->where( 'send_name LIKE :q_send_name OR send_phone LIKE :q_send_phone OR receive_name LIKE :q_receive_name OR receive_phone LIKE :q_receive_phone OR document_name LIKE :q_document_name OR bill LIKE :q_bill OR value_goods LIKE :q_value_goods OR id_service LIKE :q_id_service' );
	}
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_send_name', '%' . $q . '%' );
		$sth->bindValue( ':q_send_phone', '%' . $q . '%' );
		$sth->bindValue( ':q_receive_name', '%' . $q . '%' );
		$sth->bindValue( ':q_receive_phone', '%' . $q . '%' );
		$sth->bindValue( ':q_document_name', '%' . $q . '%' );
		$sth->bindValue( ':q_bill', '%' . $q . '%' );
		$sth->bindValue( ':q_value_goods', '%' . $q . '%' );
		$sth->bindValue( ':q_id_service', '%' . $q . '%' );
	}
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )
		->order( 'weight ASC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_send_name', '%' . $q . '%' );
		$sth->bindValue( ':q_send_phone', '%' . $q . '%' );
		$sth->bindValue( ':q_receive_name', '%' . $q . '%' );
		$sth->bindValue( ':q_receive_phone', '%' . $q . '%' );
		$sth->bindValue( ':q_document_name', '%' . $q . '%' );
		$sth->bindValue( ':q_bill', '%' . $q . '%' );
		$sth->bindValue( ':q_value_goods', '%' . $q . '%' );
		$sth->bindValue( ':q_id_service', '%' . $q . '%' );
	}
	$sth->execute();
}


$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_UPLOAD', $module_upload );
$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );

$xtpl->assign( 'Q', $q );

	foreach($khung_gio as $key => $gio)
	{
		if($key == $row['received_time'])
		{
		$selected_khung = 'selected=selected';
		}
		else $selected_khung = '';
		
		if($key == $row['delivery_date'])
		{
		$selected_khung_giao = 'selected=selected';
		}
		else $selected_khung_giao = '';
		
		
		$xtpl->assign('selected_khung_giao', $selected_khung_giao);
		$xtpl->assign('selected_khung', $selected_khung);
		$xtpl->assign('idkhung', $key);
		$xtpl->assign('khung', $gio);
        $xtpl->parse('main.gio');
        $xtpl->parse('main.gio1');
	}
	
	if(empty($row['pay']))
		$row['pay'] = 1;
	foreach($pay_array as $key => $pay)
	{
		if($key == $row['pay'])
		{
		$checkbox_pay = 'checked=checked';
		}
		else $checkbox_pay = '';
		
		
		$xtpl->assign('checkbox_pay', $checkbox_pay);
		$xtpl->assign('pay_id', $key);
		$xtpl->assign('pay_title', $pay);
        $xtpl->parse('main.pay');
	}
	
	// LẤY TÀI KHOẢN TỪ KHO HÀNG
	
	$taikhoan = $db->query('SELECT userid FROM '.$db_config['prefix'] . '_' . $module_data . '_store WHERE id ='.$row['id_store'])->fetchColumn();
	// LẤY DANH SÁCH USER
	$list_user = $db->query('SELECT * FROM '.$db_config['prefix'] . '_users WHERE active = 1 ORDER BY userid ASC')->fetchAll();
	
	foreach($list_user as $user)
	{
		$xtpl->assign( 'selected_user', $user['userid'] == $taikhoan ? 'selected=selected' : '' );
		$xtpl->assign('user', $user);
        $xtpl->parse('main.user');
	}
	
	if($taikhoan > 0)
	{
		// LẤY DANH SÁCH KHO HÀNG CỦA USER
		$list_store = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_store WHERE status = 1 AND userid ='. $taikhoan .' ORDER BY weight ASC')->fetchAll();
		
		foreach($list_store as $store)
		{
			$xtpl->assign( 'selected_store', $store['id'] == $row['id_store'] ? 'selected=selected' : '' );
			$xtpl->assign('store', $store);
			$xtpl->parse('main.store');
		}
	}
	
	// LẤY DANH SÁCH TÀI LIỆU HÓA
	$list_document = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_document WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_document as $document)
	{
		if($row['id_document'] > 0)
		{
			if($document['id'] == $row['id_document'])
			$document['checked'] = 'checked=checked';
			else $document['checked'] = '';
		}
		else
		{
			if($document['selected'] == 1)
			$document['checked'] = 'checked=checked';
			else $document['checked'] = '';
		}
		$xtpl->assign('document', $document);
        $xtpl->parse('main.document');
	}
	
	// LẤY DANH SÁCH DỊCH VỤ
	$list_service = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_service WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_service as $service)
	{	
		if($row['id_service'] > 0)
		{  
			if($service['id'] == $row['id_service'])
			$service['checked'] = 'checked=checked';
			else $service['checked'] = '';
		}
		else
		{
			if($service['selected'] == 1)
			$service['checked'] = 'checked=checked';
			else $service['checked'] = '';
		}
		$xtpl->assign('service', $service);
        $xtpl->parse('main.service');
	}
	
	// LẤY DANH SÁCH PHỤ THU
	$list_surcharge = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_surcharge WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	$mang_phuthu = explode(',',$row['id_surcharge']);
	foreach($list_surcharge as $surcharge)
	{
		$xtpl->assign( 'checked_surcharge', in_array($surcharge['id'],$mang_phuthu) ? 'checked=checked' : '' );
		$xtpl->assign('surcharge', $surcharge);
        $xtpl->parse('main.surcharge');
	}
	
	//die();
	// LẤY TỈNH THÀNH RA
	$list_tinhthanh = $db->query('SELECT provinceid, title, type FROM tms_location_province WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_tinhthanh as $tinhthanh)
	{
		if($tinhthanh['provinceid'] == $row['send_city'])
		{
		$tinhthanh['selected'] = 'selected=selected';
		}
		else $tinhthanh['selected'] = '';
		$xtpl->assign('l', $tinhthanh);
        $xtpl->parse('main.tinh');
	}

	if($row['send_district'] > 0)
	{
		// LẤY QUẬN HUYỆN RA
		$list_quan = $db->query('SELECT districtid, title, type FROM tms_location_district WHERE provinceid = '. $row['send_city'] .' and status = 1 ORDER BY weight DESC')->fetchAll();
		//print_r('SELECT districtid, title, type FROM tms_location_district WHERE provinceid = '. $row['district'] .' and status = 1 ORDER BY weight DESC');die;
		foreach($list_quan as $tinhthanh)
		{
			if($tinhthanh['districtid'] == $row['send_district'])
			{
			$tinhthanh['selected'] = 'selected=selected';
			}
			else $tinhthanh['selected'] = '';
			$xtpl->assign('l', $tinhthanh);
			$xtpl->parse('main.quan');
		}
	}
	
	if($row['send_wards'] > 0)
	{
		// LẤY XÃ PHƯỜNG RA
		
		$list_xaphuong = $db->query('SELECT wardid, title ,type FROM tms_location_ward WHERE districtid = '. $row['send_district'] .' and status = 1')->fetchAll();
		
		foreach($list_xaphuong as $tinhthanh)
		{
			if($tinhthanh['wardid'] == $row['send_wards'])
			{
			$tinhthanh['selected'] = 'selected=selected';
			}
			else $tinhthanh['selected'] = '';
			$xtpl->assign('l', $tinhthanh);
			$xtpl->parse('main.xa');
		}
	}
	
	
	// HIỂN THỊ DANH SÁCH TRẠNG THÁI VẬN ĐƠN
	if($row['id'] > 0)
	{
		// LẤY DANH TRẠNG THÁI ĐƠN HÀNG
		$list_schedule = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule WHERE status = 1 ORDER BY weight ASC')->fetchAll();
		
		// TRẠNG THÁI MỚI NHẤT CỦA VẬN ĐƠN
		$status = $db->query('SELECT status FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill WHERE id_bill ='. $row['id'] .' ORDER BY id DESC LIMIT 0,1')->fetchColumn();
		
		foreach($list_schedule as $schedule)
		{
			
			$xtpl->assign( 'selected', $schedule['id'] == $status ? 'selected=selected' : '' );
			$xtpl->assign('OPTION', $schedule);
			$xtpl->parse('main.status_bill.select_status');
		}
			
		$xtpl->parse('main.status_bill');
	}
	
	
	

if( $show_view )
{
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	if( ! empty( $q ) )
	{
		$base_url .= '&q=' . $q;
	}
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.view.generate_page' );
	}
	$number = $page > 1 ? ($per_page * ( $page - 1 ) ) + 1 : 1;
	while( $view = $sth->fetch() )
	{
		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array(
				'key' => $i,
				'title' => $i,
				'selected' => ( $i == $view['weight'] ) ? ' selected="selected"' : '') );
			$xtpl->parse( 'main.view.loop.weight_loop' );
		}
		$xtpl->assign( 'CHECK', $view['status'] == 1 ? 'checked' : '' );
		$view['delivery_date'] = ( empty( $view['delivery_date'] )) ? '' : nv_date( 'd/m/Y', $view['delivery_date'] );
		$view['received_date'] = ( empty( $view['received_date'] )) ? '' : nv_date( 'd/m/Y', $view['received_date'] );
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}


if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['add'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';