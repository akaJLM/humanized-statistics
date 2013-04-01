<?php
/*  
	Copyright 2012  Laurent B. aka Jonathan Maris
*/

// disallow direct access to file
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	wp_die(__('Sorry, but you cannot access this page directly.', 'gcs'));
}


//Add admin sub menu "categories" (plugin Api)
function gcs_add_admin_sub_menu_categories()
{
	add_submenu_page('google-chart-statistics.php', 'Stats by categories', __('Stats by categories', 'gcs'), 'manage_options', 'pages-admin/admin-cats.php', 'gcs_do_categories_stats_page');
}
add_action('gcs_add_admin_sub_menus', 'gcs_add_admin_sub_menu_categories');


//Add admin sub page "categories"
function gcs_do_categories_stats_page()
{
	global $wpdb, $blog_id;
	?>
	<div class="wrap wb-admin">
	   	<div class="icon32" id="icon-tools"><br />
	  	</div>
	  	<h2>
		<?php _e('Humanized statistics - Categories statistics', 'gcs'); ?>
	  	</h2>
        <script type="text/javascript">
		// Load the Visualization API and the piechart package.
		google.load('visualization', '1.0', {'packages':['corechart']});
		</script>
        <br />
        <table class="widefat options" style="width: 650px">
        <thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Statistics categories most visited by all users', 'gcs'); ?></th></tr></thead>
	  	<tr valign="top">
         <td scope="row">
		<?php //CATEGORIES MOST VISITED 
		$meta_key = 'gcs_category_%';
		$gcs_categories = $wpdb->get_results($wpdb->prepare("SELECT meta_key, sum(meta_value) AS total_category FROM $wpdb->postmeta WHERE meta_key LIKE %s GROUP BY meta_key ORDER BY total_category DESC", $meta_key));
		if($gcs_categories)
		{
		?>
		<script type="text/javascript">
		  google.setOnLoadCallback(drawVisualization_categories);
		  function drawVisualization_categories() {
			// Some raw data (not necessarily accurate)
			var data_categories = google.visualization.arrayToDataTable([
			['Categories',
			  <?php
				$mynames = "";
				foreach($gcs_categories as $key => $cat)
				{
					$cat_id = explode('_', $cat->meta_key);
					$cat_id = $cat_id[2];
					$category_name = get_cat_name($cat_id);
					if($category_name)
					{
						$mynames .= '\''.$category_name.'\',';
					}
				}
				$names = preg_replace('#,$#', '', $mynames);
				echo $names;
				?>
				],
				['Categories',<?php 
				$myvalues = "";
				foreach($gcs_categories as $k => $val)
				{
					$test_val = $val->total_category;
					if($test_val)
					{
						$myvalues .= $test_val . ',';
					}
				}
				$values = preg_replace('#,$#', '', $myvalues);
				echo $values;
				?>
				]
			]);
	
			var options = {
			  title : 'Categories most hits by all users',
			  vAxis: {title: "Visitors"},
			  seriesType: "bars",
			  width: 600,
			  height: 310
			};
	
			var chart_categories = new google.visualization.ComboChart(document.getElementById('chart_div_categories'));
			chart_categories.draw(data_categories, options);
		  }
		</script>
        <div class="gcs_chart" id="chart_div_categories"></div>
       	<?php 
		} 
		else
		{
			echo __('Currently no datas for categories most visited', 'gcs');
		}
		?>
        </td>
	  </tr>
      </table><br /><table class="widefat options" style="width: 650px">
        <thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Statistics categories most prefered by known users', 'gcs'); ?></th></tr></thead>
      	  	<tr valign="top">
         <td scope="row">
		<?php //CATEGORIES MOST VISITED 
		$meta_key = 'gcs_cat_'.$blog_id.'_%';
		$gcs_categories_prefered = $wpdb->get_results($wpdb->prepare("SELECT meta_key, sum(meta_value) AS total_category FROM $wpdb->usermeta WHERE meta_key LIKE %s GROUP BY meta_key ORDER BY total_category DESC", $meta_key));
		if($gcs_categories_prefered)
		{
		?>
		<script type="text/javascript">
		  google.setOnLoadCallback(drawVisualization_categories_prefered);
		  function drawVisualization_categories_prefered() {
			// Some raw data (not necessarily accurate)
			var data_categories_prefered = google.visualization.arrayToDataTable([
			  ['Categories',
			  <?php
				$mynames = "";
				foreach($gcs_categories_prefered as $key => $cat)
				{
					$cat_id = explode('_', $cat->meta_key);
					$category_name = get_cat_name(''.$cat_id[3].'');
					if($category_name)
					{
						$mynames .= '\''.$category_name.'\',';
					}
				}
				$names = preg_replace('#,$#', '', $mynames);
				echo $names;
				?>
				],
				['Categories',
				<?php 
				$myvalues = "";
				foreach($gcs_categories_prefered as $k => $val)
				{
					$cat_id = explode('_', $val->meta_key);
					$category_name = get_cat_name(''.$cat_id[3].'');
					if($category_name)
					{
						$myvalues .= $val->total_category . ',';
					}
				}
				$values = preg_replace('#,$#', '', $myvalues);
				echo $values;
				?>
				]
			]);
	
			var options = {
			  title : 'Categories most prefered by known users',
			  vAxis: {title: "Visitors"},
			  seriesType: "bars",
			  width: 600,
			  height: 310
			};
	
			var chart_categories_prefered = new google.visualization.ComboChart(document.getElementById('chart_div_categories_prefered'));
			chart_categories_prefered.draw(data_categories_prefered, options);
		  }
		</script>
        <div class="gcs_chart" id="chart_div_categories_prefered"></div>
        <?php 
		} 
		else
		{
			echo __('Currently no datas for categories prefered by known users', 'gcs');
		}
		/*var_dump($gcs_categories);
		echo '<br />&nbsp;<br />';
		var_dump($gcs_categories_prefered);*/
		//$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE 'gcs\_cat\_%'");//DEBUG CATEGORIES
		//$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE 'gcs\_hit'");//DEBUG HITS
		?>
         </td>
	  </tr>
      </table>
<?php 
}
?>