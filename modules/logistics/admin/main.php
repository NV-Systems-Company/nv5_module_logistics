<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

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

// THỐNG KÊ TOÀN THỜI GIAN
$where = '';
$tong_vandon = $db->query('SELECT count(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1')->fetchColumn();
$xtpl->assign( 'tong_vandon', $tong_vandon );

$tong_khachhang = $db->query('SELECT count(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 GROUP BY userid_add')->fetchColumn();
$xtpl->assign( 'tong_khachhang', $tong_khachhang );

// đối soát là trình trạng cuối cùng của lịch trình
$id_doisoat = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_schedule WHERE status =1')->fetchColumn();

$sql = 'SELECT count(t1.id) FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1 LEFT JOIN '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill WHERE t1.status = 1 AND t2.status = '. $id_doisoat .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
$tong_doisoat = $db->query($sql)->fetchColumn();
$xtpl->assign( 'tong_doisoat', $tong_doisoat );

// trả hàng là lịch trình gần cuối cùng

$id_trahang = $id_doisoat - 1;
$sql = 'SELECT count(t1.id) FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1 LEFT JOIN '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill WHERE t1.status = 1 AND t2.status = '. $id_trahang .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
$tong_trahang = $db->query($sql)->fetchColumn();
$xtpl->assign( 'tong_trahang', $tong_trahang );

$tong_thu = $db->query('SELECT sum(value_goods) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1')->fetchColumn();
$xtpl->assign( 'tong_thu', number_format($tong_thu,0,",",",") );

$tong_cuocphi = $db->query('SELECT sum(total_money) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1')->fetchColumn();
$xtpl->assign( 'tong_cuocphi', number_format($tong_cuocphi,0,",",",") );


//print_r($tong_thuho);die;

// KẾT THÚC THỐNG KÊ TOÀN THỜI GIAN


// THỐNG KÊ THÁNG HIỆN TẠI

$thang_nam = date('m/Y',NV_CURRENTTIME);
$xtpl->assign( 'thang_nam', $thang_nam);
$ngay_dautien_thang = '01/'.$thang_nam;

$date_first = 0;
if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $ngay_dautien_thang, $m ) )
{
		$_hour = $nv_Request->get_int( 'add_date_hour', 'post', 0 );
		$_min = $nv_Request->get_int( 'add_date_min', 'post', 0 );
		$date_first = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
}
		

$tong_vandon_thang = $db->query('SELECT count(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND add_date >= '. $date_first . ' AND add_date <= '. NV_CURRENTTIME)->fetchColumn();
$xtpl->assign( 'tong_vandon_thang', $tong_vandon_thang );

$tong_khachhang_thang = $db->query('SELECT count(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND add_date >= '. $date_first . ' AND add_date <= '. NV_CURRENTTIME .' GROUP BY userid_add')->fetchColumn();
$xtpl->assign( 'tong_khachhang_thang', $tong_khachhang_thang );

// đối soát là trình trạng cuối cùng của lịch trình
$id_doisoat = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_schedule WHERE status =1')->fetchColumn();

$sql = 'SELECT sum(t1.total_money) FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1 LEFT JOIN '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill WHERE t1.status = 1 AND t1.add_date >= '. $date_first . ' AND t1.add_date <= '. NV_CURRENTTIME .' AND t2.status = '. $id_doisoat .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
$tong_doisoat_thang = $db->query($sql)->fetchColumn();
$xtpl->assign( 'tong_doisoat_thang', number_format($tong_doisoat_thang,0,",",",") );

// trả hàng là lịch trình gần cuối cùng

$id_dagiaohang = $id_doisoat - 2;
$sql = 'SELECT sum(t1.total_money) FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1 LEFT JOIN '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill WHERE t1.status = 1 AND t1.add_date >= '. $date_first . ' AND t1.add_date <= '. NV_CURRENTTIME .' AND t2.status = '. $id_dagiaohang .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
$chuadoisoat_tk_thang = $db->query($sql)->fetchColumn();
$xtpl->assign( 'chuadoisoat_tk_thang', number_format($chuadoisoat_tk_thang,0,",",",") );

// đang giao hàng 

$id_danggiao = $id_doisoat - 4;
$sql = 'SELECT count(t1.id) FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1 LEFT JOIN '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill WHERE t1.status = 1 AND t1.add_date >= '. $date_first . ' AND t1.add_date <= '. NV_CURRENTTIME .' AND t2.status = '. $id_danggiao .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
$danggiao_thang = $db->query($sql)->fetchColumn();
$xtpl->assign( 'danggiao_thang', $danggiao_thang );


//print_r($chuadoisoat_tk_thang);die;

$tong_thu_thang = $db->query('SELECT sum(value_goods) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND add_date >= '. $date_first . ' AND add_date <= '. NV_CURRENTTIME)->fetchColumn();
$xtpl->assign( 'tong_thu_thang', number_format($tong_thu_thang,0,",",",") );

$tong_cuocphi_thang = $db->query('SELECT sum(total_money) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND add_date >= '. $date_first . ' AND add_date <= '. NV_CURRENTTIME)->fetchColumn();
$xtpl->assign( 'tong_cuocphi_thang', number_format($tong_cuocphi_thang,0,",",",") );

