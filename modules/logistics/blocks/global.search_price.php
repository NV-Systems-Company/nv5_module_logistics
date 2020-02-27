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

if (! nv_function_exists('nv_search_price')) {

    /**
     * nv_search_price()
     *
     * @return
     */
    function nv_search_price($block_config)
    {
        global $nv_Cache, $global_config, $site_mods, $db_slave, $module_name,$lang_module, $db, $db_config;
        $module = $block_config['module'];
		
			 // Language
            if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/' . NV_LANG_DATA . '.php')) {
                require_once NV_ROOTDIR . '/modules/' . $module . '/language/' . NV_LANG_DATA . '.php';
				
            }

			if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/logistics/block.search_price.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/logistics/block.search_price.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $xtpl = new XTemplate('block.search_price.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/logistics');
			
            $xtpl->assign( 'LANG', $lang_module );
			$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
			$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
			$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
			$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
			$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
			$xtpl->assign( 'MODULE_NAME', $module );
			$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
			$xtpl->assign( 'OP', 'detail');
			$xtpl->assign( 'TEMPLATE', $block_theme);
			
			// LẤY DANH SÁCH TÀI LIỆU HÓA
			$list_document = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_document WHERE status = 1 ORDER BY weight ASC')->fetchAll();
			//print_r($list_document);die;
			foreach($list_document as $document)
			{
				if($document['selected'] == 1)
				$document['checked'] = 'checked=checked';
				else $document['checked'] = '';
				$xtpl->assign('document', $document);
				$xtpl->parse('main.document');
			}
			
			// LẤY DANH SÁCH DỊCH VỤ
			$list_service = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_service WHERE status = 1 ORDER BY weight ASC')->fetchAll();
			
			foreach($list_service as $service)
			{
				if($service['selected'] == 1)
				$service['checked'] = 'checked=checked';
				else $service['checked'] = '';
				$xtpl->assign('service', $service);
				$xtpl->parse('main.service');
			}
			
			// LẤY DANH SÁCH PHỤ THU
			$list_surcharge = $db->query('SELECT * FROM '.$db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_surcharge WHERE status = 1 ORDER BY weight ASC')->fetchAll();
			
			foreach($list_surcharge as $surcharge)
			{
				$xtpl->assign('surcharge', $surcharge);
				$xtpl->parse('main.surcharge');
			}
			
			
			// LẤY TỈNH THÀNH RA
			$list_tinhthanh = $db->query('SELECT provinceid, title, type FROM tms_location_province WHERE status = 1 ORDER BY weight ASC')->fetchAll();
			
			foreach($list_tinhthanh as $tinhthanh)
			{
				$xtpl->assign('l', $tinhthanh);
				$xtpl->parse('main.tinh');
			}


			$xtpl->parse('main');
			return $xtpl->text('main');
      
    }
}

$content = nv_search_price($block_config);