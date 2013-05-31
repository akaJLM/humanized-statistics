<?php
/*  
	Copyright 2012  Laurent B. akaJLM
*/

// disallow direct access to file
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	wp_die(__('Sorry, but you cannot access this page directly.', 'gcs'));
}


//Add admin sub menu "categories" (plugin Api)
function gcs_add_admin_sub_menu_home()
{
	add_submenu_page('google-chart-statistics.php', 'Stats home page', __('Stats home page', 'gcs'), 'manage_options', 'pages-admin/admin-home.php', 'gcs_do_home_stats_page');
}
add_action('gcs_add_admin_sub_menus', 'gcs_add_admin_sub_menu_home');


//Add admin sub page "categories"
function gcs_do_home_stats_page()
{
	global $wpdb;
	?>
	<div class="wrap wb-admin">
	   	<div class="icon32" id="icon-tools"><br />
	  	</div>
	  	<h2>
		<?php _e('Humanized statistics - Home statistics', 'gcs'); ?>
	  	</h2>
        <script type="text/javascript">
		// Load the Visualization API and the piechart package.
		google.load('visualization', '1.0', {'packages':['corechart']});
		</script>
        <br />
        <table class="widefat options" style="width: 650px">
        <thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Statistics home page total sticky VS normal', 'gcs'); ?></th></tr></thead>
	  	<tr valign="top">
         <td scope="row">
         <?php 
		$meta_key = 'gcs_referer_home';
		$gcs_total_home = $wpdb->get_results($wpdb->prepare("SELECT meta_key, sum(meta_value) AS total_home FROM $wpdb->postmeta WHERE meta_key=%s GROUP BY meta_key", $meta_key));
		$meta_key = 'gcs_sticky_home';
		$gcs_total_home_sticky = $wpdb->get_results($wpdb->prepare("SELECT meta_key, sum(meta_value) AS total_sticky FROM $wpdb->postmeta WHERE meta_key=%s GROUP BY meta_key", $meta_key));
		if($gcs_total_home_sticky || $gcs_total_home)
		{
		?>
		<?php //CATEGORIES MOST VISITED ?>
		<script type="text/javascript">
		  google.setOnLoadCallback(drawVisualization_total_sticky_vs_normal);
		  function drawVisualization_total_sticky_vs_normal() {
			// Some raw data (not necessarily accurate)
			var data_total_sticky_vs_normal = google.visualization.arrayToDataTable([
			<?php
				$total_home = $gcs_total_home[0]->total_home;
				$total_sticky = $gcs_total_home_sticky[0]->total_sticky;
			?>
			  ['Home', 
			  <?php
			   if($total_home > $total_sticky)
				{
					echo '\'Normal\', \'Sticky\'';
				}
				else
				{
					echo '\'Sticky\', \'Home\'';
				}
			  ?>
			  ],
				['Hits',
				<?php
				if(!$total_home)
				{
					$total_home = 0;
				}
				if(!$total_sticky)
				{
					$total_sticky = 0;
				}
				if($total_home > $total_sticky)
				{
					echo ''.$total_home.',' .$total_sticky. '';
				}
				else
				{
					echo ''.$total_sticky.',' .$total_home. '';
				}
				?>
				]
			]);
	
			var options = {
			  title : 'Statistics home - most hits on Normal posts VS Sticky posts',
			  vAxis: {title: "Home users"},
			  seriesType: "bars",
			  width: 600,
			  height: 244
			};
	
			var chart_total_sticky_vs_normal = new google.visualization.ComboChart(document.getElementById('chart_div_total_sticky_vs_normal'));
			chart_total_sticky_vs_normal.draw(data_total_sticky_vs_normal, options);
		  }
		</script>
        <div class="gcs_chart" id="chart_div_total_sticky_vs_normal"></div>
        <?php 
		} 
		else
		{
			_e('Currently no datas', 'gcs');
		}
		?>
         </td>
	  </tr>
      </table><br /><table class="widefat options" style="width: 650px">
        <thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Statistics home page from normal posts - user in bounce to', 'gcs'); ?></th></tr></thead>
	  	<tr valign="top">
         <td scope="row">
         <?php
		$meta_key = 'gcs_referer_home';
		$gcs_total_home_by_cats = $wpdb->get_results($wpdb->prepare("
		SELECT sum(meta_value) AS total, name
		
		FROM $wpdb->postmeta AS pm, $wpdb->posts AS p, $wpdb->term_relationships AS tr, $wpdb->term_taxonomy AS tt, $wpdb->terms AS t 

		WHERE pm.post_id=p.ID AND p.post_status='publish'
		AND pm.post_id=tr.object_id
		AND tr.term_taxonomy_id=tt.term_taxonomy_id
		AND tt.taxonomy='category'
		AND tr.term_taxonomy_id=t.term_id
		AND meta_key=%s
		
		GROUP BY t.name
		
		ORDER BY total DESC",
		$meta_key));
		if($gcs_total_home_by_cats)
		{
		?>
		<script type="text/javascript">
		google.setOnLoadCallback(drawVisualization_home_by_cats);
		function drawVisualization_home_by_cats() {
			// Some raw data (not necessarily accurate)
			var data_home_by_cats = google.visualization.arrayToDataTable([
				['Categories',
				<?php
				$mynames = "";
				foreach($gcs_total_home_by_cats as $h => $val)
				{
					$mynames .= '\''.$val->name.'\',';
				}
				$names = preg_replace('#,$#', '', $mynames);
				echo $names;
				?>
				],
				['Category',<?php 
				$myvalues = "";
				foreach($gcs_total_home_by_cats as $h => $val)
				{
					$myvalues .= $val->total . ',';
				}
				$values = preg_replace('#,$#', '', $myvalues);
				echo $values;
				?>
				]
			]);
		
			var options = {
			  title : 'Home normal posts - user in bounce to',
			  vAxis: {title: "Visitors"},
			  seriesType: "bars",
			  width: 600,
			  height: 244
			};
	
			var chart_home_by_cats = new google.visualization.ComboChart(document.getElementById('chart_div_home_by_cats'));
			chart_home_by_cats.draw(data_home_by_cats, options);
		}
		</script>
        <div class="gcs_chart" id="chart_div_home_by_cats"></div>
        <?php 
		} 
		else
		{
			_e('Currently no datas', 'gcs');
		}
		?>
         </td>
	  </tr>
      </table><br /><table class="widefat options" style="width: 650px">
        <thead><tr><th colspan="4" class="dashboard-widget-title"><?php _e('Statistics home page from sticky posts - user in bounce to', 'gcs'); ?></th></tr></thead>
	  	<tr valign="top">
         <td scope="row">
         <?php
		$meta_key = 'gcs_sticky_home';
		$gcs_total_sticky_home_by_cats = $wpdb->get_results($wpdb->prepare("
		SELECT sum(meta_value) AS total, name
		
		FROM $wpdb->postmeta AS pm, $wpdb->posts AS p, $wpdb->term_relationships AS tr, $wpdb->term_taxonomy AS tt, $wpdb->terms AS t 

		WHERE pm.post_id=p.ID AND p.post_status='publish'
		AND pm.post_id=tr.object_id
		AND tr.term_taxonomy_id=tt.term_taxonomy_id
		AND tt.taxonomy='category'
		AND tr.term_taxonomy_id=t.term_id
		AND meta_key=%s
		
		GROUP BY t.name
		
		ORDER BY total DESC",
		$meta_key));
		if($gcs_total_sticky_home_by_cats)
		{
		?>
		<script type="text/javascript">
		google.setOnLoadCallback(drawVisualization_sticky_by_cats);
		function drawVisualization_sticky_by_cats() {
			// Some raw data (not necessarily accurate)
			var data_sticky_by_cats = google.visualization.arrayToDataTable([
				['Categories',
				<?php
				$mynames = "";
				foreach($gcs_total_sticky_home_by_cats as $h => $val)
				{
					$mynames .= '\''.$val->name.'\',';
				}
				$names = preg_replace('#,$#', '', $mynames);
				echo $names;
				?>
				],
				['Category',<?php 
				$myvalues = "";
				foreach($gcs_total_sticky_home_by_cats as $h => $val)
				{
					$myvalues .= $val->total . ',';
				}
				$values = preg_replace('#,$#', '', $myvalues);
				echo $values;
				?>
				]
			]);
		
			var options = {
			  title : 'Home sticky posts - user in bounce to',
			  vAxis: {title: "Visitors"},
			  seriesType: "bars",
			  width: 600,
			  height: 244
			};
	
			var chart_sticky_by_cats = new google.visualization.ComboChart(document.getElementById('chart_div_sticky_by_cats'));
			chart_sticky_by_cats.draw(data_sticky_by_cats, options);
		}
		</script>
        <div class="gcs_chart" id="chart_div_sticky_by_cats"></div>
        <?php 
		} 
		else
		{
			_e('Currently no datas', 'gcs');
		}
		?>
         </td>
	  </tr>
      </table>
<?php
}
?>