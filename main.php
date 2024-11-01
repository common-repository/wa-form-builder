<?php
/*
Plugin Name: WA Form Builder
Plugin URI: http://codecanyon.net/item/x-forms-wordpress-form-creator-plugin/5214711?ref=Basix
Plugin Prefix: wap_ 
Module Ready: Yes
Plugin TinyMCE: popup
Description: Capture user information from your site using this extremely user friendly form builder including features such as: Create forms by simply <strong>dragging and dropping</strong>; Use textfields, textareas, dropdowns, radio buttons and check boxes to capture information from your website; Use an <strong>interactive visual editor</strong> on the front-end of your website to style your forms!; Activate the <strong>interactive tutorial</strong> when you need help; <strong>All forms submited are stored</strong> and can be viewed from the plugin menu;  Sends <strong>confirmation mails</strong> to the users completing a form...and many more! 
Author: Webaways
Version: 1.1
Author URI: http://codecanyon.net/item/x-forms-wordpress-form-creator-plugin/5214711?ref=Basix
License: GPLv2
*/

//ini_set('error_reporting',0);
error_reporting(0);
wp_enqueue_script('jquery');


require( dirname(__FILE__) . '/includes/Core/includes.php');
require( dirname(__FILE__) . '/includes/class.admin.php');

define('SESSION_ID',rand(0,99999999999));


/***************************************/
/**********  Configuration  ************/
/***************************************/
class WAFormBuilder_Config{
	
	
	/*************  General  ***************/
	/************  DONT EDIT  **************/

	/* The displayed name of your plugin */
	public $plugin_name;
	/* The alias of the plugin used by external entities */
	public $plugin_alias;
	/* Enable or disable external modules */
	public $enable_modules;
	/* Plugin Prefix */
	public $plugin_prefix;
	/* Plugin table */
	public $plugin_table, $component_table;
	/* Admin Menu */
	public $plugin_menu;
	/* Add TinyMCE */
	public $add_tinymce;
	
	
	/************* Database ****************/
	
	/* Sets the primary key for table created above */
	public $plugin_db_primary_key = 'Id';
	/* Database table fields array */
	public $plugin_db_table_fields = array
			(
			'title'								=>	'text',
			'description'						=>	'text',
			'mail_to'							=>  'text',
			'confirmation_mail_body'			=>  'text',
			'confirmation_mail_subject'			=>	'text',
			'from_address'						=>  'text',
			'from_name'							=>  'text',
			'on_screen_confirmation_message'	=>  'text',
			'confirmation_page'					=>  'text',
			'form_fields'						=>	'text',
			'visual_settings'					=>	'text',
			'google_analytics_conversion_code'  =>  'text',
			);
			
	public $addtional_table_fields = array
			(
			'wa_form_builder_Id'	=>	'text',
			'meta_key'				=>	'text',
			'meta_value'			=>  'text',
			'time_added'			=>	'text'
			);
			
	/********** Default form Elements ***********/
	public $default_fields = array
		(
		'title' => array
			(
			'grouplabel'	=>	'Title',
			'type'			=>	'text',
			'req'			=>	'1',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'On screen title of the form.'
			),
		'description' => array
			(
			'grouplabel'	=>	'Description',
			'type'			=>	'textarea',
			'req'			=>	'0',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'On screen description of the form.'
			),
		'mail_to' => array
			(
			'grouplabel'	=>	'Mail To',
			'type'			=>	'text',
			'req'			=>	'0',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'Comma-separated list of email addresses to recieve mails when form is submitted.'
			),
		'confirmation_mail_subject' => array
			(
			'grouplabel'	=>	'Confirmation Mail Subject',
			'type'			=>	'text',
			'req'			=>	'0',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'This is the subject of the mail the specified addresses will receive.'
			),
		'confirmation_mail_body' => array
			(
			'grouplabel'	=>	'Confirmation Mail Body',
			'type'			=>	'textarea',
			'req'			=>	'0',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'Add HTML or normal text here to be sent to addresses specified in above field (Mail to) after the form is submited.<br /><br />Use shortcode <strong>[form_data]</strong> were mail form entry details are to be inserted.'
			),
		'from_address' => array
			(
			'grouplabel'	=>	'From Address',
			'type'			=>	'text',
			'req'			=>	'0',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'The address from were the mail origenates.'
			),
		'from_name' => array
			(
			'grouplabel'	=>	'From Name',
			'type'			=>	'text',
			'req'			=>	'0',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'The name of the person/organization from were the mail origenates.'
			),
		/*'confirmation_page' => array
			(
			'grouplabel'	=>	'Confirmation Page',
			'type'			=>	'text',
			'req'			=>	'0',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'Redirect to this page after the form is submited, for example: http://your_site_URL/thank-you/'
			),*/
		'on_screen_confirmation_message' => array
			(
			'grouplabel'	=>	'On Screen Confirmation Message',
			'type'			=>	'textarea',
			'req'			=>	'0',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'The on-screen message to be displayed after the form is submited.'
			),
		'google_analytics_conversion_code' => array
			(
			'grouplabel'	=>	'Google Analytics Conversion Code',
			'type'			=>	'textarea',
			'req'			=>	'0',
			'items'			=>	'',
			'origen'		=>	'default',
			'description'	=>	'Paste your Google Analytics Coonversion Code here and it will be added after a form submission.'
			)
		
		);		
		
