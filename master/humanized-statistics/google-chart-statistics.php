<?php
/*
Plugin Name: Humanized statistics
Plugin URI: http://kwark.allwebtuts.net
Description: Statistics for wordpress by posts, by pages and for home page (sticky posts vs normal posts), and by categories
Author: Laurent (KwarK) Bertrand
Version: 0.1
Author URI: http://kwark.allwebtuts.net
*/

/*  
	Copyright 2012  Laurent (KwarK) Bertrand  (email : kwark@allwebtuts.net)
	
	Small pizza donation @ http://kwark.allwebtuts.net
	
	You can not remove comments such as my informations.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


// disallow direct access to file
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	wp_die(__('Sorry, but you cannot access this page directly.', 'gcs'));
}

// General plugins path or url
$GLOBALS['gcs_plugin_path'] = $gcs_plugin_path = PLUGIN_DIR_PATH(__FILE__);
$GLOBALS['gcs_plugin_url'] = $gcs_plugin_url = WP_PLUGIN_URL . '/humanized-statistics/';
$GLOBALS['gcs_url'] = $gcs_url = home_url;

//langages
load_plugin_textdomain( 'gcs', true, dirname( plugin_basename( __FILE__ ) ) . '/wp-languages/' );



//Basics for admin page
add_action('admin_menu', 'gcs_options_page');

function gcs_options_page()
{
	add_menu_page('Statistics', __('Statistics', 'gcs'), 'manage_options', 'google-chart-statistics.php');
	add_submenu_page('google-chart-statistics.php', 'General config', __('General configuration', 'gcs'), 'manage_options', 'google-chart-statistics.php', 'gcs_do_admin_page');
	
	do_action('gcs_add_admin_sub_menus'); //plugin api
}


if(is_admin())
{
    wp_register_style('gcs-admincss', plugins_url('css/admin.css', __FILE__));
    wp_enqueue_style('gcs-admincss');
	
	//Default on installation hook
	function gcs_add_defaut_settings()
	{
		global $wpdb;
		
		$settings = array(
			'gcs_request_divisor' => '1',
			'gcs_3d_chart' => '',
			'gcs_themify' => 'dark-hive',
			'facebook_referer' => 'facebook.com',
			'twitter_referer' => 'twitter.com',
			'google_referer' => 'plus.google.com',
			'linkedin_referer' => 'linkedin.com',
			'pinterest_referer' => 'pinterest.com',
			'scoop_referer' => 'scoop.it',
			'qzone_referer' => 'qzone.qq.com',
			'weibo_referer' => 'weibo.com',
			'vk_referer' => 'vk.com',
			'gcs_social' => 'facebook.com,twitter.com,plus.google.com,linkedin.com,pinterest.com,scoop.it,weibo.com,qzone.qq.com,vk.com'
		);
	
		foreach ($settings as $key => $value)
		{
			update_option($key, $value);
		}
	}
	register_activation_hook(__FILE__, 'gcs_add_defaut_settings');
	
	function gcs_delete_defaut_settings()
	{
		global $wpdb;
		
		$settings = array(
			'gcs_request_divisor',
			'gcs_3d_chart',
			'gcs_themify',
			'facebook_referer',
			'twitter_referer',
			'google_referer',
			'linkedin_referer',
			'pinterest_referer',
			'scoop_referer',
			'qzone_referer',
			'weibo_referer',
			'vk_referer',
			'gcs_social'
		);
	
		foreach ($settings as $v) {
			delete_option( ''.$v.'' );
		}
	//Delete remains
	$wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'gcs\_%'");
	$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE 'gcs\_%'");
	}
	register_uninstall_hook(__FILE__, 'gcs_delete_defaut_settings');
	
	//Construct admin page
	include($gcs_plugin_path . 'pages-admin/admin-gcs.php');
	include($gcs_plugin_path . 'pages-admin/admin-cats.php');
	include($gcs_plugin_path . 'pages-admin/admin-home.php');
}



//Special copyright - Copyright © 2008 Darrin Yeager under BSD license http://www.dyeager.org/
function getDefaultLanguage()
{
   if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
   {
	  return parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
   }
}
function parseDefaultLanguage($http_accept, $deflang = "en")
{
   if(isset($http_accept) && strlen($http_accept) > 1)
   {
	  # Split possible languages into array
	  $x = explode(',', $http_accept);
	  
	  foreach ($x as $val)
	  {
		 #check for q-value and create associative array. No q-value means 1 by rule
		 if(preg_match("/(.*);q=([0-1]{0,1}\.\d{0,4})/i", $val, $matches))
		 {
			$lang[$matches[1]] = (float)$matches[2];
		 }
		 else
		 {
			$lang[$val] = 1.0;
		 }
	  }

	  #return default language (highest q-value)
	  $qval = 0.0;
	  foreach($lang as $key => $value)
	  {
		 if($value > $qval)
		 {
			$qval = (float)$value;
			$deflang = $key;
		 }
	  }
   }
   return strtolower($deflang);
}
//End special copyright


//Function Get IP Address
function gcs_ip()
{
	if(isset($_SERVER['HTTP_CLIENT_IP']))
	{
		$ip_address = $_SERVER['HTTP_CLIENT_IP'];
	} 
	elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} 
	elseif(isset($_SERVER['REMOTE_ADDR']))
	{
		$ip_address = $_SERVER['REMOTE_ADDR'];
	} 
	else
	{
		$ip_address = '';
	}
	if(strpos($ip_address, ',') !== false)
	{
		$ip_address = explode(',', $ip_address);
		$ip_address = $ip_address[0];
	}
	return esc_attr($ip_address);
}


//Main function for all statistics collector
add_filter('the_content', 'gcs_filter_statistics');

function gcs_filter_statistics($content)
{
	if(!is_home() && !is_front_page())
	{
		global $id;
		
		$mule = get_option('gcs_request_divisor');
		
		if(!$mule)
		$mule = 1;
		
		$hit = 0; 
		$hit = get_post_meta($id, 'gcs_hit', true);
		
		$up = $hit + 1;
		update_post_meta($id, 'gcs_hit', $up);
		
		$counter = $hit / $mule;
		
		//ONLY HERE FOR PERFORMANCE TEST ON LOCALHOST - LEAVE COMMENTED
		/*$before = (float)preg_replace('#,#', '.', timer_stop(0));*/
		
		if(is_int($counter)) //To make sur if gcs_hit > (int) 1... (after a php warning, or others problems)
		{
			global $user_ID, $blog_id, $is_iphone, $is_chrome, $is_safari, $is_NS4, $is_opera, $is_macIE, $is_winIE, $is_gecko, $is_lynx, $is_IE;
			//Get all meta (one request)
			$gcs = get_post_meta($id);

			update_post_meta($id, 'gcs_hit', 1);
			
			$userip = gcs_ip();
			$verif_ip = $userip;
			
			$browser_language = getDefaultLanguage();
			
			//Filter IP and Total known visitors statistics + known users by browser languages
			if(is_user_logged_in())
			{
				$user_ip = get_user_meta($user_ID, 'gcs_ip', true);
	
				//Filter IP know user disconnected
				if(!$user_ip || $user_ip !== $verif_ip)
				{
					update_user_meta($user_ID, 'gcs_ip', $verif_ip);
				}
				
				//Total visit known users
				$count = 0; 
				$count = $gcs['gcs_total_known'][0];
				
				$up = $count + $mule;
				update_post_meta($id, 'gcs_total_known', $up);
				
				
				//Total visit known users by browser languages
				if($browser_language)
				{
					$count = 0; 
					$count = $gcs['gcs_total_known_'.$browser_language.''][0];
					
					$up = $count + $mule;
					update_post_meta($id, 'gcs_total_known_'.$browser_language.'', $up);
				}
				
				//Update user meta prefered categories
				$category = get_the_category();
				$cat = $category[0]->term_id;
				
				if($cat)
				{
					//!\Here it's user_meta!
					$count = 0;
					$count = get_user_meta($user_ID, 'gcs_cat_'.$blog_id.'_'.$cat.'', true);
					
					$up = $count + $mule;
					update_user_meta($user_ID, 'gcs_cat_'.$blog_id.'_'.$cat.'', $up);
				}
			}
			
			//Total unknown visitors statistics + unknown users by browser languages
			if(!is_user_logged_in())
			{
				global $wpdb;
				
				$verif_ip = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->usermeta WHERE meta_key='gcs_ip' AND meta_value=%s", $userip));
				//Unknow user
				if(!$verif_ip)
				{
					//Total unknown visitor
					$count = 0;
					$count = $gcs['gcs_total_unknown'][0];
					
					$up = $count + $mule;
					update_post_meta($id, 'gcs_total_unknown', $up);
					
					//Total visit unknown users by browser languages
					if($browser_language)
					{
						$count = 0; 
						$count = $gcs['gcs_total_unknown_'.$browser_language.''][0];
						
						$up = $count + $mule;
						update_post_meta($id, 'gcs_total_unknown_'.$browser_language.'', $up);
					}
				}
				
				//known user but disconnected
				if($verif_ip)
				{
					//Total known visitor
					$count = 0;
					$count = $gcs['gcs_total_known'][0];
					
					$up = $count + $mule;
					update_post_meta($id, 'gcs_total_known', $up);
					
					//Total visit known users by browser languages
					if($browser_language)
					{
						$count = 0; 
						$count = $gcs['gcs_total_known_'.$browser_language.''][0];
						
						$up = $count + $mule;
						update_post_meta($id, 'gcs_total_known_'.$browser_language.'', $up);
					}
					
					//Total known visitor disconnected
					$count = 0;
					$count = $gcs['gcs_total_known_disconnected'][0];
					
					$up = $count + $mule;
					update_post_meta($id, 'gcs_total_known_disconnected', $up);
				}
			}
			
			//Social networks vs Internal vs Organics
			$referer = preg_replace('#/$#', '', wp_get_referer());
			/*var_dump($from);*/
			if($referer)
			{
				$temp = parse_url($referer);
				$from = $temp['host'];
				
				//www + host name + extension
				$referer_host = $from;
				
				//host name + extension
				$referer_full = preg_replace('#www\.#', '', $from);
				/*var_dump($referer_full);*/
				
				//Update by social referers
				$socials = get_option('gcs_social');
				$temp = explode(',', $socials);
				
				foreach($temp as $dom)
				{
					if($dom == $referer_full)
					{
						//host name
						$referer_name = preg_replace('#\.#', '_', $referer_full);
						/*var_dump($referer_name);*/
						$count = 0;
						$count = $gcs['gcs_social-'.$referer_name.''][0];
						
						$up = $count + $mule;
						update_post_meta($id, 'gcs_social-'.$referer_name.'', $up);
					}
				}
			
				//Update internal referer
				$site_url = get_bloginfo('wpurl');
				$internal_dom = preg_replace('#http://|https://|/$#', '', $site_url);
				$current_url = preg_replace('#/$#', '', get_permalink());
			
				if($referer_host == $internal_dom && $current_url != $referer)
				{
					$count = 0;
					$count = $gcs['gcs_internal'][0];
					
					$up = $count + $mule;
					update_post_meta($id, 'gcs_internal', $up);
				}
				
				//Update where referer is home page
				if(!is_sticky($id) && $referer == $site_url)
				{
					$count = 0;
					$count = $gcs['gcs_referer_home'][0];
					
					$up = $count + $mule;
					update_post_meta($id, 'gcs_referer_home', $up);
				}
				
				//Update by is_sticky from home page
				if(is_sticky($id) && $referer == $site_url)
				{
					$count = 0;
					$count = $gcs['gcs_sticky_home'][0];
					
					$up = $count + $mule;
					update_post_meta($id, 'gcs_sticky_home', $up);
				}
				
				//Update for search organics
				$test_organics = preg_match('#(www\.|)google\.+(.*)|search\.msn\.+(.*)|search\.yahoo\.+(.*)|(www\.|)bing\.+(.*)|msxml\.excite\.com|search\.lycos\.com|(www\.|)alltheweb\.com|search\.aol\.com|ask\.com|(www\.|)hotbot\.com|(www\.|)metacrawler\.com|go\.google\.com|search\.netscape\.com|dpxml\.webcrawler\.com|search\.earthlink\.net|(www\.|)ask\.co\.uk#i', $referer_host, $matches);
				
				if($test_organics)
				{
					$name = preg_replace(array('#www\.#', '#\.#'), array('', '_'), $matches[0]);
					
					$count = 0;
					$count = $gcs['gcs_organics-'.$name.''][0];
					
					$up = $count + $mule;
					update_post_meta($id, 'gcs_organics-'.$name.'', $up);
				}
			}
			
			//Update post meta total by categories
			$category = get_the_category();
			$cat = $category[0]->term_id;
			
			if($cat)
			{
				$count = 0;
				$count = $gcs['gcs_category_'.$cat.''][0];
				
				$up = $count + $mule;
				update_post_meta($id, 'gcs_category_'.$cat.'', $up);
			}
			
			//Update for browsers
			if($is_iphone || $is_chrome || $is_safari || $is_NS4 || $is_opera || $is_macIE || $is_winIE || $is_gecko || $is_lynx || $is_IE)
			{
				if($is_iphone)
				$navigator = 'is_phone';//iPhone Safari
				elseif($is_chrome)
				$navigator = 'is_chrome';//Google Chrome
				elseif($is_winIE)
				$navigator = 'is_winie';//Windows Internet Explorer
				elseif($is_IE)
				$navigator = 'is_ie';//Internet Explorer
				elseif($is_gecko)
				$navigator = 'is_gecko';//FireFox
				elseif($is_opera)
				$navigator = 'is_opera';//Opera
				elseif($is_NS4)
				$navigator = 'is_ns4';//Netscape 4
				elseif($is_safari)
				$navigator = 'is_safari';//Safari
				elseif($is_macIE)
				$navigator = 'is_macie';//Mac Internet Explorer
				elseif($is_lynx)
				$navigator = 'is_lynx';//Lynx
				else
				$navigator = 'is_others';//Unknown/Others
				
				$count = 0;
				$count = $gcs['gcs_browser_'.$navigator.''][0];
				
				//Update counter by navigator
				$up = $count + $mule;
				update_post_meta($id, 'gcs_browser_'.$navigator.'', $up);
				
				//Update number queries
				$up = get_num_queries();
				update_post_meta($id, 'gcs_total_queries', $up);
				
				//Update memory peak usage
				$up = memory_get_peak_usage();
				$up = $up/1024/1024;
				update_post_meta($id, 'gcs_total_memory', $up);
				
				//Update time to load by navigator
				$count = $gcs['gcs_total_loadtime_when_'.$navigator.''][0];
				if(!$count)
				{
					$count = 10; //Cheating if no one first time up to date
				}
				$up = timer_stop(0);
				
				if($up < $count)
				{
					update_post_meta($id, 'gcs_total_loadtime_when_'.$navigator.'', $up);
				}
			}
			//Update visitors by countries (LEAVE COMMENTED! GEOIP php extension needed! Chart for countries currently not builded!)
			/*$country = geoip_country_name_by_name($userip);
			if ($country)
			{
				$country = preg_replace(array('# #', '#%20#'), array('_', '_'), strtolower($country));
				$count = 0;
				$count = get_post_meta($id, 'gcs_geoip_'.$country.'', true);
				
				$up = $count + $mule;
				update_post_meta($id, 'gcs_geoip_'.$country.'', $up);
			}*/
		}
		//ONLY HERE FOR PERFORMANCE TEST ON LOCALHOST - LEAVE COMMENTED
		/*$after = (float)preg_replace('#,#', '.', timer_stop(0));*/
		
	}
	//ONLY HERE FOR PERFORMANCE TEST ON LOCALHOST - LEAVE COMMENTED
	/*echo $after - $before . ' seconde';*/
	return $content;
}//END main function statistics collector
?>