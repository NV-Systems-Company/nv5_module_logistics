<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

 
if($nv_Request->isset_request('tai_khoanajax', 'get'))
{
	$tai_khoan = $nv_Request->get_title('tai_khoanajax','get', '');
	if(!empty($tai_khoan))
	{
		$bgad = $db->query("SELECT bang_gia FROM tms_khach_hang_row WHERE ma_kh LIKE '".$tai_khoan."'")->fetchColumn();
		
		print $bgad;die;
	}
	die();
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
		if ( $nv_Request->isset_request( 'ma_bill', 'post,get' ) )
		{
			$ma_bill = $nv_Request->get_string( 'ma_bill', 'post,get', '');
			$awbncc = $nv_Request->get_string( 'awbncc', 'post,get', '');
			
			
			
			$tenncc = $nv_Request->get_string( 'tenncc', 'post,get', '');
			$dia_chi_goi = $nv_Request->get_string( 'dia_chi_goi', 'post,get', '');
			$ngay_nhan = $nv_Request->get_string( 'ngay_nhan', 'post,get', '');
			$dia_chi_nhan = $nv_Request->get_string( 'dia_chi_nhan', 'post,get', '');
			$ngay_goi = $nv_Request->get_string( 'ngay_gioi', 'post,get', '');
			$tai_khoan = $nv_Request->get_string( 'tai_khoan', 'post,get', '');
			$page = $nv_Request->get_int( 'page', 'post,get', 1);
		}
		$nv_Cache->delMod( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
		die();
	}
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
$ma_bill_cu = $nv_Request->get_title( 'ma_bill_cu', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['ma_bill'] = $nv_Request->get_title( 'ma_bill', 'post', '' );
	$row['awbncc'] = $nv_Request->get_title( 'awbncc', 'post', '' );
	$row['st_dtt'] = $nv_Request->get_string( 'st_dtt', 'post', '' );
	$row['st_dtt'] = str_replace( ',', '', $row['st_dtt'] );
	$row['tai_khoan'] = $nv_Request->get_title( 'tai_khoan', 'post', '' );
	 $row['ngay_nhan'] = NV_CURRENTTIME;
	$row['id_nuoc_den'] = $nv_Request->get_title( 'id_nuoc_den', 'post','' );
	$row['nguoi_nhan'] = $nv_Request->get_title( 'nguoi_nhan', 'post', '' );
	
	$row['ngay_them'] = NV_CURRENTTIME;
	$publ_date = $nv_Request->get_title('ngay_gioi', 'post', '');
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m)) {
        $phour = date('H',NV_CURRENTTIME);
        $pmin = date('i',NV_CURRENTTIME);
       $row['ngay_gioi'] = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
	   
    } 
	$row['tenncc'] = $nv_Request->get_title( 'tenncc', 'post', '' );
	
	//print(date('d/m/Y-H:i',1523331360));die;
	
	if( empty( $row['ma_bill'] ) )
	{
		$error[] = $lang_module['error_required_ma_bill'];
	}
	elseif($row['ma_bill'] > 0){
		// KIỂM TRA VẬN ĐƠN TRÙNG
		$dem = $db->query("SELECT count(*) FROM ". $db_config['prefix'] . "_" . $module_data . "_rows WHERE ma_bill ='".$row['ma_bill']."'")->fetchColumn();
		
		if($dem > 0 and $ma_bill_cu != $row['ma_bill'] )
			$error[] = 'Tracking bị trùng ! ';
	
	}
	if( empty( $row['ngay_gioi'] ) )
	{
		$error[] = 'Ngày gửi trống !';
	}
	//print_r($error);die;
	if( empty( $error ) )
	{
	
		// THAY ĐỔI THÔNG TIN người nhận , ngày phát , giờ phát thay đổi -> GỬI MAIL
		$guimail = false;
		if($row['id'] > 0)
		{
			// lấy thông tin người nhận , ngày phát , giờ phát thay đổi ra
			$thongtin_cu = $db->query('SELECT nguoi_nhan, ngay_gioi  FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id ='.$row['id'])->fetch();
			//print();die;
			if( (!empty($row['nguoi_nhan'])) and (!empty($row['ngay_gioi'])) and ($phour_goi != 0 or $pmin_goi != 0))
			{
			if((($thongtin_cu['nguoi_nhan'] != $row['nguoi_nhan']) or ($thongtin_cu['ngay_gioi'] != $row['ngay_gioi'])))
				$guimail = true;
			}
			
		}
		else{
			if( (!empty($row['nguoi_nhan'])) and (!empty($row['ngay_gioi'])) and ($phour_goi != 0 or $pmin_goi != 0))
				$guimail = true;
		}
		
		try
		{
			if( empty( $row['id'] ) )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_rows (weight, ma_bill, awbncc, tenncc, id_tai_lieu, id_nuoc_den, id_khoi_luong, nguoi_goi, ngay_gioi, gio_goi, dia_chi_goi, ten_nv_goi, nguoi_nhan, ngay_nhan, gio_nhan, dia_chi_nhan, bgad, giagoc, phikhac, ppxd, vat, tongtien, chieckhau, st_dtt, ghi_chu, tai_khoan, cuoc_phi, id_trang_thai, active, ngay_them) VALUES (:weight, :ma_bill, :awbncc, :tenncc,  :id_tai_lieu, :id_nuoc_den, :id_khoi_luong, :nguoi_goi, :ngay_gioi, :gio_goi, :dia_chi_goi, :ten_nv_goi, :nguoi_nhan, :ngay_nhan, :gio_nhan, :dia_chi_nhan, :bgad, :giagoc, :phikhac, :ppxd, :vat, :tongtien, :chieckhau, :st_dtt, :ghi_chu, :tai_khoan, :cuoc_phi, :id_trang_thai, :active, :ngay_them)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

				$stmt->bindValue( ':active', 1, PDO::PARAM_INT );

				

			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET ma_bill = :ma_bill, awbncc = :awbncc, tenncc = :tenncc, id_tai_lieu = :id_tai_lieu, id_nuoc_den = :id_nuoc_den, id_khoi_luong = :id_khoi_luong, nguoi_goi = :nguoi_goi, ngay_gioi = :ngay_gioi, gio_goi = :gio_goi, dia_chi_goi = :dia_chi_goi, ten_nv_goi = :ten_nv_goi, nguoi_nhan = :nguoi_nhan, ngay_nhan = :ngay_nhan, gio_nhan = :gio_nhan, dia_chi_nhan = :dia_chi_nhan, bgad = :bgad, giagoc = :giagoc, phikhac = :phikhac, ppxd = :ppxd, vat = :vat, tongtien = :tongtien, chieckhau = :chieckhau, st_dtt = :st_dtt, ghi_chu = :ghi_chu, tai_khoan = :tai_khoan, cuoc_phi = :cuoc_phi, id_trang_thai = :id_trang_thai, ngay_them = :ngay_them WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':ma_bill', $row['ma_bill'], PDO::PARAM_STR );
			$stmt->bindParam( ':awbncc', $row['awbncc'], PDO::PARAM_STR );
			$stmt->bindParam( ':tenncc', $row['tenncc'], PDO::PARAM_STR );
			$stmt->bindParam( ':id_tai_lieu', $row['id_tai_lieu'], PDO::PARAM_STR );
			$stmt->bindParam( ':id_nuoc_den', $row['id_nuoc_den'], PDO::PARAM_STR );
			$stmt->bindParam( ':id_khoi_luong', $row['id_khoi_luong'], PDO::PARAM_STR );
			$stmt->bindParam( ':nguoi_goi', $row['nguoi_goi'], PDO::PARAM_STR );
			$stmt->bindParam( ':ngay_gioi', $row['ngay_gioi'], PDO::PARAM_INT );
			$stmt->bindParam( ':gio_goi', $row['gio_goi'], PDO::PARAM_STR );
			$stmt->bindParam( ':dia_chi_goi', $row['dia_chi_goi'], PDO::PARAM_STR );
			$stmt->bindParam( ':ten_nv_goi', $row['ten_nv_goi'], PDO::PARAM_STR );
			$stmt->bindParam( ':nguoi_nhan', $row['nguoi_nhan'], PDO::PARAM_STR );
			$stmt->bindParam( ':ngay_nhan', $row['ngay_nhan'], PDO::PARAM_INT );
			$stmt->bindParam( ':gio_nhan', $row['gio_nhan'], PDO::PARAM_STR );
			$stmt->bindParam( ':dia_chi_nhan', $row['dia_chi_nhan'], PDO::PARAM_STR );
			$stmt->bindParam( ':bgad', $row['bgad'], PDO::PARAM_STR );
			$stmt->bindParam( ':giagoc', $row['giagoc'], PDO::PARAM_STR );
			$stmt->bindParam( ':phikhac', $row['phikhac'], PDO::PARAM_STR );
			$stmt->bindParam( ':ppxd', $row['ppxd'], PDO::PARAM_STR );
			$stmt->bindParam( ':vat', $row['vat'], PDO::PARAM_STR );
			$stmt->bindParam( ':tongtien', $row['tongtien'], PDO::PARAM_STR );
			$stmt->bindParam( ':chieckhau', $row['chieckhau'], PDO::PARAM_STR );
			$stmt->bindParam( ':st_dtt', $row['st_dtt'], PDO::PARAM_STR );
			$stmt->bindParam( ':ghi_chu', $row['ghi_chu'], PDO::PARAM_STR );
			$stmt->bindParam( ':tai_khoan', $row['tai_khoan'], PDO::PARAM_STR );
			$stmt->bindParam( ':cuoc_phi', $row['cuoc_phi'], PDO::PARAM_STR );
			$stmt->bindParam( ':id_trang_thai', $row['id_trang_thai'], PDO::PARAM_INT );
			$stmt->bindParam( ':ngay_them', $row['ngay_them'], PDO::PARAM_INT );
			
			try{
			$exc = $stmt->execute();
			}
			catch( PDOException $e )
			{
				trigger_error( $e->getMessage() );
				die( $e->getMessage() ); //Remove this line after checks finished
			}
			if( $exc )
			{
				
				$nv_Cache->delMod( $module_name );
				if( empty( $row['id'] ) )
				{
				// CẬP NHẬT THÔNG TIN BAN ĐẦU CHO VẬN ĐƠN 
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh (id_van_don, weight, ngay, status) VALUES (:id_van_don, :weight, :ngay, :status)' );
				
				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				// LẤY VẬN ĐƠN MỚI NHẤT RA
				
				$m = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE ma_bill = '".  $row['ma_bill'] ."' ORDER BY id DESC LIMIT 0,1")->fetch();
				
				$status = 'Chấp nhận dịch vụ';
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );
				$stmt->bindParam( ':id_van_don', $m['id'], PDO::PARAM_INT );
				$stmt->bindParam( ':ngay', $m['ngay_gioi'], PDO::PARAM_INT );
				$stmt->bindParam( ':status', $status, PDO::PARAM_STR );
			
					try{
					$exc = $stmt->execute();
					}
					catch( PDOException $e )
					{
						trigger_error( $e->getMessage() );
						die( $e->getMessage() ); //Remove this line after checks finished
					}
					
				// KẾT THÚC CẬP NHẬT THÔNG TIN BAN ĐẦU CHO VẬN ĐƠN
				
				
				
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op.'&t=1' );
				die();
				}
				else{
				// CẬP NHẬT LẠI THÔNG TIN 
				//die("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_lich_trinh SET  ngay = ". $row['ngay_nhan'] ." WHERE id_van_don=" . $row['id'] ." AND status = 'Shipment picked up'");
				//$kq = $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_lich_trinh SET  ngay = ". $row['ngay_nhan'] ." WHERE id_van_don=" . $row['id'] ." AND status = 'Shipment picked up'");
				if ( $nv_Request->isset_request( 'ma_bill_s', 'post,get' ) )
				{
					$ma_bill_s = $nv_Request->get_string( 'ma_bill_s', 'post,get', '');
					$awbncc_s = $nv_Request->get_string( 'awbnccs_s', 'post,get', '');
					$tenncc_s = $nv_Request->get_string( 'tenncc_s', 'post,get', '');
					$dia_chi_goi_s = $nv_Request->get_string( 'dia_chi_goi_s', 'post,get', '');
					$ngay_nhan_s = $nv_Request->get_string( 'ngay_nhan_s', 'post,get', '');
					$dia_chi_nhan_s = $nv_Request->get_string( 'dia_chi_nhan_s', 'post,get', '');
					$ngay_goi_s = $nv_Request->get_string( 'ngay_gioi_s', 'post,get', '');
					$tai_khoan_s = $nv_Request->get_string( 'tai_khoan_s', 'post,get', '');
					$page_s = $nv_Request->get_int( 'page_s', 'post,get',1);
					Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&s=1&ma_bill='.$ma_bill_s.'&awbncc='.$awbncc_s.'&tenncc='.$tenncc_s.'&dia_chi_goi='.$dia_chi_goi_s.'&ngay_nhan='.$ngay_nhan_s.'&dia_chi_nhan='.$dia_chi_nhan_s.'&ngay_gioi='.$ngay_goi_s.'&tai_khoan='.$tai_khoan_s.'&page='.$page_s  );
					die();
				}
				else{				
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&s=1' );
				die();
				}
				}
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
	
		if(!empty($row['ngay_gioi']))
		{
		$tdate_goi = date('H|i', $row['ngay_gioi']);
		$row['ngay_gioi'] = date('d/m/Y',$row['ngay_gioi']);
		}
		if(!empty($row['ngay_nhan']))
		{
		$tdate_nhan = date('H|i', $row['ngay_nhan']);
		$row['ngay_nhan'] = date('d/m/Y',$row['ngay_nhan']);
		}
		
		// % PPXD = (GIAGOC+ PHIKHAC)*%PPXD
		if($row['giagoc'] >= 0 and $row['phikhac'] >= 0 and $row['ppxd'] >= 0 )
		{
			$row['ppxd1'] = (($row['giagoc'] + $row['phikhac'] )* ($row['ppxd']/100));
			
		}
		
		// % VAT = =(GIAGOC + PHIKHAC) +(GIAGOC + PHIKHAC)*%PPXD)*%VAT
		if($row['giagoc'] >= 0 and $row['phikhac'] >= 0 and $row['ppxd'] >= 0 and $row['vat'] >= 0  )
		{
			$row['vat1'] = (($row['giagoc'] + $row['phikhac'] ) + ($row['giagoc'] + $row['phikhac'] )* ($row['ppxd']/100)) * ($row['vat']/100) ;
		}
		
		
		// %CHIETKHAU = = GIAGOC*%CHIETKHAU
		if($row['giagoc'] >= 0 and $row['chieckhau'] >= 0 )
		{
			$row['chieckhau1'] = ($row['giagoc'] * ($row['chieckhau']/100) );
		}
		
		// XUẤT RA TỔNG TIỀN TONG TIEN= GIAGOC +PHIKHAC +(2 DẦU BẰNG CỦA % PPXD VA %VAT)
		
		if($row['giagoc'] >= 0 and $row['phikhac'] >= 0 and $row['ppxd1'] >=0 and $row['vat1'] >=0 )
		{
			
			$row['tong_tien'] = ($row['giagoc'] + $row['phikhac'] + $row['ppxd1'] + $row['vat1'] );
		}
		
		
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
		die();
	}
	
	// NHẬN THÔNG TIN TÌM KIẾM XUẤT RA NGOÀI
	if ( $nv_Request->isset_request( 'ma_bill', 'post,get' ) )
	{
		$ma_bill = $nv_Request->get_string( 'ma_bill', 'post,get', '');
		$awbncc = $nv_Request->get_string( 'awbncc', 'post,get', '');
		$tenncc = $nv_Request->get_string( 'tenncc', 'post,get', '');
		$dia_chi_goi = $nv_Request->get_string( 'dia_chi_goi', 'post,get', '');
		$ngay_nhan = $nv_Request->get_string( 'ngay_nhan', 'post,get', '');
		$dia_chi_nhan = $nv_Request->get_string( 'dia_chi_nhan', 'post,get', '');
		$ngay_goi = $nv_Request->get_string( 'ngay_gioi', 'post,get', '');
		$tai_khoan = $nv_Request->get_string( 'tai_khoan', 'post,get', '');
		$page = $nv_Request->get_int( 'page', 'post,get',1);
	}
}
else
{
	// print_r(vsprintf('MA-%07s', '456'));die;
	// LẤY MÃ BILL TIẾP THEO RA
	// LẤY id_van_don max
	$max_idvandon = $db->query("SELECT max(id) as id FROM " . $db_config['prefix'] . "_" . $module_data . "_rows ")->fetchColumn();
	
	$row['id'] = 0;
	$row['ma_bill'] = vsprintf('MA-%07s', $max_idvandon - 7837 + 1);
	$row['awbncc'] = '';
	$row['tenncc'] = '';
	$row['id_tai_lieu'] = '';
	$row['id_nuoc_den'] = '';
	$row['id_khoi_luong'] = '';
	$row['nguoi_goi'] = '';
	$row['ngay_gioi'] = '';
	$row['gio_goi'] = '';
	$row['dia_chi_goi'] = '';
	$row['ten_nv_goi'] = '';
	$row['nguoi_nhan'] = '';
	$row['ngay_nhan'] = '';
	$row['gio_nhan'] = '';
	$row['dia_chi_nhan'] = '';
	$row['ghi_chu'] = '';
	$row['tai_khoan'] = '';
	$row['cuoc_phi'] = '';
	$row['id_trang_thai'] = '';
}



