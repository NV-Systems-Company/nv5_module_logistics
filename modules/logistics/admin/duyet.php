<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */



if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

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
		if ( $nv_Request->isset_request( 'ma_bill', 'post,get' ) )
		{
			$ma_bill = $nv_Request->get_string( 'ma_bill', 'post,get', '');
			$awbncc = $nv_Request->get_string( 'awbncc', 'post,get', '');
			
			
			
			$tenncc = $nv_Request->get_string( 'tenncc', 'post,get', '');
			$dia_chi_goi = $nv_Request->get_string( 'dia_chi_goi', 'post,get', '');
			$ngay_nhan = $nv_Request->get_string( 'ngay_nhan', 'post,get', '');
			$dia_chi_nhan = $nv_Request->get_string( 'dia_chi_nhan', 'post,get', '');
			$ngay_goi = $nv_Request->get_string( 'ngay_gioi', 'post,get', '');
			$tai_khoan = $nv_Request->get_string( 'tai_khoan', 'post,get', '');
			$page = $nv_Request->get_int( 'page', 'post,get', 1);
		}
		$nv_Cache->delMod( $module_name );
		Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=duyet');
		die();
	}
}


//change status
if( $nv_Request->isset_request( 'change_status', 'post, get' ) )
{
	$id = $nv_Request->get_int( 'id', 'post, get', 0 );
	$content = 'NO_' . $id;

	$query = 'SELECT active FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( isset( $row['active'] ) )
	{
		$active = ( $row['active'] ) ? 0 : 1;
		$query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET active=' . intval( $active ) . ' WHERE id=' . $id;
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

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_rows t1' )
		->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh t2 ON t1.id = t2.id_van_don');

	if ( $nv_Request->isset_request( 'ma_bill', 'post,get' ) )
	{
		$nguoi_nhan = $nv_Request->get_int( 'nguoi_nhan', 'post,get',0);
		$ma_bill = $nv_Request->get_string( 'ma_bill', 'post,get', '');
		$awbncc = $nv_Request->get_string( 'awbncc', 'post,get', '');
		$tenncc = $nv_Request->get_string( 'tenncc', 'post,get', '');
		$dia_chi_goi = $nv_Request->get_string( 'dia_chi_goi', 'post,get', '');
		$ngay_nhan = $nv_Request->get_string( 'ngay_nhan', 'post,get', '');
		$dia_chi_nhan = $nv_Request->get_string( 'dia_chi_nhan', 'post,get', '');
		//$nguoi_nhan = $nv_Request->get_string( 'nguoi_nhan', 'post,get', '');
		$gio_goi = $nv_Request->get_string( 'gio_goi', 'post,get', '');
		$ngay_goi = $nv_Request->get_string( 'ngay_gioi', 'post,get', '');
		
		$tai_khoan = $nv_Request->get_string( 'tai_khoan', 'post,get', '');
		$ghi_chu = $nv_Request->get_string( 'ghi_chu', 'post,get', '');
		$search = "";
		
		$ngay_moinhat_tam = $nv_Request->get_string( 'ngay_moi_nhat', 'post,get', '');
		if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $ngay_moinhat_tam, $m)) {
			$phour_nhan = $nv_Request->get_int('phour_nhan', 'post', 0);
			$pmin_nhan = $nv_Request->get_int('pmin_nhan', 'post', 0);
		   $ngay_moinhat = mktime($phour_nhan, $pmin_nhan, 0, $m[2], $m[1], $m[3]);
		  

		}
		
		
		
		if($nguoi_nhan == 1)
		{
			$search .= " AND t1.nguoi_nhan != ''";
		}
		if($nguoi_nhan == 2)
		{
			$search .= " AND t1.nguoi_nhan = ''";
		}
		//die($search);
		if(!empty($ma_bill))
		{
			$search = " AND t1.ma_bill LIKE '%".$ma_bill."%'";
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
		//print(date('d/m/y-H:I',1515469897 ));die;
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
					$search .= " AND t1.ngay_nhan >= ".$ngaynhan_tu." AND t1.ngay_nhan <= ".$ngaynhan_den;
					
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
			   $search .= " AND t1.ngay_gioi >= ".$ngaynhan_tu." AND t1.ngay_gioi <= ".$ngaynhan_den;
			}
			else {
				$m = explode('/',$ngay_goi);
				if(count($m) == 2)
				{
					$ngaynhan_tu = mktime(0, 0, 0, $m[0],1, $m[1]);
					$ngaynhan_den = mktime(0, 0, 0, $m[0],31, $m[1]);
					$search .= " AND t1.ngay_gioi >= ".$ngaynhan_tu." AND t1.ngay_gioi <= ".$ngaynhan_den;
				}
				if(count($m) == 1)
				{
					$ngaynhan_tu = mktime(0, 0, 0, 1, 1, $m[0]);
					$m[0] = $m[0] + 1;
					$ngaynhan_den = mktime(0, 0, 0, 1, 1, $m[0]);
					$search .= " AND t1.ngay_gioi >= ".$ngaynhan_tu." AND t1.ngay_gioi <= ".$ngaynhan_den;
				}
			}
			
		}
		if(!empty($tai_khoan))
		{
			$search .= " AND t1.tai_khoan LIKE '%".$tai_khoan."%'";
		}
	
		$db->where( 't1.active = 1 '.$search );
	}
	
	$sql1 = 'SELECT * 
			FROM (
			SELECT MAX( ngay ) ngay , id_van_don
			FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh
			GROUP BY id_van_don
			) AS t2
			INNER JOIN '.$db_config['prefix'] . '_' . $module_data . '_rows t1 ON t2.id_van_don = t1.id
			WHERE t1.active = 1' . $search;
	$kq = $db->query($sql1)->fetchAll();
	$num_items = count($kq);
	
	//$sql = 'SELECT t1.*, t2.ngay FROM '.$db_config['prefix'] . '_' . $module_data . '_rows t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh t2 ON t1.id = t2.id_van_don WHERE t1.active = 1' . $search .' GROUP BY t1.id ORDER BY t1.ngay_nhan DESC LIMIT 20 OFFSET '.( ( $page - 1 ) * $per_page );
	
	$sql = 'SELECT * 
			FROM (
			SELECT MAX( ngay ) ngay , id_van_don
			FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh
			GROUP BY id_van_don
			) AS t2
			INNER JOIN '.$db_config['prefix'] . '_' . $module_data . '_rows t1 ON t2.id_van_don = t1.id
			WHERE t1.active = 0' . $search .' 
			ORDER BY ngay_nhan DESC
			LIMIT 20 OFFSET '.( ( $page - 1 ) * $per_page );
	
	//die($sql);
	
	$list = $db->query($sql)->fetchAll();
	
	
}

