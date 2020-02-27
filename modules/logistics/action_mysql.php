<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
// BẢNG TRA CƯỚC PHÍ
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_price";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_price_document";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_zone";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_document";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_zone_address";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_service"; // BẢNG GÓI DỊCH VỤ
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_surcharge"; // BẢNG PHỤ THU

// KẾT THÚC BẢNG TRA CƯỚC PHÍ

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_store";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_customer";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_schedule"; // Lịch trình vận đơn
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_rows";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_schedule_bill";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_price (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL,
  id_document int(11) NOT NULL COMMENT 'id tài liệu hàng hóa',
  id_service int(11) NOT NULL COMMENT 'id gói dịch vụ',
  id_zone int(11) NOT NULL COMMENT 'id khu vực',
  active int(11) NOT NULL DEFAULT '1' COMMENT 'kích hoạt',
  price text NOT NULL COMMENT 'khoảng khối lượng - giá',
  note varchar(250) DEFAULT '' COMMENT 'ghi chú',
  PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_price_document (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_document int(11) NOT NULL COMMENT 'id tài liệu hàng hóa',
  price text NOT NULL COMMENT 'khoảng khối lượng - giá',
  price_min double NOT NULL DEFAULT '0' COMMENT 'giá thấp nhất',
  PRIMARY KEY (id)
) ENGINE=MyISAM";



$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_zone (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL,
  title varchar(250) NOT NULL,
  note text COMMENT 'ghi chú',
  entry_fee int(11) NOT NULL DEFAULT '1' COMMENT '1 cước nhập, 2 cước cấu hình',
  import int(11) DEFAULT '0' COMMENT 'cước phí nhập vào',
  configuration varchar(250) DEFAULT '' COMMENT 'cước phí cấu hình',
  status int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_document (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL,
  title varchar(250) NOT NULL,
  selected int(11) NOT NULL DEFAULT '0',
  status int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_service (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL,
  title varchar(250) NOT NULL,
  note text COMMENT 'ghi chú',
  selected int(11) NOT NULL DEFAULT '0',
  status int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_surcharge (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL,
  title varchar(250) NOT NULL,
  price double NOT NULL,
  note text COMMENT 'ghi chú',
  status int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_zone_address (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL, 
  city int(10) NOT NULL DEFAULT '0' COMMENT 'tỉnh thành',
  district varchar(250) NOT NULL COMMENT 'danh sách quận huyện',
  wards varchar(250) DEFAULT '' COMMENT 'danh sách xã phường',
  id_zone int(11) NOT NULL COMMENT 'id khu vực',
  status int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

// KẾT THÚC TẠO CÁC BẢNG TRA GIÁ

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_store (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL,
  title varchar(250) NOT NULL,
  name varchar(250) NOT NULL COMMENT 'tên người quản lý',
  phone varchar(250) NOT NULL,
  city int(10) NOT NULL DEFAULT '0' COMMENT 'tỉnh thành',
  district int(10) NOT NULL DEFAULT '0' COMMENT 'quận huyện',
  wards int(10) NOT NULL DEFAULT '0' COMMENT 'xã phường',
  address varchar(250) NOT NULL,
  note text COMMENT 'ghi chú',
  userid int(10) NOT NULL ,
  status int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_customer (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL,
  title varchar(250) NOT NULL COMMENT 'họ tên',
  name varchar(250) DEFAULT '' COMMENT 'tên công ty',
  phone varchar(250) NOT NULL,
  city int(10) NOT NULL DEFAULT '0' COMMENT 'tỉnh thành',
  district int(10) NOT NULL DEFAULT '0' COMMENT 'quận huyện',
  wards int(10) NOT NULL DEFAULT '0' COMMENT 'xã phường',
  address varchar(250) NOT NULL,
  note text COMMENT 'ghi chú',
  userid int(10) NOT NULL ,
  status int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

// BẢNG DANH SÁCH LỊCH TRÌNH VẬN ĐƠN _schedule

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_schedule (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL,
  title varchar(250) NOT NULL,
  note text COMMENT 'mô tả',
  statistical int(11) NOT NULL DEFAULT '0' COMMENT 'thống kê ngoài trang chủ',
  delete_user int(11) NOT NULL DEFAULT '1' COMMENT 'Lịch trình được phép xóa',
  status int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_rows (
  id int(11) NOT NULL AUTO_INCREMENT,
  weight int(11) NOT NULL,
  id_store int(11) NOT NULL,
  send_name varchar(250) NOT NULL,
  send_phone varchar(250) NOT NULL,
  send_address varchar(250) NOT NULL,
  send_city int(10) NOT NULL DEFAULT '0' COMMENT 'tỉnh thành người gửi',
  send_district int(10) NOT NULL DEFAULT '0' COMMENT 'quận huyện người gửi',
  send_wards int(10) NOT NULL DEFAULT '0' COMMENT 'xã phường người gửi',
  receive_name varchar(250) NOT NULL,
  receive_phone varchar(250) NOT NULL,
  receive_address varchar(250) NOT NULL,
  id_document int(11) NOT NULL COMMENT 'id hàng hóa',
  document_name varchar(250) NOT NULL COMMENT 'tên hàng',
  bill varchar(250) NOT NULL COMMENT 'mã đơn hàng',
  amount int(11) NOT NULL COMMENT 'số lượng',
  value_goods double NOT NULL COMMENT 'giá trị hàng',
  weight_document varchar(250) NOT NULL COMMENT 'trọng lượng',
  long_document float DEFAULT '0' COMMENT 'dài',
  wide float DEFAULT '0' COMMENT 'rộng',
  height float DEFAULT '0' COMMENT 'cao',
  id_service int(11) NOT NULL COMMENT 'id dịch vụ chuyển phát',
  id_surcharge varchar(250) DEFAULT '' COMMENT 'id phụ thu, giá trị gia tăng',
  other_requirements varchar(250) COMMENT 'yêu cầu khác',
  delivery_date int(11) COMMENT 'ngày giao hàng',
  delivery_time varchar(250) COMMENT 'khung giờ giao hàng',
  received_date int(11) COMMENT 'ngày lấy hàng',
  received_time varchar(250) COMMENT 'khung giờ lấy hàng',
  pay int(11) DEFAULT '1' COMMENT 'hình thức thanh toán lưu giá trị 1 ghi nợ, 2 tiền mặt, 3 người nhận thanh toán',
  money_collection float DEFAULT '0' COMMENT 'tiền thu hộ = tiền hàng',
  service_charge float DEFAULT '0' COMMENT 'Cước dịch vụ',
  pays float DEFAULT '0' COMMENT 'phụ cước',
  charge_for_collection float DEFAULT '0' COMMENT 'Cước thu hộ = tiền hàng x 2%',
  total_charge float DEFAULT '0' COMMENT 'tổng cước',
  vat float DEFAULT '0' COMMENT 'thuế',
  total_money float DEFAULT '0' COMMENT 'tổng thu',
  total_receivable float DEFAULT '0' COMMENT 'Tổng số phải thu',
  seller_payments float DEFAULT '0' COMMENT 'Tiền trả người bán',
  add_date int(11) NOT NULL COMMENT 'ngày tạo vận đơn',
  userid_add int(11) NOT NULL COMMENT 'Tài khoản tạo vận đơn',
  status int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_schedule_bill (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_bill int(11) NOT NULL,
  weight int(11) NOT NULL,
  add_date int(11) DEFAULT NULL,
  receiver varchar(100) DEFAULT NULL COMMENT 'người nhận',
  employees varchar(100) DEFAULT NULL COMMENT 'nhân viên',
  note varchar(250) DEFAULT NULL COMMENT 'ghi chú',
  status int(11) NOT NULL DEFAULT '0' COMMENT 'trạng thái vận đơn',
  active int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM  ";

