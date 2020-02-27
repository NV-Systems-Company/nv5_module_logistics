<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if (!defined('NV_IS_USER')) {
    Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users', true));
    die();
}
 
if ( ! defined( 'NV_IS_MOD_LOGISTICS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];


$array_data = array();

//print_r($array_data);die;

// TÌM KIẾM IN RA DANH SÁCH VẬN ĐƠN
$where = '';
$bill = $nv_Request->get_title( 'bill', 'post,get' );
$status = $nv_Request->get_int( 'status', 'post,get', 0 );
$store = $nv_Request->get_title( 'store', 'post,get' );
$phone = $nv_Request->get_title( 'phone', 'post,get' );
$ngay_tu = $nv_Request->get_title( 'ngay_tu', 'post,get' );
$ngay_den = $nv_Request->get_title( 'ngay_den', 'post,get' );



if(!empty($bill))
{
	$where .=" AND bill like '". $bill ."'";
}

if( $status > 0 )
{
	$where .= ' AND t2.status = '. $status .' AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
}

if(!empty($store))
{
	$where .=" AND id_store =".$store;
}

if(!empty($phone))
{
	$where .=" AND receive_phone like '". $phone ."'";
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
	$where_lichtrinh .=" AND t1.add_date >= ". $ngay_tu . " AND t1.add_date <= ". $ngay_den;
	$base_url .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
	$base_url_lichtrinh .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
}
elseif( $ngay_tu > 0 )
{
	$where .=" AND t1.add_date >= ". $ngay_tu;
	$where_lichtrinh .=" AND t1.add_date >= ". $ngay_tu;
	$base_url .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
	$base_url_lichtrinh .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
}
elseif( $ngay_den > 0 )
{
	$where .=" AND t1.add_date <= ". $ngay_den;
	$where_lichtrinh .=" AND t1.add_date <= ". $ngay_den;
	$base_url .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
	$base_url_lichtrinh .= '&ngay_tu=' . date('d/m/Y',$ngay_tu) .'&ngay_den='.date('d/m/Y',$ngay_den);
}



if(!empty($where))
{

	$db->sqlreset()
		->select( 'COUNT(DISTINCT(t1.id)), max(t2.id)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_rows t1' )
		->join('LEFT JOIN ' . $db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill');

	$db->where( 't1.status = 1 AND userid_add ='. $user_info['userid'] . $where);
	
	$sth = $db->prepare( $db->sql() );

	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select('DISTINCT(t1.id), t1.*')
		->order( 'id DESC' );
	$sth = $db->prepare( $db->sql() );
	
//	die($db->sql());
	$data = $db->query( $db->sql() )->fetchAll();
	
	foreach($data as $row)
	{
		// LẤY DANH SÁCH LỊCH TRÌNH

		$row['lichtrinh'] = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t1, '.$db_config['prefix'] . '_' . $module_data . '_schedule t2  WHERE t1.status = t2.id AND t2.status = 1 AND t1.id_bill ='. $row['id'] .' AND t1.active = 1 ORDER BY t1.weight DESC')->fetchAll();
		
		$array_data[] = $row;
	}

}

// KẾT THÚC TÌM KIẾM IN TẤT CẢ VẬN ĐƠN



// LẤY DANH SÁCH TÀI LIỆU HÓA
	$list_document = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_document WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
// LẤY DANH SÁCH DỊCH VỤ
	$list_service = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_service WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
// LẤY DANH SÁCH PHỤ THU
	$list_surcharge = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_surcharge WHERE status = 1 ORDER BY weight ASC')->fetchAll();

//print_r($list_lichtrinh);die;





$contents = nv_theme_logistics_print_all( $array_data, $list_document, $list_service, $list_surcharge );

//include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, false);
//include NV_ROOTDIR . '/includes/footer.php';