	/********** Default Panel Elements user functions ***********/
	public $default_elements = array
			(
			'title'				=>	'WAFormBuilder_default_element_get_title',
			'description'		=>	'WAFormBuilder_default_element_get_description',
			'featured_image'	=>	'WAFormBuilder_default_element_get_featured_image',
			);
			
			
	/************* Admin Menu **************/
	public function build_plugin_menu(){
	
		$plugin_alias  = $this->plugin_alias;
		$plugin_name  = $this->plugin_name;
				
		$this->plugin_menu = array
			(
			$this->plugin_name => array
				(
				'menu_page'	=>	array
					(
					'page_title' 	=> $this->plugin_name,
					'menu_title' 	=> $this->plugin_name,
					'capability' 	=> 'administrator',
					'menu_slug' 	=> 'WA-'.$plugin_alias.'-main',
					'function' 		=> 'WAFormBuilder_main_page',
					'icon_url' 		=> WP_PLUGIN_URL.'/wa-form-builder/images/menu_icon.png',
					'position '		=> ''
					),
				'sub_menu_page'		=>	array
					(
					'Manage Forms' => array
						(
						'parent_slug' 	=> 'WA-'.$plugin_alias.'-main',
						'page_title' 	=> 'Manage Forms',
						'menu_title' 	=> 'Manage Forms',
						'capability' 	=> 'administrator',
						'menu_slug' 	=> 'WA-'.$plugin_alias.'-view-forms',
						'function' 		=> 'WAFormBuilder_view_forms_page',
						),
					'Forms entries' => array
						(
						'parent_slug' 	=> 'WA-'.$plugin_alias.'-main',
						'page_title' 	=> 'Forms entries',
						'menu_title' 	=> 'Forms entries',
						'capability' 	=> 'administrator',
						'menu_slug' 	=> 'WA-'.$plugin_alias.'-forms-data',
						'function' 		=> 'WAFormBuilder_forms_data_page',
						),
					'General Settings' => array
						(
						'parent_slug' 	=> 'WA-'.$plugin_alias.'-main',
						'page_title' 	=> 'General Settings',
						'menu_title' 	=> 'General Settings',
						'capability' 	=> 'administrator',
						'menu_slug' 	=> 'WA-'.$plugin_alias.'-forms-settings',
						'function' 		=> 'WAFormBuilder_settings_page',
						)
					)
				)			
			);
	}
	
	public function __construct()
		{ 
		$header_info = IZC_Functions::get_file_headers(dirname(__FILE__).DIRECTORY_SEPARATOR.'main.php');

		$this->plugin_name 		= $header_info['Plugin Name'];
		$this->enable_modules 	= ($header_info['Module Ready']='Yes') ? true : false ;
		$this->plugin_alias		= IZC_Functions::format_name($this->plugin_name);
		$this->plugin_prefix	= $header_info['Plugin Prefix'];
		$this->plugin_table		= $this->plugin_prefix.$this->plugin_alias;
		$this->component_table	= $this->plugin_table;
		$this->add_tinymce		= $header_info['Plugin TinyMCE'];
		$this->build_plugin_menu(); 
		}
}


/***************************************/
/*************  Hooks   ****************/
/***************************************/


add_action('wp_ajax_WAFormBuilder_tinymce_window', 'WAFormBuilder_tinymce_window');
/* On plugin activation */
register_activation_hook(__FILE__, 'WAFormBuilder_run_instalation' );
/* On plugin deactivation */
register_deactivation_hook(__FILE__, 'WAFormBuilder_deactivate');
/* Called from page */
add_shortcode( 'WAFormBuilder', 'WAFormBuilder_ui_output' );
/* Build admin menu */
add_action('admin_menu', 'WAFormBuilder_main_menu');
/* Initialize rewrite rules */
add_action('init', 'WAFormBuilder_rewrite');
/* Add action button to TinyMCE Editor */
add_action('init', 'WAFormBuilder_add_mce_button');
/* Add Theme css for panels */
//wp_register_style('panels', get_template_directory_uri() .'/css/panels.css');
//wp_enqueue_style( 'panels' );

