<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$row = array();
$error = array();
$row['id_document'] = $nv_Request->get_int( 'id_document', 'post,get', 0 );

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['quantity'] = array_unique($nv_Request->get_typed_array('quantity', 'post', 'string', array()));
	$row['price'] = array_unique($nv_Request->get_typed_array('price', 'post', 'string', array()));
	
	$row['price_min'] = $nv_Request->get_int( 'price_min', 'post,get', 0 );
	
	$weight_price_tam = array();
	$i = 0;
	foreach($row['quantity'] as $quantity)
	{
		if($quantity >= 0)
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

	if( empty( $error ) )
	{
		try
		{
			// KIỂM TRA $row['id_document'] ĐÃ TỒN TẠI CHƯA
			$count = $db->query('SELECT count(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_price_document WHERE id_document ='.$row['id_document'])->fetchColumn();
			if($count == 0)
			{
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_price_document (id_document, price, price_min) VALUES (:id_document, :price, :price_min)' );
				
				$stmt->bindParam( ':id_document', $row['id_document'], PDO::PARAM_INT );

			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_price_document SET price = :price, price_min =:price_min WHERE id_document=' . $row['id_document'] );
			}
		
			$stmt->bindParam( ':price', $weight_price, PDO::PARAM_STR );
			$stmt->bindParam( ':price_min', $row['price_min'], PDO::PARAM_INT );

			$exc = $stmt->execute();
			if( $exc )
			{
				$nv_Cache->delMod( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=document');
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
elseif( $row['id_document'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_price_document WHERE id_document=' . $row['id_document'] )->fetch();
	
}
else
{
	$row['id_document'] = 0;
	$row['id'] = 0;
	$row['price'] = '';
	$row['price_min'] = 0;
}

$id_document = $nv_Request->get_int( 'id_document', 'post,get', 0 );

$where = '';
if($id_document > 0)
	$where .= ' AND id_document ='.$id_document;

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_price_document' );

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
$xtpl->assign( 'id_document', $id_document );
$xtpl->assign( 'ROW', $row );


if(!empty($row['price']))
{
	$list_price = json_decode($row['price'],true);
	foreach($list_price as $price)
	{
		$xtpl->assign( 'price', $price );
		$xtpl->parse('main.price');
	}

}
else
{
	$xtpl->parse('main.price_add');
}

	
	// LẤY DANH SÁCH TÀI LIỆU HÓA
	$list_document = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_document WHERE status = 1 ORDER BY weight ASC')->fetchAll();
	
	foreach($list_document as $document)
	{
		if($document['id'] == $id_document)
			$document['selected'] = 'selected=selected';
		else $document['selected'] = '';
		
		$xtpl->assign('document', $document);
        $xtpl->parse('main.document');
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