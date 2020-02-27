<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

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
	$id = $nv_Request->get_int( 'delete_id', 'post, get', 0 );
	$content = 'NO_' . $id;

	$query = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( isset( $row['status'] ) )
	{
		$status = ( $row['status'] ) ? 0 : 1;
		$query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET status=' . intval( $status ) . ' WHERE id=' . $id;
		$db->query( $query );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items');
		die();
	}
	$nv_Cache->delMod( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['id_store'] = $nv_Request->get_int( 'id_store', 'post', 0 );
	$row['send_name'] = $nv_Request->get_title( 'send_name', 'post', '' );
	$row['send_phone'] = $nv_Request->get_title( 'send_phone', 'post', '' );
	$row['send_address'] = $nv_Request->get_title( 'send_address', 'post', '' );
	$row['send_city'] = $nv_Request->get_int( 'send_city', 'post', 0 );
	$row['send_district'] = $nv_Request->get_int( 'send_district', 'post', 0 );
	$row['send_wards'] = $nv_Request->get_int( 'send_wards', 'post', 0 );
	$row['receive_name'] = $nv_Request->get_title( 'receive_name', 'post', '' );
	$row['receive_phone'] = $nv_Request->get_title( 'receive_phone', 'post', '' );
	$row['id_document'] = $nv_Request->get_int( 'id_document', 'post', 0 );
	$row['document_name'] = $nv_Request->get_title( 'document_name', 'post', '' );
	$row['bill'] = $nv_Request->get_title( 'bill', 'post', '' );
	$row['amount'] = $nv_Request->get_int( 'amount', 'post', 0 );
	$row['value_goods'] = $nv_Request->get_title( 'value_goods', 'post', '' );
	$row['weight_document'] = $nv_Request->get_title( 'weight_document', 'post', '' );
	$row['long_document'] = $nv_Request->get_title( 'long_document', 'post', '' );
	$row['wide'] = $nv_Request->get_title( 'wide', 'post', '' );
	$row['height'] = $nv_Request->get_title( 'height', 'post', '' );
	$row['id_service'] = $nv_Request->get_int( 'id_service', 'post', 0 );
	$row['id_surcharge'] = $nv_Request->get_title( 'id_surcharge', 'post', '' );
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
	$row['pays'] = $nv_Request->get_title( 'pays', 'post', '' );
	$row['charge_for_collection'] = $nv_Request->get_title( 'charge_for_collection', 'post', '' );
	$row['total_charge'] = $nv_Request->get_title( 'total_charge', 'post', '' );
	$row['vat'] = $nv_Request->get_title( 'vat', 'post', '' );
	$row['total_money'] = $nv_Request->get_title( 'total_money', 'post', '' );
	$row['total_receivable'] = $nv_Request->get_title( 'total_receivable', 'post', '' );
	$row['seller_payments'] = $nv_Request->get_title( 'seller_payments', 'post', '' );

	if( empty( $row['id_store'] ) )
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
	elseif( empty( $row['receive_phone'] ) )
	{
		$error[] = $lang_module['error_required_receive_phone'];
	}
	elseif( empty( $row['id_document'] ) )
	{
		$error[] = $lang_module['error_required_id_document'];
	}
	elseif( empty( $row['document_name'] ) )
	{
		$error[] = $lang_module['error_required_document_name'];
	}
	elseif( empty( $row['bill'] ) )
	{
		$error[] = $lang_module['error_required_bill'];
	}
	elseif( empty( $row['amount'] ) )
	{
		$error[] = $lang_module['error_required_amount'];
	}
	elseif( empty( $row['value_goods'] ) )
	{
		$error[] = $lang_module['error_required_value_goods'];
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

				$row['add_date'] = 0;
				$row['userid_add'] = 0;

				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_rows (weight, id_store, send_name, send_phone, send_address, send_city, send_district, send_wards, receive_name, receive_phone, id_document, document_name, bill, amount, value_goods, weight_document, long_document, wide, height, id_service, id_surcharge, other_requirements, delivery_date, delivery_time, received_date, received_time, pay, money_collection, pays, charge_for_collection, total_charge, vat, total_money, total_receivable, seller_payments, add_date, userid_add, status) VALUES (:weight, :id_store, :send_name, :send_phone, :send_address, :send_city, :send_district, :send_wards, :receive_name, :receive_phone, :id_document, :document_name, :bill, :amount, :value_goods, :weight_document, :long_document, :wide, :height, :id_service, :id_surcharge, :other_requirements, :delivery_date, :delivery_time, :received_date, :received_time, :pay, :money_collection, :pays, :charge_for_collection, :total_charge, :vat, :total_money, :total_receivable, :seller_payments, :add_date, :userid_add, :status)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

				$stmt->bindParam( ':add_date', $row['add_date'], PDO::PARAM_INT );
				$stmt->bindParam( ':userid_add', $row['userid_add'], PDO::PARAM_INT );
				$stmt->bindValue( ':status', 1, PDO::PARAM_INT );


			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET id_store = :id_store, send_name = :send_name, send_phone = :send_phone, send_address = :send_address, send_city = :send_city, send_district = :send_district, send_wards = :send_wards, receive_name = :receive_name, receive_phone = :receive_phone, id_document = :id_document, document_name = :document_name, bill = :bill, amount = :amount, value_goods = :value_goods, weight_document = :weight_document, long_document = :long_document, wide = :wide, height = :height, id_service = :id_service, id_surcharge = :id_surcharge, other_requirements = :other_requirements, delivery_date = :delivery_date, delivery_time = :delivery_time, received_date = :received_date, received_time = :received_time, pay = :pay, money_collection = :money_collection, pays = :pays, charge_for_collection = :charge_for_collection, total_charge = :total_charge, vat = :vat, total_money = :total_money, total_receivable = :total_receivable, seller_payments = :seller_payments WHERE id=' . $row['id'] );
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
			$stmt->bindParam( ':id_document', $row['id_document'], PDO::PARAM_INT );
			$stmt->bindParam( ':document_name', $row['document_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':bill', $row['bill'], PDO::PARAM_STR );
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
			$stmt->bindParam( ':pays', $row['pays'], PDO::PARAM_STR );
			$stmt->bindParam( ':charge_for_collection', $row['charge_for_collection'], PDO::PARAM_STR );
			$stmt->bindParam( ':total_charge', $row['total_charge'], PDO::PARAM_STR );
			$stmt->bindParam( ':vat', $row['vat'], PDO::PARAM_STR );
			$stmt->bindParam( ':total_money', $row['total_money'], PDO::PARAM_STR );
			$stmt->bindParam( ':total_receivable', $row['total_receivable'], PDO::PARAM_STR );
			$stmt->bindParam( ':seller_payments', $row['seller_payments'], PDO::PARAM_STR );

			$exc = $stmt->execute();
			if( $exc )
			{
				$nv_Cache->delMod( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
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
	$row['id_document'] = 0;
	$row['document_name'] = '';
	$row['bill'] = '';
	$row['amount'] = 0;
	$row['value_goods'] = '';
	$row['weight_document'] = '';
	$row['long_document'] = '0';
	$row['wide'] = '0';
	$row['height'] = '0';
	$row['id_service'] = 0;
	$row['id_surcharge'] = '';
	$row['other_requirements'] = '';
	$row['delivery_date'] = 0;
	$row['delivery_time'] = '';
	$row['received_date'] = 0;
	$row['received_time'] = '';
	$row['pay'] = 1;
	$row['money_collection'] = '0';
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




$export_word = $nv_Request->get_int( 'export_word', 'post, get', 0 );
if($export_word == 2)
{
	$op = 'print-all';
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;


$where = '';
$where_lichtrinh = '';
$bill = $nv_Request->get_title( 'bill', 'post,get' );
$status = $nv_Request->get_int( 'status', 'post,get', 0 );
$userid = $nv_Request->get_int( 'userid', 'post,get', 0 );
$id_store = $nv_Request->get_int( 'id_store', 'post,get', 0 );
$phone = $nv_Request->get_title( 'phone', 'post,get' );
$ngay_tu = $nv_Request->get_title( 'ngay_tu', 'post,get' );
$ngay_den = $nv_Request->get_title( 'ngay_den', 'post,get' );

//$userid_array = array_unique($nv_Request->get_typed_array('userid', 'post,get', 'int', array()));

//$userid = implode(',',$userid_array);



if(!empty($bill))
{
	$where .=" AND bill like '". $bill ."'";
	$where_lichtrinh .=" AND bill like '". $bill ."'";
	$base_url .= '&bill=' . $bill;

}

if( $status > 0 )
{
	$where .= ' AND t2.status = '. $status .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
	$base_url .= '&status=' . $status;
}
else
{
	$where .= ' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
}

if($userid > 0)
{
	$where .=" AND userid_add=".$userid;
	$where_lichtrinh .=" AND userid_add=".$userid;
	$base_url .= '&userid=' . $userid;
}

if($id_store > 0)
{
	$where .=" AND id_store=".$id_store;
	$where_lichtrinh .=" AND id_store=".$id_store;
	$base_url .= '&id_store=' . $id_store;
}

if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $ngay_tu, $m ) )
	{
		$_hour = $nv_Request->get_int( 'add_date_hour', 'post', 0 );
		$_min = $nv_Request->get_int( 'add_date_min', 'post', 0 );
		$ngay_tu = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
else
	{
		$ngay_tu = 0;
	}

	
if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $ngay_den, $m ) )
	{
		$_hour = $nv_Request->get_int( 'add_date_hour', 'post', 23 );
		$_min = $nv_Request->get_int( 'add_date_min', 'post', 59 );
		$ngay_den = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
else
	{
		$ngay_den = 0;
	}
	
	

if($ngay_tu > 0 and $ngay_den > 0)
{
	
	$where .=" AND t1.add_date >= ". $ngay_tu . " AND t1.add_date <= ". $ngay_den;
	$base_url .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
}
elseif( $ngay_tu > 0 )
{
	$where .=" AND t1.add_date >= ". $ngay_tu;
	$base_url .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
}
elseif( $ngay_den > 0 )
{
	$where .=" AND t1.add_date <= ". $ngay_den;
	$base_url .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
}
else
{
	// Náº¾U KHÃ”NG CHá»ŒN NGÃ€Y Tá»ª VÃ€ NGÃ€Y Äáº¾N Sáº¼ Láº¤Y Máº¶C Äá»ŠNH NGÃ€Y Äáº¦U TIÃŠN Äáº¾N NGÃ€Y HIá»†N Táº I Cá»¦A THÃNG HIá»†N Táº I
	
	// láº¥y thÃ¡ng hiá»‡n táº¡i ra
	$thanghientai = date('m',NV_CURRENTTIME);
	$namhientai = date('Y',NV_CURRENTTIME);
	
	$date = '01/'. $thanghientai .'/'. $namhientai;
	
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $date, $m ) )
	{
		$_hour = $nv_Request->get_int( 'add_date_hour', 'post', 0 );
		$_min = $nv_Request->get_int( 'add_date_min', 'post', 0 );
		$ngay_tu = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
		{
			$ngay_tu = 0;
		}
		
	
	
	$ngay_den = NV_CURRENTTIME;
	
	$where .=" AND t1.add_date >= ". $ngay_tu . " AND t1.add_date <= ". $ngay_den;
	$base_url .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
	
}



// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	// THá»°C THI
	//$sql ='SELECT * FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1, '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 WHERE t1.id = t2.id_bill AND t1.status = 1 AND t1.userid_add ='. $user_info['userid'] .' AND t2.status = 1 AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
	//die($sql);
	
	// Káº¾T THÃšC THá»°C THI
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(DISTINCT(t1.id)), max(t2.id)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_rows t1' )
		->join('LEFT JOIN ' . $db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill');

	$db->where( 't1.status = 1 '. $where);
	
	$sth = $db->prepare( $db->sql() );

	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select('t1.*')
		->order( 't2.add_date DESC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );
	//die($db->sql());
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


$xtpl->assign( 'bill', $bill );
$xtpl->assign( 'phone', $phone );
if($ngay_tu > 0)
$xtpl->assign( 'ngay_tu', date('d/m/Y',$ngay_tu));
if($ngay_den > 0)
$xtpl->assign( 'ngay_den', date('d/m/Y',$ngay_den));

	// Láº¤Y DANH TRáº NG THÃI ÄÆ N HÃ€NG
	$list_schedule = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_schedule as $schedule)
	{
		
		$xtpl->assign( 'selected', $schedule['id'] == $status ? 'selected=selected' : '' );
		$xtpl->assign('OPTION', $schedule);
		
        $xtpl->parse('main.view.select_status');
	}
	
	
	// Láº¤Y DANH TRáº NG THÃI ÄÆ N HÃ€NG
	$list_user = $db->query('SELECT * FROM '.$db_config['prefix'] . '_users WHERE active = 1 ORDER BY userid ASC')->fetchAll();
	
	foreach($list_user as $user)
	{
		
		$xtpl->assign( 'selected', $user['userid'] == $userid ? 'selected=selected' : '' );
		$xtpl->assign('user', $user);
		
        $xtpl->parse('main.view.user');
	}
	
	
	if($userid > 0)
	{
		// LẤY DANH SÁCH KHO HÀNG CỦA USER
		$list_store = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_store WHERE status = 1 AND userid ='. $userid .' ORDER BY weight ASC')->fetchAll();
		
		foreach($list_store as $store)
		{
			$xtpl->assign( 'selected_store', $store['id'] == $id_store ? 'selected=selected' : '' );
			$xtpl->assign('store', $store);
			$xtpl->parse('main.view.store');
		}
	}


if( $show_view )
{

	if($export_word == 2)
	{
		Header( 'Location: ' . $base_url);
		die();
	}

	if($export_word == 1)
	{
		// XUáº¤T THÃ”NG TIN RA FILE EXCEL
		
		$db->sqlreset()
		->select( 'COUNT(DISTINCT(t1.id)), max(t2.id)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_rows t1' )
		->join('LEFT JOIN ' . $db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill');

		$db->where( 't1.status = 1 '. $where);
		
		$sth_word = $db->prepare( $db->sql() );

		$db->select('t1.*')
			->order( 't2.add_date DESC' );
		$sth_word = $db->prepare( $db->sql() );
		//die($db->sql());
		$sth_word->execute();
		
		require_once NV_ROOTDIR . '/modules/'. $module_file .'/Classes/PHPExcel.php';

	//Khá»Ÿi táº¡o Ä‘á»‘i tÆ°á»£ng
	$excel = new PHPExcel();
		//Chá»n trang cáº§n ghi (lÃ  sá»‘ tá»« 0->n)
	$excel->setActiveSheetIndex(0);
		//Táº¡o tiÃªu Ä‘á» cho trang. (cÃ³ thá»ƒ khÃ´ng cáº§n)
	$excel->getActiveSheet()->setTitle('Danh sÃ¡ch váº­n Ä‘Æ¡n');

		//XÃ©t chiá»u rá»™ng cho tá»«ng, náº¿u muá»‘n set height thÃ¬ dÃ¹ng setRowHeight()
	$excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
	$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
	$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);

		//XÃ©t in Ä‘áº­m cho khoáº£ng cá»™t
	$excel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
		//Táº¡o tiÃªu Ä‘á» cho tá»«ng cá»™t
		//Vá»‹ trÃ­ cÃ³ dáº¡ng nhÆ° sau:
		/**
		 * |A1|B1|C1|..|n1|
		 * |A2|B2|C2|..|n1|
		 * |..|..|..|..|..|
		 * |An|Bn|Cn|..|nn|
		 */
		$excel->getActiveSheet()->setCellValue('A1', 'Stt');
		$excel->getActiveSheet()->setCellValue('B1', $lang_module['date']);
		$excel->getActiveSheet()->setCellValue('C1', $lang_module['bill']);
		$excel->getActiveSheet()->setCellValue('D1', $lang_module['to']);
		$excel->getActiveSheet()->setCellValue('E1', $lang_module['weight_document_note']);
		$excel->getActiveSheet()->setCellValue('F1', $lang_module['id_service']);
		$excel->getActiveSheet()->setCellValue('G1', $lang_module['value_goods'] . ' - ' . $lang_module['note']);
		$excel->getActiveSheet()->setCellValue('H1', $lang_module['money_collection']);
		$excel->getActiveSheet()->setCellValue('I1', $lang_module['service_charge']);
		$excel->getActiveSheet()->setCellValue('J1', $lang_module['pays']);
		$excel->getActiveSheet()->setCellValue('K1', $lang_module['total_charge']);
		$excel->getActiveSheet()->setCellValue('L1', $lang_module['vat']);
		$excel->getActiveSheet()->setCellValue('M1', $lang_module['total_money']);
		// thá»±c hiá»‡n thÃªm dá»¯ liá»‡u vÃ o tá»«ng Ã´ báº±ng vÃ²ng láº·p
		// dÃ²ng báº¯t Ä‘áº§u = 2
	
		$stt = 1;
		$numRow = 2;
		while( $view = $sth_word->fetch() )
		{
			
			if($view['send_city'] > 0)
			{
				$tinhthanh = $db->query('SELECT title, type FROM '. $db_config['prefix'] . '_location_province WHERE status = 1 AND provinceid ='.$view['send_city'])->fetch();
				$view['city'] = $tinhthanh['type'] . ' ' . $tinhthanh['title'];
			}
						
			if($view['id_service'] > 0)
			{
				$view['id_service'] = $db->query('SELECT title FROM '. $db_config['prefix'] . '_' . $module_data . '_service WHERE status = 1 AND id ='.$view['id_service'])->fetchColumn();
							
			}
			else $view['id_service'] = '';
						
			$excel->getActiveSheet()->setCellValue('A'.$numRow, $stt);
			$excel->getActiveSheet()->setCellValue('B'.$numRow, date('d/m/Y',$view['add_date']));
			$excel->getActiveSheet()->setCellValue('C'.$numRow, $view['bill']);
			$excel->getActiveSheet()->setCellValue('D'.$numRow, $view['city']);
			$excel->getActiveSheet()->setCellValue('E'.$numRow, $view['weight_document']);
			$excel->getActiveSheet()->setCellValue('F'.$numRow, $view['id_service']);
			$excel->getActiveSheet()->setCellValue('G'.$numRow, $view['money_collection']);
			$excel->getActiveSheet()->setCellValue('H'.$numRow, $view['charge_for_collection']);
			$excel->getActiveSheet()->setCellValue('I'.$numRow, $view['service_charge']);
			$excel->getActiveSheet()->setCellValue('J'.$numRow, $view['pays']);
			$excel->getActiveSheet()->setCellValue('K'.$numRow, $view['total_charge']);
			$excel->getActiveSheet()->setCellValue('L'.$numRow, $view['vat']);
			$excel->getActiveSheet()->setCellValue('M'.$numRow, $view['total_money']);
			$numRow++;
			$stt++;
		
		}
		// Khá»Ÿi táº¡o Ä‘á»‘i tÆ°á»£ng PHPExcel_IOFactory Ä‘á»ƒ thá»±c hiá»‡n ghi file
		// á»Ÿ Ä‘Ã¢y mÃ¬nh lÆ°u file dÆ°á»›i dáº¡ng excel2007 vÃ  cho ngÆ°á»i dÃ¹ng download luÃ´n
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="data.xls"');
		PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('php://output');
		
		die();
	}

//Káº¾T THÃšC XUáº¤T RA FILE WORD
	
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.view.generate_page' );
	}
	$number = $page > 1 ? ($per_page * ( $page - 1 ) ) + 1 : 1;
	$stt = 0;
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
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add&amp;id=' . $view['id'];
		$view['link_schedule_bill'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=schedule_bill&amp;id_bill=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		//print_r($view['link_edit']);die;
		if($view['id_service'] > 0)
		$view['id_service'] = $db->query('SELECT title FROM '.$db_config['prefix'] . '_' . $module_data . '_service WHERE id ='. $view['id_service'])->fetchColumn();
		$view['add_date'] = date('d/m/Y - H:i', $view['add_date']);
		$stt++;
		$xtpl->assign( 'stt', $stt );
		
		// TRáº NG THÃI Má»šI NHáº¤T Cá»¦A Váº¬N ÄÆ N
		$status = $db->query('SELECT status, add_date FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill WHERE id_bill ='. $view['id'] .' ORDER BY add_date DESC LIMIT 0,1')->fetch();
		$delete_edit = 1;
		if($status['status'] > 0)
		{
			$trangthai_moi = $db->query('SELECT title, delete_user FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule WHERE id ='. $status['status'] .' ORDER BY id DESC LIMIT 0,1')->fetch();
			//print_r($trangthai_moi);die;
			$view['trangthai_moi'] = $trangthai_moi['title'];
			$delete_edit = $trangthai_moi['delete_user'];
			$view['ngay_trangthai_moi'] = date('d/m/Y - H:i',$status['add_date']);
			
		}
		if($delete_edit)
		{
			$xtpl->parse( 'main.view.loop.edit' );
		}
		
		if($delete_edit)
		{
			$xtpl->assign( 'edit_bill', '');
		}
		else
		{
			$xtpl->assign( 'edit_bill', 'class="none_edit_bill"');
		}
		
		$view['value_goods'] = number_format($view['value_goods'],0,",",",");
		$view['total_money'] = number_format($view['total_money'],0,",",",");
		
		$view['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . $view['bill'] . $global_config['rewrite_exturl'];
		
		$view['url_print']  = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print&id=' . $view['id'] . '&checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		
		if($view['send_city'] > 0)
		{
			$tinhthanh = $db->query('SELECT title, type FROM tms_location_province WHERE status = 1 AND provinceid ='.$view['send_city'])->fetch();
			$view['send_city'] = $tinhthanh['type'] . ' ' . $tinhthanh['title'];
		}
		else $view['send_city'] = '';
		
		if($view['send_district'] > 0)
		{
			$quanhuyen = $db->query('SELECT title, type FROM tms_location_district WHERE status = 1 AND districtid ='.$view['send_district'])->fetch();
			$view['send_district'] = $quanhuyen['type'] . ' ' . $quanhuyen['title'];
		}
		else $view['send_district'] = '';
		
		if(!empty($view['send_city']) or !empty($view['send_district']))
		$view['address_sent'] = $view['send_district'] . ' - '. $view['send_city'];
		
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

$page_title = $lang_module['items'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';