$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_UPLOAD', $module_upload );
$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
$xtpl->assign( 'OP', $op );

// XUẤT DANH SÁCH KHÁCH HÀNG

$khachhang = $db->query('SELECT * FROM tms_users')->fetchAll();
//print_r($row);die;
foreach($khachhang as $kh)
{
	if($row['tai_khoan'] == $kh['userid'])
		$xtpl->assign( 'selected', 'selected=selected');
	else $xtpl->assign( 'selected', '');
	$xtpl->assign( 'kh', $kh);
	$xtpl->parse( 'main.kh' );
}


// XUẤT THÔNG TIN % PPXD
if($row['ppxd1'] > 0)
{
	$row['ppxd1'] = number_format($row['ppxd1'],3 );
	$so = explode('.',$row['ppxd1']);
	if($so[1] == 0)
	{
		$row['ppxd1'] = $so[0];
	}
	$xtpl->assign( 'ppxd', $row['ppxd1']);
	$xtpl->parse( 'main.ppxd' );
}

// XUẤT THÔNG TIN % VAT
if($row['vat1'] > 0)
{
	$row['vat1'] = number_format($row['vat1'],3 );
	$so = explode('.',$row['vat1']);
	if($so[1] == 0)
	{
		$row['vat1'] = $so[0];
	}
	$xtpl->assign( 'vat', $row['vat1']);
	$xtpl->parse( 'main.vat' );
}

