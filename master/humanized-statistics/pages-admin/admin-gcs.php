<?php 
/*  
	Copyright 2012  Laurent (KwarK) Bertrand  (email : kwark@allwebtuts.net)
*/

// disallow direct access to file
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	wp_die(__('Sorry, but you cannot access this page directly.', 'ams'));
}

global $gcs_plugin_path, $gcs_plugin_url;

//Admin charts Api
wp_register_script('gcs_google_charts', 'https://www.google.com/jsapi');
wp_enqueue_script('gcs_google_charts');

wp_enqueue_script('jquery-ui-slider');
wp_enqueue_script('jquery-ui-button');

$themify = get_option('gcs_themify');

if(!$themify){$themify = 'dark-hive';}

wp_register_style('gcs_jquery_ui_css', $gcs_plugin_url . 'css/'.$themify.'/jquery-ui-1.8.23.custom.css');
wp_enqueue_style('gcs_jquery_ui_css');
/*wp_register_script('gcs_admin_pagination', ''.$gcs_plugin_url.'js/jPages.min.js', array('jquery'));
wp_enqueue_script('gcs_admin_pagination');*/

//Start admin page
function gcs_do_admin_page()
{
	if(isset($_POST['submitted']) && $_POST['submitted'] == "yes")
	{
		//Requests divisor
		$gcs_request_divisor = stripslashes($_POST['gcs_request_divisor']);
		
		update_option('gcs_request_divisor', $gcs_request_divisor);
		
		if($gcs_request_divisor == '0')//Debug if user hit manually 0
		{
			update_option('gcs_request_divisor', '1');
		}

		//Themify
		$gcs_themify = stripslashes($_POST['gcs_themify']);
		update_option('gcs_themify', $gcs_themify);
		
		$gcs_3d_chart = stripslashes($_POST['gcs_3d_chart']);
		update_option('gcs_3d_chart', $gcs_3d_chart);
		
		//Referers
		$facebook_referer = stripslashes($_POST['facebook_referer']);
		update_option('facebook_referer', $facebook_referer);
		
		$twitter_referer = stripslashes($_POST['twitter_referer']);
		update_option('twitter_referer', $twitter_referer);
		
		$google_referer = stripslashes($_POST['google_referer']);
		update_option('google_referer', $google_referer);
		
		$linkedin_referer = stripslashes($_POST['linkedin_referer']);
		update_option('linkedin_referer', $linkedin_referer);
		
		$pinterest_referer = stripslashes($_POST['pinterest_referer']);
		update_option('pinterest_referer', $pinterest_referer);
		
		$scoop_referer = stripslashes($_POST['scoop_referer']);
		update_option('scoop_referer', $scoop_referer);
		
		$qzone_referer = stripslashes($_POST['qzone_referer']);
		update_option('qzone_referer', $qzone_referer);
		
		$weibo_referer = stripslashes($_POST['weibo_referer']);
		update_option('weibo_referer', $weibo_referer);
		
		$vk_referer = stripslashes($_POST['vk_referer']);
		update_option('vk_referer', $vk_referer);
		
		//Construct referers
		$temp = array();
		$temp[] = get_option('facebook_referer');
		$temp[] = get_option('twitter_referer');
		$temp[] = get_option('google_referer');
		$temp[] = get_option('linkedin_referer');
		
		$temp[] = get_option('pinterest_referer');
		$temp[] = get_option('scoop_referer');
		
		$temp[] = get_option('weibo_referer');
		$temp[] = get_option('qzone_referer');
		
		$temp[] = get_option('vk_referer');
		
		if($temp)
		{
			$gcs_social = implode(',', array_filter($temp));
			update_option('gcs_social', $gcs_social);
			
			/*var_dump($gcs_social);*/
		}
		
		echo '<div id="message" class="updated fade"><p><strong>';
		_e('Your settings have been saved', 'gcs');
		echo '.</strong></p></div>';
	}
?>
<?php /**Start Form for advertises**/?>
<div class="wrap wb-admin">
	   	<div class="icon32" id="icon-tools"><br />
	  	</div>
	  	<h2>
		<?php _e('Google chart statistics - General configuration', 'gcs'); ?>
	  	</h2>
	  	<form method="post" action="" class="gcs_admin">
		<p class="submit">
		<input type="submit" name="options_submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
		</p>
        <script type="text/javascript">
          jQuery(document).ready(function($){
			<?php /*?>$( "#gcs_divisor_slider" ).slider({
				value:<?php $temp = get_option('gcs_request_divisor'); if(!$temp){echo (int)1;}else {echo (int)$temp;} ?>,
				min: 1,
				max: 1000,
				slide: function( event, ui ) {
					$( "#gcs_request_divisor" ).val( ui.value );
				}
			});
			$( "#gcs_request_divisor" ).val( $( "#gcs_divisor_slider" ).slider( "value" ) );<?php */?>
			
			
			var valMap = [1, 2, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000, 10000];
			$("#gcs_divisor_slider").slider({
				range: false,
				min: 0,
				max: 12,
				values: [0],
				slide: function(event, ui) {                        
						$("#gcs_request_divisor").val(valMap[ui.values[0]]);       
				}  
			});
			$("#gcs_request_divisor").val(<?php $temp = get_option('gcs_request_divisor'); echo (int)$temp; ?>);
	
			//Theme
			$("#themify").buttonset();
			$("#dchart").buttonset();
			
			//Main World
			$("#facebook").buttonset();
			$("#twitter").buttonset();
			$("#linkedin").buttonset();
			$("#google").buttonset();
			
			//Main Pinboard
			$("#pinterest").buttonset();
			$("#scoop").buttonset();
			
			//Main China
			$("#qzone").buttonset();
			$("#weibo").buttonset();
			
			//Main Russia
			$("#vk").buttonset();
		});
		</script>
        <table class="widefat options" style="width: 650px">
        <thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Options plugin theme in dashboard', 'gcs'); ?></th></tr></thead>
	  	<tr valign="top">
        <td scope="row"><label>
			<?php _e('Themify the plugin with...', 'gcs'); 
			$temp = get_option('gcs_themify');
			?>
		  </label></td>
		<td scope="row" style="vertical-align:middle">
        <div id="themify"><input type="radio" id="themify1" name="gcs_themify" value="dark-hive" <?php if($temp == 'dark-hive'){ echo 'checked="checked"';}?> /><label for="themify1">dark</label>
        <input type="radio" id="themify2" name="gcs_themify" value="start" <?php if($temp == 'start'){ echo 'checked="checked"';}?> /><label for="themify2">start</label>
         <input type="radio" id="themify3" name="gcs_themify" value="black-tie" <?php if($temp == 'black-tie'){ echo 'checked="checked"';}?> /><label for="themify3">black</label>
		</div>
        </td>
         <td scope="row"></td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Themify your administration (bar, buttons,...)', 'gcs'); ?>"></span>
          </td>
	  </tr>
      <tr valign="top">
       <td scope="row" style="width:380px;"><label>
			<?php _e('Themify datas with 3D Chart', 'gcs'); 
			$temp = get_option('gcs_3d_chart');
			?>
		  </label></td>
		<td scope="row" style="vertical-align:middle;">
        <div id="dchart"><input type="radio" id="dchart1" name="gcs_3d_chart" value="yes" <?php if($temp == 'yes'){ echo 'checked="checked"';}?> /><label for="dchart1">On</label>
        <input type="radio" id="dchart2" name="gcs_3d_chart" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="dchart2">Off</label>
		</div>
        </td>
         <td scope="row"></td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Themify your visual datas with Chart 3D', 'gcs'); ?>"></span>
        </td>
	  </tr>
      </table><br /><table class="widefat options" style="width: 650px">
        <thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Options performance', 'gcs'); ?></th></tr></thead>
      <tr valign="top">
        <td scope="row" style="width:380px;"><label>
			<?php _e('Collect statistics of 1 user every...', 'gcs'); ?>
		  </label></td>
		<td scope="row">
			<div style="margin:5px; width:150px" id="gcs_divisor_slider"></div>
        </td>
		<td scope="row"><input type="text" id="gcs_request_divisor" name="gcs_request_divisor" style="border:0; cursor:default; opacity:0.5; color: #0078AE; font-weight:bold; text-shadow:0.1em 0.1em #77D5F7;" /></td>
         <td scope="row"><span class="livetv_help" title="<?php _e('Decrease request if your site has a lot of visitors. This option don\'t affects any others statistics and the plugin creates an accurate assessment. You may leave 1 to disable this option and hit 1/1 user. You may use left and right arrow keys on your keyboard to decrease or increase this option.', 'gcs'); ?>"></span></td>
	  </tr>
      </table><br /><table class="widefat options" style="width: 650px">
        <thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Options social networks referers', 'gcs'); ?></th></tr><tr><th colspan="4" class="dashboard-widget-title"><?php _e('World', 'gcs'); ?></th></tr></thead>
	  	<tr valign="top">
        <td scope="row" style="width:380px;"><label>
			<?php _e('Collect statistics for Facebook referer', 'gcs'); 
			$temp = get_option('facebook_referer');
			?>
		  </label></td>
		<td scope="row" style="vertical-align:middle">
        <div id="facebook"><input type="radio" id="facebook1" name="facebook_referer" value="facebook.com" <?php if($temp == 'facebook.com'){ echo 'checked="checked"';}?> /><label for="facebook1">On</label>
		<input type="radio" id="facebook2" name="facebook_referer" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="facebook2">Off</label></div>
        </td>
         <td scope="row">&nbsp;</td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Turn Off or On for statistics survey of facebook.com', 'gcs'); ?>"></span>
          </td>
	  </tr>
      <tr valign="top">
        <td scope="row" style="width:380px;"><label>
			<?php _e('Collect statistics for Twitter referer', 'gcs'); 
			$temp = get_option('twitter_referer');
			?>
		  </label></td>
		<td scope="row">
        <div id="twitter"><input type="radio" id="twitter1" name="twitter_referer" value="twitter.com" <?php if($temp == 'twitter.com'){ echo 'checked="checked"';}?> /><label for="twitter1">On</label>
		<input type="radio" id="twitter2" name="twitter_referer" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="twitter2">Off</label></div>
        </td>
         <td scope="row">&nbsp;</td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Turn Off or On for statistics survey of twitter.com', 'gcs'); ?>"></span>
        </td>
	  </tr>
      <tr valign="top">
        <td scope="row" style="width:380px;"><label>
			<?php _e('Collect statistics for Google+ referer', 'gcs'); 
			$temp = get_option('google_referer');
			?>
		  </label></td>
		<td scope="row">
        <div id="google"><input type="radio" id="google1" name="google_referer" value="plus.google.com" <?php if($temp == 'plus.google.com'){ echo 'checked="checked"';}?> /><label for="google1">On</label>
		<input type="radio" id="google2" name="google_referer" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="google2">Off</label></div>
        </td>
         <td scope="row">&nbsp;</td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Turn Off or On for statistics survey of plus.google.com', 'gcs'); ?>"></span>
        </td>
	  </tr>
       <tr valign="top">
        <td scope="row" style="width:380px;"><label>
			<?php _e('Collect statistics for Linkedin referer', 'gcs'); 
			$temp = get_option('linkedin_referer');
			?>
		  </label></td>
		<td scope="row">
        <div id="linkedin"><input type="radio" id="linkedin1" name="linkedin_referer" value="linkedin.com" <?php if($temp == 'linkedin.com'){ echo 'checked="checked"';}?> /><label for="linkedin1">On</label>
		<input type="radio" id="linkedin2" name="linkedin_referer" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="linkedin2">Off</label></div>
        </td>
         <td scope="row">&nbsp;</td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Turn Off or On for statistics survey of linkedin.com', 'gcs'); ?>"></span>
         </td>
	  </tr>
	<thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Pinboard', 'gcs'); ?></th></tr></thead>
      <tr valign="top">
        <td scope="row" style="width:380px;"><label>
			<?php _e('Collect statistics for Pinterest referer', 'gcs'); 
			$temp = get_option('pinterest_referer');
			?>
		  </label></td>
		<td scope="row">
        <div id="pinterest"><input type="radio" id="pinterest1" name="pinterest_referer" value="pinterest.com" <?php if($temp == 'pinterest.com'){ echo 'checked="checked"';}?> /><label for="pinterest1">On</label>
		<input type="radio" id="pinterest2" name="pinterest_referer" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="pinterest2">Off</label></div>
        </td>
         <td scope="row">&nbsp;</td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Turn Off or On for statistics survey of pinterest.com', 'gcs'); ?>"></span>
        </td>
	  </tr>
      <tr valign="top">
        <td scope="row" style="width:380px;"><label>
			<?php _e('Collect statistics for Scoop.it referer', 'gcs'); 
			$temp = get_option('scoop_referer');
			?>
		  </label></td>
		<td scope="row">
        <div id="scoop"><input type="radio" id="scoop1" name="scoop_referer" value="scoop.it" <?php if($temp == 'scoop.it'){ echo 'checked="checked"';}?> /><label for="scoop1">On</label>
		<input type="radio" id="scoop2" name="scoop_referer" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="scoop2">Off</label></div>
        </td>
         <td scope="row">&nbsp;</td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Turn Off or On for statistics survey of scoop.it', 'gcs'); ?>"></span>
        </td>
	  </tr>
		<thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Main China', 'gcs'); ?></th></tr></thead>
      <tr valign="top">
        <td scope="row" style="width:380px;"><label>
			<?php _e('Collect statistics for Qzone referer', 'gcs'); 
			$temp = get_option('qzone_referer');
			?>
		  </label></td>
		<td scope="row">
        <div id="qzone"><input type="radio" id="qzone1" name="qzone_referer" value="qzone.qq.com" <?php if($temp == 'qzone.qq.com'){ echo 'checked="checked"';}?> /><label for="qzone1">On</label>
		<input type="radio" id="qzone2" name="qzone_referer" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="qzone2">Off</label></div>
        </td>
         <td scope="row">&nbsp;</td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Turn Off or On for statistics survey of qzone.qq.com', 'gcs'); ?>"></span>
        </td>
	  </tr>
      <tr valign="top">
        <td scope="row" style="width:380px;"><label>
			<?php _e('Collect statistics for Weibo referer', 'gcs'); 
			$temp = get_option('weibo_referer');
			?>
		  </label></td>
		<td scope="row">
        <div id="weibo"><input type="radio" id="weibo1" name="weibo_referer" value="weibo.com" <?php if($temp == 'weibo.com'){ echo 'checked="checked"';}?> /><label for="weibo1">On</label>
		<input type="radio" id="weibo2" name="weibo_referer" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="weibo2">Off</label></div>
        </td>
         <td scope="row">&nbsp;</td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Turn Off or On for statistics survey of weibo.com', 'gcs'); ?>"></span>
        </td>
	  </tr>
		<thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Main Russia', 'gcs'); ?></th></tr></thead>
      <tr valign="top">
        <td scope="row" style="width:380px"><label>
			<?php _e('Collect statistics for vk referer', 'gcs'); 
			$temp = get_option('vk_referer');
			?>
		  </label></td>
		<td scope="row">
        <div id="vk"><input type="radio" id="vk1" name="vk_referer" value="vk.com" <?php if($temp == 'vk.com'){ echo 'checked="checked"';}?> /><label for="vk1">On</label>
		<input type="radio" id="vk2" name="vk_referer" value="" <?php if($temp == ''){ echo 'checked="checked"';}?> /><label for="vk2">Off</label></div>
        </td>
         <td scope="row">&nbsp;</td>
        <td scope="row">
		  <span class="livetv_help" title="<?php _e('Turn Off or On for statistics survey of vk.com', 'gcs'); ?>"></span>
        </td>
	  </tr>
      </table>
      <p class="submit">
		  <input name="submitted" type="hidden" value="yes" />
		  <input type="submit" name="options_submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
		</p>
	  </form>
<?php 
/*var_dump($gcs_categories_prefered);*/
}

