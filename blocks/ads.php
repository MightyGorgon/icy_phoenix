<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_ads'))
{
	function cms_block_ads()
	{
		global $db, $cache, $config, $template, $images, $user, $lang, $block_id, $cms_config_vars;

		include_once(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_main_ads.' . PHP_EXT);

		$banner_var_all = '';
		$banner_var_guests = '';
		switch ($cms_config_vars['md_ads_type'][$block_id])
		{
			case 1:
				$banner_var_all = 'banner_h_s_all';
				$banner_var_guests = 'banner_h_s_guests';
				break;
			case 2:
				$banner_var_all = 'banner_h_m_all';
				$banner_var_guests = 'banner_h_m_guests';
				break;
			case 3:
				$banner_var_all = 'banner_h_l_all';
				$banner_var_guests = 'banner_h_l_guests';
				break;
			case 4:
				$banner_var_all = 'banner_v_s_all';
				$banner_var_guests = 'banner_v_s_guests';
				break;
			case 5:
				$banner_var_all = 'banner_v_m_all';
				$banner_var_guests = 'banner_v_m_guests';
				break;
			case 6:
				$banner_var_all = 'banner_v_l_all';
				$banner_var_guests = 'banner_v_l_guests';
				break;
			case 7:
				$banner_var_all = 'banner_b_s_all';
				$banner_var_guests = 'banner_b_s_guests';
				break;
			case 8:
				$banner_var_all = 'banner_b_m_all';
				$banner_var_guests = 'banner_b_m_guests';
				break;
			case 9:
				$banner_var_all = 'banner_b_l_all';
				$banner_var_guests = 'banner_b_l_guests';
				break;
			default:
				$banner_var_all = 'banner_h_l_all';
				$banner_var_guests = 'banner_h_l_guests';
				break;
		}

		$ads_blocks = array();
		$ads_content = '';
		if (is_array($$banner_var_all) && (sizeof($$banner_var_all) > 0))
		{
			foreach ($$banner_var_all as $tmp_ads)
			{
				$ads_blocks[] = $tmp_ads;
			}
		}
		if (!$user->data['session_logged_in'] && is_array($$banner_var_guests) && (sizeof($$banner_var_guests) > 0))
		{
			foreach ($$banner_var_guests as $tmp_ads)
			{
				$ads_blocks[] = $tmp_ads;
			}
		}

		$ads_counter = sizeof($ads_blocks);
		if ($ads_counter > 0)
		{
			$microtime = explode(' ', microtime());
			$ads_seed = (intval($microtime[0] * 100000) % $ads_counter);
			$ads_content = $ads_blocks[$ads_seed];
		}

		$template->assign_vars(array(
			'ADS_CONTENT' => $ads_content,
			)
		);
	}
}

cms_block_ads();

?>