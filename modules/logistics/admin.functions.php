<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

define( 'NV_IS_FILE_ADMIN', true );


$allow_func = array( 'main', 'financials','items','add', 'approval', 'calendar', 'schedule_bill', 'import', 'store', 'store_add', 'document', 'zone', 'zone_address', 'price', 'surcharge', 'service', 'returns', 'price_document','customer_user', 'print-all');

// KHUNG GIỜ LẤY HÀNG
	$khung_gio = array(
	"1"=> $lang_module['khunggio1'],
	"2"=> $lang_module['khunggio2']
	);
	
// KHUNG GIỜ LẤY HÀNG
	$pay_array = array(
	"1"=> $lang_module['ghino'],
	"2"=> $lang_module['tienmat'],
	"3"=> $lang_module['nguoinhanhthanhtoan']
	);
	
	
function nv_theme_logistics_print_all( $data, $list_document, $list_service, $list_surcharge )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $db;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );

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
			
			if(!empty($tinhthanh))
			$array_data['receive_address'] =  $array_data['receive_address'] . ' ' . $xaphuong['type'] . ' ' . $xaphuong['title'] . ' ' . $quanhuyen['type'] . ' ' . $quanhuyen['title'] . ' ' . $tinhthanh['type'] . ' ' . $tinhthanh['title'];
			
			// KẾT THÚC LẤY THÀNH PHỐ QUẬN HUYỆN NGƯỜI NHẬN RA
			
			$xtpl->assign('row', $array_data);
			
			foreach($list_document as $document)
			{
				if($document['id'] == $array_data['id_document'])
					$document['checked'] = 'checked=checked';
				else $document['checked'] = '';
				$xtpl->assign('document', $document);
				$xtpl->parse('main.yes_print.print.document');
			}
			
			foreach($list_service as $service)
			{
				if($service['id'] == $array_data['id_service'])
					$service['checked'] = 'checked=checked';
				else $service['checked'] = '';
				$xtpl->assign('service', $service);
				$xtpl->parse('main.yes_print.print.service');
			}
			
			$mang_phuthu = explode(',',$array_data['id_surcharge']);
			foreach($list_surcharge as $surcharge)
			{
				if(in_array($surcharge['id'],$mang_phuthu))
					$surcharge['checked'] = 'checked=checked';
				else $surcharge['checked'] = '';
				$xtpl->assign('surcharge', $surcharge);
				$xtpl->parse('main.yes_print.print.surcharge');
			}
			
			if(!empty($array_data['lichtrinh']))
			{
				foreach($array_data['lichtrinh'] as $lichtrinh)
				{
					$lichtrinh['add_date'] = date('d/m/Y - h:i',$lichtrinh['add_date']);
					$xtpl->assign('lichtrinh', $lichtrinh);						
				$xtpl->parse('main.yes_print.print.lichtrinh.loop');
				}
				$xtpl->parse('main.yes_print.print.lichtrinh');
			}
			
			if($array_data['long_document'] > 0 and $array_data['wide'] > 0 and $array_data['height'] > 0)
			$xtpl->parse('main.yes_print.print.quydoi');
			
			$xtpl->parse('main.yes_print.print');
		
		}
		
		$xtpl->parse( 'main.yes_print' );
		
	}
	else
	{
		$xtpl->parse('main.no_print');
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}