add_action('add_meta_boxes', 'gcsplugin_add_custom_box');
add_action('save_post', 'gcsplugin_save_postdata');

// Adds a box to the main column on the Post and Page edit screens
function gcsplugin_add_custom_box()
{
	add_meta_box( 
        'gcsplugin_sectionid1',
        __( 'Statistics Performances', 'gcs' ),
        'gcsplugin_inner_custom_box1',
        'post' 
    );
    add_meta_box(
        'gcsplugin_sectionid1',
        __( 'Statistics Performances', 'gcs' ), 
        'gcsplugin_inner_custom_box1',
        'page'
    );
	add_meta_box( 
        'gcsplugin_sectionid2',
        __( 'Statistics Organics', 'gcs' ),
        'gcsplugin_inner_custom_box2',
        'post' 
    );
    add_meta_box(
        'gcsplugin_sectionid2',
        __( 'Statistics Organics', 'gcs' ), 
        'gcsplugin_inner_custom_box2',
        'page'
    );
	add_meta_box( 
        'gcsplugin_sectionid3',
        __( 'Statistics Networks', 'gcs' ),
        'gcsplugin_inner_custom_box3',
        'post' 
    );
    add_meta_box(
        'gcsplugin_sectionid3',
        __( 'Statistics Networks', 'gcs' ), 
        'gcsplugin_inner_custom_box3',
        'page'
    );
	add_meta_box( 
        'gcsplugin_sectionid4',
        __( 'Statistics Users', 'gcs' ),
        'gcsplugin_inner_custom_box4',
        'post' 
    );
    add_meta_box(
        'gcsplugin_sectionid4',
        __( 'Statistics Users', 'gcs' ), 
        'gcsplugin_inner_custom_box4',
        'page'
    );
}

