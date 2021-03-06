<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if($nv_Request->isset_request('id_tinhthanh', 'get'))
{
	$id_tinhthanh = $nv_Request->get_int('id_tinhthanh','get', 0);
	if($id_tinhthanh > 0)
	{
		$list_quan = $db->query('SELECT * FROM '. $db_config['prefix'] . '_location_district WHERE status = 1 and provinceid = '. $id_tinhthanh .' ORDER BY weight ASC')->fetchAll();
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
		$list_quan = $db->query('SELECT * FROM '. $db_config['prefix'] . '_location_ward WHERE status = 1 and districtid = '. $id_quanhuyen .' ORDER BY title ASC')->fetchAll();
		$html = '<option value=0>-- Chọn xã phường --</option>';
					foreach($list_quan as $l)
					{
						$html .= '<option value='.$l['wardid'].'>'.$l['type'] . ' '. $l['title'].'</option>';
					}
		print $html;die;
	}

}

$export_word = $nv_Request->get_int( 'export_word', 'post, get', 0 );
if($export_word == 2)
{
	$op = 'print-all';
}

//change status
if( $nv_Request->isset_request( 'change_status', 'post, get' ) )
{
	$id = $nv_Request->get_int( 'id', 'post, get', 0 );
	$content = 'NO_' . $id;

	$query = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_customer WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( isset( $row['status'] ) )
	{
		$status = ( $row['status'] ) ? 0 : 1;
		$query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_customer SET status=' . intval( $status ) . ' WHERE id=' . $id;
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
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_customer WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_customer SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_customer SET weight=' . $new_vid . ' WHERE id=' . $id;
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
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_customer WHERE id =' . $db->quote( $id );
		$result = $db->query( $sql );
		list( $weight) = $result->fetch( 3 );
		
		$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_customer  WHERE id = ' . $db->quote( $id ) );
		if( $weight > 0)
		{
			$sql = 'SELECT id, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_customer WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while(list( $id, $weight) = $result->fetch( 3 ))
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_customer SET weight=' . $weight . ' WHERE id=' . intval( $id ));
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
	$row['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$row['phone'] = $nv_Request->get_title( 'phone', 'post', '' );
	$row['name'] = $nv_Request->get_title( 'name', 'post', '' );
	$row['city'] = $nv_Request->get_int( 'city', 'post', 0 );
	$row['district'] = $nv_Request->get_int( 'district', 'post', 0 );
	$row['wards'] = $nv_Request->get_int( 'wards', 'post', 0 );
	$row['address'] = $nv_Request->get_title( 'address', 'post', '' );
	$row['note'] = $nv_Request->get_string( 'note', 'post', '' );

	if( empty( $row['title'] ) )
	{
		$error[] = $lang_module['error_required_title'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{

				$row['userid'] = $admin_info['userid'];

				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_customer (weight, title, phone, name, city, district, wards, address, note, userid, status) VALUES (:weight, :title, :phone, :name, :city, :district, :wards, :address, :note, :userid, :status)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_customer' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

				$stmt->bindParam( ':userid', $row['userid'], PDO::PARAM_INT );
				$stmt->bindValue( ':status', 1, PDO::PARAM_INT );


			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_customer SET title = :title, phone = :phone, name =:name, city = :city, district = :district, wards = :wards, address = :address, note = :note WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':phone', $row['phone'], PDO::PARAM_STR );
			$stmt->bindParam( ':name', $row['name'], PDO::PARAM_STR );
			$stmt->bindParam( ':city', $row['city'], PDO::PARAM_INT );
			$stmt->bindParam( ':district', $row['district'], PDO::PARAM_INT );
			$stmt->bindParam( ':wards', $row['wards'], PDO::PARAM_INT );
			$stmt->bindParam( ':address', $row['address'], PDO::PARAM_STR );
			$stmt->bindParam( ':note', $row['note'], PDO::PARAM_STR, strlen($row['note']) );

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
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_customer WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['title'] = '';
	$row['phone'] = '';
	$row['name'] = '';
	$row['city'] = 0;
	$row['district'] = 0;
	$row['wards'] = 0;
	$row['address'] = '';
	$row['note'] = '';
}



$where = '';

$userid = $nv_Request->get_int( 'userid', 'post,get', 0 );
if($userid > 0)
$where .= ' AND userid ='.$userid;

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 30;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_customer' );

	$db->where( 'status = 1 '. $where );
	
	$sth = $db->prepare( $db->sql() );


	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )
		->order( 'weight ASC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );

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


// LẤY DANH TRẠNG THÁI ĐƠN HÀNG
	$list_user = $db->query('SELECT * FROM '.$db_config['prefix'] . '_users WHERE active = 1 ORDER BY userid ASC')->fetchAll();
	
	foreach($list_user as $user)
	{
		
		$xtpl->assign( 'selected', $user['userid'] == $userid ? 'selected=selected' : '' );
		$xtpl->assign('user', $user);
		
        $xtpl->parse('main.user');
	}

// LẤY TỈNH THÀNH RA
	$list_tinhthanh = $db->query('SELECT provinceid, title, type FROM '. $db_config['prefix'] . '_location_province WHERE status = 1 ORDER BY weight DESC')->fetchAll();
	
	foreach($list_tinhthanh as $tinhthanh)
	{
		if($tinhthanh['provinceid'] == $row['city'])
		{
		$tinhthanh['selected'] = 'selected=selected';
		}
		else $tinhthanh['selected'] = '';
		$xtpl->assign('l', $tinhthanh);
        $xtpl->parse('main.tinh');
	}
	
	if($row['district'] > 0)
	{
		// LẤY QUẬN HUYỆN RA
		$list_quan = $db->query('SELECT districtid, title, type FROM '. $db_config['prefix'] . '_location_district WHERE provinceid = '. $row['city'] .' and status = 1 ORDER BY weight DESC')->fetchAll();
		//print_r('SELECT districtid, title, type FROM '. $db_config['prefix'] . '_location_district WHERE provinceid = '. $row['district'] .' and status = 1 ORDER BY weight DESC');die;
		foreach($list_quan as $tinhthanh)
		{
			if($tinhthanh['districtid'] == $row['district'])
			{
			$tinhthanh['selected'] = 'selected=selected';
			}
			else $tinhthanh['selected'] = '';
			$xtpl->assign('l', $tinhthanh);
			$xtpl->parse('main.quan');
		}
	}
	
	if($row['wards'] > 0)
	{
		// LẤY XÃ PHƯỜNG RA
		
		$list_xaphuong = $db->query('SELECT wardid, title ,type FROM '. $db_config['prefix'] . '_location_ward WHERE districtid = '. $row['district'] .' and status = 1')->fetchAll();
		
		foreach($list_xaphuong as $tinhthanh)
		{
			if($tinhthanh['wardid'] == $row['wards'])
			{
			$tinhthanh['selected'] = 'selected=selected';
			}
			else $tinhthanh['selected'] = '';
			$xtpl->assign('l', $tinhthanh);
			$xtpl->parse('main.xa');
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
		
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_customer' );

	$db->where( 'status = 1 '. $where );
	
	$sthex = $db->prepare( $db->sql() );


	$sthex->execute();
	$num_items = $sthex->fetchColumn();

	$db->select( '*' )
		->order( 'weight ASC' );
	$sthex = $db->prepare( $db->sql() );

	$sthex->execute();
	
	
		
		require_once NV_ROOTDIR . '/modules/'. $module_file .'/Classes/PHPExcel.php';

	//Khá»Ÿi táº¡o Ä‘á»‘i tÆ°á»£ng
	$excel = new PHPExcel();
		//Chá»n trang cáº§n ghi (lÃ  sá»‘ tá»« 0->n)
	$excel->setActiveSheetIndex(0);
		//Táº¡o tiÃªu Ä‘á» cho trang. (cÃ³ thá»ƒ khÃ´ng cáº§n)
	$excel->getActiveSheet()->setTitle('DANH SÁCH KHÁCH HÀNG');

	
	$header = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff')
        ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '24408e')
        ),
		'borders' => array(
    'allborders' => array(
        'style' => PHPExcel_Style_Border::BORDER_THICK,
        'color' => array('argb' => '24408e')
     )
  )
		,
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
	
	$bottom = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff')
        ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '24408e')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
	$money = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        )
    );
	$classstt = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
	$hea_text = array(
	 'font' => array(
			'size'  => 30,
            'bold' => true,
            'color' => array('rgb' => '24408e')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
$excel->getActiveSheet()->mergeCells('A1:G1');
$excel->getActiveSheet()->setCellValue('A1', 'DANH SÁCH KHÁCH HÀNG');
	$excel->getActiveSheet()->getStyle('A1')->applyFromArray($hea_text);
		$excel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
		



			$excel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
			$excel->getActiveSheet()->getStyle('A2:G2')->applyFromArray($header);
			$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
		
		
		$excel->getActiveSheet()->setCellValue('A2', 'STT');
		$excel->getActiveSheet()->setCellValue('B2', $lang_module['title']);
		$excel->getActiveSheet()->setCellValue('C2', $lang_module['phone']);
		$excel->getActiveSheet()->setCellValue('D2', $lang_module['address']);
		$excel->getActiveSheet()->setCellValue('E2', $lang_module['wards']);
		$excel->getActiveSheet()->setCellValue('F2', $lang_module['district']);
		$excel->getActiveSheet()->setCellValue('G2', $lang_module['city']);
		
		
	$excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$excel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
	$excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	$excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
	$excel->getActiveSheet()->getColumnDimension('G')->setWidth(34);
	
	
		$stt = 1;
		$numRow = 3;
		while( $view = $sthex->fetch() )
		{
			
			if($view['city'] > 0)
			{
				$tinhthanh = $db->query('SELECT title, type FROM '. $db_config['prefix'] . '_location_province WHERE status = 1 AND provinceid ='.$view['city'])->fetch();
				$view['city'] = $tinhthanh['type'] . ' ' . $tinhthanh['title'];
			}
		if($view['district'] > 0)
			{
				
				$list_quan = $db->query('SELECT title, type FROM '. $db_config['prefix'] . '_location_district WHERE status = 1 AND districtid ='.$view['district'])->fetch();
				$view['district'] = $list_quan['type'] . ' ' . $list_quan['title'];
			}
						
	if($view['wards'] > 0)
			{
				$list_wards = $db->query('SELECT title, type FROM '. $db_config['prefix'] . '_location_ward WHERE status = 1 AND wardid ='.$view['wards'])->fetch();
				$view['wards'] = $list_wards['type'] . ' ' . $list_wards['title'];
			}
						
			$excel->getActiveSheet()->setCellValue('A'.$numRow, $stt);
			$excel->getActiveSheet()->setCellValue('B'.$numRow,$view['title']);
			$excel->getActiveSheet()->setCellValue('C'.$numRow, $view['phone']);
			$excel->getActiveSheet()->setCellValue('D'.$numRow, $view['address']);
			$excel->getActiveSheet()->setCellValue('E'.$numRow, $view['wards']);
			$excel->getActiveSheet()->setCellValue('F'.$numRow, $view['district']);
			$excel->getActiveSheet()->setCellValue('G'.$numRow, $view['city']);
	
			$numRow++;
			$stt++;
		
		}
	
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="data.xls"');
		PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('php://output');
		
		die();
	}
	
	
	
	
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	if($userid > 0)
{
	$where .= ' AND userid ='.$userid;
	$base_url .= '&userid=' . $userid;
}
	
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.view.generate_page' );
	}
	$number = $page > 1 ? ($per_page * ( $page - 1 ) ) + 1 : 1;
	$stt = 1;
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
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=store_add&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		
		$tinhthanh = $db->query('SELECT title, type FROM '. $db_config['prefix'] . '_location_province WHERE status = 1 AND provinceid ='.$view['city'])->fetch();
		$view['city'] = $tinhthanh['type'] . ' ' . $tinhthanh['title'];
		
		$quanhuyen = $db->query('SELECT title, type FROM '. $db_config['prefix'] . '_location_district WHERE status = 1 AND districtid ='.$view['district'])->fetch();
		$view['district'] = $quanhuyen['type'] . ' ' . $quanhuyen['title'];
		
		$wards = $db->query('SELECT wardid, title ,type FROM '. $db_config['prefix'] . '_location_ward WHERE wardid = '. $view['wards'] .' and status = 1')->fetch();
		$view['wards'] = $wards['type'] . ' ' . $wards['title'];
		
		if($view['userid'] > 0)
		$view['userid'] = $db->query('SELECT username FROM '. $db_config['prefix'] .'_users WHERE userid = '. $view['userid'])->fetchColumn();
		
		$xtpl->assign( 'VIEW', $view );
		$xtpl->assign( 'stt', $stt );
		$stt++;
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

$page_title = $lang_module['customer_user'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';