/***************************************/
/*********  Hook functions   ***********/
/***************************************/
/* Convert menu to WP Admin Menu */
function WAFormBuilder_main_menu(){
	$config = new WAFormBuilder_Config();
	IZC_Admin_menu::build_menu($config->plugin_name);
}
/* Called on plugin activation */
function WAFormBuilder_run_instalation(){
	
	WAFormBuilder_rewrite();
    flush_rewrite_rules();
	
	$config = new WAFormBuilder_Config();
		
	$instalation = new IZC_Instalation();
	
	$instalation->component_name 			=  $config->plugin_name;
	$instalation->component_prefix 			=  $config->plugin_prefix;
	$instalation->component_alias			=  $config->plugin_alias;
	$instalation->component_default_fields	=  $config->default_fields;
	$instalation->component_menu 			=  $config->plugin_menu;	
	$instalation->db_table_fields			=  $config->plugin_db_table_fields;
	$instalation->db_table_primary_key		=  $config->plugin_db_primary_key;
	$instalation->run_instalation('full');
	
	/************************************************/
	/************  Additional Table   ***************/
	/************************************************/
	$extra_instalation = new IZC_Instalation();
	
	
	$extra_instalation->component_prefix 		=  $config->plugin_prefix;
	$extra_instalation->component_alias			=  'wa_form_meta';
	$extra_instalation->db_table_fields			=  $config->addtional_table_fields;
	$extra_instalation->db_table_primary_key	=  $config->plugin_db_primary_key;
	$extra_instalation->install_component_table();
	
	if(!get_option('wa-forms-default-settings'))
		{
		add_option
			('wa-forms-default-settings',array
				(
				'display_title' => 'Yes',
				'display_description' => 'Yes',
				'send_user_mail' => 'No',
				'mail_to' => get_option('admin_email'),
				'confirmation_mail_subject' => get_option('blogname').' Form Submission',
				'confirmation_mail_body' => 'Thank you for connecting with us. We will respond to you shortly.[form_data]',
				'from_address' => get_option('admin_email'),
				'from_name' => get_option('blogname'),
				'on_screen_confirmation_message' => 'Thank you for connecting with us. We will respond to you shortly.',
				)
			);
		}
	
	
}

/* Called on plugin deactivation */
function WAFormBuilder_deactivate() {
    flush_rewrite_rules();
}

/* rewrite rules */
function WAFormBuilder_rewrite() {
  	//add_rewrite_rule('([^/]+)/([^/]+)?$', 'index.php?pagename=$matches[1]&args=$matches[2]','top'); 
   // add_rewrite_tag('%args%', '([^&]+)');
}

/* Add action button to TinyMCE Editor */
function WAFormBuilder_add_mce_button() {
	add_filter("mce_external_plugins", "WAFormBuilder_tinymce_plugin");
 	add_filter('mce_buttons', 'WAFormBuilder_register_button');
}

/* register button to be called from JS */
function WAFormBuilder_register_button($buttons) {
   array_push($buttons, "separator", "wa_form_builder");
   return $buttons;
}

/* Send request to JS */
function WAFormBuilder_tinymce_plugin($plugin_array) {
   $plugin_array['wa_form_builder'] = WP_PLUGIN_URL.'/wa-form-builder/tinyMCE/plugin.js';
   return $plugin_array;
}

function WAFormBuilder_tinymce_window(){
	include_once( dirname(__FILE__).'/includes/window.php');
    die();
}
	


if(isset($_REQUEST['tutorial']))
	{
	wp_register_script('wa-tutorial-adding-froms-to-pages', WP_PLUGIN_URL . '/wa-form-builder/js/tutorial-adding-froms-to-pages.js');
	wp_enqueue_script('wa-tutorial-adding-froms-to-pages');
	wp_register_style('wa-tutorial-adding-froms-to-pages-css', WP_PLUGIN_URL . '/wa-form-builder/css/admin.css');
	wp_enqueue_style('wa-tutorial-adding-froms-to-pages-css');
	}
/***************************************/
/*********   Admin Pages   *************/
/***************************************/
//Landing page
function WAFormBuilder_main_page(){

	$config 	= new WAFormBuilder_Config();
	$template 	= new IZC_Template();
	$custom		= new IZFForms();
	
	$custom->plugin_name  = $config->plugin_name;
	$custom->plugin_alias = $config->plugin_alias;
	$custom->plugin_table = $config->plugin_table;

	if(isset($_REQUEST['tutorial']))	
		$template -> add_js('var tutorial = true;');
	
	wp_register_script('wa-forms_main_page-functions', WP_PLUGIN_URL .'/wa-form-builder/js/functions.js');
	wp_enqueue_script('wa-forms_main_page-functions');
			
	$template -> build_header( $config->plugin_name,'Canvas' , $template->build_menu($modules_menu),'',$config->plugin_alias);

	$body .= $custom->customize_forms();	

	$template -> build_body($body);
	$template -> build_footer('');	
	$template -> print_template();
}

