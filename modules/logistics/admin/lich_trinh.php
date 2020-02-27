<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
session_start();


// UPDATE LẠI active nếu ngày > ngày hiện tại

	$update = $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh SET active =1 WHERE ngay <= ' . NV_CURRENTTIME);
	

// KẾT THÚC
		// NHẬN THÔNG TIN TÌM KIẾM XUẤT RA NGOÀI
		if ( $nv_Request->isset_request( 'ma_bill', 'post,get' ) )
		{
			$nguoi_nhan = $nv_Request->get_int( 'nguoi_nhan', 'post,get',0);
			$ngay_moi_nhat = $nv_Request->get_string( 'ngay_moi_nhat', 'post,get',0);
			$ma_bill = $nv_Request->get_string( 'ma_bill', 'post,get', '');
			$awbncc = $nv_Request->get_string( 'awbncc', 'post,get', '');
			$tenncc = $nv_Request->get_string( 'tenncc', 'post,get', '');
			$dia_chi_goi = $nv_Request->get_string( 'dia_chi_goi', 'post,get', '');
			$ngay_nhan = $nv_Request->get_string( 'ngay_nhan', 'post,get', '');
			$dia_chi_nhan = $nv_Request->get_string( 'dia_chi_nhan', 'post,get', '');
			$ngay_goi = $nv_Request->get_string( 'ngay_gioi', 'post,get', '');
			$tai_khoan = $nv_Request->get_string( 'tai_khoan', 'post,get', '');
			$page1 = $nv_Request->get_int( 'page1', 'post,get',1);
			$_SESSION["link"] =  NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&ma_bill='.$ma_bill.'&awbncc='.$awbncc.'&tenncc='.$tenncc.'&dia_chi_goi='.$dia_chi_goi.'&ngay_nhan='.$ngay_nhan.'&dia_chi_nhan='.$dia_chi_nhan.'&ngay_gioi='.$ngay_goi.'&tai_khoan='.$tai_khoan.'&nguoi_nhan='.$nguoi_nhan.'&ngay_moi_nhat='.$ngay_moi_nhat.'&page='.$page1;
		}
if( $nv_Request->isset_request( 'id_van_don', 'get,post' ) )
{
	$id_van_don = $nv_Request->get_int( 'id_van_don', 'get,post', 0 );
	if($id_van_don > 0)
	{
		$tt_bill = $db->query('SELECT ma_bill, st_dtt, tai_khoan, awbncc FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id =' . $id_van_don)->fetch();
		
	}
}
else {
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
		die();
	}

if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $id;
	if( $new_vid > 0 )
	{
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh SET weight=' . $new_vid . ' WHERE id=' . $id;
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
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh WHERE id =' . $db->quote( $id );
		$result = $db->query( $sql );
		list( $weight) = $result->fetch( 3 );
		
		$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh  WHERE id = ' . $db->quote( $id ) );
		if( $weight > 0)
		{
			$sql = 'SELECT id, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while(list( $id, $weight) = $result->fetch( 3 ))
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh SET weight=' . $weight . ' WHERE id=' . intval( $id ));
			}
		}
		$nv_Cache->delMod( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op.'&id_van_don=' . $id_van_don);
		die();
	}
}