// XUẤT THÔNG TIN % CHIETKHAU
if($row['chieckhau1'] > 0)
{
	$row['chieckhau1'] = number_format($row['chieckhau1'],3 );
	$so = explode('.',$row['chieckhau1']);
	if($so[1] == 0)
	{
		$row['chieckhau1'] = $so[0];
	}
	$xtpl->assign( 'chieckhau', $row['chieckhau1']);
	$xtpl->parse( 'main.chieckhau' );
}

if($row['giagoc'] > 0)
{
	
	$row['giagoc'] = number_format($row['giagoc'],3 );
	$so = explode('.',$row['giagoc']);
	if($so[1] == 0)
	{
		$row['giagoc'] = $so[0];
	}
}

if($row['st_dtt'] > 0)
{
	$row['st_dtt'] = number_format($row['st_dtt'],3 );
	$so = explode('.',$row['st_dtt']);
	if($so[1] == 0)
	{
		$row['st_dtt'] = $so[0];
	}
}

if($row['phikhac'] > 0)
{
	$row['phikhac'] = number_format($row['phikhac'],3 );
	$so = explode('.',$row['phikhac']);
	if($so[1] == 0)
	{
		$row['phikhac'] = $so[0];
	}
}

if($row['tong_tien'] > 0)
{
	$row['tong_tien'] = number_format($row['tong_tien'],3 );
	$so = explode('.',$row['tong_tien']);
	if($so[1] == 0)
	{
		$row['tong_tien'] = $so[0];
	}
	$xtpl->assign( 'tong_tien', $row['tong_tien']);
	$xtpl->parse( 'main.tong_tien' );
}