function WAFormBuilder_view_forms_page(){	
	$config 	= new WAFormBuilder_Config();
	$template 	= new IZC_Template();
	
	$config->sub_heading = "Manage Forms";

	if(isset($_REQUEST['tutorial']))
		{	
		wp_register_script('wa-tutorial-view-forms', WP_PLUGIN_URL .'/wa-form-builder/js/tutorial-view-forms.js');
		wp_enqueue_script('wa-tutorial-view-forms');
		}
		
	$template->build_landing_page($config);
}
function WAFormBuilder_forms_data_page(){
	
	$config 	= new WAFormBuilder_Config();
	$template 	= new IZC_Template();
	$custom		= new IZFForms();
	
	$custom->plugin_name  = $config->plugin_name;
	$custom->plugin_alias = $config->plugin_alias;
	$custom->plugin_table = $config->plugin_table;

	wp_register_script('wa-forms_data_page-functions', WP_PLUGIN_URL .'/wa-form-builder/js/functions.js');
	wp_enqueue_script('wa-forms_data_page-functions');
		
	if(isset($_REQUEST['tutorial']))
		{	
		wp_register_script('wa-tutorial-view-forms', WP_PLUGIN_URL .'/wa-form-builder/js/tutorial-view-entries.js');
		wp_enqueue_script('wa-tutorial-view-forms');
		}
	$template -> build_header( $config->plugin_name,'Form Entries' , $template->build_menu($modules_menu),'',$config->plugin_alias);
	

	$body = $custom->forms_data();	

	$template -> build_body($body);
	$template -> build_footer('');	
	$template -> print_template();
	
}

