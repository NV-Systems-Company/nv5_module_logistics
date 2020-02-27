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

if (! nv_function_exists('tra_cuoc_van_don')) {

   function tra_cuoc_van_don($block_config)
    {
        global  $global_config, $site_mods, $module_name, $db, $db_config, $lang_module ;
        $module = $block_config['module'];
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/van-don/global.tra_cuoc_van_don.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/van-don/global.tra_cuoc_van_don.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

			if (file_exists(NV_ROOTDIR . '/modules/van-don/language/' . $global_config['site_lang'] . '.php')) {
                require NV_ROOTDIR . '/modules/van-don/language/' . $global_config['site_lang'] . '.php';
            }
            $xtpl = new XTemplate('global.tra_cuoc_van_don.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/van-don');
			
			// LẤY KHỐI LƯỢNG
			
			$list_khoi_luong = $db->query('SELECT id, tieu_de FROM ' . $db_config['prefix'] . "_" . $site_mods[$module]['module_data'] . '_khoi_luong ORDER BY weight ASC')->fetchAll();

			// LẤY TÀI LIỆU
			$list_tai_lieu = $db->query('SELECT id, tieu_de FROM ' . $db_config['prefix'] . "_" . $site_mods[$module]['module_data'] . '_tai_lieu ORDER BY weight ASC')->fetchAll();

			// LẤY NƯỚC
			$list_nuoc = $db->query('SELECT id, tieu_de FROM ' . $db_config['prefix'] . "_" . $site_mods[$module]['module_data'] . '_nuoc ORDER BY weight ASC')->fetchAll();
			
			// XUẤT RA KHỐI LƯỢNG
			if(!empty($list_khoi_luong))
			{
				foreach($list_khoi_luong as $kl)
				{
					$xtpl->assign( 'kl', $kl);
					$xtpl->parse( 'main.khoi_luong' );
				}
			}

			// XUẤT RA TÀI LIỆU
			if(!empty($list_tai_lieu))
			{
				foreach($list_tai_lieu as $tl)
				{
					$xtpl->assign( 'tl', $tl);
					$xtpl->parse( 'main.tai_lieu' );
				}
			}

			// XUẤT RA NƯỚC
			if(!empty($list_nuoc))
			{
				foreach($list_nuoc as $n)
				{
					$xtpl->assign( 'n', $n);
					$xtpl->parse( 'main.nuoc_den' );
				}
			}

            $xtpl->assign('LINK',NV_BASE_SITEURL.NV_LANG_DATA.'/van-don');
			
			$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
			$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
			$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
			$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
			$xtpl->assign( 'MODULE_NAME',  $module);
			$xtpl->assign( 'MODULE_UPLOAD', $module_upload );
			$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
			$xtpl->assign( 'OP', 'main' );
			$xtpl->assign( 'LANG', $lang_module );
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
}
$content = tra_cuoc_van_don($block_config);