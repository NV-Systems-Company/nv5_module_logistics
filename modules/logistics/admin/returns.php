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

	$query = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( isset( $row['status'] ) )
	{
		$status = ( $row['status'] ) ? 0 : 1;
		$query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET status=' . intval( $status ) . ' WHERE id=' . $id;
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
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET weight=' . $new_vid . ' WHERE id=' . $id;
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
	$row['id_store'] = $nv_Request->get_int( 'id_store', 'post', 0 );
	$row['send_name'] = $nv_Request->get_title( 'send_name', 'post', '' );
	$row['send_phone'] = $nv_Request->get_title( 'send_phone', 'post', '' );
	$row['send_address'] = $nv_Request->get_title( 'send_address', 'post', '' );
	$row['send_city'] = $nv_Request->get_int( 'send_city', 'post', 0 );
	$row['send_district'] = $nv_Request->get_int( 'send_district', 'post', 0 );
	$row['send_wards'] = $nv_Request->get_int( 'send_wards', 'post', 0 );
	$row['receive_name'] = $nv_Request->get_title( 'receive_name', 'post', '' );
	$row['receive_phone'] = $nv_Request->get_title( 'receive_phone', 'post', '' );
	$row['id_document'] = $nv_Request->get_int( 'id_document', 'post', 0 );
	$row['document_name'] = $nv_Request->get_title( 'document_name', 'post', '' );
	$row['bill'] = $nv_Request->get_title( 'bill', 'post', '' );
	$row['amount'] = $nv_Request->get_int( 'amount', 'post', 0 );
	$row['value_goods'] = $nv_Request->get_title( 'value_goods', 'post', '' );
	$row['weight_document'] = $nv_Request->get_title( 'weight_document', 'post', '' );
	$row['long_document'] = $nv_Request->get_title( 'long_document', 'post', '' );
	$row['wide'] = $nv_Request->get_title( 'wide', 'post', '' );
	$row['height'] = $nv_Request->get_title( 'height', 'post', '' );
	$row['id_service'] = $nv_Request->get_int( 'id_service', 'post', 0 );
	$row['id_surcharge'] = $nv_Request->get_title( 'id_surcharge', 'post', '' );
	$row['other_requirements'] = $nv_Request->get_title( 'other_requirements', 'post', '' );
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'delivery_date', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['delivery_date'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['delivery_date'] = 0;
	}
	$row['delivery_time'] = $nv_Request->get_title( 'delivery_time', 'post', '' );
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'received_date', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['received_date'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['received_date'] = 0;
	}
	$row['received_time'] = $nv_Request->get_title( 'received_time', 'post', '' );
	$row['pay'] = $nv_Request->get_int( 'pay', 'post', 0 );
	$row['money_collection'] = $nv_Request->get_title( 'money_collection', 'post', '' );
	$row['pays'] = $nv_Request->get_title( 'pays', 'post', '' );
	$row['charge_for_collection'] = $nv_Request->get_title( 'charge_for_collection', 'post', '' );
	$row['total_charge'] = $nv_Request->get_title( 'total_charge', 'post', '' );
	$row['vat'] = $nv_Request->get_title( 'vat', 'post', '' );
	$row['total_money'] = $nv_Request->get_title( 'total_money', 'post', '' );
	$row['total_receivable'] = $nv_Request->get_title( 'total_receivable', 'post', '' );
	$row['seller_payments'] = $nv_Request->get_title( 'seller_payments', 'post', '' );

	if( empty( $row['id_store'] ) )
	{
		$error[] = $lang_module['error_required_id_store'];
	}
	elseif( empty( $row['send_name'] ) )
	{
		$error[] = $lang_module['error_required_send_name'];
	}
	elseif( empty( $row['send_phone'] ) )
	{
		$error[] = $lang_module['error_required_send_phone'];
	}
	elseif( empty( $row['send_address'] ) )
	{
		$error[] = $lang_module['error_required_send_address'];
	}
	elseif( empty( $row['receive_name'] ) )
	{
		$error[] = $lang_module['error_required_receive_name'];
	}
	elseif( empty( $row['receive_phone'] ) )
	{
		$error[] = $lang_module['error_required_receive_phone'];
	}
	elseif( empty( $row['id_document'] ) )
	{
		$error[] = $lang_module['error_required_id_document'];
	}
	elseif( empty( $row['document_name'] ) )
	{
		$error[] = $lang_module['error_required_document_name'];
	}
	elseif( empty( $row['bill'] ) )
	{
		$error[] = $lang_module['error_required_bill'];
	}
	elseif( empty( $row['amount'] ) )
	{
		$error[] = $lang_module['error_required_amount'];
	}
	elseif( empty( $row['value_goods'] ) )
	{
		$error[] = $lang_module['error_required_value_goods'];
	}
	elseif( empty( $row['id_service'] ) )
	{
		$error[] = $lang_module['error_required_id_service'];
	}
	elseif( empty( $row['pay'] ) )
	{
		$error[] = $lang_module['error_required_pay'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{

				$row['add_date'] = 0;
				$row['userid_add'] = 0;

				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_rows (weight, id_store, send_name, send_phone, send_address, send_city, send_district, send_wards, receive_name, receive_phone, id_document, document_name, bill, amount, value_goods, weight_document, long_document, wide, height, id_service, id_surcharge, other_requirements, delivery_date, delivery_time, received_date, received_time, pay, money_collection, pays, charge_for_collection, total_charge, vat, total_money, total_receivable, seller_payments, add_date, userid_add, status) VALUES (:weight, :id_store, :send_name, :send_phone, :send_address, :send_city, :send_district, :send_wards, :receive_name, :receive_phone, :id_document, :document_name, :bill, :amount, :value_goods, :weight_document, :long_document, :wide, :height, :id_service, :id_surcharge, :other_requirements, :delivery_date, :delivery_time, :received_date, :received_time, :pay, :money_collection, :pays, :charge_for_collection, :total_charge, :vat, :total_money, :total_receivable, :seller_payments, :add_date, :userid_add, :status)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

				$stmt->bindParam( ':add_date', $row['add_date'], PDO::PARAM_INT );
				$stmt->bindParam( ':userid_add', $row['userid_add'], PDO::PARAM_INT );
				$stmt->bindValue( ':status', 1, PDO::PARAM_INT );


			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET id_store = :id_store, send_name = :send_name, send_phone = :send_phone, send_address = :send_address, send_city = :send_city, send_district = :send_district, send_wards = :send_wards, receive_name = :receive_name, receive_phone = :receive_phone, id_document = :id_document, document_name = :document_name, bill = :bill, amount = :amount, value_goods = :value_goods, weight_document = :weight_document, long_document = :long_document, wide = :wide, height = :height, id_service = :id_service, id_surcharge = :id_surcharge, other_requirements = :other_requirements, delivery_date = :delivery_date, delivery_time = :delivery_time, received_date = :received_date, received_time = :received_time, pay = :pay, money_collection = :money_collection, pays = :pays, charge_for_collection = :charge_for_collection, total_charge = :total_charge, vat = :vat, total_money = :total_money, total_receivable = :total_receivable, seller_payments = :seller_payments WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':id_store', $row['id_store'], PDO::PARAM_INT );
			$stmt->bindParam( ':send_name', $row['send_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':send_phone', $row['send_phone'], PDO::PARAM_STR );
			$stmt->bindParam( ':send_address', $row['send_address'], PDO::PARAM_STR );
			$stmt->bindParam( ':send_city', $row['send_city'], PDO::PARAM_INT );
			$stmt->bindParam( ':send_district', $row['send_district'], PDO::PARAM_INT );
			$stmt->bindParam( ':send_wards', $row['send_wards'], PDO::PARAM_INT );
			$stmt->bindParam( ':receive_name', $row['receive_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':receive_phone', $row['receive_phone'], PDO::PARAM_STR );
			$stmt->bindParam( ':id_document', $row['id_document'], PDO::PARAM_INT );
			$stmt->bindParam( ':document_name', $row['document_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':bill', $row['bill'], PDO::PARAM_STR );
			$stmt->bindParam( ':amount', $row['amount'], PDO::PARAM_INT );
			$stmt->bindParam( ':value_goods', $row['value_goods'], PDO::PARAM_STR );
			$stmt->bindParam( ':weight_document', $row['weight_document'], PDO::PARAM_STR );
			$stmt->bindParam( ':long_document', $row['long_document'], PDO::PARAM_STR );
			$stmt->bindParam( ':wide', $row['wide'], PDO::PARAM_STR );
			$stmt->bindParam( ':height', $row['height'], PDO::PARAM_STR );
			$stmt->bindParam( ':id_service', $row['id_service'], PDO::PARAM_INT );
			$stmt->bindParam( ':id_surcharge', $row['id_surcharge'], PDO::PARAM_STR );
			$stmt->bindParam( ':other_requirements', $row['other_requirements'], PDO::PARAM_STR );
			$stmt->bindParam( ':delivery_date', $row['delivery_date'], PDO::PARAM_INT );
			$stmt->bindParam( ':delivery_time', $row['delivery_time'], PDO::PARAM_STR );
			$stmt->bindParam( ':received_date', $row['received_date'], PDO::PARAM_INT );
			$stmt->bindParam( ':received_time', $row['received_time'], PDO::PARAM_STR );
			$stmt->bindParam( ':pay', $row['pay'], PDO::PARAM_INT );
			$stmt->bindParam( ':money_collection', $row['money_collection'], PDO::PARAM_STR );
			$stmt->bindParam( ':pays', $row['pays'], PDO::PARAM_STR );
			$stmt->bindParam( ':charge_for_collection', $row['charge_for_collection'], PDO::PARAM_STR );
			$stmt->bindParam( ':total_charge', $row['total_charge'], PDO::PARAM_STR );
			$stmt->bindParam( ':vat', $row['vat'], PDO::PARAM_STR );
			$stmt->bindParam( ':total_money', $row['total_money'], PDO::PARAM_STR );
			$stmt->bindParam( ':total_receivable', $row['total_receivable'], PDO::PARAM_STR );
			$stmt->bindParam( ':seller_payments', $row['seller_payments'], PDO::PARAM_STR );

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
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['id_store'] = 0;
	$row['send_name'] = '';
	$row['send_phone'] = '';
	$row['send_address'] = '';
	$row['send_city'] = 0;
	$row['send_district'] = 0;
	$row['send_wards'] = 0;
	$row['receive_name'] = '';
	$row['receive_phone'] = '';
	$row['id_document'] = 0;
	$row['document_name'] = '';
	$row['bill'] = '';
	$row['amount'] = 0;
	$row['value_goods'] = '';
	$row['weight_document'] = '';
	$row['long_document'] = '0';
	$row['wide'] = '0';
	$row['height'] = '0';
	$row['id_service'] = 0;
	$row['id_surcharge'] = '';
	$row['other_requirements'] = '';
	$row['delivery_date'] = 0;
	$row['delivery_time'] = '';
	$row['received_date'] = 0;
	$row['received_time'] = '';
	$row['pay'] = 1;
	$row['money_collection'] = '0';
	$row['pays'] = '0';
	$row['charge_for_collection'] = '0';
	$row['total_charge'] = '0';
	$row['vat'] = '0';
	$row['total_money'] = '0';
	$row['total_receivable'] = '0';
	$row['seller_payments'] = '0';
}

if( empty( $row['delivery_date'] ) )
{
	$row['delivery_date'] = '';
}
else
{
	$row['delivery_date'] = date( 'd/m/Y', $row['delivery_date'] );
}

if( empty( $row['received_date'] ) )
{
	$row['received_date'] = '';
}
else
{
	$row['received_date'] = date( 'd/m/Y', $row['received_date'] );
}

$q = $nv_Request->get_title( 'q', 'post,get' );

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{

	// THỰC THI
	//$sql ='SELECT * FROM '. $db_config['prefix'] . '_' . $module_data . '_rows t1, '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 WHERE t1.id = t2.id_bill AND t1.status = 1 AND t1.userid_add ='. $user_info['userid'] .' AND t2.status = 1 AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
	//die($sql);
	
	$where = ' AND t2.status = 6 AND t2.add_date = (SELECT max(add_date) FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill t3 WHERE t3.id_bill = t1.id)';
	
	// KẾT THÚC THỰC THI
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(DISTINCT(t1.id)), max(t2.id)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_rows t1' )
		->join('LEFT JOIN ' . $db_config['prefix'] . '_' . $module_data . '_schedule_bill t2 ON t1.id = t2.id_bill');

	$db->where( 't1.status = 1'. $where);
	
	$sth = $db->prepare( $db->sql() );

	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select('DISTINCT(t1.id), t1.*')
		->order( 'weight ASC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );
	//die($db->sql());
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
	$stt = 0;
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
		$view['delivery_date'] = ( empty( $view['delivery_date'] )) ? '' : nv_date( 'd/m/Y', $view['delivery_date'] );
		$view['received_date'] = ( empty( $view['received_date'] )) ? '' : nv_date( 'd/m/Y', $view['received_date'] );
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add&amp;id=' . $view['id'];
		$view['link_schedule_bill'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=schedule_bill&amp;id_bill=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		
		$view['id_service'] = $db->query('SELECT title FROM '.$db_config['prefix'] . '_' . $module_data . '_service WHERE id ='. $view['id_service'])->fetchColumn();
		$view['add_date'] = date('d/m/Y - H:i', $view['add_date']);
		$stt++;
		$xtpl->assign( 'stt', $stt );
		
		// TRẠNG THÁI MỚI NHẤT CỦA VẬN ĐƠN
		$status = $db->query('SELECT status, add_date FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule_bill WHERE id_bill ='. $view['id'] .' ORDER BY add_date DESC LIMIT 0,1')->fetch();
		
		if($status['status'] > 0)
		{
			$view['trangthai_moi'] = $db->query('SELECT title FROM '.$db_config['prefix'] . '_' . $module_data . '_schedule WHERE id ='. $status['status'] .' ORDER BY id DESC LIMIT 0,1')->fetchColumn();
			$view['ngay_trangthai_moi'] = date('d/m/Y - H:i',$status['add_date']);
			
		}
		$view['value_goods'] = number_format($view['value_goods'],0,",",",");
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

$page_title = $lang_module['returns'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';