//PERFORMANCES
function gcsplugin_inner_custom_box1($post)
{
  	// Use nonce for verification
  	wp_nonce_field( plugin_basename( __FILE__ ), 'gcsplugin_nonce' );

  	global $wpdb;
	$id = $post->ID;
	?>
	<script type="text/javascript">
	google.load('visualization', '1', {packages:['gauge']});
	</script>
	<?php //START GAUGES PERFORMANCE QUERIES AND MEMORY 
	$meta_key = 'gcs_total_queries';
	$total_queries = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key=%s", $id, $meta_key));
	$meta_key = 'gcs_total_memory';
	$total_memory = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key=%s", $id, $meta_key));
	if($total_queries || $total_memory)
	{
	?>
	<script type="text/javascript">
          google.setOnLoadCallback(drawChart_<?php echo $id; ?>_gauges);
          function drawChart_<?php echo $id; ?>_gauges() {
            var data_<?php echo $id; ?>_gauges = google.visualization.arrayToDataTable([
              ['Label', 'Value'],
              <?php echo '[\'Request\', '.(int)$total_queries[0]->meta_value.'],'; ?>
               <?php echo '[\'Memory\', '.(int)$total_memory[0]->meta_value.']'; ?>
            ]);
    
            var options = {
              width: 270, height: 244,
              redFrom: 75, redTo: 100,
              yellowFrom:55, yellowTo: 75,
			  greenFrom:0, greenTo: 40,
			  max: 100,
              minorTicks: 5
            };
    
            var chart_<?php echo $id; ?>_gauges = new google.visualization.Gauge(document.getElementById('chart_div_<?php echo $id; ?>_gauges'));
            chart_<?php echo $id; ?>_gauges.draw(data_<?php echo $id; ?>_gauges, options);
          }
        </script>
        <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_gauges"></div>
        <?php } ?>
    

  
		<?php //START GAUGES BY NAVIGATORS 
		$meta_key = 'gcs_total_loadtime_when_is_%';
		$total_queries_navs = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key LIKE %s ORDER BY meta_value ASC", $id, $meta_key));
		$temp = count($total_queries_navs);
		if($total_queries_navs)
		{
		?>
    	<script type="text/javascript">
          google.setOnLoadCallback(drawChart_<?php echo $id; ?>_navigators);
          function drawChart_<?php echo $id; ?>_navigators() {
            var data_<?php echo $id; ?>_navigators = google.visualization.arrayToDataTable([
            ['Label', 'Value'],
            <?php
			foreach ($total_queries_navs as $n => $nav)
			{
				$stat_value = $nav->meta_value;
				$stat_name = explode('_', $nav->meta_key);
				$stat_name = strtoupper($stat_name[5]);
				
				echo '[\''.$stat_name.'\', '.preg_replace('#,#', '.', $stat_value).'],';
			} 
			?>
            ]);
    
            var options = {
			<?php 
			if($temp == '1')
			{
			?>
              width: 131, height: 131,
			<?php 
			}
			else
			{ 
			?>
			 width: 270, height: 244,
			<?php 
			}
			?>
			  min: 0, max: 15,
              redFrom: 4, redTo: 15,
              yellowFrom:2.3, yellowTo: 4,
			  greenFrom:0, greenTo: 1.8,
              minorTicks: 1
            };
    
            var chart_<?php echo $id; ?>_navigators = new google.visualization.Gauge(document.getElementById('chart_div_<?php echo $id; ?>_navigators'));
            chart_<?php echo $id; ?>_navigators.draw(data_<?php echo $id; ?>_navigators, options);
          }
        </script>
        <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_navigators"></div>
        <?php } ?>
<?php 
}


