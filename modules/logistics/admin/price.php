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

	$query = 'SELECT active FROM ' . $db_config['prefix'] . '_' . $module_data . '_price WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( isset( $row['active'] ) )
	{
		$active = ( $row['active'] ) ? 0 : 1;
		$query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_price SET active=' . intval( $active ) . ' WHERE id=' . $id;
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
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_price WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_price SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_price SET weight=' . $new_vid . ' WHERE id=' . $id;
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
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_price WHERE id =' . $db->quote( $id );
		$result = $db->query( $sql );
		list( $weight) = $result->fetch( 3 );
		
		$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_price  WHERE id = ' . $db->quote( $id ) );
		if( $weight > 0)
		{
			$sql = 'SELECT id, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_price WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while(list( $id, $weight) = $result->fetch( 3 ))
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_price SET weight=' . $weight . ' WHERE id=' . intval( $id ));
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
	//$row['quantity'] = $nv_Request->get_title( 'quantity', 'post', '' );
	$row['id_document'] = $nv_Request->get_int( 'id_document', 'post', 0 );
	$row['id_service'] = $nv_Request->get_int( 'id_service', 'post', 0 );
	$row['id_zone'] = $nv_Request->get_int( 'id_zone', 'post', 0 );
	//$row['price'] = $nv_Request->get_title( 'price', 'post', '' );
	$row['note'] = $nv_Request->get_title( 'note', 'post', '' );
	
	$row['quantity'] = array_unique($nv_Request->get_typed_array('quantity', 'post', 'string', array()));
	$row['price'] = array_unique($nv_Request->get_typed_array('price', 'post', 'string', array()));
	
	$weight_price_tam = array();
	$i = 0;
	foreach($row['quantity'] as $quantity)
	{
		if(!empty($quantity))
		{
			$mang = array();
			$mang['quantity'] = $quantity;
			$mang['price'] = $row['price'][$i];
			$weight_price_tam[] = $mang;
		}
		
		$i++;
		
	}
	$weight_price = json_encode($weight_price_tam);
	

	if( empty( $weight_price ) )
	{
		$error[] = $lang_module['error_required_price'];
	}
	elseif( empty( $row['id_document'] ) )
	{
		$error[] = $lang_module['error_required_id_document'];
	}
	elseif( empty( $row['id_service'] ) )
	{
		$error[] = $lang_module['error_required_id_service'];
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
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_price (weight, id_document, id_service, id_zone, active, price, note) VALUES (:weight, :id_document, :id_service, :id_zone, :active, :price, :note)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_price' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

				$stmt->bindValue( ':active', 1, PDO::PARAM_INT );


			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_price SET id_document = :id_document, id_service = :id_service, id_zone = :id_zone, price = :price, note = :note WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':id_document', $row['id_document'], PDO::PARAM_INT );
			$stmt->bindParam( ':id_service', $row['id_service'], PDO::PARAM_INT );
			$stmt->bindParam( ':id_zone', $row['id_zone'], PDO::PARAM_INT );
			$stmt->bindParam( ':price', $weight_price, PDO::PARAM_STR );
			$stmt->bindParam( ':note', $row['note'], PDO::PARAM_STR );

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
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_price WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['id_document'] = 0;
	$row['id_service'] = 0;
	$row['id_zone'] = 0;
	$row['price'] = '';
	$row['note'] = '';
}

$q = $nv_Request->get_title( 'q', 'post,get' );
$id_document = $nv_Request->get_int( 'id_document', 'post,get', 0 );
$id_service = $nv_Request->get_int( 'id_service', 'post,get', 0 );
$id_zone = $nv_Request->get_int( 'id_zone', 'post,get', 0 );

$where = '';
if($id_document > 0)
	$where .= ' AND id_document ='.$id_document;
	
if($id_service > 0)
	$where .= ' AND id_service ='.$id_service;
	
if($id_zone > 0)
	$where .= ' AND id_zone ='.$id_zone;

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_price' );

	if( ! empty( $q ) )
	{
		$db->where( 'id > 0 '. $where );
	}
	else
	{
		$db->where( 'id > 0 '. $where );
	}
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
//die($db->sql());

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

if(!empty($row['price']))
{
	$list_price = json_decode($row['price'],true);
	foreach($list_price as $price)
	{
		$xtpl->assign( 'price', $price );
		$xtpl->parse('main.price');
	}

}

if($row['id'] == 0)
{
	$xtpl->parse('main.price_add');
}

// LẤY DANH SÁCH DỊCH VỤ
	$list_service = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_service WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_service as $service)
	{
		if($service['id'] == $row['id_service'])
			$service['selected'] = 'selected=selected';
		else $service['selected'] = '';
		
		if($service['id'] == $id_service)
			$service['selected_s'] = 'selected=selected';
		else $service['selected_s'] = '';
		
		
		$xtpl->assign('service', $service);
        $xtpl->parse('main.service');
        $xtpl->parse('main.view.service_s');
	}
	
	// LẤY DANH SÁCH TÀI LIỆU HÓA
	$list_document = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_document WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_document as $document)
	{
		if($document['id'] == $row['id_document'])
			$document['selected'] = 'selected=selected';
		else $document['selected'] = '';
		
		if($document['id'] == $id_document)
			$document['selected_s'] = 'selected=selected';
		else $document['selected_s'] = '';
		
		$xtpl->assign('document', $document);
        $xtpl->parse('main.document');
        $xtpl->parse('main.view.document_s');
	}
	
	
	// LẤY DANH SÁCH VÙNG
	$list_zone = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_zone WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_zone as $zone)
	{
		if($zone['id'] == $row['id_zone'])
			$zone['selected'] = 'selected=selected';
		else $zone['selected'] = '';
		
		if($zone['id'] == $id_zone)
			$zone['selected_s'] = 'selected=selected';
		else $zone['selected_s'] = '';
		
		$xtpl->assign('zone', $zone);
        $xtpl->parse('main.zone');
        $xtpl->parse('main.view.zone_s');
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
		$xtpl->assign( 'CHECK', $view['active'] == 1 ? 'checked' : '' );
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		
		$view['id_document'] = $db->query('SELECT title FROM '.$db_config['prefix'] . '_' . $module_data . '_document WHERE id ='. $view['id_document'])->fetchColumn();
		
		$view['id_zone'] = $db->query('SELECT title FROM '.$db_config['prefix'] . '_' . $module_data . '_zone WHERE id ='. $view['id_zone'])->fetchColumn();
		
		$view['id_service'] = $db->query('SELECT title FROM '.$db_config['prefix'] . '_' . $module_data . '_service WHERE id ='. $view['id_service'])->fetchColumn();
		
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

$page_title = $lang_module['price'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';