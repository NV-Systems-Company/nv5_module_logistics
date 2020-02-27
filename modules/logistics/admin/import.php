<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

//print_r(date('d/m/Y',43300));die;
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'FILE_EXCEL', NV_BASE_ADMINURL . NV_UPLOADS_DIR . '/' . $module_upload  . '/File_Excel.xlsx');  





$id_store = $nv_Request->get_int('id_store','post,get', 0);
$userid = $nv_Request->get_int('userid','post,get', 0);

if($id_store > 0 and $userid > 0)
{
if(isset($_POST['import']))
{
  $allowedExts = array("xlsx");
    $temp = explode(".", $_FILES["excel"]["name"]);
    $extension = end($temp);
 
    if (($_FILES["excel"]["size"] < 200000000000) && in_array($extension, $allowedExts)) {
        if ($_FILES["excel"]["error"] > 0)
            echo "Return Code: " . $_FILES["excel"]["error"] . "<br>";
        else{
		
			// ki?m tra forder user dã t?n t?i chua
			$filename = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $admin_info['username'];
			

			if  (!file_exists($filename)) {
				mkdir(NV_UPLOADS_DIR . '/' . $module_upload .  '/' . $admin_info['username'], 0777);
			} 
			
			
            if (file_exists($filename . '/' . $_FILES["excel"]["name"]))
            unlink($filename .'/'. $_FILES["excel"]["name"]);
			
			//die($filename .'/'. $_FILES["excel"]["name"]);
			
            move_uploaded_file($_FILES["excel"]["tmp_name"],$filename .'/'. $_FILES["excel"]["name"]); 
            // chuong trinh demo doc truc tiep file excel v?i ti?ng vi?t unicode 
			//Ðu?ng d?n file
              $file = $filename .'/'. $_FILES["excel"]["name"]; // file du  
			
				//Nhúng file PHPExcel
				require_once NV_ROOTDIR . '/modules/'. $module_file .'/Classes/PHPExcel.php';
				 
				//Ðu?ng d?n file
				//$file = NV_ROOTDIR .'/'. NV_UPLOADS_DIR . '/storexls/data.xlsx';


				//Ti?n hành xác th?c file
				$objFile = PHPExcel_IOFactory::identify($file);
				$objData = PHPExcel_IOFactory::createReader($objFile);

				//Ch? d?c d? li?u
				$objData->setReadDataOnly(true);

				// Load d? li?u sang d?ng d?i tu?ng
				$objPHPExcel = $objData->load($file);

				//L?y ra s? trang s? d?ng phuong th?c getSheetCount();
				// L?y Ra tên trang s? d?ng getSheetNames();

				//Ch?n trang c?n truy xu?t
				$sheet  = $objPHPExcel->setActiveSheetIndex(0);

				//L?y ra s? dòng cu?i cùng
				$Totalrow = $sheet->getHighestRow();
				//L?y ra tên c?t cu?i cùng
				$LastColumn = $sheet->getHighestColumn();

				//Chuy?n d?i tên c?t dó v? v? trí th?, VD: C là 3,D là 4
				$TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);

				//T?o m?ng ch?a d? li?u
				$data = [];
				$row = array();
				//Ti?n hành l?p qua t?ng ô d? li?u
				//----L?p dòng, Vì dòng d?u là tiêu d? c?t nên chúng ta s? l?p giá tr? t? dòng 2
				
				// Láº¤Y THÃ”NG TIN KHO HÃ€NG Cá»¦A USER NÃ€Y RA
				$tt_store = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $module_data . '_store WHERE status = 1 AND id ='.$id_store)->fetch();
				
				if($tt_store['city'] > 0)
				{
					$tinhthanh = $db->query('SELECT title, type FROM tms_location_province WHERE status = 1 AND provinceid ='.$tt_store['city'])->fetch();
					$tinhthanh_k = $tinhthanh['type'] . ' ' . $tinhthanh['title'];
				}
				
				
				if($tt_store['district'] > 0)
				{
					$quanhuyen = $db->query('SELECT title, type FROM tms_location_district WHERE status = 1 AND districtid ='.$tt_store['district'])->fetch();
					$quanhuyen_k = $quanhuyen['type'] . ' ' . $quanhuyen['title'];
				}
				
				if($tt_store['wards'] > 0)
				{
					$wards = $db->query('SELECT wardid, title ,type FROM tms_location_ward WHERE wardid = '. $tt_store['wards'] .' and status = 1')->fetch();
					$wards_k = $wards['type'] . ' ' . $wards['title'];
				}
				
				
				$tt_store['dia_chi_day_du'] = $tt_store['address'] . ' ' . $wards_k . ' ' . $quanhuyen_k . ' ' . $tinhthanh_k;
				
				//print_r($tt_store);die;
				
				for ($i = 2; $i <= $Totalrow; $i++)
				{
					
					if($sheet->getCellByColumnAndRow(0, $i)->getValue() > 0)
					{
					// THÊM V?N ÐON VÀO
					
					$row['add_date'] = NV_CURRENTTIME;
					$row['userid_add'] = $userid;
					
					$row['send_city'] = $row['send_district'] = $row['send_wards'] = 0;
					
					$row['pay'] = 1;
					
					//id_service = 0
					if(empty($sheet->getCellByColumnAndRow(11, $i)->getValue()))
					$row['id_service'] = 0;
					else
					$row['id_service'] = $sheet->getCellByColumnAndRow(11, $i)->getValue();
					
					//id_document = 0
					if(empty($sheet->getCellByColumnAndRow(10, $i)->getValue()))
					$row['id_document'] = 0;
					else
					$row['id_document'] = $sheet->getCellByColumnAndRow(10, $i)->getValue();
					
					$row['id_store'] = $id_store;
					
					$stt_lonnhat = $db->query('SELECT bill FROM '.$db_config['prefix'] . '_' . $module_data . '_rows WHERE userid_add ='.$row['userid_add'].' ORDER BY id DESC')->fetchColumn();
					
					if($stt_lonnhat == 0)
					$row['bill'] = sprintf(strtoupper($row['userid_add'])."%'06d", ($stt_lonnhat+1));
					else
					$row['bill'] =sprintf("%'07d", ($stt_lonnhat+1));
					
					$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_rows (weight, id_store, send_name, send_phone, send_address, send_city, send_district, send_wards, receive_name, receive_phone, receive_address, id_document, document_name, bill, amount, value_goods, weight_document, long_document, wide, height, id_service, id_surcharge, other_requirements, delivery_date, delivery_time, received_date, received_time, pay, money_collection, service_charge, pays, charge_for_collection, total_charge, vat, total_money, total_receivable, seller_payments, add_date, userid_add, status) VALUES (:weight, :id_store, :send_name, :send_phone, :send_address, :send_city, :send_district, :send_wards, :receive_name, :receive_phone, :receive_address, :id_document, :document_name, :bill, :amount, :value_goods, :weight_document, :long_document, :wide, :height, :id_service, :id_surcharge, :other_requirements, :delivery_date, :delivery_time, :received_date, :received_time, :pay, :money_collection, :service_charge, :pays, :charge_for_collection, :total_charge, :vat, :total_money, :total_receivable, :seller_payments, :add_date, :userid_add, :status)' ); 

					$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows' )->fetchColumn();
					$weight = intval( $weight ) + 1;
					$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

					$stmt->bindParam( ':add_date', $row['add_date'], PDO::PARAM_INT );
					$stmt->bindParam( ':userid_add', $row['userid_add'], PDO::PARAM_INT );
					$stmt->bindValue( ':status', 1, PDO::PARAM_INT );
					
					$stmt->bindParam( ':bill', $row['bill'], PDO::PARAM_STR );
					//print($sheet->getCellByColumnAndRow(2, $i)->getValue());die;
					$stmt->bindParam( ':id_store', $row['id_store'], PDO::PARAM_INT );
					$stmt->bindParam( ':send_name', $tt_store['name'], PDO::PARAM_STR );
					$stmt->bindParam( ':send_phone', $tt_store['phone'], PDO::PARAM_STR );
					$stmt->bindParam( ':send_address', $tt_store['dia_chi_day_du'], PDO::PARAM_STR );
					$stmt->bindParam( ':send_city', $row['send_city'], PDO::PARAM_INT );
					$stmt->bindParam( ':send_district', $row['send_district'], PDO::PARAM_INT );
					$stmt->bindParam( ':send_wards', $row['send_wards'], PDO::PARAM_INT );
					$stmt->bindParam( ':receive_name', $sheet->getCellByColumnAndRow(3, $i)->getValue(), PDO::PARAM_STR );
					$stmt->bindParam( ':receive_phone', $sheet->getCellByColumnAndRow(2, $i)->getValue(), PDO::PARAM_STR );
					$stmt->bindParam( ':receive_address', $sheet->getCellByColumnAndRow(4, $i)->getValue(), PDO::PARAM_STR );
					$stmt->bindParam( ':id_document', $row['id_document'], PDO::PARAM_INT );
					$stmt->bindParam( ':document_name', $sheet->getCellByColumnAndRow(6, $i)->getValue(), PDO::PARAM_STR );
					$stmt->bindParam( ':amount', $sheet->getCellByColumnAndRow(10, $i)->getValue(), PDO::PARAM_INT );
					$stmt->bindParam( ':value_goods', $sheet->getCellByColumnAndRow(8, $i)->getValue(), PDO::PARAM_STR );
					$stmt->bindParam( ':weight_document', $sheet->getCellByColumnAndRow(9, $i)->getValue(), PDO::PARAM_STR );
					$stmt->bindParam( ':long_document', $row['long_document'], PDO::PARAM_STR );
					$stmt->bindParam( ':wide', $row['wide'], PDO::PARAM_STR );
					$stmt->bindParam( ':height', $row['height'], PDO::PARAM_STR );
					$stmt->bindParam( ':id_service', $row['id_service'], PDO::PARAM_INT );
					$stmt->bindParam( ':id_surcharge', $sheet->getCellByColumnAndRow(12, $i)->getValue(), PDO::PARAM_STR );
					$stmt->bindParam( ':other_requirements', $sheet->getCellByColumnAndRow(13, $i)->getValue(), PDO::PARAM_STR );
					
					// NGÀY NH?N, NGÀY G?I
					
					if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $sheet->getCellByColumnAndRow(5, $i)->getValue(), $m ) )
					{
						$_hour = 0;
						$_min = 0;
						$row['delivery_date'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
					}
					else
					{
						$row['delivery_date'] = 0;
					}
				
					if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $sheet->getCellByColumnAndRow(1, $i)->getValue(), $m ) )
					{
						$_hour = 0;
						$_min = 0;
						$row['received_date'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
					}
					else
					{
						$row['received_date'] = 0;
					}
					
					$stmt->bindParam( ':delivery_date', $row['delivery_date'], PDO::PARAM_INT );
					$stmt->bindParam( ':delivery_time', $row['delivery_time'], PDO::PARAM_STR );
					$stmt->bindParam( ':received_date', $row['received_date'], PDO::PARAM_INT );
					$stmt->bindParam( ':received_time', $row['received_time'], PDO::PARAM_STR );
					$stmt->bindParam( ':pay', $row['pay'], PDO::PARAM_INT );
					$stmt->bindParam( ':money_collection', $sheet->getCellByColumnAndRow(8, $i)->getValue(), PDO::PARAM_STR );
					$stmt->bindParam( ':service_charge', $row['service_charge'], PDO::PARAM_STR );
					$stmt->bindParam( ':pays', $row['pays'], PDO::PARAM_STR );
					$stmt->bindParam( ':charge_for_collection', $row['charge_for_collection'], PDO::PARAM_STR );
					$stmt->bindParam( ':total_charge', $row['total_charge'], PDO::PARAM_STR );
					$stmt->bindParam( ':vat', $row['vat'], PDO::PARAM_STR );
					$stmt->bindParam( ':total_money', $row['total_money'], PDO::PARAM_STR );
					$stmt->bindParam( ':total_receivable', $row['total_receivable'], PDO::PARAM_STR );
					$stmt->bindParam( ':seller_payments', $row['seller_payments'], PDO::PARAM_STR );

					try
					{
					  $exc = $stmt->execute();
					  
					  if( empty( $row['id'] ) )
						{
							// THÊM L?CH TRÌNH CHO V?N ÐON NÀY
							// L?Y id trình tr?ng d?u tiên ra
							$tinhtrang_lucdau = $db->query( 'SELECT min(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_schedule' )->fetchColumn();
							
							// L?Y ID BILL USER NÀY M?I T?O RA
							$id_moi_tao = $db->query( 'SELECT max(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE userid_add ='.$userid )->fetchColumn();
						
							$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_schedule_bill (id_bill, add_date, status, active) VALUES (:id_bill, :add_date, :status, :active)' );

							$stmt->bindParam( ':id_bill', $id_moi_tao, PDO::PARAM_STR );
							$stmt->bindValue( ':add_date', NV_CURRENTTIME, PDO::PARAM_INT );
							$stmt->bindValue( ':status', $tinhtrang_lucdau, PDO::PARAM_INT );
							$stmt->bindValue( ':active', 1, PDO::PARAM_INT );
							

							$exc = $stmt->execute();
							
							// G?I MAIL Ð?N TÀI KHO?N T?O BILL
						}
					}
					catch( PDOException $e )
					{
					  die( $e->getMessage() );
					}
					
				
				
				
				
				
				}
				}
				
				$nv_Cache->delMod( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );
				die();
				
				//Hi?n th? m?ng d? li?u
				//echo '<pre>';
				//var_dump($data);
				
				$xtpl->parse('main.ok');
		
	}
    }else
      $error = "File không hợp lệ";

}
}
else
{
	$error = $lang_module['no_select_store'];
}


	// LẤY DANH SÁCH USER
	$list_user = $db->query('SELECT * FROM '.$db_config['prefix'] . '_users WHERE active = 1 ORDER BY userid ASC')->fetchAll();
	
	foreach($list_user as $user)
	{
		$xtpl->assign('user', $user);
        $xtpl->parse('main.user');
	}

if(!empty($error))
{
	$xtpl->assign( 'error', $error );
	$xtpl->parse( 'main.error' );
}


$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['import'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';