//ORGANICS
function gcsplugin_inner_custom_box2($post)
{
	// Use nonce for verification
  	wp_nonce_field( plugin_basename( __FILE__ ), 'gcsplugin_nonce' );

  	global $wpdb;
	$id = $post->ID;
	?>
	<script type="text/javascript">
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1.0', {'packages':['corechart']});
    </script>
    
	
	<?php //VISITORS FROM ORGANICS
	$meta_key = 'gcs_organics-%';
	$gcs_organics = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key LIKE %s ORDER BY CAST(meta_value AS SIGNED) DESC", $id, $meta_key));
	if($gcs_organics)
	{
	?>
	<script type="text/javascript">
  	google.setOnLoadCallback(drawVisualization_<?php echo $id; ?>_organics);
  	function drawVisualization_<?php echo $id; ?>_organics() {
		// Some raw data (not necessarily accurate)
		var data_<?php echo $id; ?>_organics = google.visualization.arrayToDataTable([
			['Organics',
			<?php
			$mynames = "";
			foreach($gcs_organics as $or => $val)
			{
				$organics_name = explode('-', preg_replace('#www\_#', '', $val->meta_key));
				$organic_name = preg_replace('#_#', '.', $organics_name[1]);
				$mynames .= '\''.ucfirst($organic_name).'\',';
			}
			$names = preg_replace('#,$#', '', $mynames);
			echo $names;
			?>
			],
			['Organics',<?php 
			$myvalues = "";
			foreach($gcs_organics as $or => $val)
			{
				$myvalues .= $val->meta_value . ',';
			}
			$values = preg_replace('#,$#', '', $myvalues);
			echo $values;
			?>
			]
		]);
	
		var options = {
		  title : 'Visitors - From organics',
		  vAxis: {title: "Visitors"},
		  seriesType: "bars",
		  width: 647,
		  height: 310
		};

		var chart_<?php echo $id; ?>_organics = new google.visualization.ComboChart(document.getElementById('chart_div_<?php echo $id; ?>_organics'));
		chart_<?php echo $id; ?>_organics.draw(data_<?php echo $id; ?>_organics, options);
  	}
	</script>
	<?php /*?>
	<script type="text/javascript">
	  // Set a callback to run when the Google Visualization API is loaded.
	  google.setOnLoadCallback(drawChart_<?php echo $id; ?>_organics);

	  // Callback that creates and populates a data table,
	  // instantiates the pie chart, passes in the data and
	  // draws it.
	  function drawChart_<?php echo $id; ?>_organics()
	  {
		// Create the data table.
		var data_<?php echo $id; ?>_organics = new google.visualization.DataTable();
		data_<?php echo $id; ?>_organics.addColumn('string', 'Topping');
		data_<?php echo $id; ?>_organics.addColumn('number', 'Slices');
		data_<?php echo $id; ?>_organics.addRows([
		<?php
		
		$meta_key = 'gcs_organics-%';
		$gcs_organics = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key LIKE %s ORDER BY CAST(meta_value AS SIGNED) DESC", $id, $meta_key));
		
		foreach($gcs_organics as $or => $val)
		{
			$organics_name = explode('-', preg_replace('#www\_#', '', $val->meta_key));
			$organic_name = preg_replace('#_#', '.', $organics_name[1]);
			echo '[\''.ucfirst($organic_name).'\', '.$val->meta_value.'],';
		}
		?>
		]);

		// Set chart options
		var options = {'title':'Visitors - From Organics',
					   'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
					   'height':244};

		// Instantiate and draw our chart, passing in some options.
		var chart_<?php echo $id; ?>_organics = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_organics'));
		chart_<?php echo $id; ?>_organics.draw(data_<?php echo $id; ?>_organics, options);
	  }
	</script>
	<?php */?>
	<div class="gcs_chart" id="chart_div_<?php echo $id; ?>_organics"></div>
	<?php 
	}
	else
	{
		_e('Currently no datas', 'gcs');
	}
}

