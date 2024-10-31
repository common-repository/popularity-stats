<?php
/*
Plugin Name: Popularity Stats
Plugin URI:
Description: The Popularity Stats plugin is designed to allow you to quickly view how popular your website is with major search engines and reporting services.
Author: Hors Hipsrectors
Author URI:
Version: 2017.08.13

/**
 * Popularity Stats core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/popularity-stats-for-wordpress/
 *
 * @package		Popularity Stats
 * @copyright		Copyright ( c ) 2017, Hors Hipsrectors
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 ( or newer )
 *
 * @since		Popularity Stats 1.0
 */

register_activation_hook( __FILE__, 'horshipsrectors_popularity_activate' );

/**
 * Adds the options page for this plugin
 */
function horshipsrectors_popularity_stats_menu( ) {
  add_options_page( 'Popularity Stats', 'Popularity Stats', 10,'popularity-stats.php', 'horshipsrectors_popularity_stats_options' );
}
add_action( 'admin_menu', 'horshipsrectors_popularity_stats_menu' );


/**
 * Adds the display page for this plugin
 */
function horshipsrectors_popularity_stats_results( ) {
  add_dashboard_page( 'Popularity Stats', 'Popularity Stats', 10,'popularity-stats.php', 'horshipsrectors_popularity_stats_results_page' );
}
add_action( 'admin_menu', 'horshipsrectors_popularity_stats_results' );