function WAFormBuilder_settings_page(){
	
	$config 	= new WAFormBuilder_Config();
	$template 	= new IZC_Template();
	$custom		= new IZFForms();
	
	$custom->plugin_name  = $config->plugin_name;
	$custom->plugin_alias = $config->plugin_alias;
	$custom->plugin_table = $config->plugin_table;

	
	$template -> build_header( $config->plugin_name,'General Settings' , $template->build_menu($modules_menu),'',$config->plugin_alias);
	
	
	wp_register_script('wa-forms_settings_page-functions', WP_PLUGIN_URL .'/wa-form-builder/js/functions.js');
	wp_enqueue_script('wa-forms_settings_page-functions');
		
	if(isset($_REQUEST['tutorial']))
		{	
		wp_register_script('wa-tutorial-settings', WP_PLUGIN_URL .'/wa-form-builder/js/tutorial-settings.js');
		wp_enqueue_script('wa-tutorial-settings');
		}
	
	$setting_values = get_option('wa-forms-default-settings');
	
	$body = '<div id="col-container">
  <div class="iz-ajax-response" id="ajax-response"></div>
  <div id="col-left">
    <div class="col-wrap ui-droppable">
      <div class="form-wrap">
        <h3 class="sub_sub_heading">Configure</h3>
        <form enctype="multipart/form-data" name="addItem" method="post" action="" class="validate" id="addtag">
          <input type="hidden" value="update_form_settings" name="action">
       		
          <div class="iz-forms-holder">
            <div id="ajax-response" class="iz-ajax-response"></div>
            <div class="module-fields"></div>
            <div class="form-fields">
			
			<fieldset class="radio  confirmation_mail">
                <legend>Send Confirmation Mail to user?</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <input type="radio" class="radio_buttons" id="send_user_mail_yes" value="Yes" name="send_user_mail" '.(($setting_values['send_user_mail']=='Yes' || !$setting_values['send_user_mail']) ? 'checked="checked"' : '').'><label for="send_user_mail_yes">Yes</label>
					<input type="radio" class="radio_buttons" id="send_user_mail_no"  value="No"  name="send_user_mail" '.(($setting_values['send_user_mail']=='No') ? 'checked="checked"' : '').'><label for="send_user_mail_no">No</label>
                    <p class="field_description">Choose whether to send a confirmation email to the user completing a form.</p>
                  </div>
                </div>
              </fieldset>';

		$custom_fields = get_option('iz-forms-custom-fields',array());
		

		foreach($custom_fields as $group=>$attr)
			{
			if($attr['format']=='email')
				$possible_mail_fields[$attr['grouplabel']] =$attr['grouplabel'];
			if(is_array($attr['items']))
				{
				foreach($attr['items'] as $key=>$val)
					if($val['format']=='email')
						$possible_mail_fields[IZC_Functions::format_name($attr['grouplabel']).'__'.IZC_Functions::format_name($val['val'])] = IZC_Functions::format_name($attr['grouplabel']).'__'.IZC_Functions::format_name($val['val']);
				}
				
			}
		if(count($possible_mail_fields)<=0)
			{
			foreach($custom_fields as $group=>$attr)
				{
				if(stristr($attr['grouplabel'],'email') || stristr($attr['grouplabel'],'e-mail') || stristr($attr['grouplabel'],'mail'))
					$possible_mail_fields[$attr['grouplabel']] = $attr['grouplabel'];
				}	
			}
			
		$body .= '<fieldset class="check mail_to_user_address">
               <legend>Users mail address</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">';
				  if(count($possible_mail_fields)<=0)
				  	$body .= '<p class="field_description">There are no possible custom fields found that that can be used as the users email address. Create a Text field and specify uin the settings that the field needs to be formated as an email.</p>';
				  else
				 	{
				  	
					foreach($possible_mail_fields as $possible_mail_field)
						{ 
					 	$possible_mail_field = IZC_Functions::format_name($possible_mail_field);         
						$body .= '<input type="checkbox" class="checkbox "  name="possible_mail_fields[]" value="'.$possible_mail_field.'" '.((in_array($possible_mail_field,$setting_values['possible_mail_fields'])) ? 'checked="checked"' : '').' id="'.$possible_mail_field.'"><label for="'.$possible_mail_field.'">'.IZC_Functions::unformat_name($possible_mail_field).'</label><br />';
						}
					}
					$body .= '<p class="field_description">Select the custom fields (as created) to be used to send the user confirmation email.</p>';
		//$body .= ' <p class="field_description">Select the field for end-user confirmation mail messages. The user who completes the form will recieve the same message as per the settings below.</p>';
                 $body .= '</div>
                </div>
              </fieldset>
			  
			  <fieldset class="radio  display_title">
                <legend>Display Form Title?</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <input type="radio" class="radio_buttons" id="display_title_yes" value="Yes" name="display_title" '.(($setting_values['display_title']=='Yes' || !$setting_values['display_title']) ? 'checked="checked"' : '').'><label for="display_title_yes">Yes</label>
					<input type="radio" class="radio_buttons" id="display_title_no"  value="No"  name="display_title" '.(($setting_values['display_title']=='No') ? 'checked="checked"' : '').'><label for="display_title_no">No</label>
                    <p class="field_description">Choose whether to show the form title on your site.</p>
                  </div>
                </div>
              </fieldset>
			  
			  <fieldset class="radio  display_description">
                <legend>Display Form Description?</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <input type="radio" class="radio_buttons" id="display_description_yes" value="Yes" name="display_description" '.(($setting_values['display_description']=='Yes' || !$setting_values['display_description']) ? 'checked="checked"' : '').'><label for="display_description_yes">Yes</label>
					<input type="radio" class="radio_buttons" id="display_description_no"  value="No"  name="display_description" '.(($setting_values['display_description']=='No') ? 'checked="checked"' : '').'><label for="display_description_no">No</label>
                   <p class="field_description">Choose whether to show the form description on your site.</p>
                  </div>
                </div>
              </fieldset>
			  
              <fieldset class="text mail_to">
                <legend>Mail To</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <input type="text" class=" " value="'.$setting_values['mail_to'].'" name="mail_to">
                    <p class="field_description">Comma-separated list of email addresses to recieve mails when form is submitted.</p>
                  </div>
                </div>
              </fieldset>
              <fieldset class="text confirmation_mail_subject">
                <legend>Confirmation Mail Subject</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <input type="text" class=" " value="'.$setting_values['confirmation_mail_subject'].'" name="confirmation_mail_subject">
                    <p class="field_description">This is the subject of the mail the specified addresses will receive.</p>
                  </div>
                </div>
              </fieldset>
              <fieldset class="textarea confirmation_mail_body">
                <legend>Confirmation Mail Body</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <textarea name="confirmation_mail_body" class="">'.(($setting_values['confirmation_mail_body']=='') ? '[form_data]' : $setting_values['confirmation_mail_body'] ).'</textarea>
                    <p class="field_description">Add HTML or normal text here to be sent to addresses specified in above field (Mail to) after the form is submited.<br>
                      <br>
                      Use shortcode <strong>[form_data]</strong> were mail form entry details are to be inserted.</p>
                  </div>
                </div>
              </fieldset>
              <fieldset class="text from_address">
                <legend>From Address</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <input type="text" class=" " value="'.$setting_values['from_address'].'" name="from_address">
                    <p class="field_description">The address from were the mail origenates.</p>
                  </div>
                </div>
              </fieldset>
              <fieldset class="text from_name">
                <legend>From Name</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <input type="text" class=" " value="'.$setting_values['from_name'].'" name="from_name">
                    <p class="field_description">The name of the person/organization from were the mail origenates.</p>
                  </div>
                </div>
              </fieldset>
             <!-- <fieldset class="text confirmation_page">
                <legend>Confirmation Page</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <input type="text" class=" " value="'.$setting_values['confirmation_page'].'" name="confirmation_page">
                    <p class="field_description">Redirect to this page after the form is submited, for example: http://your_site_URL/thank-you/</p>
                  </div>
                </div>
              </fieldset>-->
              <fieldset class="textarea on_screen_confirmation_message">
                <legend>On Screen Confirmation Message</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <textarea name="on_screen_confirmation_message" class="">'.$setting_values['on_screen_confirmation_message'].'</textarea>
                    <p class="field_description">The on-screen message to be displayed after the form is submited if a confirmation is not entered</p>
                  </div>
                </div>
              </fieldset>
			  <fieldset class="textarea google_analytics_conversion_code">
                <legend>Google Analytics Conversion Code</legend>
                <div class="iz-form-item">
                  <div class="iz-form-item">
                    <textarea name="google_analytics_conversion_code" class="">'.$setting_values['google_analytics_conversion_code'].'</textarea>
                    <p class="field_description">Paste your Google Analytics Coonversion Code here and it will be added after a form submission.</p>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
          <div class="submit">
            <p class="submit">
              <input type="submit" name="submit" id="submit" data-action="iz-insert" class="iz-submit button-primary iz-plugin-submit" value="      Save Settings       ">
            </p>
            <input type="hidden" value="[&quot;title&quot;,&quot;description&quot;,&quot;mail_to&quot;,&quot;confirmation_mail_subject&quot;,&quot;confirmation_mail_body&quot;,&quot;from_address&quot;,&quot;from_name&quot;,&quot;confirmation_page&quot;,&quot;on_screen_confirmation_message&quot;]" name="fields">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
';



$template -> build_body($body);
$template -> build_footer('');	
$template -> print_template();

}

