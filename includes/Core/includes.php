<?php
//JS Dependancies
wp_enqueue_script('jquery');

/***************************************/
/**********  CORE CLASSES  *************/
/***************************************/
include_once( 'class.install.php');
include_once( 'class.db.php');
include_once( 'class.admin_menu.php');
include_once( 'class.template.php');
include_once( 'class.functions.php');
include_once( 'class.admin.php');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-ui-mouse');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-position');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-slider');
/***************************************/
/**************  ADMIN  ****************/
/***************************************/
if(is_admin() && ( isset($_GET['page']) && stristr($_GET['page'],'wa') ) ){
	/***************/
	/*** WP Core ***/
	/***************/
	//JS
	

	wp_enqueue_script('admin-widgets');
	wp_enqueue_script('wp-admin-response');
	wp_enqueue_script('admin-tags');
	wp_enqueue_script('underscore');
	wp_enqueue_script('backbone');
	wp_enqueue_script('core-functions',WP_PLUGIN_URL . '/wa-form-builder/includes/Core/js/functions.js');
	wp_enqueue_script('iz-grid', WP_PLUGIN_URL . '/wa-form-builder/includes/Core/js/iz-grid.js');
	wp_enqueue_script('iz_json2',  WP_PLUGIN_URL . '/wa-form-builder/includes/Core/js/json2.min.js');
	//CSS
	wp_enqueue_style('widgets');
	wp_enqueue_style ('jquery-ui');
	wp_enqueue_style('jquery_ui_all', WP_PLUGIN_URL . '/wa-form-builder/includes/Core/css/jquery.ui.all.css');	
	wp_enqueue_style('jquery_ui_base',  WP_PLUGIN_URL . '/wa-form-builder/includes/Core/css/jquery.ui.base.css');
	wp_enqueue_style('jquery_ui_core',  WP_PLUGIN_URL . '/wa-form-builder/includes/Core/css/jquery.ui.core.css');
	wp_enqueue_style('jquery_ui_datepicker',  WP_PLUGIN_URL . '/wa-form-builder/includes/Core/css/jquery.ui.datepicker.css');	
	wp_enqueue_style('jquery_ui_resizable',  WP_PLUGIN_URL . '/wa-form-builder/includes/Core/css/jquery.ui.resizable.css');	
	wp_enqueue_style('jquery_ui_theme',  WP_PLUGIN_URL . '/wa-form-builder/includes/Core/css/jquery.ui.theme.css');	
	wp_enqueue_style('wa-admin-styles', WP_PLUGIN_URL .'/wa-form-builder/css/admin.css');	
	wp_print_scripts();
	wp_print_styles();
}

/***************************************/
/**************  PUBLIC  ***************/
/***************************************/
//JS
wp_register_script('public-functions', WP_PLUGIN_URL . '/wa-form-builder/includes/Core/js/public.js');
wp_enqueue_script('public-functions');
wp_register_script('core-public-functions', WP_PLUGIN_URL . '/wa-form-builder/includes/Core/js/public-functions.js');
wp_enqueue_script('core-public-functions');
//CSS
wp_register_style('defaults', WP_PLUGIN_URL . '/wa-form-builder/includes/Core/css/defaults.css');
wp_enqueue_style('defaults');
wp_register_style('ui-lightness',  WP_PLUGIN_URL . '/wa-form-builder/includes/Core/css/themes/ui-lightness.css');
wp_enqueue_style('ui-lightness');
?>