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
	$id_quanhuyen = $nv_Request->get_title('id_quanhuyen','get', '');
	if(!empty($id_quanhuyen))
	{ 
		//print('SELECT * FROM tms_location_ward WHERE status = 1 and districtid IN('. $id_quanhuyen .') ORDER BY title ASC');die;
		$list_quan = $db->query('SELECT * FROM tms_location_ward WHERE status = 1 and districtid IN('. $id_quanhuyen .')  ORDER BY districtid ASC, title ASC')->fetchAll();
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

	$query = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone_address WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( isset( $row['status'] ) )
	{
		$status = ( $row['status'] ) ? 0 : 1;
		$query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_zone_address SET status=' . intval( $status ) . ' WHERE id=' . $id;
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
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone_address WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_zone_address SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_zone_address SET weight=' . $new_vid . ' WHERE id=' . $id;
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
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone_address WHERE id =' . $db->quote( $id );
		$result = $db->query( $sql );
		list( $weight) = $result->fetch( 3 );
		
		$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone_address  WHERE id = ' . $db->quote( $id ) );
		if( $weight > 0)
		{
			$sql = 'SELECT id, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone_address WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while(list( $id, $weight) = $result->fetch( 3 ))
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_zone_address SET weight=' . $weight . ' WHERE id=' . intval( $id ));
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
	$row['city'] = $nv_Request->get_int( 'city', 'post', 0 );
	//$row['district'] = $nv_Request->get_int( 'district', 'post', 0 );
	//$row['wards'] = $nv_Request->get_int( 'wards', 'post', 0 );
	$row['id_zone'] = $nv_Request->get_int( 'id_zone', 'post', 0 );
	
	$row['district'] = $nv_Request->get_array('district', 'post', '');
	$row['district'] = implode(',', $row['district']);
	
	$row['wards'] = $nv_Request->get_array('wards', 'post', '');
	$row['wards'] = implode(',', $row['wards']);
	//print_r($row['district']);die;
	if( empty( $row['city'] ) )
	{
		$error[] = $lang_module['error_required_city'];
	}
	elseif( empty( $row['district'] ) )
	{
		$error[] = $lang_module['error_required_district'];
	}
	elseif( empty( $row['id_zone'] ) )
	{
		$error[] = $lang_module['error_required_id_zone'];
	}
	

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_zone_address (weight, city, district, wards, id_zone, status) VALUES (:weight, :city, :district, :wards, :id_zone, :status)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone_address' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

				$stmt->bindValue( ':status', 1, PDO::PARAM_INT );


			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_zone_address SET city = :city, district = :district, wards = :wards, id_zone = :id_zone WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':city', $row['city'], PDO::PARAM_INT );
			$stmt->bindParam( ':district', $row['district'], PDO::PARAM_STR );
			$stmt->bindParam( ':wards', $row['wards'], PDO::PARAM_STR );
			$stmt->bindParam( ':id_zone', $row['id_zone'], PDO::PARAM_INT );

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
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone_address WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['city'] = 0;
	$row['district'] = 0;
	$row['wards'] = 0;
	$row['id_zone'] = 0;
}

$q = $nv_Request->get_title( 'q', 'post,get' );
$id_zone = $nv_Request->get_int( 'id_zone', 'post,get',0 );
// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_zone_address' );
	$search = "";
	
	if($id_zone > 0)
	{
		$search .= " AND id_zone = ".$id_zone;
	}
	
	$db->where( 'status = 1 '.$search );
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

// LẤY TỈNH THÀNH RA
	$list_tinhthanh = $db->query('SELECT provinceid, title, type FROM tms_location_province WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
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
	
	if(!empty($row['district']))
	{
		// LẤY QUẬN HUYỆN RA
		$list_quan = $db->query('SELECT districtid, title, type FROM tms_location_district WHERE provinceid = '. $row['city'] .' and status = 1 ORDER BY weight DESC')->fetchAll();
		$mang_district = explode(',',$row['district']);
		foreach($list_quan as $tinhthanh)
		{
			if(in_array($tinhthanh['districtid'],$mang_district))
			{
			$tinhthanh['selected'] = 'selected=selected';
			}
			else $tinhthanh['selected'] = '';
			$xtpl->assign('l', $tinhthanh);
			$xtpl->parse('main.quan');
		}
	}
	
	if($row['district'] > 0)
	{
		// LẤY XÃ PHƯỜNG RA
		
		$mang_district = explode(',',$row['district']);
		$district_dau = $mang_district[0];
		$list_xaphuong = $db->query('SELECT wardid, title ,type FROM tms_location_ward WHERE districtid = '. $district_dau .' and status = 1')->fetchAll();
		$mang_wardid = explode(',',$row['wards']);
		foreach($list_xaphuong as $tinhthanh)
		{
			if(in_array($tinhthanh['wardid'],$mang_wardid))
			{
			$tinhthanh['selected'] = 'selected=selected';
			}
			else $tinhthanh['selected'] = '';
			$xtpl->assign('l', $tinhthanh);
			$xtpl->parse('main.xa');
		}
	}
	

	// LẤY DANH SÁCH KHU VỰC 
	$list_zone = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_zone WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_zone as $zone)
	{
		if($zone['id'] == $row['id_zone'])
		{
		$zone['selected'] = 'selected=selected';
		}
		else $tinhthanh['selected'] = '';
		$xtpl->assign('zone', $zone);
        $xtpl->parse('main.zone');
        $xtpl->parse('main.view.zone_search');
	}
	

$xtpl->assign( 'Q', $q );

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
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		
		$view['id_zone'] = $db->query('SELECT title FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone WHERE id='.$view['id_zone'])->fetchColumn();
		
		$tinhthanh = $db->query('SELECT title, type FROM tms_location_province WHERE status = 1 AND provinceid ='.$view['city'])->fetch();
		$view['city'] = $tinhthanh['type'] . ' ' . $tinhthanh['title'];
		
		//$quanhuyen = $db->query('SELECT title, type FROM tms_location_district WHERE status = 1 AND districtid ='.$view['district'])->fetch();
		//$view['district'] = $quanhuyen['type'] . ' ' . $quanhuyen['title'];
		
		//$wards = $db->query('SELECT wardid, title ,type FROM tms_location_ward WHERE wardid = '. $view['wards'] .' and status = 1')->fetch();
		//$view['wards'] = $wards['type'] . ' ' . $wards['title'];
		
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

$page_title = $lang_module['zone_address'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';