//Modules page
function WAFormBuilder_modules_page(){
	$config 	= new WAFormBuilder_Config();
	$template 	= new IZC_Template();
	$template->build_module_page($config);
}
//Dynamic Panels
function WAFormBuilder_panels_page(){
	
	$config 	= new WAFormBuilder_Config();
	$template 	= new IZC_Template();
	$panel 		= new IZC_Panels();
	
	$panel->default_elements = $config->default_elements;
	$panel->plugin_alias = $config->plugin_alias;

	$panel->build_panel($config->plugin_name,true);
	$panel->build_panel($config->plugin_name.' Details');
	
	$template -> build_header( $config->plugin_name ,'','','',$config->plugin_alias);
	$template -> build_body('No panels available');
	$template -> build_footer('');	
	$template -> print_template();
}


/***************************************/
/********   Default Elements   *********/
/***************************************/
function WAFormBuilder_default_element_get_title($args=''){
	return (!empty($args['args']->title)) ? '<a href="'.$args['link'].'">'.$args['args']->title.'</a>' : '';	
}

function WAFormBuilder_default_element_get_description($args=''){
	return ($args['panel']=='Units') ? IZC_Functions::view_excerpt($args['args']->description,150) : ((!is_admin()) ? '<div id="iz-scroll" class="iz-scroll">'.$args['args']->description.'<div class="scroll-arrow-bottom ieHax"></div><div class="scroll-arrow-top ieHax" style="display:none;"></div></div>' : $args['args']->description );	
}

function WAFormBuilder_default_element_get_featured_image($args=''){

	$path = ABSPATH.'/wp-content/iz-uploads/thumbs/';
		 
	$directoryHandle = opendir($path);
	
	$counter = 0;
	
	while ($images = readdir($directoryHandle))
		{
		if($images != '.' && $images != '..')
			{
			if(strstr($images,$args['args']->session_Id))
				{
				if($args['args']->featured_image==$images)
					$featured_image = '<img src="'.get_option('siteurl').'/wp-content/iz-uploads/thumbs/'.$images.'" class="active" />';
				else
					$thumbs .= '<img src="'.get_option('siteurl').'/wp-content/iz-uploads/thumbs/'.$images.'"  />';
				
				
				$counter ++;
				}
			}
		}

	if($args['panel']=='Units')
		{
		$output .= (!empty($args['args']->featured_image)) ? '<img src="'.get_option('siteurl').'/wp-content/iz-uploads/thumbs/'.$args['args']->featured_image.'" />'  : '<img src="'.WP_PLUGIN_URL.'/IZ-Listings/default.png" />';	
		$output .= '<div class="count-images">';
		$output .= $counter.' Photos';
		$output .= '</div>';
		}
	else
		{
		$output .= (!empty($args['args']->featured_image)) ? '<img src="'.get_option('siteurl').'/wp-content/iz-uploads/'.$args['args']->featured_image.'" />'  : '<img src="'.WP_PLUGIN_URL.'/IZ-Listings/default.png" />';		

		if($counter>0)
			{
			$output .= '<div style="clear:both;width:100%;"></div>';	
			$output .= '<div class="slide-left"></div>';
			
			$output .= '<div class="image-slider">';
				
				$output .= '<div class="thumbs">';
					$output .= $featured_image.$thumbs;
				$output .= '</div>';
			$output .= '</div>';
			
			$output .= '<div class="slide-right"></div>';
			}	
		}
	
	return $output;
}


/***************************************/
/*********   User Interface   **********/
/***************************************/