//print_r($tong_thuho);die;

// KẾT THÚC THỐNG KÊ THEO THÁNG HIỆN TẠI



// THỐNG KÊ NGÀY HIỆN TẠI

$thang_nam = date('m/Y',NV_CURRENTTIME);
$ngay = date('d',NV_CURRENTTIME);
$xtpl->assign( 'thang_nam', $thang_nam);
$ngay_hientai = $ngay.'/'.$thang_nam;

$date_first = 0;
if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $ngay_hientai, $m ) )
{
		$_hour = $nv_Request->get_int( 'add_date_hour', 'post', 0 );
		$_min = $nv_Request->get_int( 'add_date_min', 'post', 0 );
		$date_first = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
}
	

$tong_vandon_ngay = $db->query('SELECT count(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND add_date >= '. $date_first . ' AND add_date <= '. NV_CURRENTTIME)->fetchColumn();
$xtpl->assign( 'tong_vandon_ngay', $tong_vandon_ngay );

$tong_khachhang_ngay = $db->query('SELECT count(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND add_date >= '. $date_first . ' AND add_date <= '. NV_CURRENTTIME .' GROUP BY userid_add')->fetchColumn();
$xtpl->assign( 'tong_khachhang_ngay', $tong_khachhang_ngay );

// đối soát là trình trạng cuối cùng của lịch trình
$id_doisoat = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_schedule WHERE status =1')->fetchColumn();

$sql = 'SELECT sum(t1.total_money) FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1 LEFT JOIN '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill WHERE t1.status = 1 AND t1.add_date >= '. $date_first . ' AND t1.add_date <= '. NV_CURRENTTIME .' AND t2.status = '. $id_doisoat .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
$tong_doisoat_ngay = $db->query($sql)->fetchColumn();
$xtpl->assign( 'tong_doisoat_ngay', number_format($tong_doisoat_ngay,0,",",",") );

// trả hàng là lịch trình gần cuối cùng

$id_dagiaohang = $id_doisoat - 2;
$sql = 'SELECT sum(t1.total_money) FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1 LEFT JOIN '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill WHERE t1.status = 1 AND t1.add_date >= '. $date_first . ' AND t1.add_date <= '. NV_CURRENTTIME .' AND t2.status = '. $id_dagiaohang .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
$chuadoisoat_tk_ngay = $db->query($sql)->fetchColumn();
$xtpl->assign( 'chuadoisoat_tk_ngay', number_format($chuadoisoat_tk_ngay,0,",",",") );

// đang giao hàng 

$id_danggiao = $id_doisoat - 4; //print_r($id_danggiao);die;
$sql = 'SELECT count(t1.id) FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1 LEFT JOIN '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill WHERE t1.status = 1 AND t1.add_date >= '. $date_first . ' AND t1.add_date <= '. NV_CURRENTTIME .' AND t2.status = '. $id_danggiao .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
$danggiao_ngay = $db->query($sql)->fetchColumn();
$xtpl->assign( 'danggiao_ngay', $danggiao_ngay );

//print_r($chuadoisoat_tk_thang);die;

$tong_thu_ngay = $db->query('SELECT sum(value_goods) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND add_date >= '. $date_first . ' AND add_date <= '. NV_CURRENTTIME)->fetchColumn();
$xtpl->assign( 'tong_thu_ngay', number_format($tong_thu_ngay,0,",",",") );

$tong_cuocphi_ngay = $db->query('SELECT sum(total_money) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND add_date >= '. $date_first . ' AND add_date <= '. NV_CURRENTTIME)->fetchColumn();
$xtpl->assign( 'tong_cuocphi_ngay', number_format($tong_cuocphi_ngay,0,",",",") );

//print_r($tong_thuho);die;

// KẾT THÚC THỐNG KÊ NGÀY HIỆN TẠI


// THỐNG KÊ TRẠNG THÁI VẬN ĐƠN HIỂN THỊ NGOÀI TRANG CHỦ
	
	$list_schedule_home = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule WHERE status = 1 AND statistical = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_schedule_home as $schedule)
	{		
		// ĐẾM CÓ SỐ LƯỢNG VẬN ĐƠN TRÌNH TRẠNG NÀY


		$sql = 'SELECT t1.*, t2.status FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1 LEFT JOIN '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill WHERE t1.status = 1 '. $where .' AND t2.status = '. $schedule['id'] .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id) ORDER BY t1.id DESC LIMIT 0,5';
		$rows_bill = $db->query($sql)->fetchAll();
		$xtpl->assign('schedule', $schedule);
		foreach($rows_bill as $row)
		{
			$row['add_date'] = date('d/m/Y - H:i',$row['add_date']);
			$row['total_money'] = number_format($row['total_money'],0,",",".");
			$xtpl->assign('row', $row);
			//print_r($row);die;
			$xtpl->parse('main.bill_home.loop');
		}
		
		
		
        $xtpl->parse('main.bill_home');
	}


$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['main'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';