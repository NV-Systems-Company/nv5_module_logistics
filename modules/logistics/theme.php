<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_IS_MOD_LOGISTICS' ) ) die( 'Stop!!!' );

/**
 * nv_theme_logistics_main()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_logistics_main ( $array_data )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

    

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}


function nv_theme_logistics_print( $array_data, $list_document, $list_service, $list_surcharge )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $db;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
//print_r($lang_module);die;
    if(!empty($array_data))
	{
		$array_data['add_date'] = date('d/m/Y - h:i',$array_data['add_date']);
		
		$array_data['value_goods'] = number_format($array_data['value_goods'],0,",",".");
		
		$barcodeType= 'code39';
		$barcodeDisplay= 'horizontal';
		$barcodeSize= 20;
		$printText= 'true';
		
		$array_data['barcode'] = '<img class="barcode" alt="'.$array_data['bill'].'" src="'.NV_BASE_SITEURL .'logistics/barcode/?text='.$array_data['bill'].'&codetype='.$barcodeType.'&orientation='.$barcodeDisplay.'&size='.$barcodeSize.'&print='.$printText.'" />';
		
		// LẤY TỈNH THÀNH QUẬN HUYỆN NGƯỜI NHẬN RA
		$tinhthanh = $quanhuyen =$xaphuong = '';
		if($array_data['send_city'] > 0)
		$tinhthanh = $db->query('SELECT type, title FROM tms_location_province WHERE status = 1 and provinceid = '. $array_data['send_city'])->fetch();
		
		if($array_data['send_district'] > 0 and $array_data['send_city'] > 0)
		$quanhuyen = $db->query('SELECT type, title FROM tms_location_district WHERE status = 1 and provinceid = '. $array_data['send_city'] .' and  districtid = '. $array_data['send_district'])->fetch();
		
		if($array_data['send_wards'] > 0 and $array_data['send_district'] > 0)
		$xaphuong = $db->query('SELECT type, title FROM tms_location_ward WHERE status = 1 and districtid = '. $array_data['send_district'] .' and wardid ='. $array_data['send_wards'] )->fetch();
		
		$array_data['receive_address'] =  $array_data['receive_address'] . ' ' . $xaphuong['type'] . ' ' . $xaphuong['title'] . ' ' . $quanhuyen['type'] . ' ' . $quanhuyen['title'] . ' ' . $tinhthanh['type'] . ' ' . $tinhthanh['title'];
		
		// KẾT THÚC LẤY THÀNH PHỐ QUẬN HUYỆN NGƯỜI NHẬN RA
		
		$xtpl->assign('row', $array_data);
		
		foreach($list_document as $document)
		{
			if($document['id'] == $array_data['id_document'])
				$document['checked'] = 'checked=checked';
			else $document['checked'] = '';
			$xtpl->assign('document', $document);
			$xtpl->parse('main.document');
		}
		
		foreach($list_service as $service)
		{
			if($service['id'] == $array_data['id_service'])
				$service['checked'] = 'checked=checked';
			else $service['checked'] = '';
			$xtpl->assign('service', $service);
			$xtpl->parse('main.service');
		}
		
		$mang_phuthu = explode(',',$array_data['id_surcharge']);
		foreach($list_surcharge as $surcharge)
		{
			if(in_array($surcharge['id'],$mang_phuthu))
				$surcharge['checked'] = 'checked=checked';
			else $surcharge['checked'] = '';
			$xtpl->assign('surcharge', $surcharge);
			$xtpl->parse('main.surcharge');
		}
		
		if(!empty($array_data['lichtrinh']))
		{
			foreach($array_data['lichtrinh'] as $lichtrinh)
			{
				$lichtrinh['add_date'] = date('d/m/Y - h:i',$lichtrinh['add_date']);
				$xtpl->assign('lichtrinh', $lichtrinh);						
			$xtpl->parse('main.lichtrinh.loop');
			}
			$xtpl->parse('main.lichtrinh');
		}
		
		if($array_data['long_document'] > 0 and $array_data['wide'] > 0 and $array_data['height'] > 0)
		$xtpl->parse('main.quydoi');
		
		
		
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}




function nv_theme_logistics_print_all( $data, $list_document, $list_service, $list_surcharge )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $db;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

    if(!empty($data))
	{
		foreach($data as $array_data)
		{
			//PRINT_R($data);DIE;
			$array_data['add_date'] = date('d/m/Y - h:i',$array_data['add_date']);
			
			$array_data['value_goods'] = number_format($array_data['value_goods'],0,",",".");
			
			$barcodeType= 'code39';
			$barcodeDisplay= 'horizontal';
			$barcodeSize= 20;
			$printText= 'true';
			
			$array_data['barcode'] = '<img class="barcode" alt="'.$array_data['bill'].'" src="'.NV_BASE_SITEURL .'logistics/barcode/?text='.$array_data['bill'].'&codetype='.$barcodeType.'&orientation='.$barcodeDisplay.'&size='.$barcodeSize.'&print='.$printText.'" />';
			
			// LẤY TỈNH THÀNH QUẬN HUYỆN NGƯỜI NHẬN RA
			$tinhthanh = $quanhuyen =$xaphuong = '';
			if($array_data['send_city'] > 0)
			$tinhthanh = $db->query('SELECT type, title FROM tms_location_province WHERE status = 1 and provinceid = '. $array_data['send_city'])->fetch();
			
			if($array_data['send_district'] > 0 and $array_data['send_city'] > 0)
			$quanhuyen = $db->query('SELECT type, title FROM tms_location_district WHERE status = 1 and provinceid = '. $array_data['send_city'] .' and  districtid = '. $array_data['send_district'])->fetch();
			
			if($array_data['send_wards'] > 0 and $array_data['send_district'] > 0)
			$xaphuong = $db->query('SELECT type, title FROM tms_location_ward WHERE status = 1 and districtid = '. $array_data['send_district'] .' and wardid ='. $array_data['send_wards'] )->fetch();
			
			$array_data['receive_address'] =  $array_data['receive_address'] . ' ' . $xaphuong['type'] . ' ' . $xaphuong['title'] . ' ' . $quanhuyen['type'] . ' ' . $quanhuyen['title'] . ' ' . $tinhthanh['type'] . ' ' . $tinhthanh['title'];
			
			// KẾT THÚC LẤY THÀNH PHỐ QUẬN HUYỆN NGƯỜI NHẬN RA
			
			$xtpl->assign('row', $array_data);
			
			foreach($list_document as $document)
			{
				if($document['id'] == $array_data['id_document'])
					$document['checked'] = 'checked=checked';
				else $document['checked'] = '';
				$xtpl->assign('document', $document);
				$xtpl->parse('main.print.document');
			}
			
			foreach($list_service as $service)
			{
				if($service['id'] == $array_data['id_service'])
					$service['checked'] = 'checked=checked';
				else $service['checked'] = '';
				$xtpl->assign('service', $service);
				$xtpl->parse('main.print.service');
			}
			
			$mang_phuthu = explode(',',$array_data['id_surcharge']);
			foreach($list_surcharge as $surcharge)
			{
				if(in_array($surcharge['id'],$mang_phuthu))
					$surcharge['checked'] = 'checked=checked';
				else $surcharge['checked'] = '';
				$xtpl->assign('surcharge', $surcharge);
				$xtpl->parse('main.print.surcharge');
			}
			
			if(!empty($array_data['lichtrinh']))
			{
				foreach($array_data['lichtrinh'] as $lichtrinh)
				{
					$lichtrinh['add_date'] = date('d/m/Y - h:i',$lichtrinh['add_date']);
					$xtpl->assign('lichtrinh', $lichtrinh);						
				$xtpl->parse('main.print.lichtrinh.loop');
				}
				$xtpl->parse('main.print.lichtrinh');
			}
			
			if($array_data['long_document'] > 0 and $array_data['wide'] > 0 and $array_data['height'] > 0)
			$xtpl->parse('main.print.quydoi');
			
			$xtpl->parse('main.print');
		
		}
		
		
		
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}


/**
 * nv_theme_logistics_detail()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_logistics_detail ( $array_data, $list_document, $list_service, $list_surcharge )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $db;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'login', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users', true));
//print_r($lang_module);die;

	
    if(!empty($array_data))
	{
		$array_data['add_date'] = date('d/m/Y - h:i',$array_data['add_date']);
		
		$array_data['value_goods'] = number_format($array_data['value_goods'],0,",",".");
		
		$barcodeType= 'code39';
		$barcodeDisplay= 'horizontal';
		$barcodeSize= 20;
		$printText= 'true';
		
		$array_data['barcode'] = '<img class="barcode" alt="'.$array_data['bill'].'" src="'.NV_BASE_SITEURL .'logistics/barcode/?text='.$array_data['bill'].'&codetype='.$barcodeType.'&orientation='.$barcodeDisplay.'&size='.$barcodeSize.'&print='.$printText.'" />';
		
		// LẤY TỈNH THÀNH QUẬN HUYỆN NGƯỜI NHẬN RA
		$tinhthanh = $quanhuyen =$xaphuong = '';
		if($array_data['send_city'] > 0)
		$tinhthanh = $db->query('SELECT type, title FROM tms_location_province WHERE status = 1 and provinceid = '. $array_data['send_city'])->fetch();
		
		if($array_data['send_district'] > 0 and $array_data['send_city'] > 0)
		$quanhuyen = $db->query('SELECT type, title FROM tms_location_district WHERE status = 1 and provinceid = '. $array_data['send_city'] .' and  districtid = '. $array_data['send_district'])->fetch();
		
		if($array_data['send_wards'] > 0 and $array_data['send_district'] > 0)
		$xaphuong = $db->query('SELECT type, title FROM tms_location_ward WHERE status = 1 and districtid = '. $array_data['send_district'] .' and wardid ='. $array_data['send_wards'] )->fetch();
		
		if(!empty($tinhthanh['type']))
		$array_data['receive_address'] =  $array_data['receive_address'] . ' ' . $xaphuong['type'] . ' ' . $xaphuong['title'] . ' ' . $quanhuyen['type'] . ' ' . $quanhuyen['title'] . ' ' . $tinhthanh['type'] . ' ' . $tinhthanh['title'];
		
		// KẾT THÚC LẤY THÀNH PHỐ QUẬN HUYỆN NGƯỜI NHẬN RA
		$xtpl->assign('row', $array_data);
		
		foreach($list_document as $document)
		{
			if($document['id'] == $array_data['id_document'])
				$document['checked'] = 'checked=checked';
			else $document['checked'] = '';
			$xtpl->assign('document', $document);
			$xtpl->parse('main.document');
		}
		
		foreach($list_service as $service)
		{
			if($service['id'] == $array_data['id_service'])
				$service['checked'] = 'checked=checked';
			else $service['checked'] = '';
			$xtpl->assign('service', $service);
			$xtpl->parse('main.service');
		}
		
		$mang_phuthu = explode(',',$array_data['id_surcharge']);
		foreach($list_surcharge as $surcharge)
		{
			if(in_array($surcharge['id'],$mang_phuthu))
				$surcharge['checked'] = 'checked=checked';
			else $surcharge['checked'] = '';
			$xtpl->assign('surcharge', $surcharge);
			$xtpl->parse('main.surcharge');
		}
		
		if(!empty($array_data['lichtrinh']))
		{
			foreach($array_data['lichtrinh'] as $lichtrinh)
			{
				$lichtrinh['add_date'] = date('d/m/Y - h:i',$lichtrinh['add_date']);
				$xtpl->assign('lichtrinh', $lichtrinh);				if(!empty($lichtrinh['receiver'])){$xtpl->parse('main.lichtrinh.loop.receiver');}				if(!empty($lichtrinh['employees'])){$xtpl->parse('main.lichtrinh.loop.employees');}				if(!empty($lichtrinh['note'])){$xtpl->parse('main.lichtrinh.loop.note');}
				$xtpl->parse('main.lichtrinh.loop');
			}
			$xtpl->parse('main.lichtrinh');
		}
		
		if($array_data['long_document'] > 0 and $array_data['wide'] > 0 and $array_data['height'] > 0)
		$xtpl->parse('main.quydoi');
		
		
		if (!defined('NV_IS_USER')) {
				$xtpl->parse( 'main.login1' );
				$xtpl->parse( 'main.login2' );
			}
			else
			{
				$xtpl->parse( 'main.login_ok1' );	
				$xtpl->parse( 'main.login_ok2' );	
			}
		
		
	}
	
	

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_logistics_search()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_logistics_search ( $data, $list_document, $list_service, $list_surcharge )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $db;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'login', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users', true));
	//print_r($lang_module);die;


    if(!empty($data))
	{
		$barcodeType= 'code39';
		$barcodeDisplay= 'horizontal';
		$barcodeSize= 20;
		$printText= 'true';
		
		foreach($data as $array_data)
		{
			//print_r($data);die;
			$array_data['add_date'] = date('d/m/Y - h:i',$array_data['add_date']);
			
			$array_data['value_goods'] = number_format($array_data['value_goods'],0,",",".");
			
			$array_data['barcode'] = '<img class="barcode" alt="'.$array_data['bill'].'" src="'.NV_BASE_SITEURL .'logistics/barcode/?text='.$array_data['bill'].'&codetype='.$barcodeType.'&orientation='.$barcodeDisplay.'&size='.$barcodeSize.'&print='.$printText.'" />';
			
			// LẤY TỈNH THÀNH QUẬN HUYỆN NGƯỜI NHẬN RA
			$tinhthanh = $quanhuyen =$xaphuong = '';
			if($array_data['send_city'] > 0)
			$tinhthanh = $db->query('SELECT type, title FROM tms_location_province WHERE status = 1 and provinceid = '. $array_data['send_city'])->fetch();
			
			if($array_data['send_district'] > 0 and $array_data['send_city'] > 0)
			$quanhuyen = $db->query('SELECT type, title FROM tms_location_district WHERE status = 1 and provinceid = '. $array_data['send_city'] .' and  districtid = '. $array_data['send_district'])->fetch();
			
			if($array_data['send_wards'] > 0 and $array_data['send_district'] > 0)
			$xaphuong = $db->query('SELECT type, title FROM tms_location_ward WHERE status = 1 and districtid = '. $array_data['send_district'] .' and wardid ='. $array_data['send_wards'] )->fetch();
			
			if(!empty($tinhthanh['type']))
			$array_data['receive_address'] =  $array_data['receive_address'] . ' ' . $xaphuong['type'] . ' ' . $xaphuong['title'] . ' ' . $quanhuyen['type'] . ' ' . $quanhuyen['title'] . ' ' . $tinhthanh['type'] . ' ' . $tinhthanh['title'];
			
			// KẾT THÚC LẤY THÀNH PHỐ QUẬN HUYỆN NGƯỜI NHẬN RA
			
			$xtpl->assign('row', $array_data);
			
			foreach($list_document as $document)
			{
				if($document['id'] == $array_data['id_document'])
					$document['checked'] = 'checked=checked';
				else $document['checked'] = '';
				$xtpl->assign('document', $document);
				$xtpl->parse('main.list.document');
			}
			
			foreach($list_service as $service)
			{
				if($service['id'] == $array_data['id_service'])
					$service['checked'] = 'checked=checked';
				else $service['checked'] = '';
				$xtpl->assign('service', $service);
				$xtpl->parse('main.list.service');
			}
			
			$mang_phuthu = explode(',',$array_data['id_surcharge']);
			foreach($list_surcharge as $surcharge)
			{
				if(in_array($surcharge['id'],$mang_phuthu))
					$surcharge['checked'] = 'checked=checked';
				else $surcharge['checked'] = '';
				$xtpl->assign('surcharge', $surcharge);
				$xtpl->parse('main.list.surcharge');
			}
			
			if(!empty($array_data['lichtrinh']))
			{
				foreach($array_data['lichtrinh'] as $lichtrinh)
				{
					$lichtrinh['add_date'] = date('d/m/Y - h:i',$lichtrinh['add_date']);
					$xtpl->assign('lichtrinh', $lichtrinh);				if(!empty($lichtrinh['receiver'])){$xtpl->parse('main.list.lichtrinh.loop.receiver');}				if(!empty($lichtrinh['employees'])){$xtpl->parse('main.list.lichtrinh.loop.employees');}				if(!empty($lichtrinh['note'])){$xtpl->parse('main.list.lichtrinh.loop.note');}
					$xtpl->parse('main.list.lichtrinh.loop');
				}
				$xtpl->parse('main.list.lichtrinh');
			}
			
			if($array_data['long_document'] > 0 and $array_data['wide'] > 0 and $array_data['height'] > 0)
			$xtpl->parse('main.list.quydoi');
			
			if (!defined('NV_IS_USER')) {
				$xtpl->parse( 'main.list.login1' );
				$xtpl->parse( 'main.list.login2' );
			}
			else
			{
				$xtpl->parse( 'main.list.login_ok1' );	
				$xtpl->parse( 'main.list.login_ok2' );	
			}
			
			$xtpl->parse('main.list');
			
		}
		
	}
	else
	{
		$xtpl->parse( 'main.empty' );
	}
	
	

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_logistics_add()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_logistics_add ( $array_data )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

    

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_logistics_items()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_logistics_items ( $array_data )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

    

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_logistics_store()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_logistics_store ( $array_data )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

    

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}