$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['id_van_don'] = $nv_Request->get_int( 'id_van_don', 'post', 0 );
	$row['gio'] = $nv_Request->get_title( 'gio', 'post', '' );
	$row['chi_tiet'] = $nv_Request->get_title( 'chi_tiet', 'post', '' );
	$row['nguoi_nhan'] = $nv_Request->get_title( 'nguoi_nhan', 'post', '' );
	$row['nv_phu_trach'] = $nv_Request->get_title( 'nv_phu_trach', 'post', '' );
	$row['status'] = $nv_Request->get_title( 'status', 'post', '' );

	$publ_date = $nv_Request->get_title('ngay', 'post', '');
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m)) {
        $phour = date('H',NV_CURRENTTIME);
        $pmin = date('i',NV_CURRENTTIME);
       $row['ngay'] = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
    } else {
        $row['ngay'] = NV_CURRENTTIME;
    }
	if( empty( $row['ngay'] ) )
	{
		$error[] = $lang_module['error_required_ngay'];
	}
	elseif( empty( $row['status'] ) )
	{
		$error[] = $lang_module['error_required_id_trang_thai'];
	}

	if( empty( $error ) and $row['id_van_don'] > 0 )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh (id_van_don, weight, ngay, gio, nguoi_nhan, nv_phu_trach, ghi_chu, status) VALUES (:id_van_don, :weight, :ngay, :gio, :nguoi_nhan, :nv_phu_trach, :ghi_chu, :status)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );


			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh SET id_van_don = :id_van_don, ngay = :ngay, gio = :gio, nguoi_nhan = :nguoi_nhan, nv_phu_trach = :nv_phu_trach, ghi_chu = :ghi_chu, status =:status WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':id_van_don', $row['id_van_don'], PDO::PARAM_INT );
			$stmt->bindParam( ':ngay', $row['ngay'], PDO::PARAM_INT );
			$stmt->bindParam( ':gio', $row['gio'], PDO::PARAM_STR );
			$stmt->bindParam( ':nguoi_nhan', $row['nguoi_nhan'], PDO::PARAM_STR );
			$stmt->bindParam( ':nv_phu_trach', $row['nv_phu_trach'], PDO::PARAM_STR );
			$stmt->bindParam( ':ghi_chu', $row['chi_tiet'], PDO::PARAM_STR );
			$stmt->bindParam( ':status', $row['status'], PDO::PARAM_STR );
			



			$exc = $stmt->execute();
			if( $exc )
			{
				// GỬI MAIL LỊCH TRÌNH CHO TÀI KHOẢN GỬI
				
				// LẤY tai_khoan $row['id_van_don']
				$tai_khoan = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id = ".$row['id_van_don'])->fetch();
				
				if(!empty($tai_khoan['ngay_gioi']))
						$ngaygoi = date('d/m/Y - H:i', $tai_khoan['ngay_gioi']);
				
				// LẤY THÔNG TIN EMAIL CỦA TÀI KHOẢN 
				if($tai_khoan['tai_khoan'] > 0)
				$email_gui = $db->query("SELECT email, first_name, last_name FROM tms_users WHERE userid = ".$tai_khoan['tai_khoan'])->fetch();
				
				if(!empty($email_gui['email']))
				{
					$ftitle = 'SEF Saigon - Thông báo tình trạng đơn hàng ['.$tai_khoan['ma_bill'].']';
					$fcon_mail = '<div class="ladingInfo">
					<table>
						<tbody>
						<tr style="background: #ccc;padding: 10px;">
							<td style="padding-right: 10px;"><img src="http://sefsaigon.com/themes/default/images/logo_sef.png"/></td>
							<td style="padding:10px">
								<div style="margin-bottom: 10px;"><strong>Địa chỉ:</strong><a href="https://www.google.com/maps?q=2/13+C%E1%BB%99ng+H%C3%B2a&entry=gmail&source=g"> 108/2/13 Cộng Hòa, P. 4, Q. Tân Bình, TP. HCM</a></div>
								<div style="margin-bottom: 10px;"><strong>Điện thoại:</strong> 028.3845.3999 - 028.3547.5118 - 028.3547.5119</div>
								<div style="margin-bottom: 10px;"><strong>Email:</strong> sef@sefsaigon.com</div>
								<div style="margin-bottom: 10px;"><strong>Website:</strong> <a href=http://sefsaigon.com>http://sefsaigon.com</a></div>
							</td>
						</tr>
						<tr>
							<td colspan="2">Xin chào '.$email_gui['first_name'].' '.$email_gui['last_name'].', </td>
						</tr>
						<tr>
							<td colspan="2">Trạng thái vận đơn '.$tai_khoan['ma_bill'].' đã được cập nhật: '.$row['status'].'</td>
						</tr>
						<tr>
							<td style="padding-top: 10px;" colspan="2">Thông tin vận đơn chi tiết:</td>
						</tr>
						<tr>
							<td>
								<h3>Mã bill</h3>
							</td>
							<td>
								<strong>: '.$tai_khoan['ma_bill'].'</strong>
							</td>
						</tr>
						<tr>
							<td>Trạng thái lịch trình</td>
							<td>: <span>'.$row['status'].'</span></td>
						</tr>
						<tr>
							<td>Tên người gửi</td>
							<td><span>: '. $email_gui['first_name'] . ' ' . $email_gui['last_name'] .'</span></td>
						</tr>
						<tr>
							<td>Email người gửi</td>
							<td><span>: '. $email_gui['email'] .'</span></td>
						</tr>
						<tr>
							<td>Ngày gửi</td>
							<td><span>: '. $ngaygoi .'</span></td>
						</tr>
						<tr>
							<td>Người nhận</td>
							<td><span>: '. $tai_khoan['nguoi_nhan'] .'</span></td>
						</tr>
						<tr>
							<td>Số tiền thu hộ</td>
							<td>: <span>'. number_format($tai_khoan['st_dtt'],0,",",".") .'</span></td>
						</tr>
						<tr>
							<td>Địa chỉ nhận</td>
							<td>: <span>'.$tai_khoan['id_nuoc_den'].'</span></td>
						</tr>
						<tr>
							<td>Nội dung hàng</td>
							<td>: <span>'.$tai_khoan['awbncc'] .'</span></td>
						</tr>
						
					</tbody></table>
					<div style="margin-top:10px;margin-bottom:10px">
							<div>Ghi chú: '.$tai_khoan['tenncc'].'</div>
					</div>
					<div style="margin-top:10px">
						Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website SEF Saigon - Công ty giao nhận nhanh hàng hóa Sài Gòn. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi!
					</div>
				</div>
				';
				
				
					@nv_sendmail($from, $email_gui['email'], $ftitle, $fcon_mail);
				
				
				}
				
				$nv_Cache->delMod( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op.'&id_van_don=' . $id_van_don);
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
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh WHERE id=' . $row['id'] )->fetch();
	if(!empty($row['ngay']))
	{
	$tdate = date('H|i', $row['ngay']);
	$row['ngay'] = date('d/m/Y', $row['ngay']);
	}
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op.'&id_van_don=' . $id_van_don);
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['id_van_don'] = 0;
	$row['ngay'] = date('d/m/Y',NV_CURRENTTIME);
	$row['gio'] = '';
	$row['chi_tiet'] = '';
	$row['status'] = '';
}

