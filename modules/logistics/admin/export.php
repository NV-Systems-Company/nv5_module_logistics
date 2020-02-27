<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

require( NV_ROOTDIR . "/includes/class/php-excel.class.php" );
require( NV_ROOTDIR . "/includes/class/excel_reader.php" );
if ( $nv_Request->isset_request( 'ma_bill', 'post,get' ) )
	{
		$ma_bill = $nv_Request->get_string( 'ma_bill', 'post,get', '');
		$awbncc = $nv_Request->get_string( 'awbncc', 'post,get', '');
		$tenncc = $nv_Request->get_string( 'tenncc', 'post,get', '');
		$dia_chi_goi = $nv_Request->get_string( 'dia_chi_goi', 'post,get', '');
		$ngay_nhan = $nv_Request->get_string( 'ngay_nhan', 'post,get', '');
		$dia_chi_nhan = $nv_Request->get_string( 'dia_chi_nhan', 'post,get', '');
		$nguoi_nhan = $nv_Request->get_int( 'nguoi_nhan', 'post,get',0);
		$gio_goi = $nv_Request->get_string( 'gio_goi', 'post,get', '');
		$ngay_goi = $nv_Request->get_string( 'ngay_gioi', 'post,get', '');
		$tai_khoan = $nv_Request->get_string( 'tai_khoan', 'post,get', '');
		$ghi_chu = $nv_Request->get_string( 'ghi_chu', 'post,get', '');
		$ngay_moinhat_tam = $nv_Request->get_string( 'ngay_moi_nhat', 'post,get', '');
		$search = "";
		if(!empty($ma_bill))
		{
			$search = " AND t1.ma_bill LIKE '%".$ma_bill."%'";
		}
		if($nguoi_nhan == 1)
		{
			$search .= " AND t1.nguoi_nhan != ''";
		}
		if($nguoi_nhan == 2)
		{
			$search .= " AND t1.nguoi_nhan = ''";
		}
		if(!empty($awbncc))
		{
			$search .= " AND t1.awbncc LIKE '%".$awbncc."%'";
		}
		if(!empty($tenncc))
		{
			$search .= " AND t1.tenncc LIKE '%".$tenncc."%'";
		}
		if(!empty($dia_chi_goi))
		{
			$search .= " AND t1.dia_chi_goi LIKE '%".$dia_chi_goi."%'";
		}
		if(!empty($ghi_chu))
		{
			$search .= " AND t1.ghi_chu LIKE '%".$ghi_chu."%'";
		}
		
		if(!empty($ngay_moinhat_tam))
		{
			
			
	
			if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $ngay_moinhat_tam, $m)) {
				$phour = $nv_Request->get_int('phour', 'post', 0);
				$pmin = $nv_Request->get_int('pmin', 'post', 0);
			   $ngay_moinhat_tu = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
			   $m[1] = $m[1] + 1;
			   $ngay_moinhat_den = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
			  $search .= " AND t2.ngay >= ".$ngay_moinhat_tu." AND t2.ngay < ".$ngay_moinhat_den;
			 // print(date('d/m/y-H:I',1515430800 ));die;
			}
			else {
				$m = explode('/',$ngay_moinhat_tam);
				if(count($m) == 2)
				{
					if( preg_match( "/^([0-9]{1,2})\/([0-9]{4})$/", $ngay_nhan, $a ) )
					{
					$max_day = cal_days_in_month( CAL_GREGORIAN, $a[1], $a[2] ); //lay ngay lon nhat cua thang						
					$ngay_moinhat_tu = mktime(0, 0, 0, $m[0],1, $m[1]);
					$ngay_moinhat_den = mktime(23, 59, 59, $m[0],$max_day, $m[1]);
					$search .= " AND t2.ngay >= ".$ngay_moinhat_tu." AND t2.ngay < ".$ngay_moinhat_den;
					
					}
				}
				if(count($m) == 1)
				{
					$ngay_moinhat_tu = mktime(0, 0, 0, 1, 1, $m[0]);
					$m[0] = $m[0] + 1;
					$ngay_moinhat_den = mktime(0, 0, 0, 1, 1, $m[0]);
					$search .= " AND t2.ngay >= ".$ngay_moinhat_tu." AND t2.ngay < ".$ngay_moinhat_den;
					
				}
			}
			//die($search);
		}
		
		
		if(!empty($ngay_nhan))
		{
			if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $ngay_nhan, $m)) {
				$phour = $nv_Request->get_int('phour', 'post', 0);
				$pmin = $nv_Request->get_int('pmin', 'post', 0);
			   $ngaynhan_tu = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
			   $m[1] = $m[1] + 1;
			   $ngaynhan_den = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
			   $search .= " AND t1.ngay_nhan >= ".$ngaynhan_tu." AND t1.ngay_nhan < ".$ngaynhan_den;
			}
			else {
				$m = explode('/',$ngay_nhan);
				if(count($m) == 2)
				{
					if( preg_match( "/^([0-9]{1,2})\/([0-9]{4})$/", $ngay_nhan, $a ) )
					{
					$max_day = cal_days_in_month( CAL_GREGORIAN, $a[1], $a[2] ); //lay ngay lon nhat cua thang						
					$ngaynhan_tu = mktime(0, 0, 0, $m[0],1, $m[1]);
					$ngaynhan_den = mktime(23, 59, 59, $m[0],$max_day, $m[1]);
					$search .= " AND t1.ngay_nhan >= ".$ngaynhan_tu." AND t1.ngay_nhan <= ".$ngaynhan_den;
					
					}
				}
				if(count($m) == 1)
				{
					$ngaynhan_tu = mktime(0, 0, 0, 1, 1, $m[0]);
					$m[0] = $m[0] + 1;
					$ngaynhan_den = mktime(0, 0, 0, 1, 1, $m[0]);
					$search .= " AND t1.ngay_nhan >= ".$ngaynhan_tu." AND t1.ngay_nhan < ".$ngaynhan_den;
				}
			}
			
		}
		if(!empty($dia_chi_nhan))
		{
			$search .= " AND t1.dia_chi_nhan LIKE '%".$dia_chi_nhan."%'";
		}
		if(!empty($ngay_goi))
		{
			if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $ngay_goi, $m)) {
				$phour = $nv_Request->get_int('phour', 'post', 0);
				$pmin = $nv_Request->get_int('pmin', 'post', 0);
			   $ngaynhan_tu = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
			   $m[1] = $m[1] + 1;
			   $ngaynhan_den = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
			   $search .= " AND t1.ngay_gioi >= ".$ngaynhan_tu." AND t1.ngay_gioi < ".$ngaynhan_den;
			}
			else {
				$m = explode('/',$ngay_goi);
				if(count($m) == 2)
				{
					$ngaynhan_tu = mktime(0, 0, 0, $m[0],1, $m[1]);
					$ngaynhan_den = mktime(0, 0, 0, $m[0],31, $m[1]);
					$search .= " AND t1.ngay_gioi >= ".$ngaynhan_tu." AND t1.ngay_gioi < ".$ngaynhan_den;
				}
				if(count($m) == 1)
				{
					$ngaynhan_tu = mktime(0, 0, 0, 1, 1, $m[0]);
					$m[0] = $m[0] + 1;
					$ngaynhan_den = mktime(0, 0, 0, 1, 1, $m[0]);
					$search .= " AND t1.ngay_gioi >= ".$ngaynhan_tu." AND t1.ngay_gioi < ".$ngaynhan_den;
				}
			}
			
		}
		if(!empty($tai_khoan))
		{
			$search .= " AND t1.tai_khoan LIKE '%".$tai_khoan."%'";
		}
	
	}

