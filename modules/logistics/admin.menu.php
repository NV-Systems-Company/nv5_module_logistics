<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if ( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );


$menu_add = array();
$menu_add['import'] = $lang_module['import'];
$submenu['add'] = array( 'title' => $lang_module['add'], 'submenu' => $menu_add );

$menu_items = array();
$menu_items['returns'] = $lang_module['returns'];
$menu_items['approval'] = $lang_module['approval'];
$submenu['items'] = array( 'title' => $lang_module['items'], 'submenu' => $menu_items );



$submenu['financials'] = $lang_module['financials'];

$submenu['document'] = $lang_module['document'];
$submenu['service'] = $lang_module['service'];
$submenu['surcharge'] = $lang_module['surcharge'];
$submenu['zone'] = $lang_module['zone'];
$submenu['zone_address'] = $lang_module['zone_address'];
$submenu['price'] = $lang_module['price'];
$submenu['calendar'] = $lang_module['calendar'];
$submenu['store'] = $lang_module['store'];

$menu_store_add = array();
$menu_store_add['store_add'] = $lang_module['store_add'];
$submenu['store'] = array( 'title' => $lang_module['store'], 'submenu' => $menu_store_add );
$submenu['customer_user'] = $lang_module['customer_user'];
