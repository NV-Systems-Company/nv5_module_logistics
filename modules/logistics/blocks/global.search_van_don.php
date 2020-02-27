<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2018 NV Systems. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 13 Jun 2018 03:19:45 GMT
 */

if (! defined('NV_SYSTEM')) {
    die('Stop!!!');
}

if (! nv_function_exists('search_van_don')) {

   function search_van_don($block_config)
    {
        global  $global_config, $site_mods, $module_name, $lang_module ;
        $module = $block_config['module'];
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/van-don/global.search_van_don.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/van-don/global.search_van_don.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }
			if (file_exists(NV_ROOTDIR . '/modules/van-don/language/' . NV_LANG_DATA . '.php')) {
                require NV_ROOTDIR . '/modules/van-don/language/' . NV_LANG_DATA . '.php';
				
            }
            $xtpl = new XTemplate('global.search_van_don.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/van-don');
			
            $xtpl->assign('LINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=van-don');
			
			$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
			$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
			$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
			$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
			$xtpl->assign( 'MODULE_NAME', $module);
			$xtpl->assign( 'MODULE_UPLOAD', $module_upload );
			$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
			$xtpl->assign( 'OP', 'main' );
			$xtpl->assign( 'LANG', $lang_module );
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
}
$content = search_van_don($block_config);