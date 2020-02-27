<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

$lang_siteinfo = nv_get_lang_module($mod);

$_arr_siteinfo = array();
$cacheFile = NV_LANG_DATA . '_siteinfo_' . NV_CACHE_PREFIX . '.cache';
if (($cache = $nv_Cache->getItem($mod, $cacheFile)) != false) {
    $_arr_siteinfo = unserialize($cache);
} else {
    // Tong so bai viet
$_arr_siteinfo['tongsovandon'] = $db_slave->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $mod_data . '_rows ')->fetchColumn();


    $nv_Cache->setItem($mod, $cacheFile, serialize($_arr_siteinfo));
}

// Tong so bai viet
$siteinfo[] = array(
    'key' => $lang_siteinfo['tongsovandon'],
    'value' => number_format($_arr_siteinfo['tongsovandon'])
);