// LẤY TÀI LIỆU
$list_tai_lieu = $db->query('SELECT id, tieu_de FROM ' . $db_config['prefix'] . '_' . $module_data . '_tai_lieu ORDER BY weight ASC')->fetchAll();

// LẤY NƯỚC
$list_nuoc = $db->query('SELECT id, tieu_de FROM ' . $db_config['prefix'] . '_' . $module_data . '_nuoc ORDER BY weight ASC')->fetchAll();

session_start();
$_SESSION["link"] = "";
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

$xtpl->assign( 'ma_bill', $ma_bill );
$xtpl->assign( 'awbncc', $awbncc );
$xtpl->assign( 'tenncc', $tenncc );
$xtpl->assign( 'dia_chi_goi', $dia_chi_goi );
$xtpl->assign( 'ngay_nhan', $ngay_nhan );
$xtpl->assign( 'dia_chi_nhan', $dia_chi_nhan );
$xtpl->assign( 'ngay_moinhat_tam', $ngay_moinhat_tam );
if($nguoi_nhan == 1)
$xtpl->assign( 'select_danhan', 'selected=selected');
if($nguoi_nhan == 2)
$xtpl->assign( 'select_chuanhan', 'selected=selected');
$xtpl->assign( 'ngay_goi', $ngay_goi );
$xtpl->assign( 'tai_khoan', $tai_khoan );
$xtpl->assign( 'ghi_chu', $ghi_chu );
$xtpl->assign( 'LINK_EXCEL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=export&ma_bill='.$ma_bill.'&awbncc='.$awbncc.'&tenncc='.$tenncc.'&ghi_chu='.$ghi_chu.'&dia_chi_goi='.$dia_chi_goi.'&ngay_nhan='.$ngay_nhan.'&dia_chi_nhan='.$dia_chi_nhan.'&ngay_gioi='.$ngay_goi.'&tai_khoan='.$tai_khoan.'&ngay_moi_nhat='.$ngay_moinhat_tam.'&nguoi_nhan='.$nguoi_nhan);




// XUẤT RA TÀI LIỆU
if(!empty($list_tai_lieu))
{
	foreach($list_tai_lieu as $tl)
	{
		if($tai_lieu == $tl['id'])
			$xtpl->assign('selected','selected=selected');
		else $xtpl->assign('selected','');
		$xtpl->assign( 'tl', $tl);
		$xtpl->parse( 'main.view.tai_lieu' );
	}
}

// XUẤT RA NƯỚC
if(!empty($list_nuoc))
{
	foreach($list_nuoc as $n)
	{
		if($noi_nhan == $n['id'])
			$xtpl->assign('selected','selected=selected');
		else $xtpl->assign('selected','');
		$xtpl->assign( 'n', $n);
		$xtpl->parse( 'main.view.nuoc_den' );
	}
}

if($admin_info['export_vd'])
{
	$xtpl->parse( 'main.view.export_vd' );
	
}