/************* Panels **************/
function WAFormBuilder_ui_output( $atts ){
		
	$form 		= new IZFForms();
	$config 	= new WAFormBuilder_Config();
		
	$defaults = array('id' => '0');
	extract( shortcode_atts( $defaults, $atts ) );
    wp_parse_args($atts, $defaults);
	
	
	if(isset($_POST['action']) && ($_POST['action']=='do_insert' || $_POST['action']=='insert_data'))
		{
		
		global $wpdb;
		$form_attr = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_wa_form_builder WHERE Id = '.$_REQUEST['wa_forms_Id']);
	
		
		
		$user_fields .= '<table width="100%" cellpadding="3" cellspacing="1" style="background:#e7e7e7; color:#666;">';
		foreach($_POST as $key=>$val)
			{
			if(
			$key!='action' &&
			$key!='current_page' &&
			$key!='ajaxurl' &&
			$key!='page_id' &&
			$key!='wa_forms_Id' &&
			$key!='submit'
			)
				{
				$user_fields .= '<tr>';
					$user_fields .= '	<td bgcolor="#f2f2f2" width="20%">'.IZC_Functions::unformat_name(str_replace('dynamic_forms','',$key)).'</td>
										<td bgcolor="#FFFFFF" >'.IZC_Functions::unformat_name($val).'</td>';
				$user_fields .= '</tr>';	
				$insert = $wpdb->insert($wpdb->prefix.'wap_wa_form_meta',
						array(
							'wa_form_builder_Id'=>$_REQUEST['wa_forms_Id'],
							'meta_key'=>$key,
							'meta_value'=>$val,
							'time_added' => mktime()
							)
					 );
				}
			}
		$user_fields .= '</table>';
		
		$default_values = get_option('wa-forms-default-settings');
		
		$from_address 							= ($form_attr->from_address) 						? $form_attr->from_address 												: $default_values['from_address'];
		$from_name 								= ($form_attr->from_name) 							? $form_attr->from_name 												: $default_values['from_name'];
		$mail_to 								= ($form_attr->mail_to) 							? $form_attr->mail_to 													: $default_values['mail_to'];
		$subject 								= ($form_attr->confirmation_mail_subject) 			? str_replace('\\','',$form_attr->confirmation_mail_subject) 			:  str_replace('\\','',$default_values['confirmation_mail_subject']);
		$body 									= ($form_attr->confirmation_mail_body) 				? str_replace('\\','',$form_attr->confirmation_mail_body) 				:  str_replace('\\','',$default_values['confirmation_mail_body']);
		$onscreen 								= ($form_attr->on_screen_confirmation_message) 		? str_replace('\\','',$form_attr->on_screen_confirmation_message) 		:  str_replace('\\','',$default_values['on_screen_confirmation_message']);
		$google_analytics_conversion_code 		= ($form_attr->google_analytics_conversion_code) 	? str_replace('\\','',$form_attr->google_analytics_conversion_code) 	:  str_replace('\\','',$default_values['google_analytics_conversion_code']);

		if(strstr($body,'[form_data]'))
			$mail_body = str_replace('[form_data]',$user_fields,$body);
		else
			$mail_body = $user_fields.$body;
			
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-Type: text/html; charset=utf-8\n\n'. "\r\n";
		$headers .= 'From: '.$from_name.' <'.$from_address.'>' . "\r\n";
			
		$send_mail = mail($mail_to,$subject,$mail_body,$headers);
		
		if($default_values['send_user_mail']!='No')
			{
			foreach($default_values['possible_mail_fields'] as $user_mail)
				{
				if(array_key_exists($user_mail,$_POST))
					if($_POST[$user_mail])
						$send_user_mail = mail($_POST[$user_mail],$subject,$mail_body,$headers);
				}
			}

		$output .= '<style type="text/css" title="inline_form_styles">'.$form_attr->visual_settings.'</style>';
		$output .= '<p class="confirmation_message" id="confirmation_message">'.((strstr($onscreen,'<br />') || strstr($onscreen,'<br>') ) ? $onscreen : nl2br($onscreen)).'<p>';
		$output .= $google_analytics_conversion_code;
		}
	else
		{
		$add_confirmation_message = false;
		if ( is_user_logged_in() ) 
			{
			global $current_user;
			if($current_user->caps['administrator']==1)
				{
				/*wp_enqueue_script('jquery');
				wp_enqueue_script('wa-color-picker',WP_PLUGIN_URL.'/wa-form-builder/js/jscolor/jscolor.js');
				wp_register_script('wa-visual-settings', WP_PLUGIN_URL . '/wa-form-builder/js/visual_settings.js');
				wp_enqueue_script('wa-visual-settings');
					*/
				$add_confirmation_message = false;
				}
			}
		
		global $wpdb;
		
		$current_page = str_replace('?default=true','',$_SERVER['REQUEST_URI']);
		$current_page = str_replace('&default=true','',$current_page);
		$current_page = str_replace('&restore=all','',$current_page);
		$current_page = str_replace('&restore=current_form','',$current_page);
		
		if($_REQUEST['default']=='true')
			{
			if(	$_REQUEST['restore']=='all')
				$update = $wpdb->query( 'UPDATE '.$wpdb->prefix.'wap_wa_form_builder SET visual_settings=""');
			else
				$update = $wpdb->update ( $wpdb->prefix . 'wap_wa_form_builder', array('visual_settings'=>''), array(	'Id' => $id) );	
			
			//The below is used to clear the browser parameters of the deafault have been loaded. Header:location does not work, used javascript instead.
			echo '<script type="text/javascript"> document.location.href = "'.$current_page.'"</script>';
			}
		
		$form_attr = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_wa_form_builder WHERE Id = '.$id);
		
		
		$setting_values = get_option('wa-forms-default-settings');

		$output .= '<style type="text/css" title="inline_form_styles">'.$form_attr->visual_settings.'</style>';
		$output .= '<div class="wa_form_wrap">';
		$output .= '<div class="form_container wa_form_ui" id="form_container">';
		if($setting_values['display_title']!='No')
			$output .= (IZC_Database::get_title($id,'wap_wa_form_builder')) ? '<h2 id="form_title" class="form_title">'.IZC_Database::get_title($id,'wap_wa_form_builder').'</h2>' : '';
		if($setting_values['display_description']!='No')
			$output .= (IZC_Database::get_description($id,'wap_wa_form_builder')) ? '<p class="form_description" id="form_description">'.IZC_Database::get_description($id,'wap_wa_form_builder').'</p>' : '';
			$output .= 	'<form name="add_form_entry" id="add_form_entry" method="post" action="" class="validate">';	
				$output .= '<input type="hidden" name="action" value="insert_data">';
				$output .= '<input type="hidden" name="wa_forms_Id" value="'.$id.'">';
				$output .= '<input type="hidden" name="current_page" value="'.$current_page.'">';
				$output .= '<input type="hidden" name="ajaxurl" value="'.get_option('siteurl').'/wp-admin/admin-ajax.php">';				
				$output .=  $form->get_form_fields($id);
				$output .= '<div style="clear:both;"></div>';
				$output .=	'<p ><input type="button" value="    Submit    " id="submit_button" class="submit_form_entry submit_button" data-action="'.((isset($_REQUEST['Id'])) ? 'iz-update' : 'iz-insert').'" name="submit_form_entry">';
			$output .= 	'</form>';
		
		$output .= '</div>';
		if($add_confirmation_message)
			{
				$default_values = get_option('wa-forms-default-settings');
				$onscreen = ($form_attr->on_screen_confirmation_message) ? str_replace('\\','',$form_attr->on_screen_confirmation_message) :  str_replace('\\','',$default_values['on_screen_confirmation_message']);
				$output .= '<p><strong>Confirmation Message:</strong><br /> The styling applied here will reflect after this form is submmited. While you are logged in as an administrator you can style it here. <em><strong>NOTE:</strong> This message and the below confirmation message will not be present on this page when you are NOT logged in as an administrator.</em></p>';
				$output .= '<p class="confirmation_message" id="confirmation_message">'.$onscreen.'</p>';
			}
		$output .= '</div">';
		}
	return $output;	
}