//NETWORKS
function gcsplugin_inner_custom_box3($post)
{
	// Use nonce for verification
  	wp_nonce_field( plugin_basename( __FILE__ ), 'gcsplugin_nonce' );

  	global $wpdb;
	$id = $post->ID;
	?>
	
	<?php //ALL SOCIALS REFERERS
	$meta_key = 'gcs_social-%';
	$gcs_social = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key LIKE %s ORDER BY CAST(meta_value AS SIGNED) DESC", $id, $meta_key));
	if($gcs_social)
	{
	?>
	<script type="text/javascript">
	google.setOnLoadCallback(drawVisualization_<?php echo $id; ?>_social_referers);
	function drawVisualization_<?php echo $id; ?>_social_referers() {
		// Some raw data (not necessarily accurate)
		var data_<?php echo $id; ?>_social_referers = google.visualization.arrayToDataTable([
			['Networks',
			<?php
			$mynames = "";
			foreach($gcs_social as $s => $val)
			{
				$social_name = explode('-', $val->meta_key);
				$social_n = preg_replace('#_#', '.', $social_name[1]);
				$mynames .= '\''.ucfirst($social_n).'\',';
			}
			$names = preg_replace('#,$#', '', $mynames);
			echo $names;
			?>
			],
          	['Networks',<?php 
			$myvalues = "";
			foreach($gcs_social as $s => $val)
			{
				$myvalues .= $val->meta_value . ',';
			}
			$values = preg_replace('#,$#', '', $myvalues);
			echo $values;
			?>
			]
        ]);

        var options = {
          title : 'Visitors - From networks',
          vAxis: {title: "Visitors"},
          seriesType: "bars",
		  width: 647,
		  height: 310
        };

        var chart_<?php echo $id; ?>_social_referers = new google.visualization.ComboChart(document.getElementById('chart_div_<?php echo $id; ?>_social_referers'));
        chart_<?php echo $id; ?>_social_referers.draw(data_<?php echo $id; ?>_social_referers, options);
      }
    </script>
	<?php /*?>
	<script type="text/javascript">
	  // Set a callback to run when the Google Visualization API is loaded.
	  google.setOnLoadCallback(drawChart_<?php echo $id; ?>_social_referers);

	  // Callback that creates and populates a data table,
	  // instantiates the pie chart, passes in the data and
	  // draws it.
	  function drawChart_<?php echo $id; ?>_social_referers()
	  {
		// Create the data table.
		var data_<?php echo $id; ?>_social_referers = new google.visualization.DataTable();
		data_<?php echo $id; ?>_social_referers.addColumn('string', 'Topping');
		data_<?php echo $id; ?>_social_referers.addColumn('number', 'Slices');
		data_<?php echo $id; ?>_social_referers.addRows([
		<?php
		
		$meta_key = 'gcs_social_%';
		$gcs_social = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key LIKE %s ORDER BY meta_value ASC", $id, $meta_key));
		
		foreach($gcs_social as $s => $value)
		{
			$social_name = preg_replace('#gcs\_social\_#', '', $value->meta_key);
			echo '[\''.ucfirst($social_name).'\', '.$value->meta_value.'],';
		}
		?>
		]);

		// Set chart options
		var options = {'title':'Visitors - From Networks',
					   'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
					   'height':244};

		// Instantiate and draw our chart, passing in some options.
		var chart_<?php echo $id; ?>_social_referers = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_social_referers'));
		chart_<?php echo $id; ?>_social_referers.draw(data_<?php echo $id; ?>_social_referers, options);
	  }
	</script>
	<?php */?>
    <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_social_referers"></div>
	<?php 
	}
	else
	{
		_e('Currently no datas', 'gcs');
	}
}