if( $show_view )
{
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	if ( $nv_Request->isset_request( 'ma_bill', 'post,get' ) )
	{
		$base_url .= '&ma_bill='.$ma_bill.'&awbncc='.$awbncc.'&tenncc='.$tenncc.'&ghi_chu='.$ghi_chu.'&dia_chi_goi='.$dia_chi_goi.'&ngay_nhan='.$ngay_nhan.'&dia_chi_nhan='.$dia_chi_nhan.'&ngay_gioi='.$ngay_goi.'&tai_khoan='.$tai_khoan.'&ngay_moi_nhat='.$ngay_moinhat_tam;
	}
	
	
	//print(count($list));die;
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.view.generate_page' );
	}
	$number = $page > 1 ? ($per_page * ( $page - 1 ) ) + 1 : 1;
	$j = 1;
	
	//print($num_items);die;
	
	foreach( $list as $view)
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
		if ( $nv_Request->isset_request( 'ma_bill', 'post,get' ) )
		{
			$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tao_van_don&amp;id=' . $view['id']. '&ma_bill='.$ma_bill.'&awbncc='.$awbncc.'&tenncc='.$tenncc.'&dia_chi_goi='.$dia_chi_goi.'&ngay_nhan='.$ngay_nhan.'&dia_chi_nhan='.$dia_chi_nhan.'&ngay_gioi='.$ngay_goi.'&tai_khoan='.$tai_khoan.'&nguoi_nhan='.$nguoi_nhan.'&ngay_moi_nhat='.$ngay_moinhat_tam.'&page='.$page;
			$view['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lich_trinh&amp;id_van_don=' . $view['id']. '&ma_bill='.$ma_bill.'&awbncc='.$awbncc.'&tenncc='.$tenncc.'&dia_chi_goi='.$dia_chi_goi.'&ngay_nhan='.$ngay_nhan.'&dia_chi_nhan='.$dia_chi_nhan.'&ngay_gioi='.$ngay_goi.'&tai_khoan='.$tai_khoan.'&nguoi_nhan='.$nguoi_nhan.'&ngay_moi_nhat='.$ngay_moinhat_tam.'&page1='.$page;
			$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tao_van_don&amp;delete_id=' . $view['id'] . '&ma_bill='.$ma_bill.'&awbncc='.$awbncc.'&tenncc='.$tenncc.'&dia_chi_goi='.$dia_chi_goi.'&ngay_nhan='.$ngay_nhan.'&dia_chi_nhan='.$dia_chi_nhan.'&ngay_gioi='.$ngay_goi.'&tai_khoan='.$tai_khoan.'&nguoi_nhan='.$nguoi_nhan.'&ngay_moi_nhat='.$ngay_moinhat_tam.'&page1='.$page.'&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		}
		else{
			$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tao_van_don&amp;id=' . $view['id'];
			$view['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lich_trinh&amp;id_van_don=' . $view['id'];
			$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=duyet&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
			}
		if(!empty($view['ngay_gioi']))
				{
				$tdate = date('H|i', $view['ngay_gioi']);
				list($phour, $pmin) = explode('|', $tdate);
				if(($phour > 0) or ($pmin > 0))
				{
				$view['gio_phat'] = date("H:i",$view['ngay_gioi']);
				}
				else $view['gio_phat'] ="";
				}
		else $view['gio_phat']  ="";
		if(!empty($view['ngay_gioi']))
		{
			$view['ngay_gioi'] = date('d/m/Y',$view['ngay_gioi']);
		}
		if(!empty($view['ngay_nhan']))
		$view['ngay_nhan'] = date('d/m/Y',$view['ngay_nhan']);
		$view['ngay_them'] = date('d/m/Y',$view['ngay_them']);
		
		// Ngày lịch trình mới nhất
		//print_r($view);die;
		
		
		$ngay_lichtrinh_moi = $db->query("SELECT status FROM ". $db_config['prefix'] . "_" . $module_data . "_lich_trinh WHERE active = 1 AND id_van_don = ".$view['id']." AND ngay =".$view['ngay'])->fetch();
		
		
		
		$view['trangthaimoinhat'] = $ngay_lichtrinh_moi['status'];
		
		$view['ngay'] = date('d/m/Y',$view['ngay']);
		
		if($view['st_dtt'] > 0)
		{
			$view['st_dtt'] = number_format($view['st_dtt'],3 );
			$so = explode('.',$view['st_dtt']);
			if($so[1] == 0)
			{
				$view['st_dtt'] = $so[0];
			}
		}
		else $view['st_dtt'] ='';
		$xtpl->assign( 'STT', $j );
		$j++;
		$xtpl->assign( 'VIEW', $view );
		
		$xtpl->parse( 'main.view.loop' );
		
	}
	if ( $nv_Request->isset_request( 's', 'get' ) )
	{
		$xtpl->parse( 'main.view.suatc' );
	}
	$xtpl->parse( 'main.view' );
}



$xtpl->assign( 'NV_BASE_SITEURL',NV_BASE_SITEURL);
$xtpl->assign( 'NV_ASSETS_DIR',NV_ASSETS_DIR);
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['main'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';