function WAFormBuilder_dashboard_widget(){
	$output .= '<div class="dashboard_wrapper">';
		$output .= '<a href="http://codecanyon.net/item/x-forms-wordpress-form-creator-plugin/5214711?ref=Basix"><img src="'.WP_PLUGIN_URL . '/wa-form-builder/images/pro_banner_3.png"></a>';
	$output .= '</div>';
	echo $output;
}

function WAFormBuilder_dashboard_setup() {
	
	wp_add_dashboard_widget('wa_form_builder_widget', 'WA Forms Builder', 'WAFormBuilder_dashboard_widget');
	
	global $wp_meta_boxes;
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	$wa_form_builder_widget_backup = array('wa_form_builder_widget' => $normal_dashboard['wa_form_builder_widget']);
	unset($normal_dashboard['wa_form_builder_widget']);
	$sorted_dashboard = array_merge($wa_form_builder_widget_backup, $normal_dashboard);
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;	
} 

add_action('wp_dashboard_setup', 'WAFormBuilder_dashboard_setup' );

wp_register_style('WAFormBuilder-UI', WP_PLUGIN_URL . '/wa-form-builder/css/ui.css');
wp_enqueue_style('WAFormBuilder-UI');

wp_register_style('WAFormBuilder-ADMIN-Dashboard', WP_PLUGIN_URL . '/wa-form-builder/css/dashboard.css');
wp_enqueue_style('WAFormBuilder-ADMIN-Dashboard');

wp_register_script('wa-form-validation', WP_PLUGIN_URL . '/wa-form-builder/js/public.js');
wp_enqueue_script('wa-form-validation');
$get_current_page = $_SERVER['REQUEST_URI'];
//if(!is_admin() && !strstr($get_current_page,'wp-login'))
	//echo IZFForms::show_visual_form_settings();
?>