$q = $nv_Request->get_title( 'q', 'post,get' );

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_lich_trinh' );

	if( ! empty( $q ) )
	{
		$db->where( '(active = 1 AND ngay LIKE :q_ngay OR gio LIKE :q_gio OR chi_tiet LIKE :q_chi_tiet) AND id_van_don ='.$id_van_don );
	}
	else $db->where( 'active = 1 AND id_van_don ='.$id_van_don );
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_ngay', '%' . $q . '%' );
		$sth->bindValue( ':q_gio', '%' . $q . '%' );
		$sth->bindValue( ':q_chi_tiet', '%' . $q . '%' );
	}
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )
		->order( 'ngay DESC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_ngay', '%' . $q . '%' );
		$sth->bindValue( ':q_gio', '%' . $q . '%' );
		$sth->bindValue( ':q_chi_tiet', '%' . $q . '%' );
	}
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
if(!empty($row))
{
$xtpl->assign( 'ROW', $row );
}
if (!empty($_SESSION["link"]))
{
$xtpl->assign( 'LINK',$_SESSION["link"]);
$xtpl->parse( 'main.link' );
}
$xtpl->assign( 'Q', $q );

// XUẤT TRẠNG THÁI LỊCH TRÌNH

$list_trangthai = $db->query('SELECT * FROM trang_thai_lich_trinh ORDER BY weight ASC')->fetchAll();

foreach($list_trangthai as $tt)
{
	$tt['selected'] = ($tt['title'] == $row['status']) ? 'selected=selected' : '';
	$xtpl->assign( 'trangthai',$tt);
	$xtpl->parse( 'main.trangthai' );
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
	$j= 1;
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
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'].'&amp;id_van_don=' . $id_van_don;
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] .'&amp;id_van_don=' . $id_van_don . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$view['ngay_1'] = date('d/m/Y',$view['ngay']);
		$view['gio_1'] = date("g:i a",$view['ngay']);
		$xtpl->assign( 'VIEW', $view );
		$xtpl->assign( 'stt', $j );
		$j++;
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}


if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}
if($tt_bill['tai_khoan'] > 0)
{
	$tai_khoang_vd = $db->query('SELECT first_name, last_name FROM tms_users WHERE userid ='.$tt_bill['tai_khoan'])->fetch();
	$tt_bill['tai_khoan'] = $tai_khoang_vd['first_name'] . ' ' . $tai_khoang_vd['last_name'];
}
$xtpl->assign( 'ma_bill', $tt_bill['ma_bill'] );
$xtpl->assign( 'awbncc', $tt_bill['awbncc'] );
$xtpl->assign( 'st_dtt', number_format($tt_bill['st_dtt'],0,",",","));
$xtpl->assign( 'tai_khoan', $tt_bill['tai_khoan'] );
$xtpl->assign( 'id_van_don', $id_van_don );

list($phour, $pmin) = explode('|', $tdate);
$select = '';
for ($i = 0; $i <= 23; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $phour) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('phour', $select);
$select = '';
for ($i = 0; $i < 60; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $pmin) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('pmin', $select);

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['lich_trinh'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';