$xtpl->assign( 'ROW', $row );
if ( $nv_Request->isset_request( 'ma_bill', 'post,get' ) )
{
	$xtpl->assign( 'ma_bill', $ma_bill );
	$xtpl->assign( 'awbncc', $awbncc );
	$xtpl->assign( 'tenncc', $tenncc );
	$xtpl->assign( 'dia_chi_goi', $dia_chi_goi );
	$xtpl->assign( 'ngay_nhan', $ngay_nhan );
	$xtpl->assign( 'dia_chi_nhan', $dia_chi_nhan );
	$xtpl->assign( 'nguoi_nhan', $nguoi_nhan );
	$xtpl->assign( 'ngay_goi', $ngay_goi );
	$xtpl->assign( 'tai_khoan', $tai_khoan );
	$xtpl->assign( 'page', $page );
	$xtpl->parse( 'main.searchne' );
}
if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

// THỜI GIAN NHẬN
list($phour_nhan, $pmin_nhan) = explode('|', $tdate_nhan);
$select = '';

for ($i = 0; $i <= 23; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $phour_nhan) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('phour_nhan', $select);
$select = '';
for ($i = 0; $i < 60; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $pmin_nhan) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('pmin_nhan', $select);

// THỜI GIAN PHÁT
list($phour_goi, $pmin_goi) = explode('|', $tdate_goi);
$select = '';
for ($i = 0; $i <= 23; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $phour_goi) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('phour_goi', $select);
$select = '';
for ($i = 0; $i < 60; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $pmin_goi) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('pmin_goi', $select);

if($row['id'] > 0)
{
	$xtpl->parse( 'main.capnhat' );
}
else $xtpl->parse( 'main.them' );

if ( $nv_Request->isset_request( 't', 'get' ) )
{
	$xtpl->parse( 'main.themtc' );
}

$xtpl->assign( 'NV_BASE_SITEURL',NV_BASE_SITEURL);
$xtpl->assign( 'NV_ASSETS_DIR',NV_ASSETS_DIR);
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['main'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';