function horshipsrectors_popularity_admin_header( ) {

	if ( $_GET['page'] == 'popularity-stats.php' ) {

		wp_enqueue_script( 'jquerylib', 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js' );
		wp_enqueue_script( 'jqplot', plugins_url( '/jqplot/jquery.jqplot.js', __FILE__ ) );
		wp_enqueue_script( 'excanvas', plugins_url( '/jqplot/excanvas.js', __FILE__ ) );
		wp_enqueue_script( 'dateAxisRenderer', plugins_url( '/jqplot/plugins/ jqplot.dateAxisRenderer.js', __FILE__ ) );
		wp_enqueue_script( 'cursor', plugins_url(  '/jqplot/plugins/jqplot.cursor.js', __FILE__ ) );
		wp_enqueue_script( 'highlighter', plugins_url(  '/jqplot/plugins/jqplot.highlighter.js', __FILE__ ) );

		wp_enqueue_style( 'jqplot', plugins_url( '/jqplot/jquery.jqplot.css', __FILE__ ) );

	}
}
add_action( 'admin_head', 'horshipsrectors_popularity_admin_header' );


function horshipsrectors_popularity_stats_options( $options='' ) {

	?>

	<div class="wrap">
	<h2>Popularity Stats for WordPress Options</h2>
	<form method="post" action="options.php">
	<?php wp_nonce_field( 'update-options' ); ?>

	<h3>Services to Track</h3>
	<table class="form-table">
		<tr valign="top">
		<th scope="row">Google PageRank</th>
		<td>
		<input name="horshipsrectors_popularity_track_pagerank" type="checkbox" id="horshipsrectors_popularity_track_pagerank" value="1" <?php if( get_option( 'horshipsrectors_popularity_track_pagerank' ) == "1" ) {echo  "checked='checked'";} ?> />
		</td>
		</tr>

		<tr valign="top">
		<th scope="row">Alexa Rank</th>
		<td>
		<input name="horshipsrectors_popularity_track_alexa" type="checkbox" id="horshipsrectors_popularity_track_alexa" value="1" <?php if( get_option( 'horshipsrectors_popularity_track_alexa' ) == "1" ) {echo  "checked='checked'";} ?> />
		</td>
		</tr>

		<tr valign="top">
		<th scope="row">Inbound Links</th>
		<td>
		<input name="horshipsrectors_popularity_track_links" type="checkbox" id="horshipsrectors_popularity_track_links" value="1" <?php if( get_option( 'horshipsrectors_popularity_track_links' ) == "1" ) {echo  "checked='checked'";} ?> />
		</td>
		</tr>
	</table>

	<h3>Plugin Functions</h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Days Between Checks</th>
			<td>
			<select name="horshipsrectors_popularity_daystocheck" id="horshipsrectors_popularity_daystocheck">
			<option value="1" <?php if ( get_option( 'horshipsrectors_popularity_daystocheck' ) == "1" ) {echo 'selected="selected"';}?>>1</option>
			<option value="2" <?php if ( get_option( 'horshipsrectors_popularity_daystocheck' ) == "2" ) {echo 'selected="selected"';}?>>2</option>
			<option value="5" <?php if ( get_option( 'horshipsrectors_popularity_daystocheck' ) == "5" ) {echo 'selected="selected"';}?>>5 ( recommended )</option>
			<option value="10" <?php if ( get_option( 'horshipsrectors_popularity_daystocheck' ) == "10" ) {echo 'selected="selected"';}?>>10</option>
			<option value="15" <?php if ( get_option( 'horshipsrectors_popularity_daystocheck' ) == "15" ) {echo 'selected="selected"';}?>>15</option>
			<option value="30" <?php if ( get_option( 'horshipsrectors_popularity_daystocheck' ) == "30" ) {echo 'selected="selected"';}?>>30</option>
			</select>
			<p>You can set how many days to set between checks, checking too often may result in some systems banning you from receiving updates.</p></td>
		</tr>

		<tr valign="top">
			<th scope="row">Reports to Display</th>
			<td>
			<select name="horshipsrectors_popularity_daystodisplay" id="horshipsrectors_popularity_daystodisplay">
			<option value="1" <?php if ( get_option( 'horshipsrectors_popularity_daystodisplay' ) == "1" ) {echo 'selected="selected"';}?>>1</option>
			<option value="2" <?php if ( get_option( 'horshipsrectors_popularity_daystodisplay' ) == "2" ) {echo 'selected="selected"';}?>>2</option>
			<option value="5" <?php if ( get_option( 'horshipsrectors_popularity_daystodisplay' ) == "5" ) {echo 'selected="selected"';}?>>5 ( recommended )</option>
			<option value="10" <?php if ( get_option( 'horshipsrectors_popularity_daystodisplay' ) == "10" ) {echo 'selected="selected"';}?>>10</option>
			</select>

			<p>To help ensure your stats are super speedy, you can control how many previous reports to display on the reports screen.</p>
			</td>
		</tr>

	</table>

	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="horshipsrectors_popularity_track_pagerank,horshipsrectors_popularity_track_alexa,horshipsrectors_popularity_track_links,horshipsrectors_popularity_daystocheck,horshipsrectors_popularity_daystodisplay" />


	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
	</p>
	</form>
	</div>

	<?php

}


function horshipsrectors_popularity_stats_results_page( $options='' ) { ?>

	<div class="wrap">
	<h2>Popularity Stats for WordPress</h2>

	<?php if( get_option( 'horshipsrectors_popularity_track_pagerank' ) == "1" ) { ?>
	<h3>Google PageRank</h3>

	<p>The Google PageRank system is a measurement of trust Google places on a website and is calculated out of 10, higher PageRank values are better.</p>

	<?php
	global $wpdb;
			$chartgoogle = $wpdb->get_results( "
					SELECT *
					FROM $wpdb->options
					WHERE `option_name` LIKE '%horshipsrectors_popularity_checker_google_value_%'
					ORDER BY option_name
			" );
	if ( count( $chartgoogle ) ) { ?>

	<div id="googlechart" class="plot" style="width:728px;height:300px;"></div>
	<script language="javascript" type="text/javascript"><!--


	<?php
	$googlemax = 0;
	$googlemin = 0;
	$count = 0;
	foreach ( $chartgoogle as $result ) {
		$count++;
		if ( $result->option_value < $googlemin ) {$googlemin = $result->option_value;}
		if ( $result->option_value > $googlemax ) {$googlemax = $result->option_value;}

		$date = $result->option_name;
		$date = str_replace( "horshipsrectors_popularity_checker_google_value_","",$date );
		$date = date( 'd-M-y', $date );
		$googlelist .="['".$date."',".$result->option_value."],";
	}

	$googlelist =  substr( $googlelist, 0, strlen( $googlelist )-1 );
	?>
	s1 = [<?php echo $googlelist;?>];


	plot1 = $.jqplot( 'googlechart',[s1],{
	title: 'Google PageRank for <?php bloginfo( 'name' );?>',
	axes: {
		xaxis: {
		renderer: $.jqplot.DateAxisRenderer,
		tickOptions: { formatString: '%b %#d, %Y'},
		numberTicks: <?php echo get_option( 'horshipsrectors_popularity_daystodisplay' ); ?>
		},
		yaxis: {
		tickOptions: {},
		numberTicks: <?php echo ( $googlemax+2 ) ;?>,
		min:<?php echo ( $googlemax-1 ) ;?>,
		max:<?php echo ( $googlemax+1 ) ;?>
		}
	},
		highlighter: {
			sizeAdjust: 10,
			tooltipLocation: 'n',
			tooltipAxes: 'y',
			tooltipFormatString: 'PageRank: %.0f',
			useAxesFormatters: false
		},
		cursor: {
			show: true,
			useAxesFormatStrings: false,
			tooltipFormatString: ''
		}
	} );
	--></script>
<?php
	} else {
	?>
	<p>Sorry, there is no data to display at this point.</p>
	<?php
	}
  }
?>

<?php if( get_option( 'horshipsrectors_popularity_track_alexa' ) == "1" ) { ?>

<h3>Alexa Rank</h3>

<p>The Alexa Ranking tool measures visitors to your website through the use of a web browser plugin, lower values are better.</p>
<?php
  $chartalexa = $wpdb->get_results( "
		SELECT *
		FROM $wpdb->options
		WHERE `option_name` LIKE '%horshipsrectors_popularity_checker_alexa_rank_%'
		ORDER BY option_name
  " );

  if ( count( $chartalexa ) ) {
?>

<div id="alexarankchart" class="plot" style="width:728px;height:300px;"></div>
<script language="javascript" type="text/javascript"><!--


<?php


			$alexamax = 0;
			$count = 0;
			foreach ( $chartalexa as $result ) {
				$count++;


				$value = $result->option_value;
				$value = str_replace( ",","",$value );


				if ( $value > $alexamax ) {$alexamax = $value;}

				if ( $alexamin  < 1 ) {$alexamin = $alexamax-( $alexamax*.01 );}
				if ( $value < $alexamin ) {$alexamin = $value;}

				$date = $result->option_name;
				$date = str_replace( "horshipsrectors_popularity_checker_alexa_rank_","",$date );
				$date = date( 'd-M-y', $date );



				$alexalist .="['".$date."',".$value."],";
			}

			$alexalist =  substr( $alexalist, 0, strlen( $alexalist )-1 );




?>
   s1 = [<?php echo $alexalist;?>];


   plot1 = $.jqplot( 'alexarankchart',[s1],{
	title: 'Alexa Rank for <?php bloginfo( 'name' );?>',
	axes: {
		xaxis: {
			renderer: $.jqplot.DateAxisRenderer,
			tickOptions: {
				formatString: '%b %#d, %Y'
			},
			numberTicks: <?php echo get_option( 'horshipsrectors_popularity_daystodisplay' ); ?>

		},
		yaxis: {
			tickOptions: {


			},
				numberTicks: 10,
				min:<?php echo ( $alexamin- ( $alexamin * .01 ) ) ;?> ,
				max:<?php echo ( $alexamax+ ( $alexamax * .01 ) ) ;?>
		}
	},
	highlighter: {
		sizeAdjust: 10,
		tooltipLocation: 'n',
		tooltipAxes: 'y',
		tooltipFormatString: 'Alexa Rank: %.2f',
		useAxesFormatters: false
	},
	cursor: {
		show: true,
		useAxesFormatStrings: false,
		tooltipFormatString: ''
	}
   } );
--></script>

<?php
	} else {
	?>
	<p>Sorry, there is no data to display at this point.</p>
	<?php
	}
  }
?>




<?php if( get_option( 'horshipsrectors_popularity_track_links' ) == "1" ) { ?>

<h3>Links</h3>

<p>This is a measurement of how many other websites link to your website, higher values are better.</p>

<?php
		$chartalexa = $wpdb->get_results( "
				SELECT *
				FROM $wpdb->options
				WHERE `option_name` LIKE '%horshipsrectors_popularity_checker_alexa_links_%'
				ORDER BY option_name
		" );

		if ( count( $chartalexa ) ) {

?>

<div id="alexalinkchart" class="plot" style="width:728px;height:300px;"></div>
<script language="javascript" type="text/javascript"><!--


<?php




			$alexamax = 0;
			$alexamin = 0;
			$count = 0;
			foreach ( $chartalexa as $result ) {
				$count++;


				$value = $result->option_value;
				$value = str_replace( ",","",$value );


				if ( $value > $alexamax ) {$alexamax = $value; if( $count == 1 ) {$alexamin = $alexamax;}}
				if ( $value < $alexamin ) {$alexamin = $value;}

				$date = $result->option_name;
				$date = str_replace( "horshipsrectors_popularity_checker_alexa_links_","",$date );
				$date = date( 'd-M-y', $date );



				$alexalinklist .="['".$date."',".$value."],";
			}

			$alexalinklist =  substr( $alexalinklist, 0, strlen( $alexalinklist )-1 );




?>
   s1 = [<?php echo $alexalinklist;?>];


   plot1 = $.jqplot( 'alexalinkchart',[s1],{
	title: 'Incoming Links for <?php bloginfo( 'name' );?>',
	axes: {
		xaxis: {
			renderer: $.jqplot.DateAxisRenderer,
			tickOptions: {
				formatString: '%b %#d, %Y'
			},
			numberTicks: <?php echo get_option( 'horshipsrectors_popularity_daystodisplay' ); ?>

		},
		yaxis: {
			tickOptions: {


			},
				numberTicks: 10,
				min:<?php echo ( $alexamin- ( $alexamin * .01 ) ) ;?>,
				max:<?php echo ( $alexamax+ ( $alexamax * .01 ) ) ;?>
		}
	},
	highlighter: {
		sizeAdjust: 10,
		tooltipLocation: 'n',
		tooltipAxes: 'y',
		tooltipFormatString: 'Links: %.2f',
		useAxesFormatters: false
	},
	cursor: {
		show: true,
		useAxesFormatStrings: false,
		tooltipFormatString: ''
	}
   } );
--></script>

<?php
	} else {
	?>
	<p>Sorry, there is no data to display at this point.</p>
	<?php
	}
  }
?>

</div>


	<?php
}

function horshipsrectors_popularity_checker( $options='' ) {
	include( 'includes/getpr.php' );
	include( 'includes/getalexa.php' );

	$url = strtolower( trim( get_option( 'siteurl' ) ) );
	$url = str_replace( 'http://', '', $url );
	$url = str_replace( 'https://', '', $url );
	$url = str_replace( '/', '', $url );

		if( get_option( 'horshipsrectors_popularity_track_pagerank' ) == "1" ) {
			// check Google PageRank if two weeks have passed
			if ( ( get_option( 'horshipsrectors_popularity_checker_google_check' )+( 86400 * get_option( 'horshipsrectors_popularity_daystocheck' ) ) ) < date( 'U' ) ) {
				add_option( 'horshipsrectors_popularity_checker_google_value_'.date( 'U' ), horshipsrectors_popularity_checker_google( $url ) );
				update_option( 'horshipsrectors_popularity_checker_google_check',date( 'U' ) );
			}
		}

		if( get_option( 'horshipsrectors_popularity_track_alexa' ) == "1" || get_option( 'horshipsrectors_popularity_track_links' ) == "1" ) {

			// get Alexa Data if two weeks have passed

			if ( ( get_option( 'horshipsrectors_popularity_checker_alexa_check' )+( 86400 * get_option( 'horshipsrectors_popularity_daystocheck' ) ) ) < date( 'U' ) ) {
				update_option( 'horshipsrectors_popularity_checker_alexa_check',date( 'U' ) );
				horshipsrectors_popularity_checker_alexa( $url );
			}

		}

}
add_action( 'wp_footer', 'horshipsrectors_popularity_checker' );


function horshipsrectors_popularity_checker_google( $url='' ) {
	return horshipsrectors_popularity_getpr( $url );
}

function horshipsrectors_popularity_checker_alexa( $url='' ) {
	$url = str_replace( "www.","",$url );
	$url = "/data?cli=10&dat=s&url=$url";
	$data = horshipsrectors_popularity_getAlexaPage( $url );
	if ( strlen( $data ) > 100 ) {
		add_option( 'horshipsrectors_popularity_checker_alexa_rank_' . date( 'U' ), horshipsrectors_popularity_getAlexaRank( $data ) );
		add_option( 'horshipsrectors_popularity_checker_alexa_links_' . date( 'U' ), horshipsrectors_popularity_getAlexaLinks( $data ) );
	}
}



function horshipsrectors_popularity_activate( ) {

	if ( ! ( get_option( 'horshipsrectors_popularity_track_pagerank' ) ) )
		update_option( 'horshipsrectors_popularity_track_pagerank', '1' );

	if ( ! ( get_option( 'horshipsrectors_popularity_track_alexa' ) ) )
			update_option( 'horshipsrectors_popularity_track_alexa', '1' );

	if ( ! ( get_option( 'horshipsrectors_popularity_track_links' ) ) )
		update_option( 'horshipsrectors_popularity_track_links', '1' );

	if ( ! ( get_option( 'horshipsrectors_popularity_daystocheck' ) ) )
		update_option( 'horshipsrectors_popularity_daystocheck', '5' );

	if ( ! ( get_option( 'horshipsrectors_popularity_daystodisplay' ) ) )
		update_option( 'horshipsrectors_popularity_daystodisplay', '5' );

}