//USERS
function gcsplugin_inner_custom_box4($post)
{
	// Use nonce for verification
  	wp_nonce_field( plugin_basename( __FILE__ ), 'gcsplugin_nonce' );

  	global $wpdb;
	$id = $post->ID;
	$gcs_3d_chart = get_option('gcs_3d_chart');
?>


	<?php //TOTAL BY BROWSERS 
	$meta_key = 'gcs_browser_is_%';
	$stats_by_browser = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key LIKE %s ORDER BY CAST(meta_value AS SIGNED) DESC", $id, $meta_key));
	if($stats_by_browser)
	{
	?>
	<script type="text/javascript">
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart_<?php echo $id; ?>_browsers);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart_<?php echo $id; ?>_browsers()
	  {
        // Create the data table.
        var data_<?php echo $id; ?>_browsers = new google.visualization.DataTable();
        data_<?php echo $id; ?>_browsers.addColumn('string', 'Topping');
        data_<?php echo $id; ?>_browsers.addColumn('number', 'Slices');
        data_<?php echo $id; ?>_browsers.addRows([
		<?php
		foreach ($stats_by_browser as $b => $stat)
		{
			$stat_value = $stat->meta_value;
			$stat_name = explode('_', $stat->meta_key);
			$stat_name = strtoupper($stat_name[3]);
			
			$list .= '[\''.esc_html($stat_name).'\', '.esc_html($stat_value).'],';
		}
		 $list = preg_replace('#,$#', '', $list);
		 echo trim($list);
		?>
        ]);

        // Set chart options
        var options = {'title':'All Visitors - By Browsers',
                       'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
                       'height':244};

        // Instantiate and draw our chart, passing in some options.
        var chart_<?php echo $id; ?>_browsers = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_browsers'));
        chart_<?php echo $id; ?>_browsers.draw(data_<?php echo $id; ?>_browsers, options);
      }
    </script>
    <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_browsers"></div>
    <?php } ?>


	<?php //START IS HOME VS IS STICKY 
	$meta_key = 'gcs_sticky_home';
	$total_sticky = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key=%s", $id, $meta_key));
	$meta_key = 'gcs_referer_home';
	$total_home = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key=%s", $id, $meta_key));
	if($total_home || $total_sticky)
	{
	?>
	<script type="text/javascript">
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart_<?php echo $id; ?>_is_sticky_vs_is_home);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart_<?php echo $id; ?>_is_sticky_vs_is_home()
	  {
        // Create the data table.
        var data_<?php echo $id; ?>_is_sticky_vs_is_home = new google.visualization.DataTable();
        data_<?php echo $id; ?>_is_sticky_vs_is_home.addColumn('string', 'Topping');
        data_<?php echo $id; ?>_is_sticky_vs_is_home.addColumn('number', 'Slices');
        data_<?php echo $id; ?>_is_sticky_vs_is_home.addRows([
		<?php 
		$home_value = $total_home[0]->meta_value;
		$home_name = __('Normal', 'gcs');
		$sticky_value = $total_sticky[0]->meta_value;
		$sticky_name = __('Sticky', 'gcs');
		
		if(!$home_value)
		{
			$home_value = 0;
		}
		if(!$sticky_value)
		{
			$sticky_value = 0;
		}
		
		echo '[\''.$home_name.'\', '.$home_value.'],';
		echo '[\''.$sticky_name.'\', '.$sticky_value.']';
		?>
        ]);

        // Set chart options
        var options = {'title':'Home referer - Normal VS Sticky',
                       'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
                       'height':244};

        // Instantiate and draw our chart, passing in some options.
        var chart_<?php echo $id; ?>_is_sticky_vs_is_home = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_is_sticky_vs_is_home'));
        chart_<?php echo $id; ?>_is_sticky_vs_is_home.draw(data_<?php echo $id; ?>_is_sticky_vs_is_home, options);
      }
    </script>
    <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_is_sticky_vs_is_home"></div>
    <?php } ?>
    


	<?php //START KNOWN CONNECTED VS KNOWN DISCONNECTED 
	$meta_key = 'gcs_total_known';
	$total_known = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key=%s", $id, $meta_key));
	$meta_key = 'gcs_total_known_disconnected';
	$total_known_disconnected = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key=%s", $id, $meta_key));
	if($total_known_disconnected || $total_known)
	{ 
	?>
	<script type="text/javascript">
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart_<?php echo $id; ?>_knowncon_vs_knowndisc);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart_<?php echo $id; ?>_knowncon_vs_knowndisc()
	  {
        // Create the data table.
        var data_<?php echo $id; ?>_knowncon_vs_knowndisc = new google.visualization.DataTable();
        data_<?php echo $id; ?>_knowncon_vs_knowndisc.addColumn('string', 'Topping');
        data_<?php echo $id; ?>_knowncon_vs_knowndisc.addColumn('number', 'Slices');
        data_<?php echo $id; ?>_knowncon_vs_knowndisc.addRows([
		<?php 
		$stat_con = $total_known[0]->meta_value;
		$stat_decon = $total_known_disconnected[0]->meta_value;
		
		if(!$stat_decon)
		{
			$stat_decon = 0;
		}
		if(!$stat_con)
		{
			$stat_con = 0;
		}
		
		echo '[\''.__('Connected', 'gcs').'\', '.esc_html($stat_con).'],';
		echo '[\''.__('Disconnected', 'gcs').'\', '.esc_html($stat_decon).']';
		?>
        ]);

        // Set chart options
        var options = {'title':'Known visitors - Logged VS Offline',
                       'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
                       'height':244};

        // Instantiate and draw our chart, passing in some options.
        var chart_<?php echo $id; ?>_knowncon_vs_knowndisc = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_knowncon_vs_knowndisc'));
        chart_<?php echo $id; ?>_knowncon_vs_knowndisc.draw(data_<?php echo $id; ?>_knowncon_vs_knowndisc, options);
      }
    </script>
    <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_knowncon_vs_knowndisc"></div>
    <?php } ?>



	<?php //START KNOWN BY LANGUAGES 
	$meta_key = 'gcs_total_known_%';
	$total_known_by_languages = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key LIKE %s ORDER BY CAST(meta_value AS SIGNED) DESC", $id, $meta_key));
	if($total_known_by_languages)
	{
	?>
	<script type="text/javascript">
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart_<?php echo $id; ?>_known_by_languages);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart_<?php echo $id; ?>_known_by_languages()
	  {
        // Create the data table.
        var data_<?php echo $id; ?>_known_by_languages = new google.visualization.DataTable();
        data_<?php echo $id; ?>_known_by_languages.addColumn('string', 'Topping');
        data_<?php echo $id; ?>_known_by_languages.addColumn('number', 'Slices');
        data_<?php echo $id; ?>_known_by_languages.addRows([
		<?php
		
		$merge_known = array();
		foreach($total_known_by_languages as $lk => $lang)
		{
			$temp = $lang->meta_key;
			if($temp != 'gcs_total_known_disconnected')
			{
				$stat_value = $lang->meta_value;
				if(!$stat_value)
				{
					$stat_value = 0;
				}
				$stat_name = explode('_', $lang->meta_key);
				$stat_name = strtoupper($stat_name[3]);
				$merge_known[$stat_name] = $stat_value;
				
				echo '[\''.$stat_name.'\', '.$stat_value.'],';
			}
		}
		?>
        ]);

        // Set chart options
        var options = {'title':'Known visitors - By Languages',
                       'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
                       'height':244};

        // Instantiate and draw our chart, passing in some options.
        var chart_<?php echo $id; ?>_known_by_languages = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_known_by_languages'));
        chart_<?php echo $id; ?>_known_by_languages.draw(data_<?php echo $id; ?>_known_by_languages, options);
      }
    </script>
    <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_known_by_languages"></div>
    <?php } ?>



	<?php //START UNKNOWN BY LANGUAGES 
	$meta_key = 'gcs_total_unknown_%';
	$total_unknown_by_languages = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key LIKE %s ORDER BY CAST(meta_value AS SIGNED) DESC", $id, $meta_key));
	if($total_unknown_by_languages)
	{
	?>
	<script type="text/javascript">
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart_<?php echo $id; ?>_unknown_by_languages);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart_<?php echo $id; ?>_unknown_by_languages()
	  {
        // Create the data table.
        var data_<?php echo $id; ?>_unknown_by_languages = new google.visualization.DataTable();
        data_<?php echo $id; ?>_unknown_by_languages.addColumn('string', 'Topping');
        data_<?php echo $id; ?>_unknown_by_languages.addColumn('number', 'Slices');
        data_<?php echo $id; ?>_unknown_by_languages.addRows([
		<?php 
		$merge_unknown = array();
		foreach($total_unknown_by_languages as $lu => $lang)
		{
			$stat_value = $lang->meta_value;
			if(!$stat_value)
			{
				$stat_value = 0;
			}
			$stat_name = explode('_', $lang->meta_key);
			$stat_name = strtoupper($stat_name[3]);
			$merge_unknown[$stat_name] = $stat_value;
			
			echo '[\''.$stat_name.'\', '.$stat_value.'],';
		}
		?>
        ]);

        // Set chart options
        var options = {'title':'Unknown visitors - By Languages',
                       'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
                       'height':244};

        // Instantiate and draw our chart, passing in some options.
        var chart_<?php echo $id; ?>_unknown_by_languages = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_unknown_by_languages'));
        chart_<?php echo $id; ?>_unknown_by_languages.draw(data_<?php echo $id; ?>_unknown_by_languages, options);
      }
    </script>
    <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_unknown_by_languages"></div>
    <?php } ?>



	<?php //START TOTAL BY LANGUAGES 
	$result = array();
	if($merge_known)
	{	
		foreach($merge_known as $key => $value)
		{
			$result[$key] = $value + $merge_unknown[$key];
		}
	}
	if($merge_unknown)
	{
		foreach($merge_unknown as $key => $value)
		{
			$result[$key] = $value + $merge_known[$key];
		}
	}
	if($result)
	{ 
	?>
	<script type="text/javascript">
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart_<?php echo $id; ?>_total_by_languages);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart_<?php echo $id; ?>_total_by_languages()
	  {
        // Create the data table.
        var data_<?php echo $id; ?>_total_by_languages = new google.visualization.DataTable();
        data_<?php echo $id; ?>_total_by_languages.addColumn('string', 'Topping');
        data_<?php echo $id; ?>_total_by_languages.addColumn('number', 'Slices');
        data_<?php echo $id; ?>_total_by_languages.addRows([
		<?php
		foreach($result as $key => $value)
		{
			echo '[\''.$key.'\', '.$value.'],';
		}
		?>
        ]);

        // Set chart options
        var options = {'title':'All visitors - By Languages',
                       'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
                       'height':244};

        // Instantiate and draw our chart, passing in some options.
        var chart_<?php echo $id; ?>_total_by_languages = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_total_by_languages'));
        chart_<?php echo $id; ?>_total_by_languages.draw(data_<?php echo $id; ?>_total_by_languages, options);
      }
    </script>
    <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_total_by_languages"></div>
    <?php } ?>



	<?php //START KNOWN VS UNKNOWN 
	$meta_key = 'gcs_total_unknown';
	$total_unknown = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key=%s", $id, $meta_key));
	$meta_key = 'gcs_total_known';
	$total_known = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key=%s", $id, $meta_key));
	if($total_known || $total_unknown)
	{
	?>
	<script type="text/javascript">
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart_<?php echo $id; ?>_known_vs_unknown);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart_<?php echo $id; ?>_known_vs_unknown()
	  {
        // Create the data table.
        var data_<?php echo $id; ?>_known_vs_unknown = new google.visualization.DataTable();
        data_<?php echo $id; ?>_known_vs_unknown.addColumn('string', 'Topping');
        data_<?php echo $id; ?>_known_vs_unknown.addColumn('number', 'Slices');
        data_<?php echo $id; ?>_known_vs_unknown.addRows([
		<?php 
		$known_value = $total_known[0]->meta_value;
		$known_name = __('Known', 'gcs');
		$unknown_value = $total_unknown[0]->meta_value;
		$unknown_name = __('Unknown', 'gcs');
		
		if(!$known_value)
		{
			$known_value = 0;
		}
		if(!$unknown_value)
		{
			$unknown_value = 0;
		}
		
		echo '[\''.$known_name.'\', '.$known_value.'],';
		echo '[\''.$unknown_name.'\', '.$unknown_value.']';
		
		$merge_total = $known_value + $unknown_value;
		?>
        ]);

        // Set chart options
        var options = {'title':'All visitors - Known VS Unknown',
                       'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
                       'height':244};

        // Instantiate and draw our chart, passing in some options.
        var chart_<?php echo $id; ?>_known_vs_unknown = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_known_vs_unknown'));
        chart_<?php echo $id; ?>_known_vs_unknown.draw(data_<?php echo $id; ?>_known_vs_unknown, options);
      }
    </script>
    <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_known_vs_unknown"></div>
    <?php } ?>



	<?php //INTERNAL VS EXTERNAL
	$meta_key = 'gcs_internal';
	$total_internal = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s AND meta_key=%s", $id, $meta_key));
	$gcs_external = $merge_total - $total_internal[0]->meta_value;
	if($total_internal || $gcs_external)
	{ 
	?>
	<script type="text/javascript">
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart_<?php echo $id; ?>_internal_vs_external);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart_<?php echo $id; ?>_internal_vs_external()
	  {
        // Create the data table.
        var data_<?php echo $id; ?>_internal_vs_external = new google.visualization.DataTable();
        data_<?php echo $id; ?>_internal_vs_external.addColumn('string', 'Topping');
        data_<?php echo $id; ?>_internal_vs_external.addColumn('number', 'Slices');
        data_<?php echo $id; ?>_internal_vs_external.addRows([
		<?php
		echo '[\'Internal\', '.$total_internal[0]->meta_value.'],';
		echo '[\'External\', '. $gcs_external .']';
		?>
        ]);

        // Set chart options
        var options = {'title':'All visitors - Internal VS External',
                       'width':350,
					   <?php if($gcs_3d_chart == 'yes'){echo '\'is3D\':true,';} ?>
                       'height':244};

        // Instantiate and draw our chart, passing in some options.
        var chart_<?php echo $id; ?>_internal_vs_external = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $id; ?>_internal_vs_external'));
        chart_<?php echo $id; ?>_internal_vs_external.draw(data_<?php echo $id; ?>_internal_vs_external, options);
      }
    </script>
    <div class="gcs_chart" id="chart_div_<?php echo $id; ?>_internal_vs_external"></div>
    <?php 
	}
}


/* When the post is saved, saves our custom data */
function gcsplugin_save_postdata($post_id)
{
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['gcsplugin_nonce'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // OK, we're authenticated: we need to find and save the data

  // Do something with $mydata 
  // probably using add_post_meta(), update_post_meta(), or 
  // a custom table (see Further Reading section below)
}
?>