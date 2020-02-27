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

if (! nv_function_exists('nv_search_bill')) {

    /**
     * nv_search_bill()
     *
     * @return
     */
    function nv_search_bill($block_config)
    {
        global $nv_Cache, $global_config, $site_mods, $db_slave, $module_name,$lang_module;
        $module = $block_config['module'];
		
			 // Language
            if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/' . NV_LANG_DATA . '.php')) {
                require_once NV_ROOTDIR . '/modules/' . $module . '/language/' . NV_LANG_DATA . '.php';
				
            }

			if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/logistics/block.search_bill.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/logistics/block.search_bill.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $xtpl = new XTemplate('block.search_bill.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/logistics');
			
            $xtpl->assign( 'LANG', $lang_module );
			$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
			$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
			$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
			$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
			$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
			$xtpl->assign( 'MODULE_NAME', $module );
			$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
			$xtpl->assign( 'OP', 'detail');
			
			$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/'. $global_config['rewrite_exturl'];
			
			$xtpl->assign( 'link', $link);

            $xtpl->parse('main');
            return $xtpl->text('main');
      
    }
}

$content = nv_search_bill($block_config);