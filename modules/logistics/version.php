<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'Logistics',
	'modfuncs' => 'main,detail,search,add,items,store,print,customer,financials, barcode,print-all,import',
	'change_alias' => 'main,detail,search,add,items,store',
	'submenu' => 'main,detail,search,add,items,store,import',
	'is_sysmod' => 0,
	'virtual' => 1,
	'version' => '4.0.00',
	'date' => 'Wed, 13 Jun 2018 03:19:45 GMT',
	'author' => 'NV Systems (hoangnt@nguyenvan.vn)',
	'uploads_dir' => array($module_name),
	'note' => ''
);