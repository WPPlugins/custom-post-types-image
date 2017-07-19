<?php
/* 
Plugin Name: Custom Post Type Images
Plugin URI: 
Author URI: http://social-ink.net
Version: 0.5
Author: Yonatan Reinberg at Social Ink
Description: Attach images to custom post type archive pages and singles.
 
Copyright 2011  Yonatan Reinberg (email : yoni [a t ] s o cia l-ink DOT net) - http://social-ink.net
*/


/* **************************************************
	PLUGIN SETUP
**************************************************	*/

	//init vars
	$cptplugin_path = WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__));
	$cptplugin_url = plugins_url() . '/' . plugin_basename(dirname(__FILE__));
	$cptplugin_url_images = plugins_url() . '/' . plugin_basename(dirname(__FILE__)) . '/images';
	
	//directory vars
	$pTypeUploadDirectory = wp_upload_dir();
	$pTypeDownloadDirectory = $pTypeUploadDirectory['baseurl'] . '/cptImages/';
	$pTypeUploadDirectory = $pTypeUploadDirectory['basedir'] . '/cptImages/';	
	
	//activation
	register_activation_hook(__FILE__,'cptImages_install');
	
		function cptImages_install() {
			//global $pTypeUploadDirectory;

			//if(!file_exists($pTypeUploadDirectory)) 
			//	$pTypeUploadDirectory = mkdir($pTypeUploadDirectory);	
		}
	
	//js
	 add_action( 'admin_init', 'addJS_ptImages' );
	 
    function addJS_ptImages() {
        wp_register_script( 'ptImages_script', plugins_url('/js/cptImages_script.js', __FILE__) );
    }	 
	
	//admin menu
	add_action('admin_menu', 'addAdmin_ptImages');
	
	function addAdmin_ptImages() {
		$adminpage = add_submenu_page( "tools.php", "Manage Post Type Images", "Manage Post Type Images", "manage_options", "ptImages", "adminPage_ptImages" );	
		add_action('admin_print_styles-' . $adminpage, 'ptImages_loadstyles');
	}  
	
		function adminPage_ptImages() {  
			 include('cptImages_admin.php');  
		 }  		
		 
		function ptImages_loadstyles() {
			//It will be called only on your plugin admin page, enqueue our script here
			wp_enqueue_script( 'ptImages_script' );
		}		 
		
	//admin css
	add_action('admin_head', 'ptImages_admin_register_head');
	
		function ptImages_admin_register_head() {  
				$plugin_path = plugins_url() . '/' . plugin_basename(dirname(__FILE__));				
				$ptImages_css = $plugin_path . '/css/cptImages_admin.css';
				echo "<link rel='stylesheet' type='text/css' href='$ptImages_css' />\n";
			}	

	//links in plugins page
		if ( $GLOBALS['pagenow'] == 'plugins.php' ) {
			add_filter( 'plugin_row_meta', 'cptImages_plugin_links', 10,2);
		}			

		function cptImages_plugin_links($links, $file) {
			if ( strpos($file, basename( __FILE__)) === false ) {
				return $links;
			}
		  
			$plugin = plugin_basename(__FILE__);

			$links[] = '<a href="tools.php?page=ptImages" title="cptImages Settings">Settings</a>';
			$links[] = '<a href="http://social-ink.net" title="Visit Social Ink">Visit Social Ink</a>';
			
			return $links;
		}	
				
/* **************************************************
	PLUGIN FUNCTIONS
**************************************************	*/
	
	//@shortcode hooks
	
	//main page
	add_shortcode('cptImage', 'cptimage_output');	
	
	function cptimage_output($atts=null) {
		extract(shortcode_atts(array(	//get the attributes if any
			'cpt' => ''
		), $atts)); 	

		cptimage_image($cpt);
	}
	
	
	// @displays full image
	function cptimage_image($current_CPT=null) {
		$image_url=get_cptimage_image();
		if($image_url)
			echo "<img class=\"cpt_archive_image\" id=\"cpt_archive_image_$current_CPT\" src=\"$image_url\" alt=\"$current_CPT\" />";
	}
	
	// @gets image url; if argument is true it will print on screen, otherwise return it
	function get_cptimage_image($current_CPT=null, $echo_url = false) {
		global $pTypeDownloadDirectory;
		
		if(empty($current_CPT))		
			if(is_post_type_archive()) 
				$current_CPT = post_type_archive_title( '',false );
			elseif(is_single()) {
				global $post;
				$current_CPT=get_post_type($post);
			}
			elseif(is_category()) {
				$current_CPT='post';
			}
			
			if($current_CPT) {
				$allCPTs = get_post_types();

				foreach($allCPTs as $oneCPT) {
					$metainfo = get_post_type_object($oneCPT);
					if(($metainfo->label==$current_CPT)||($metainfo->name==$current_CPT))
						$current_post_type_image = "ptImages_" . $metainfo->name;
				}
				
				$image_url = get_option($current_post_type_image);
				
				if($image_url) {
					$current_post_type_image = $pTypeDownloadDirectory . $image_url;
						if($echo_url)
							echo $current_post_type_image;
						else
							return $current_post_type_image;					
				} else
					return false;
			} else
				return false;
	}
	
?>