$list = array();

$sql = 'SELECT * 
			FROM (
			SELECT MAX( ngay ) ngay , id_van_don
			FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh
			GROUP BY id_van_don
			) AS t2
			INNER JOIN '.$db_config['prefix'] . '_' . $module_data . '_rows t1 ON t2.id_van_don = t1.id
			WHERE t1.active = 1' . $search .'
			ORDER BY ngay_nhan ASC'; 
	

$list = $db->query($sql)->fetchAll();
//print_r($list);die;
$data[] = array ("STT","TRACKING","AWBNCC", "TENNCC","TP - NUOC DEN", "NUOC", "GIONHAN", "NGAYNHAN", "NOINHAN", "NGUOINHAN" , "GIOPHAT" , "NGAYPHAT", "MAKH" , "NVGN" , "KL" , "ĐĐ", "BGAD", "GIAGOC", "PHIKHAC", "%PPXD","PPXD", "%VAT","VAT","TONGTIEN", "%CHIETKHAU","CHIETKHAU", "ST_DTT", "NGAY_MOI_NHAT", "TRANG_THAI_MOI_NHAT");
    $i=1;
	if(!empty($list))
	{
	   foreach( $list as $row ) 
		{
			
			$row['status'] = $db->query("SELECT status FROM ". $db_config['prefix'] . "_" . $module_data . "_lich_trinh WHERE active = 1 AND id_van_don = ".$row['id']." AND ngay =".$row['ngay'])->fetchColumn();
			//print_r($row['status']);die;
			if($row['ngay'] > 0)
			$row['ngay'] = date('d/m/Y',$row['ngay']);
			else $row['ngay'] = '';
			
			if(!empty($row['ngay_nhan']))
			{
			$tdate = date('H|i', $row['ngay_nhan']);
			list($phour, $pmin) = explode('|', $tdate);
			if(($phour > 0) or ($pmin > 0))
			{
			$row['gio_nhan'] = date("H:i",$row['ngay_nhan']);
			}
			else $row['gio_nhan'] ="";
			
			$row['ngay_nhan'] = date('d/m/Y',$row['ngay_nhan']);
			}
			if(!empty($row['ngay_gioi']))
			{
			$tdate = date('H|i', $row['ngay_gioi']);
			list($phour, $pmin) = explode('|', $tdate);
			if(($phour > 0) or ($pmin > 0))
			$row['gio_goi'] = date("H:i",$row['ngay_gioi']);
			else $row['gio_goi'] ="";
			$row['ngay_gioi'] = date('d/m/Y',$row['ngay_gioi']);
			}
			
		// XUẤT PHÍ
		
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
		if($row['giagoc'] >= 0 and $row['phikhac'] >= 0 and $row['ppxd1'] >= 0 and $row['vat1'] >= 0)
		{
			$row['tong_tien'] = ($row['giagoc'] + $row['phikhac'] + $row['ppxd1'] + $row['vat1'] );
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
	
		
			$data[] = array ($i,$row['ma_bill'], $row['awbncc'], $row['tenncc'], $row['dia_chi_goi'] , $row['id_nuoc_den'], $row['gio_nhan'], $row['ngay_nhan'], $row['dia_chi_nhan'] ,$row['nguoi_nhan'], $row['gio_goi'], $row['ngay_gioi'], $row['tai_khoan'], $row['ten_nv_goi'], $row['id_khoi_luong'],$row['ghi_chu'],$row['bgad'],$row['giagoc'],$row['phikhac'],$row['ppxd'],$row['ppxd1'],$row['vat'],$row['vat1'],$row['tong_tien'],$row['chieckhau'],$row['chieckhau1'],$row['st_dtt'],$row['ngay'], $row['status']);
			$i++;
		}
    }
    // generate file (constructor parameters are optional)
    $xls = new Excel_XML('UTF-8', false, 'Sheet1');
    $xls->addArray($data);
    //$xls->generateXML(change_alias($lang_module['register']));
	$xls->generateXML("tracking");
	
	die();

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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op .'&amp;x=1';
$xtpl->assign( 'LINK', $base_url );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['export'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';