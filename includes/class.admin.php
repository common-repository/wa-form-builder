<?php
/***************************************/
/***********   Ajax Calls   ************/
/***************************************/
//Core action for populating dropdown menu on updates
//add_action('wp_ajax_populate_dropdown',		array('IZC_Database','populate_dropdown_list'));
//Call on field drop to form
add_action('wp_ajax_build_field_group',		array('IZFForms','build_field_group'));
//Get all stored field data and populate steps
add_action('wp_ajax_edit_custom_field',		array('IZFForms','edit_custom_field'));
//Delete custom field and send to archive
add_action('wp_ajax_delete_custom_field',	array('IZFForms','delete_custom_field'));
//Get avialable attributes form input type
add_action('wp_ajax_create_group',			array('IZFForms','create_group'));
//Add options to a specific group and input type
add_action('wp_ajax_add_group_options',		array('IZFForms','add_group_options'));
//Add options to a specific group and input type
add_action('wp_ajax_populate_custom_fields',array('IZFForms','get_custom_fields'));
//Get saved form fields 
add_action('wp_ajax_populate_form_fields',	array('IZFForms','get_form_fields'));
//Save custom field
add_action('wp_ajax_save_field',			array('IZFForms','save_field'));
//Save form (fields and order)
add_action('wp_ajax_save_form',				array('IZFForms','save_form'));
//Called on type check fields and textfield groups on editing
add_action('wp_ajax_update_db_field',		array('IZFForms','update_db_field'));
//Get form Id from remote module
add_action('wp_ajax_get_form_Id',			array('IZFForms','get_form_Id'));

add_action('wp_ajax_populate_form_data_list', array('IZFForms','get_form_data'));

add_action('wp_ajax_delete_form_entry', array('IZFForms','delete_form_entry'));

add_action('wp_ajax_from_entries_table_pagination', array('IZFForms','from_entries_table_pagination'));

add_action('wp_ajax_populate_form_data_table', array('IZFForms','biuld_form_data_table'));

add_action('wp_ajax_update_form_settings', array('IZFForms','update_form_settings'));

add_action('wp_ajax_save_visual_settings',  array('IZFForms','save_visual_settings') );
add_action('wp_ajax_nopriv_save_visual_settings',  array('IZFForms','save_visual_settings') );

add_action('wp_ajax_save_visual_settings_panel_position',  array('IZFForms','save_visual_settings_panel_position') );
add_action('wp_ajax_nopriv_save_visual_settings_panel_position',  array('IZFForms','save_visual_settings_panel_position') );
if(!class_exists('IZC_cURL'))
	{
		class IZC_cURL {
			var $headers;
			var $user_agent;
			var $compression;
			var $cookie_file;
			var $proxy;
			
			function cURL($cookies=TRUE,$cookie='cookies.txt',$compression='gzip',$proxy='') {
				$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
				$this->headers[] = 'Connection: Keep-Alive';
				$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
				$this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
				$this->compression=$compression;
				$this->proxy=$proxy;
				$this->cookies=$cookies;
			}
			
			function get($url) {
				$process = curl_init($url);
				curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
				curl_setopt($process, CURLOPT_HEADER, 0);
				curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
				if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
				if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
				curl_setopt($process,CURLOPT_ENCODING , $this->compression);
				curl_setopt($process, CURLOPT_TIMEOUT, 30);
				if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
				curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
				$return = curl_exec($process);
				curl_close($process);
				return $return;
			}
			function post($url,$data) {
				$process = curl_init($url);
				curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
				curl_setopt($process, CURLOPT_HEADER, 1);
				curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
				if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
				if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
				curl_setopt($process, CURLOPT_ENCODING , $this->compression);
				curl_setopt($process, CURLOPT_TIMEOUT, 30);
				if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
				curl_setopt($process, CURLOPT_POSTFIELDS, $data);
				curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($process, CURLOPT_POST, 1);
				$return = curl_exec($process);
				curl_close($process);
				return $return;
			}
			function error($error) {
			//echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
			//die;
			}
		}
	}

class IZFForms{
	
	public $data_fields;
	
	public function __construct(){
		$this->add_new();
	}
	public function update_form_settings(){
		
		if(!get_option('wa-forms-default-settings'))
			add_option('wa-forms-default-settings',array());
		
		update_option('wa-forms-default-settings',$_POST);
		
		IZC_Functions::print_message( 'updated' , 'Settings Updated' );
		die();
	}
	
	public function save_visual_settings_panel_position(){
		//if(!isset($_COOKIE['visual_settings_panel_position']))
			setcookie("visual_settings_panel_position", $_POST['position'], time()+60*60*24*30,'/');
		//else
		//	$_COOKIE['visual_settings_panel_position'] = $_POST['position'];
		die();
	}
	
	public function save_visual_settings(){
		
		global $wpdb;
		if($_POST['save_for']=='all')
			$update = $wpdb->query( 'UPDATE '.$wpdb->prefix.'wap_wa_form_builder SET visual_settings="'.$_POST['css'].'"');
		else
			$update = $wpdb->update ( $wpdb->prefix . 'wap_wa_form_builder', array('visual_settings'=>$_POST['css']), array(	'Id' => $_POST['form_Id']) );
		echo $update;
		die();	
	}
	
	public function show_visual_form_settings(){
		
		$settings_pos = explode(',',$_COOKIE['visual_settings_panel_position']);
	$colors = array();
		$output = '';
		
		$data = file_get_contents(get_template_directory_uri().'/style.css','r');
		
		if(!$data)
			{	
			$cc = new IZC_cURL();
			$data = $cc->get(get_template_directory_uri().'/style.css');
			}

		$pattern 	= '/[A-F0-9]{6}/';
		preg_match_all($pattern , strtoupper($data),$matches);
		$pattern2 	= '/(font-family:)+[a-z A-Z 0-9  ,"\'-]+(;)/';
		preg_match_all($pattern2 , $data, $matches2);
		
		$fonts 	= array_unique($matches2[0]);
		$colors = array_unique($matches[0]);
		
		$output .= '<div class="color_pallet" style="display:none;"><p class="theme_color_head">Colors used by current theme</p>';
		if($data)
			{
			$output .= '<div class="theme_color transparent" data-color="transparent" style="background-color:transparent;"></div>';
			foreach($colors as $color)
				{
					$output .= '<div class="theme_color" data-color="'. $color.'" style="background-color:#'. $color.';"></div>';
				}
			}
		else
			$output .= '<p class="theme_color_head"><strong>Sorry, stylesheet could not be read.</strong></p>';
		
		$output .= '</div>';	
		
		$output .= '<div class="visual_form_settings" style="display:none;top:'.$settings_pos[0].';left:'.$settings_pos[1].';" >';
		
			$output .= '<div class="css" style="display:none;"></div>';
			
			$output .= '<div class="over_object" style="display:none;"></div>';
			$output .= '<div class="current_object" style="display:none;"></div>';
							
			$output .= '<div class="none" id="none" style="display:none;"></div>';
			
			$output .= '<div class="head" title="move"><div class="minimize" title="Minimize"></div></div>';
			$output .= '<div class="current_edit">Currently styling:</div>';
			$output .= '<div class="element_title">none</div>';
			
			$output .= '<div class="visual_settings" id="visual_settings">';
				
				///////////////////* FONT */////////////////////////////
				$output .= '<h3>Font</h3>';
					$output .= '<div>';
						$output .= '<div class="setting-holder">';
							$output .= '<label>Font Style:</label>';
							$output .= '<div class="font-styles text-decoration"><u>U</u></div>';
							$output .= '<div class="font-styles font-style"><em>I</em></div>';							
							$output .= '<div class="font-styles font-weight"><strong>B</strong></div>';
						$output .= '</div>';
						
						$output .= '<div class="setting-holder select">';
							$output .= '<label for="border-style">Font Family:</label>';
							$output .= '
							<select name="font-family" id="font-family" class="slider">
								<optgroup class="browser-safe-fonts" label="Browser-safe fonts">
									<option value="">Default</option>
									<option value="Arial">Arial</option>
									<option value="Comic Sans MS">Comic Sans MS</option>
									<option value="Courier New">Courier New</option>
									<option value="Georgia">Georgia</option>
									<option value="Impact">Impact</option>
									<option value="Times New Roman">Times New Roman</option>
									<option value="Trebuchet MS">Trebuchet MS</option>
									<option value="Verdana">Verdana</option>
								</optgroup>
								';
							if($data)
								{
								$output .= '<optgroup class="single-fields" label="Fonts used by current theme">';
									foreach($fonts  as $font)
										{
										$font 		= str_replace('"','',$font);
										$font 		= str_replace(';','',$font);
										$font_style = explode(':',$font);
										$fonts 		= explode(',',$font_style[1]);
										
										foreach($fonts as $single_font)
											$individual_fonts_array[$single_font] = $single_font;
										}
										
									$individual_fonts = array_unique($individual_fonts_array);
																	
									foreach($individual_fonts as $individual_font)				
										$output .= '<option value="'.trim($individual_font).'">'.$individual_font.'</option>';
							
								$output .= '</optgroup>';
								}
						$output .= '</select>';
						$output .= '</div>';
						$output .= '<div class="setting-holder select">';
							$output .= '<label for="border-style">Text Aling:</label>';
							$output .= '
							<select name="text-align" id="text-align" class="slider">
								<option value="none">None</option>
								<option value="left">Left</option>
								<option value="right">Right</option>
								<option value="center">Center</option>
								<option value="justify">Justify</option>
							</select>';
						$output .= '</div>';
						$output .= '<div class="setting-holder color">';
							$output .= '<label for="color">Color:</label>';
							$output .= '<input type="text" value="" id="color" name="color" class="color">';
						$output .= '</div>';
						$output .= '<div class="setting-holder">';
							$output .= '<label for="font-size">Font Size:</label>';
							$output .= '<input name="font-size" id="font-size" class="slider" type="text"/>';
							$output .= '<div class="slider" id="slider-font-size"></div>';
						$output .= '</div>';
						$output .= '<div class="setting-holder">';
							$output .= '<label for="line-height">Line Height:</label>';
							$output .= '<input name="line-height" id="line-height" class="slider" type="text"/>';
							$output .= '<div class="slider" id="slider-line-height"></div>';
						$output .= '</div>';
						$output .= '<div class="setting-holder">';
							$output .= '<label for="letter-spacing">Letter Spacing:</label>';
							$output .= '<input name="letter-spacing" id="letter-spacing" class="slider" type="text"/>';
							$output .= '<div class="slider" id="slider-letter-spacing"></div>';
						$output .= '</div>';
						$output .= '<div class="setting-holder select">';
							$output .= '<label for="text-transform">Text Transform:</label>';
							$output .= '
							<select name="text-transform" id="text-transform" class="slider">
								<option value="none">Default</option>
								<option value="uppercase">Uppercase</option>
								<option value="lowercase">Lowercase</option>
								<option value="capitalize">Capitalize</option>
							</select>';
						$output .= '</div>';
					$output .= '</div>';
					
			///////////////////* DIMENTIONS */////////////////////////////
				 $output .= '<h3>Dimentions</h3>';
				 $output .= '<div>';
					$output .= '<div class="setting-holder">';
						$output .= '<label for="width">Width:</label>';
						$output .= '<input name="width" id="width" class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-width"></div>';
					$output .= '</div>';
					$output .= '<div class="setting-holder">';
						$output .= '<label for="height">Height:</label>';
						$output .= '<input name="height" id="height" class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-height"></div>';
					$output .= '</div>';
				 $output .= '</div>';	
			
			///////////////////* BACKGROUND */////////////////////////////
				 $output .= '<h3>Background</h3>';
				 $output .= '<div>';
					
					$output .= '<div class="setting-holder color">';
						$output .= '<label for="background-color">Background Color:</label>';
						$output .= '<input type="text" value="" id="background-color" name="background-color" class="color">';
					$output .= '</div>';
					/*$output .= '<div class="setting-holder select">';
						$output .= '<label for="display">Display:</label>';
						$output .= '
						<select name="display" id="display" class="slider">
							<option value="block">Yes</option>
							<option value="none">No</option>
						</select>';
					$output .= '</div>';*/
					
					
				 $output .= '</div>';
				
				///////////////////* PADDING */////////////////////////////
				 $output .= '<h3>Padding</h3>';
				 $output .= '<div>';
					$output .= '<div class="setting-holder">';
						$output .= '<label for="padding-top">Padding Top:</label>';
						$output .= '<input name="padding-top" id="padding-top"  class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-padding-top"></div>';
					$output .= '</div>';
					
					$output .= '<div class="setting-holder">';
						$output .= '<label for="padding-right">Padding Right:</label>';
						$output .= '<input name="padding-right" id="padding-right"  class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-padding-right"></div>';
					$output .= '</div>';
					
					$output .= '<div class="setting-holder">';
						$output .= '<label for="padding-bottom">Padding Bottom:</label>';
						$output .= '<input name="padding-bottom" id="padding-bottom"  class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-padding-bottom"></div>';
					$output .= '</div>';
					
					$output .= '<div class="setting-holder">';
						$output .= '<label for="padding-left">Padding Left:</label>';
						$output .= '<input name="padding-left" id="padding-left"  class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-padding-left"></div>';
					$output .= '</div>';
				 $output .= '</div>';	
					
				 ///////////////////* MARGIN */////////////////////////////
				 $output .= '<h3>Margin</h3>';
				 $output .= '<div>';
					$output .= '<div class="setting-holder">';
						$output .= '<label for="margin-top">Margin Top:</label>';
						$output .= '<input name="margin-top" id="margin-top"  class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-margin-top"></div>';
					$output .= '</div>';
					
					$output .= '<div class="setting-holder">';
						$output .= '<label for="margin-right">Margin Right:</label>';
						$output .= '<input name="margin-right" id="margin-right"  class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-margin-right"></div>';
					$output .= '</div>';
					
					$output .= '<div class="setting-holder">';
						$output .= '<label for="margin-bottom">Margin Bottom:</label>';
						$output .= '<input name="margin-bottom" id="margin-bottom"  class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-margin-bottom"></div>';
					$output .= '</div>';
					
					$output .= '<div class="setting-holder">';
						$output .= '<label for="margin-left">Margin Left:</label>';
						$output .= '<input name="margin-left" id="margin-left"  class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-margin-left"></div>';
					$output .= '</div>';
				$output .= '</div>';
				
				
				
				$output .= '<h3>Border</h3>';
				$output .= '<div>';
					$output .= '<div class="setting-holder select">';
						$output .= '<label for="border-style">Border Style:</label>';
						$output .= '
						<select name="border-style" id="border-style" class="slider">
							<option value="none">None</option>
							<option value="solid">Solid</option>
							<option value="dashed">Dashed</option>
							<option value="dotted">Dotted</option>
						</select>';
					$output .= '</div>';
					$output .= '<div class="setting-holder color">';
						$output .= '<label for="border-color">Border Color:</label>';
						$output .= '<input type="text" value="#999999" id="border-color" name="border-color" class="color">';
					$output .= '</div>';
					$output .= '<div class="setting-holder">';
						$output .= '<label for="border-width">Border Width:</label>';
						$output .= '<input name="border-width" id="border-width" class="slider" type="text"/>';
						$output .= '<div class="slider" id="slider-border-width"></div>';
					$output .= '</div>';
				$output .= '</div>';
			
			///////////////////* GENERAL */////////////////////////////
				 $output .= '<h3>Advanced</h3>';
				 $output .= '<div>';
					$output .= '<div class="setting-holder select">';
						$output .= '<label for="float">Float</label>';
						$output .= '
						<select name="float" id="float" class="slider">
							<option value="none">None</option>
							<option value="left">Left</option>
							<option value="right">Right</option>
						</select>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
			$output .=	'<p class="save_visual_settings">
							<p class="save_options"><strong>Saving options</strong><br />
							<input type="radio" name="save_for" value="form_only" id="form_only" checked="checked"><label for="form_only">Save visual settings for <strong>this form only</strong>.</label><br />
							<input type="radio" name="save_for" value="all" id="all"><label for="all">Save visual settings for <strong>all forms</strong>.</label>
							</p>
						<input type="button" value="    Save Settings   " id="button" class="button save_visual_settings" data-action="save_visual_settings" name="save_visual_settings">
						<input type="button" value="Restore Defaults" id="button" class="button revert_default" data-action="revert_default" name="revert_default">
						</p>';
		$output .= '</div>';
		
		return $output;	
	}
	
	/***************************************/
	/***********   Forms Page   ************/
	/***************************************/
	public function add_new(){
		
		$template 		= new IZC_Template();
		$config 		= new WAFormBuilder_Config();
		$db 			= new IZC_Database();
//		$modules		= new IZC_Modules();
	
		$db->module_table = IZC_Functions::format_name($config->plugin_name);
		$db->get_module_table();
		
		$template->table = 'izm_'.IZC_Functions::format_name($config->plugin_name);
		
		$template->plugin_alias = $config->plugin_alias;
		
		$template->form_fields = 
			array
				(
				'Title'=> 
					array
						(
						'type'	=>'text',
						'name'	=>'title',
						),
				'Description'=> 
					array
						(
						'type'	=>'textarea',
						'name'	=>'description',
						),
				'Mail To'=> 
					array
						(
						'type'	=>'text',
						'name'	=>'mail_to',
						)
				);
		//Add linked modules to end of array		
		//$modules->get_linked_modules('izm_'.$db->module_table,$config->plugin_alias,$template);

		$this->data_fields = $template->form_fields;
		return $template->build_form();
	}
	
	public function list_data(){
		
		global $wpdb;
		
		$config 	= new WAFormBuilder_Config();
		$template 	= new IZC_Template();
		
		$template->table = 'izm_'.IZC_Functions::format_name($config->plugin_name);
		
		$i = 0;
		foreach($this->data_fields as $header=>$val)	
			{ 
			if($header!='Parent')
				{
				$is_foreing_key = $wpdb->query('SHOW FIELDS FROM '.$wpdb->prefix . $template->table. ' LIKE "'.IZC_Functions::format_name($header).'_Id"');
				$headers[$i] = ($is_foreing_key) ? IZC_Functions::format_name($header).'_Id': $header;
				$i++;
				}
			}
		
		$template->data_fields = $headers;
		$output  = $template->build_data_list();
		
		$output .= IZC_Functions::add_js_function('populate_list(\''.json_encode($headers).'\',\'izm_'.IZC_Functions::format_name($config->plugin_name).'\',\''.$_GET['page'].'\',\''.$config->plugin_alias.'\')');
		
		return $output;
	}
	
	public function forms_data(){
		
		global $wpdb;
		
		
		$output = '';
		
		$output .= '<div class="">';
			$output .= '<div id="widgets-right" style="width:100%;">';
				$output .= '<div class="widgets-holder-wrap " id="available-widgets">';
					$output .= '<div class="sidebar-name">';
						$output .= '<div class="sidebar-name-arrow">';
						$output .= '<br>';
						$output .= '</div>';
							$output .= '<h3>';
								$output .= 'Forms';
							$output .= '</h3>';
					$output .= '</div>';

					$output .= '<div class="widget-holder draggable_forms">';
						$output .= '<p class="description">Drag the forms below to the dropable area (table).</p>';
						
						$forms = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_wa_form_builder');

						foreach($forms as $form)
							{
							$output .= '<div id="'.$form->Id.'" class="widget draggable-form ui-draggable" data-form-id="'.$form->Id.'">
								<div class="widget-top"><div class="widget-title"><h4 style="float:left;">'.$form->title.'</h4>
								</div></div></div>';	
							}

						$output .= '<br class="clear">';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
		
		$output .= '<div class="col-wrap">';
		
		
		
		$output .= IZFForms::biuld_form_data_table();
		
		$output .= 	'</div>';
		
		
		
		return $output;
				
	}
	
	public function biuld_form_data_table(){
		
		global $wpdb;
		
		$form_fields = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_wa_form_builder WHERE Id='.(($_POST['form_Id']) ? $_POST['form_Id'] : '0'));
		
		$form_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_wa_form_meta WHERE wa_form_builder_Id='.(($_POST['form_Id']) ? $_POST['form_Id'] : '0').' ORDER BY time_added');
		
		$form_field_array = json_decode($form_fields->form_fields,true);
		
		foreach($form_field_array as $header=>$val)	
				{ 
				if($header=='name' || $header=='Name')
					$header = '_name';
				
				if($val['origen']=='filter')
					$headers[$i] = IZC_Functions::format_name($header).'_Id';
				elseif($val['origen']=='default')
					$headers[$i] = IZC_Functions::format_name($header);
				else
					{
					if($val['type']=='textgroup')
						{
						foreach($val['items'] as $item)
							{
							$headers[$i] = IZC_Functions::format_name(''.$header.'__'.$item['val']);
							$i++;
							}
						}
					elseif($val['type']=='check')
						{
						foreach($val['items'] as $item)
							{
							$headers[$i] = IZC_Functions::format_name(''.$header.'__'.$item);
							$i++;
							}
						}
					else
						{
						$headers[$i] = ''.IZC_Functions::format_name($header);
						}
					}
				$i++;
				}
		
		/*echo '<pre>';
		print_r($form_field_array);
		echo '</pre>';*/
		
		//$template = new IZC_Template();
		
		$output .= '<p><strong>Currently viewing form entries for:</strong> '.(($_POST['form_Id']) ? '<h1>'.IZC_Database::get_title($_POST['form_Id'].'</h1>','wap_wa_form_builder').'</h1>' : 'None<br /><em>Drag and drop forms from above and drop them in area marked "DROP HERE" below.</em>' ).'</p>';
			$output .= '<form method="post" action="" id="posts-filter">';
				//$output .= '<input type="hidden" name="table" value="'.$this->component_table.'">';
				
				$output .= '<div class="tablenav top">';

					/*$output .= '<div class="alignleft actions">';
						$output .= '<select name="action">';
							$output .= '<option selected="selected" value="-1">Bulk Actions</option>';
							$output .= '<option value="batch-delete">Delete</option>';
						$output .= '</select>';
						$output .= '<input type="submit" value="Apply" class="button-secondary action" id="doaction" name="">';
					$output .= '</div>';
					*/
					
					
					/*$output .= '<div class="table-options">';
						
						$output .= '<div class="tab">';
							$output .= '<p>Table Options</p>';						
						
							$output .= '<div class="hide-cols-wrapper">';	
								$i = 0;
								
								foreach($headers as $header)	
									{
									$output .= '<span class="the-col">';
									$output .= '<input id="'.$header.'_'.$i.'" type="checkbox" name="'.$header.'" value="'.$i.'" checked="checked" />&nbsp;&nbsp;';
									$output .= '<label for="'.$header.'_'.$i.'">'.IZC_Functions::unformat_name(str_ireplace('id','',$header)).'</label>';
									$output .= '</span>';
									$i++;
									}
							$output	.= '</div>';
						$output	.= '</div>';
					$output	.= '</div>';
				*/
					$output	.= '<div class="tablenav-pages">';
					//Populated from Ajax response: build_admin_table_pagination
					$output	.= '</div>';
					
				$output .= '</div>';
	
				$output .= '<br class="clear">';
$output .= '<div class="drop_area" '.((!$_POST['form_Id']) ? 'style="display:block"' : '').'><p>DROP HERE</p></div>';
				$output .= '<table cellspacing="0" class="wp-list-table resiable-columns widefat fixed tags iz-list-table resizabletable" id="iz_col_resize" '.((!$_POST['form_Id']) ? 'style="display:none"' : '').'>';
				
					$output .= '<thead>';
					$output .= '<tr>';
					//$output .= '<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>';	
					
					/*foreach($this->data_fields as $header)	
							{
							$output .= '<th valign="bottom" class="manage-column sortable '.((isset($_REQUEST['orderby'])) ? (($_REQUEST['order']=='desc' && $_REQUEST['orderby']==$header) ? 'desc' : 'asc') : 'desc').'  column-'.$header.'"><a href="?page='.$_REQUEST['page'].'&orderby='.$header.'&amp;order='.(($_REQUEST['order']=='asc') ? 'desc' : 'asc').'"><span>'.IZC_Functions::unformat_name(str_replace('Id','',$header)).'</span><span class="sorting-indicator"></span></a></th>';
							}*/
							
					//echo '<pre>';
					//print_r($this->data_fields);
					foreach($headers as $header)	
							{
					
								$output .= '<th valign="bottom" class="manage-column"><span class="">'.IZC_Functions::unformat_name(str_replace('Id','',str_ireplace('dynamic_forms','',$header))).'</span></th>'; //<span class="sorting-indicator"></span>
							}
					//$output .= '<th class="manage-column column-cb check-column" scope="col"></th>';	
					$output .= '</tr>';
					$output .= '</thead>';
					
					$output .= '<tfoot>';
					$output .= '<tr>';
					//$output .= '<th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>';
					
					foreach($headers as $header)	
							{
							$output .= '<th valign="bottom" class="manage-column"><span class="">'.IZC_Functions::unformat_name(str_replace('Id','',str_ireplace('dynamic_forms','',$header))).'</span></th>'; //<span class="sorting-indicator"></span>
							}
					
					//$output .= '<th class="manage-column column-cb check-column" scope="col"></th>';	
					$output .= '</tr>';
					$output .= '</tfoot>';
					
					$output .= '<tbody class="list:tag" id="the-list">';
					//Populated from Ajax response
					$output .= '</tbody>';
				
				$output .= '</table>';
				
				$output .= '<div class="tablenav top">';
				$output .= '<div class="alignleft actions">';
				$output .= '<select name="action2">';
				$output .= '<option selected="selected" value="-1">Bulk Actions</option>';
				$output .= '<option value="batch-delete">Delete</option>';
				$output .= '</select>';
				$output .= '<input type="submit" value="Apply" class="button-secondary action" id="doaction" name="">';
				$output .= '</div>';
				
				$output	.= '<div class="tablenav-pages">';
				//Populated from Ajax response: build_admin_table_pagination
				$output	.= '</div>';
				
				$output .= '<br class="clear"></div>';
			
			$output .= '</form>';
			
			$output .= "<input type='hidden' name='additional_params' value='".json_encode($additional_params)."'>";
			$output .= "<input type='hidden' name='table_headers' value='".json_encode($headers)."'>";
			$output .= '<input type="hidden" name="page" value="'.$_REQUEST['page'].'">';
			$output .= '<input type="hidden" name="orderby" value="">';
			$output .= '<input type="hidden" name="order" value="desc">';
			$output .= '<input type="hidden" name="current_page" value="0">';
			$output .= '<input type="hidden" name="wa_form_Id" value="'.$_POST['form_Id'].'">';
		
		$output .=  IZC_Functions::add_js_function('
			
			jQuery(document).ready(
			function ()
				{
				populate_form_data_list(\''.json_encode($headers).'\',\''.$config->plugin_table.'\',\''.$_GET['page'].'\',\'\',\'\',\''.$_POST['form_Id'].'\')
				}
			);
			
			
			');
		if($_POST['form_Id'])	{
			echo $output;
			die();
		}
		else
			return $output;
	}
	
	
	public function get_form_data(){

		global $wpdb;
		
		$args 		= str_replace('\\','',$_POST['args']);
		$headings 	= json_decode($args);

		
		$sql = 'SELECT * FROM '.$wpdb->prefix.'wap_wa_form_meta WHERE wa_form_builder_Id='.$_POST['form_Id'].' GROUP BY time_added ORDER BY time_added DESC
										LIMIT '.((isset($_POST['current_page'])) ? $_POST['current_page']*10 : '0'  ).',10 ';
		$results 	= $wpdb->get_results($sql);
		
		
		//$debug .= '<tr>';	
		//$debug .= '<td></td><td class="manage-column" colspan="'.(count($headings)).'">'.$sql.'</td>';
		//$debug .= '</tr>';		
		//echo $debug;

		
		//var_dump($headings );
		if($results)
			{
			$i = 1;			
			foreach($results as $data)
				{
				$old_record = $data->last_update;	
				
				if($new_record!=$old_record && $i!=1)
					{
					//$output .= '<th class="expand" scope="row"></th>';
					$output .= '</tr>';	
					}
				
				if($new_record!=$old_record)
					{
					$output .= '<tr class="row parent" id="tag-'.$data->Id.'">';
					//$output .= '<th class="check-column" scope="row"><input type="checkbox" value="'.$data->Id.'" name="checked[]"></th>';
					}
					$k =1;
					foreach($headings as $heading)	
						{
						//if($data->meta_key == $heading)
						//	$output .= '<td class="manage-column column-'.$heading.'">'.$data->meta_value.'&nbsp;</td>'; 
						//else
						//	{
							
							$check_field = $wpdb->get_row('SELECT meta_key,meta_value FROM '.$wpdb->prefix.'wap_wa_form_meta WHERE meta_key="'.$heading.'" AND time_added="'.$data->time_added.'"');
							
							if($check_field)
								{
								
								$output .= '<td class="manage-column column-'.$heading.'">'.$check_field->meta_value.'&nbsp;';
								$output .= (($k==1) ? '<div class="row-actions"><span class="delete"><a href="javascript:delete_form_entry(\''.$data->time_added.'\',\''.$data->Id.'\');" >Delete</a></span></div>' : '' ).'</td>';

								$output .= '</td>';
								}
							else
								{
								$output .= '<td class="manage-column column-'.$heading.'">&nbsp;'; 
								$output .= (($k==1) ? '<div class="row-actions"><span class="delete"><a href="javascript:delete_form_entry(\''.$data->time_added.'\',\''.$data->Id.'\');" >Delete</a></span></div>' : '' ).'</td>';
								}
						//	}
						$k++;
						}
					
					
				
				
				$new_record = $old_record;
				$i++;
				}
			}
		else
			{
			$output .= '<tr>';	
			$output .= '<td class="manage-column" colspan="'.(count($headings)).'">Sorry, No entires found</td>';
			$output .= '</tr>';
			}
			
		echo $output;
		die();
	}
	
	
	public function from_entries_table_pagination(){

		
		$total_records = IZFForms::get_total_form_entries($_POST['wa_form_Id']);
		
		$total_pages = ((is_float($total_records/10)) ? (floor($total_records/10))+1 : $total_records/10);
		
		$output .= '<span class="displaying-num">'.IZFForms::get_total_form_entries($_POST['wa_form_Id']).' items</span>';
		if($total_pages>1)
			{				
			$output .= '<span class="pagination-links">';
			$output .= '<a class="first-page wafb-first-page">&lt;&lt;</a>&nbsp;';
			$output .= '<a title="Go to the next page" class="wafb-prev-page prev-page">&lt;</a>&nbsp;';
			$output .= '<span class="paging-input"> ';
			$output .= '<span class="current-page">'.($_POST['current_page']+1).'</span> of <span class="total-pages">'.$total_pages.'</span>&nbsp;</span>';
			$output .= '<a title="Go to the next page" class="wafb-next-page next-page">&gt;</a>&nbsp;';
			$output .= '<a title="Go to the last page" class="wafb-last-page last-page">&gt;&gt;</a></span>';
			}
		echo $output;
		die();
	}
	
	public function get_total_form_entries($wa_form_Id){
		global $wpdb;
		$get_count  = $wpdb->get_results('SELECT Id FROM '.$wpdb->prefix .'wap_wa_form_meta WHERE wa_form_builder_Id='.$wa_form_Id.' GROUP BY time_added');

		return count($get_count);
		
		
		
	}
	
	public function delete_form_entry(){
		global $wpdb;
		
		$wpdb->query('DELETE FROM ' .$wpdb->prefix. 'wap_wa_form_meta WHERE time_added = "'.$_POST['last_update'].'"');

		IZC_Functions::print_message( 'updated' , 'Item deleted' );
		die();
	}
	
	/***************************************/
	/*******   Customizing Forms   *********/
	/***************************************/
	public function customize_forms(){
		
		$db 		= new IZC_Database();
		$template 	= new IZC_Template();
		$config 	= new WAFormBuilder_Config();

		//Data storage
		$output .= '<div class="saved_stamp"></div>';
		$output .= '<h3 style="display:none"><span class="db_action" >Add</span> Field</h3>';
		$output .= '<div class="form_Id" style="display:none"></div>';
		$output .= '<div class="backup"><div class="old-group-label"></div><div class="group-label"></div><div class="group-options"></div></div>';
		
		//Create Custom fields panel
		$output .= '<div class="">';
			$output .= '<div id="widgets-right" style="width:100%;">';
				$output .= '<div class="widgets-holder-wrap " id="available-widgets">';
					$output .= '<div class="sidebar-name">';
						$output .= '<div class="sidebar-name-arrow">';
						$output .= '<br>';
						$output .= '</div>';
							$output .= '<h3>';
								$output .= 'Create a new custom field';
							$output .= '</h3>';
					$output .= '</div>';

					$output .= '<div class="widget-holder create_fields">';
						$output .= '<p class="description"></p>';
						
						//Step - 1: Selecting a field type
						$output .= '<div class="custom-fields-container left active">';
							$output .= '<div class="step">1</div>';
							$output .= '<div class="custom-fields left " >';
								$output .= '<select name="field-type" id="field-type" >';
									$output .= '<option value="0"			>---- Select Field Type----</option>';
									$output .= '<optgroup label="Single value fields" class="single-fields">';											
										$output .= '<option value="text"		>&nbsp;&nbsp;Textfield</option>';								
										$output .= '<option value="textarea"	>&nbsp;&nbsp;Textarea</option>';									
										$output .= '<option value="dropdown"	>&nbsp;&nbsp;Dropdown list</option>';
										$output .= '<option value="radio"		>&nbsp;&nbsp;Radio button Group</option>';									
									$output .= '</optgroup>';
									/*$output .= '<optgroup label="Multi value fields" class="multi-fields">';										
										$output .= '<option value="textgroup"	>&nbsp;&nbsp;Textfield Group</option>';
										$output .= '<option value="check"		>&nbsp;&nbsp;Checkbox Group	</option>';
									$output .= '</optgroup>';*/						
								$output .= '</select>';
							$output .= '</div>';
						$output .= '</div>';
						
						//Step - 2: Creating a group
						$output .= '<div class="custom-fields-container mid">';
							$output .= '<div class="step">2</div>';
							$output .= '<div class="custom-fields mid">';
							//populated by wp_ajax_create_group
							$output .= '</div>';
						$output .= '</div>';
						
						//Step - 3: Adding options to a group
						$output .= '<div class="custom-fields-container right">';
							$output .= '<div class="step">3</div>';
							$output .= '<div class="custom-fields right">';
							//populated by wp_ajax_add_group_options							
							$output .= '</div>';
						$output .= '</div>';

						$output .= '<br class="clear">';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
		//END: Create Custom fields panel	
		
		//Froms canves
		$output .= '<div class="widget-liquid-left">';
			$output .= '<div id="widgets-left">';
				$output .= '<div class="widgets-holder-wrap ui-droppable" id="available-widgets">';
					$output .= '<div class="widgets-holder-wrap">';
					
						$output .= '<div class="sidebar-name">';
							$output .= $template->build_dropdown($this->plugin_table,'Select a form to edit:',$this->plugin_alias,'--- Create a New Form ---').'<h3 style="float:left;">';
							$output .= '<span>';
							//$output .= '<img class="ajax-feedback" alt="" title="" src="'.get_option('siteurl').'wp-admin/images/wpspin_dark.gif">';
							$output .= '</span>';
							$output .= '</h3><div style="float:right;padding:4px 8px 0 0;"><div class="save_form_as sidebar-name"><span class="save_form_as">Save Form As:&nbsp;&nbsp;<input type="text" name="form_title"></span><input type="button" id="iz-save-form" class="button" onclick="save_form(this);" value="     Save Form    "></div></div><div class="clear"></div>';
						$output .= '</div>';
						
						$output .= '<div id="primary-widget-area" style="border:1px solid #CCC; padding:10px;">';
							$output .= '<div class="sidebar-description">';
								$output .= '<div class="description"><em></em></div>';

								$output .= '<div class="drop-sort iz-forms-holder" style="min-height:400px; border:1px solid #ccc;">';
								//populated by wp_ajax_populate_form_fields
								$output .= '</div>';
								
								$output .= '<div style="float:right;padding:10px 0 0 0;"><div class="save_form_as sidebar-name"><span class="save_form_as">Save Form As:&nbsp;&nbsp;<input type="text" name="form_title"></span><input type="button" id="iz-save-form" class="button" onclick="save_form(this);" value="     Save Form    "></div></div>';
								
							$output .= '<br class="clear">';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
		//END: Froms canves	
		
		//Fields container	
		$output  .= '<div class="widget-liquid-right">';
			$output .= '<div id="widgets-right">';

				//Custom Fields
				$output .= '<div class="widgets-holder-wrap iz-holder" id="available-widgets">';
					$output .= '<div class="sidebar-name">';
						$output .= '<div class="sidebar-name-arrow">';
						$output .= '<br>';
						$output .= '</div>';
							$output .= '<h3>';
								$output .= 'Custom fields';
							$output .= '</h3>';
					$output .= '</div>';

					$output .= '<div class="widget-holder">';
						$output .= '<p class="description">Drag a form element from here to the form on the left to add and order them.</p>';
						$output .= '<div id="widget-list" class="iz-custom-form-fields">';

						$output .= $this->get_custom_fields();		
												
						$output .= '</div>';
						$output .= '<br class="clear">';
					$output .= '</div>';
				$output .= '</div>';
				//END: Custom Fields
				
				//Default Fields
				/*$output .= '<div class="widgets-holder-wrap iz-holder" id="available-widgets">';
					$output .= '<div class="sidebar-name">';
						$output .= '<div class="sidebar-name-arrow">';
						$output .= '<br>';
						$output .= '</div>';
							$output .= '<h3>';
								$output .= 'Default fields';
							$output .= '</h3>';
					$output .= '</div>';

					$output .= '<div class="widget-holder">';
						$output .= '<p class="description">Drag a form element from here to the form on the left to add and order them.</p>';
						$output .= '<div id="widget-list" class="iz-default-form-fields">';

						$output .= $this->get_default_fields();		
												
						$output .= '</div>';
						$output .= '<br class="clear">';
					$output .= '</div>';
				$output .= '</div>';*/
				//END: Default Fields
				 
				//Module Fields
				/*$output .= '<div class="widgets-holder-wrap iz-holder" id="available-widgets">';
					$output .= '<div class="sidebar-name">';
						$output .= '<div class="sidebar-name-arrow">';
						$output .= '<br>';
						$output .= '</div>';
							$output .= '<h3>';
								$output .= 'Module fields';
							$output .= '</h3>';
					$output .= '</div>';

					$output .= '<div class="widget-holder">';
						$output .= '<p class="description">Drag a form element from here to the form on the left to add and order them.</p>';
						$output .= '<div id="widget-list" class="iz-module-form-fields">';
						
						$filters = get_option('iz-filters');
						if(!is_array($config->link_to_modules))
							$config->link_to_modules =array();
						foreach($filters[$config->plugin_alias] as $filter=>$val)
							{
							if(!array_key_exists($filter,$config->link_to_modules) && !empty($filter))
								{
								$output .= '<div data-filter-name="'.$filter.'" data-group-label="'.$filter.'" data-filter-type="'.$val['type'].'" data-field-origen="module" class="widget rightDrag  iz-draggable" id="">	';
									$output .= '<div class="widget-top">';
										$output .= '<div class="widget-title">';
											$output .= '<h4 style="float:left;">'.IZC_Functions::unformat_name($filter).' </h4>';
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';
								}
							}
												
						$output .= '</div>';
						$output .= '<br class="clear">';
					$output .= '</div>';
				$output .= '</div>';*/
				//END: Module Fields
				
			$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
    
	
	/***************************************/
	/*********   Populate Form   ***********/
	/***************************************/	
	public function get_form_fields($form_Id=''){
		
		global $wpdb;
		
		$Id 	= (isset($_POST['form_Id'])) ? $_POST['form_Id'] : $form_Id;
		$config = new WAFormBuilder_Config();

		
		$data 	= $wpdb->get_var('SELECT form_fields FROM '.$wpdb->prefix . $config->plugin_table. ' WHERE Id='.$Id);
		$fields = json_decode($data,true);
		
		if(is_array($fields))
			{
			foreach($fields as $field)
				{
				$output .= IZFForms::get_field_group($field['type'],$field['grouplabel'],$field['items'],json_encode( $field ),$field['origen'], $field['required'], $fields[$field['grouplabel']]);
				
				}
			}
		if(count($fields)<=0)
			{
			if($_POST['action']=='populate_form_fields')
				$output = '<p class="description no-fields">No fields have been added to this form. <br /><br />Please <strong>drag fields</strong> from the Custom fields sidebar, <strong>drop them here</strong> and <strong>save</strong> the form! </p>';
			}
		if($_POST['action']=='populate_form_fields') { echo $output; die(); } else { return $output;}
	}
	
	//Call on field drop to form
	public function build_field_group() {
		
		global $wpdb;
		
		$output  .= '<div class="remove-field-group" title="Remove group" onclick="remove_custom_group(this);"></div>';
		$output  .= '<div class="sort-field-group" title="Drag to position"></div>';
		
		
		if($_POST['field_origen']!='' && $_POST['field_origen']!='undefined')
			{
			//Module fields
			if($_POST['field_origen']=='module')
				{
				$data_array = array
					(
					'grouplabel' 	=> $_POST['filter_name'],
					'type'			=> $_POST['filter_type'],
					'origen'		=> 'filter'
					);
				$output  .= '<div class="data-array">'.json_encode($data_array).'</div>';
	
				switch($_POST['filter_type'])
					{
					case 'dropdown':
						$output  .= '<fieldset class="'.$_POST['filter_type'].' '.$_POST['filter_name'].'">';
							$output  .= '<legend>'.IZC_Functions::unformat_name($_POST['filter_name']).'</legend>';
							$template = new IZC_Template();
							$output .= $template->build_dropdown($_POST['filter_name'],'none');
						$output  .= '</fieldset>';
					break;
					case 'radio':
										
					break;
					case 'text':
						$output  .= '<fieldset class="'.$_POST['filter_type'].' '.$_POST['filter_name'].'">';
							$output .= '<legend>'.IZC_Functions::unformat_name($_POST['filter_name']).'</legend>';
							$output .= '<input type="text" name="'.$_POST['filter_name'].'">';
						$output  .= '</fieldset>';
					break;
					case 'textarea':
						$output  .= '<fieldset class="'.$_POST['filter_type'].' '.$_POST['filter_name'].'">';
							$output .= '<legend>'.IZC_Functions::unformat_name($_POST['filter_name']).'</legend>';
							$output .= '<textarea name="'.$_POST['filter_name'].'"></textarea>';
						$output  .= '</fieldset>';
					break;
					case 'file':
						$output  .= '<fieldset class="'.$_POST['filter_type'].' '.$_POST['filter_name'].'">';
							$output .= '<legend>'.IZC_Functions::unformat_name($_POST['filter_name']).'</legend>';
							$output .= '<input type="file" name="'.$_POST['filter_name'].'">';
						$output  .= '</fieldset>';
					break;
					}
				}
			
			//defualt fields
			if($_POST['field_origen']=='default')
				{
				$config = new WAFormBuilder_Config();
				$field_groups = get_option('iz-default-fields',array());
				
				$label = $field_groups[IZC_Functions::format_name($config->plugin_alias)][$_POST['group_label']]['grouplabel'];
				if(is_array($field_groups[IZC_Functions::format_name($config->plugin_alias)][$_POST['group_label']]['items']))
					$items = array_reverse($field_groups[IZC_Functions::format_name($config->plugin_alias)][$_POST['group_label']]['items']);
				
				$field_groups[IZC_Functions::format_name($config->plugin_alias)][$_POST['group_label']]['origen'] = 'default';
				
				$check_required = $field_groups[IZC_Functions::format_name($config->plugin_alias)][$_POST['group_label']]['required'];			
				$type  = $field_groups[IZC_Functions::format_name($config->plugin_alias)][$_POST['group_label']]['type'];
				$output  .= '<div class="data-array">'.json_encode($field_groups[IZC_Functions::format_name($config->plugin_alias)][$_POST['group_label']]).'</div>';
					$output  .= '<fieldset class="'.$type.' '.IZC_Functions::format_name($label).'">';
						$output  .= '<legend>'.$label.' '.((!$check_required) ? '' : '*').'</legend>';
							$output .= IZC_Template::build_field($field_groups[IZC_Functions::format_name($config->plugin_alias)][$_POST['group_label']]);
					$output  .= '</fieldset>';	
				}
			}
		//Custom fields
		else
			{
			$config = new WAFormBuilder_Config();
			
			$field_groups = get_option('iz-forms-custom-fields',array());
			
			$field_groups[$_POST['group_label']]['grouplabel'] = str_replace('\\','',$field_groups[$_POST['group_label']]['grouplabel']);
			
			$label = $field_groups[$_POST['group_label']]['grouplabel'];
			
			
			
			if(is_array($field_groups[$_POST['group_label']]['items']))
				$items = array_reverse($field_groups[$_POST['group_label']]['items']);
			
			$type  = $field_groups[$_POST['group_label']]['type'];
			
			
			$required  = $field_groups[$_POST['group_label']]['required'];
			$output  .= '<div class="data-array">'.json_encode($field_groups[$_POST['group_label']]).'</div>';
				$output  .= '<fieldset class="'.$type.' ">';
					$output  .= '<legend>'.ucfirst($label).' '.(($required) ? '*' : '').'</legend>';
						$output .= IZC_Template::build_field($field_groups[$_POST['group_label']]);
				$output  .= '</fieldset>';
			}
		echo $output;
		die();
	}
	
	public function get_field_group($type='',$label='',$items=array(),$data_array=array(),$field_origen='custom',$field_required='',$args){


		$attr = json_decode($data_array,1);
		
		/*echo '<pre>';
		print_r($attr);
		echo '</pre>';*/
		
		$output  .= '<div class="iz-sortable" data-group-label="'.$label.'">';
		$output  .= '<div class="data-array">'.$data_array.'</div>';
		$output  .= '<div class="remove-field-group" onclick="remove_custom_group(this);" title="Remove group"></div>';
		$output  .= '<div class="sort-field-group" title="Drag to position"></div>';
			$output  .= '<fieldset id="fieldset" class="fieldset '.$type.' '.IZC_Functions::format_name($label).'">';
				$output  .= '<legend id="legends" class="legends">'.str_ireplace('_name','Name',$label).' '.(($field_required) ? '*' : '').'</legend>';		
					//Module fields
					if($field_origen=='filter')
						{		
						$template = new IZC_Template();
						$output .= '<div class="iz-form-item">';
						switch($type)
							{
							case 'dropdown':
							$output .= $template->build_dropdown($label,'none');
							break;
							case 'radio':
							break;
							case 'text':
							$output .= '<input type="text" name="'.$label.'">';
							break;
							case 'textarea':
							$output .= '<textarea name="'.$label.'"></textarea>';
							break;
							case 'file':
							$output .= '<input type="file" name="'.$label.'">';
							break;
							}
						$output .= '</div>';
						}
					//Default fields	
					elseif($field_origen=='default')
						{		
						$output .= IZC_Template::build_field($args);
						}
					//Custom fields
					else
						{
						$config = new WAFormBuilder_Config();
						
						$custom_items = $items;
	
						if($type=='check')
							{
							$custom_items = array();
							foreach($items as $item)
								$custom_items[$item] = IZC_Functions::format_name($config->plugin_name).'__'.IZC_Functions::format_name($label).'__'.IZC_Functions::format_name($item);
							}
						
						$output .= IZC_Template::build_field($args,$attr);
						}						
			$output  .= '</fieldset>';
		$output .= '</div>';
		
		return $output;	
	}
 
	

	/***************************************/
	/********   Creating Fields   **********/
	/***************************************/ 
	//Step 2: creating a group 
	public function create_group(){
		
		$config = new WAFormBuilder_Config();
		
		$custom_fields = get_option('iz-forms-custom-fields', array());
		
		if(!is_array($custom_fields))
			$custom_fields = array();
			
		switch($_POST['input'])
			{
			case 'text':
				$output .= '<input data-old-val="'.$_POST['old_value'].'" placeholder="Enter label and hit enter" type="text" id="label"  onChange="add_group_options();" name="group-label"><div class="gears"></div>';
				$output .= '<div class="group_element_attr" style="display:none;">';
					$output .= '<label>Required</label> <input data-attr="required" type="radio" name="field-req" value="required" '.(($custom_fields[$_POST['old_value']]['required']=='required') ? 'checked="checked"' : '').'>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;';
					$output .= '<input data-attr="required" type="radio" name="field-req" value="" '.(($custom_fields[$_POST['old_value']]['required']=='') ? 'checked="checked"' : '').'>&nbsp;&nbsp;No<br />';
					$output .= '<div class="devider"></div>';
					//$output .= '<label>Visibility</label><input data-attr="visibility" type="radio" name="field-visibility" value="Private" '.(($custom_fields[$_POST['old_value']]['visibility']=='Private') ? 'checked="checked"' : '').'>&nbsp;&nbsp;Private&nbsp;&nbsp;&nbsp;&nbsp;';
					//$output .= '<input data-attr="visibility" type="radio" name="field-visibility" value="Public" '.(($custom_fields[$_POST['old_value']]['visibility']=='Public' || $custom_fields[$_POST['old_value']]['visibility']=='') ? 'checked="checked"' : '').'>&nbsp;&nbsp;Public<br />';
					//$output .= '<div class="devider"></div>';
					$output .= '<label>Format</label><input data-attr="format" type="radio" name="field-format" value="text" '.(($custom_fields[$_POST['old_value']]['format']=='text' || $custom_fields[$_POST['old_value']]['format']=='') ? 'checked="checked"' : '').'>&nbsp;&nbsp;Text&nbsp;&nbsp;&nbsp;&nbsp;';
					$output .= '<input data-attr="format" type="radio" name="field-format" value="email" '.(($custom_fields[$_POST['old_value']]['format']=='email') ? 'checked="checked"' : '').'>&nbsp;&nbsp;Email&nbsp;&nbsp;&nbsp;&nbsp;';
					$output .= '<input data-attr="format" type="radio" name="field-format" value="number" '.(($custom_fields[$_POST['old_value']]['format']=='number') ? 'checked="checked"' : '').'>&nbsp;&nbsp;Number';
				$output .= '</div>';
			break;
			case 'textgroup':
				$output .= '<input placeholder="Enter label and hit enter" type="text" id="label"  onChange="add_group_options();" name="group-label"><br />';
			break;
			case 'textarea':
			case 'dropdown':
			case 'radio':
				$output .= '<input data-old-val="'.$_POST['old_value'].'" placeholder="Enter label and hit enter" type="text" id="label"  onChange="add_group_options();" name="group-label"><div class="gears"></div>';
				$output .= '<div class="group_element_attr" style="display:none;">';
					$output .= '<label>Required</label> <input data-attr="required" type="radio" name="field-req" value="required" '.(($custom_fields[$_POST['old_value']]['required']=='required') ? 'checked="checked"' : '').'>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;';
					$output .= '<input data-attr="required" type="radio" name="field-req" value="" '.(($custom_fields[$_POST['old_value']]['required']=='') ? 'checked="checked"' : '').'>&nbsp;&nbsp;No<br />';
					//$output .= '<div class="devider"></div>';
					//$output .= '<label>Visibility</label><input data-attr="visibility" type="radio" name="field-visibility" value="Private" '.(($custom_fields[$_POST['old_value']]['visibility']=='Private') ? 'checked="checked"' : '').'>&nbsp;&nbsp;Private&nbsp;&nbsp;&nbsp;&nbsp;';
					//$output .= '<input data-attr="visibility" type="radio" name="field-visibility" value="Public" '.(($custom_fields[$_POST['old_value']]['visibility']=='Public' || $custom_fields[$_POST['old_value']]['visibility']=='') ? 'checked="checked"' : '').'>&nbsp;&nbsp;Public<br />';
				$output .= '</div>';
			break;
			case 'check':
				$output .= '<input onfocus="if(this.value==\'\'){ show_iz_tooltip(this,\'Enter group label and hit enter \'); }"  placeholder="Enter lable and hit enter" type="text" id="label" name="group-label" onChange="add_group_options();"><br />';
			break;
			}
		echo $output;
		die();
	}
	
	//Step 3: Adding group options 
	public function add_group_options(){
		
		$config = new WAFormBuilder_Config();
		
		switch($_POST['fieldtype'])
			{				
			case 'text':
			case 'textarea':
				$output  = '<input  onclick="save_field(this,\'save\',jQuery(\'input[name=group-label]\').val());clear_all();" type="button" class="button" value="   Save Field  ">';
			break;
			
			case 'textgroup':
				$output .= '<input name="iz-add-option" onchange="add_new_option(this);" onfocus="if(this.value==\'\'){ show_iz_tooltip(this,\'Enter a value and hit enter\'); }" type="text" class="iz-add-options" placeholder="Add new textfield and hit enter" >';
				
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<ul class="opt-list iz-sortable"></ul>';
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<input name="save-field-button" onclick="save_field(this,\'save\',jQuery(\'input[name=group-label]\').val());" type="button" class="button" value="   Save Field  "><div style="float:right;padding-top:4px;"><a href="javascript:clear_option_list();" style="text-decoration:none;">Clear List</a></div>';
				break;
			case 'dropdown':
				$output .= '<input name="iz-add-option" onchange="add_new_option(this);" onfocus="if(this.value==\'\'){ show_iz_tooltip(this,\'Enter a value and hit enter\'); }" type="text" class="iz-add-options" placeholder="Add new option and hit enter" >';
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<ul class="opt-list iz-sortable"></ul>';
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<input name="save-field-button" onclick="save_field(this,\'save\',jQuery(\'input[name=group-label]\').val());" type="button" class="button" value="   Save Field  "><div style="float:right;padding-top:4px;"><a href="javascript:clear_option_list();" style="text-decoration:none;">Clear List</a></div>';
				break;
			case 'radio':
				$output .= '<input name="iz-add-option" onchange="add_new_option(this);" onfocus="if(this.value==\'\'){ show_iz_tooltip(this,\'Enter a value and hit enter\'); }" type="text" class="iz-add-options" placeholder="Add new radio button and hit enter" >';
				
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<ul class="opt-list iz-sortable"></ul>';
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<input name="save-field-button" onclick="save_field(this,\'save\',jQuery(\'input[name=group-label]\').val());" type="button" class="button" value="   Save Field  "><div style="float:right;padding-top:4px;"><a href="javascript:clear_option_list();" style="text-decoration:none;">Clear List</a></div>';
				break;
			case 'check':
				$output .= '<input name="iz-add-option" onchange="add_new_option(this);" onfocus="if(this.value==\'\'){ show_iz_tooltip(this,\'Enter a value and hit enter\'); }" type="text" class="iz-add-options" placeholder="Add new check box and hit enter" >';
				
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<ul class="opt-list iz-sortable"></ul>';
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<input name="save-field-button" onclick="save_field(this,\'save\',jQuery(\'input[name=group-label]\').val());" type="button" class="button" value="   Save Field  "><div style="float:right;padding-top:4px;"><a href="javascript:clear_option_list();" style="text-decoration:none;">Clear List</a></div>';
				break;
			default:
				$output .= '<input name="iz-add-option" onchange="add_new_option(this);" onfocus="if(this.value==\'\'){ show_iz_tooltip(this,\'Enter a value and hit enter\'); }" type="text" class="iz-add-options" placeholder="Add new item and hit enter" >';
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<ul class="opt-list iz-sortable"></ul>';
				$output .= '<div class="iz-spacer"></div>';
				$output .= '<input name="save-field-button" onclick="save_field(this,\'save\',jQuery(\'input[name=group-label]\').val());" type="button" class="button" value="   Save Field  "><div style="float:right;padding-top:4px;"><a href="javascript:clear_option_list();" style="text-decoration:none;">Clear List</a></div>';
			break;
			}
		echo $output;
		die();
	}
	
	
	/***************************************/
	/***********   DB Actions   ************/
	/***************************************/ 
	
	public function save_form(){
		
		global $wpdb;
		
		$config = new WAFormBuilder_Config();

		foreach($_POST['form_fields'] as $field=>$val)
			{
			$val = str_replace('\\','',$val);
			$field_attr = json_decode($val,true);
			$new_data[$field_attr['grouplabel']] = $field_attr;
			}
		
		if($_POST['db_action']!='update')
			{
			$insert = $wpdb->insert 
				( 
				$wpdb->prefix . $config->plugin_table, 
				array(
					'form_fields' 	=> json_encode($new_data),
					'title' 		=> $_POST['save_as'],
					'plugin' 		=> 'shared'
					 )
				);
			}
		else
			{
			$update = $wpdb->update 
				( 
				$wpdb->prefix . $config->plugin_table, 
				array('form_fields' => json_encode($new_data)), 
				array('Id' => $_POST['form_Id']),												 
				array('%s'), 
				array('%d')
				);
			}
		$wpdb->show_errors(); 
		$wpdb->print_error();
		die();
	}	
	
	public function save_field(){
		
		global $wpdb;
		
		$config 		= new WAFormBuilder_Config();
		$db 	 		= new IZC_Database();
		$plugin_alias 	= IZC_Functions::format_name($config->plugin_name);
		$group_options 	= array
			(
			$_POST['grouplabel'] => array
				(
				'grouplabel'		=>	str_replace('\\','',$_POST['grouplabel']),
				'old_grouplabel'	=>	$_POST['old_grouplabel'],
				'type'				=>	$_POST['type'],
				'required'			=>	$_POST['required'],
				'visibility'		=>	$_POST['visibility'],
				'format'			=>	$_POST['format'],
				'items'				=>	$_POST['items'],
				'old_items'			=>	$_POST['old_items'],
				'to_be_archived'	=>	$_POST['to_be_archived'],
				)
			);

		//Update table
		$group_label  		= IZC_Functions::format_name($group_options[$_POST['grouplabel']]['grouplabel']);
		$old_group_label 	= IZC_Functions::format_name($_POST['old_grouplabel']);
		
		switch($group_options[$_POST['grouplabel']]['type'])
			{
			case 'check':
			case 'textgroup':
				//Alter if exists
				foreach($group_options[$_POST['grouplabel']]['items'] as $item)
					{
					$db->plugin_table =  $config->plugin_alias;
					$custom_fields = $db->get_foreign_fields($plugin_alias);
					
					$new_item = $plugin_alias.'__'.$old_group_label.'__'.IZC_Functions::format_name($item);
					
					foreach($custom_fields as $custom_field)
						{
						
						$explode_column_name = explode('__',$custom_field);
						$prefix				 = $explode_column_name[0];
						$stored_group_name	 = $explode_column_name[1];
						$stored_field_name	 = $explode_column_name[2];
						
						$old_column_label = $prefix.'__'.$old_group_label.'__'.$stored_field_name;
						$new_column_label = $prefix.'__'.$group_label.'__'.$stored_field_name;

						if($prefix!='dynamic_forms_Id' && $stored_field_name)
							{
							//$wpdb->query('ALTER TABLE '.$wpdb->prefix .'wap_'. $config->plugin_alias.' 
							//CHANGE COLUMN  `'.$old_column_label.'` `'.$new_column_label.'` text');
							
							}
						}
					}
				//Add if not exist						
				foreach($group_options[$_POST['grouplabel']]['old_items'] as $item)
					{
					$new_item = $plugin_alias.'__'.$old_group_label.'__'.IZC_Functions::format_name($item);	
						
					//if(!in_array($new_item,	$custom_fields))
					//if(is_array($items))
					//	$wpdb->query('ALTER TABLE '.$wpdb->prefix .'wap_'. $config->plugin_alias .' ADD COLUMN '.$plugin_alias.'__'.$group_label.'__'.IZC_Functions::format_name($item['val']).' text');
					//else
					//	$wpdb->query('ALTER TABLE '.$wpdb->prefix .'wap_'. $config->plugin_alias .' ADD COLUMN '.$plugin_alias.'__'.$group_label.'__'.IZC_Functions::format_name($item).' text');	
					//echo 'ALTER TABLE '.$wpdb->prefix . $config->plugin_alias .' ADD COLUMN '.$plugin_alias.'__'.$group_label.'__'.IZC_Functions::format_name($item).' varchar(255) NULL DEFAULT NULL';		
					}
					
				//Archive	
				foreach($_POST['to_be_archived'] as $item)
					{
					$new_column = $plugin_alias.'__'.$group_label.'__'.IZC_Functions::format_name($item);
					
					//$wpdb->query('ALTER TABLE '.$wpdb->prefix .'wap_'. $config->plugin_alias.' 
					//CHANGE COLUMN  `'.$new_column.'` `'.$new_column.'__archive` text');	
					
					//echo 'ALTER TABLE '.$wpdb->prefix . $config->plugin_alias.' CHANGE COLUMN  `'.$new_column.'` `'.$new_column.'__archive` varchar(255) NULL DEFAULT NULL
					//';
					}
						
			break;
			default:
				//Alter if exists
					$db->plugin_table =  $config->plugin_alias;
					$custom_fields = $db->get_foreign_fields($plugin_alias);

					if($group_options[$_POST['grouplabel']]['old_grouplabel'])
						{
						//$wpdb->query('ALTER TABLE '.$wpdb->prefix .'wap_'. $config->plugin_alias.' CHANGE COLUMN  `'.$plugin_alias.'__'.$group_options[$_POST['grouplabel']]['old_grouplabel'].'` `'.$plugin_alias.'__'.$group_options[$_POST['grouplabel']]['grouplabel'].'` text');
						}
						
					
				//Add if not exist
				//$wpdb->query('ALTER TABLE '.$wpdb->prefix .'wap_'. $config->plugin_alias .' ADD COLUMN '.$plugin_alias.'__'.$group_label.' text');
				
				//Archive
				//WORKING
				
			break;
			}


		//Update form field
		$get_form_fields 	= $wpdb->get_results('SELECT Id,form_fields FROM '.$wpdb->prefix . $config->plugin_table);
		
		
		
		if(!is_array($form_fields))
			$form_fields = array();
		
		foreach($get_form_fields as $form)
			{
			$form_fields = json_decode($form->form_fields,true);
			
			if(in_array($form_fields[$_POST['old_grouplabel']], $form_fields))
				{
				unset($form_fields[$_POST['old_grouplabel']]);
				
				$new_fields = array_merge($group_options,$form_fields);
				
				
				print_r($group_options);
				print_r($form_fields);
				
				$update = $wpdb->update 
					( 
					$wpdb->prefix . $config->plugin_table, 
					array('form_fields' => json_encode($new_fields)), 
					array('Id' => $form->Id),												 
					array('%s'), 
					array('%d')
					);
				}
			}
		//update_current_meta_data
		
		$old_meta_key = IZC_Functions::format_name($_POST['old_grouplabel']);
		$new_meta_key = IZC_Functions::format_name($_POST['grouplabel']);
		
		if($old_meta_key=='name')
			$old_meta_key = '_name';
		if($new_meta_key=='name')
			$new_meta_key = '_name';
		
		$update_current_meta_data = $wpdb->query('UPDATE '.$wpdb->prefix . 'wap_wa_form_meta SET meta_key="'.$new_meta_key.'" where meta_key="'.$old_meta_key.'";'); 
					/*( 
					$wpdb->prefix . 'wap_wa_form_meta', 
					array('meta_key' => $new_meta_key), 
					array('wa_form_builder_Id' => $form->Id, 'meta_key' => $old_meta_key)
					);*/
		$wpdb->show_errors(); 
		echo '###'.$wpdb->print_error();
		//Update Custom fields
		$groups = get_option('iz-forms-custom-fields', array());
		
		if(in_array($groups[$_POST['old_grouplabel']], $groups))
			unset($groups[$_POST['old_grouplabel']]);

		$old_opt = $groups;
		if(!is_array($old_opt))
			$old_opt = array();
			
		$new_opt = array_merge($old_opt, $group_options);
		update_option('iz-forms-custom-fields', $new_opt);
		
		
		die();	
	}
	
	//Called from type check and textfield groups
	public function update_db_field(){
		
		global $wpdb;
		
		$config 		= new WAFormBuilder_Config();
		$db 	 		= new IZC_Database();
		$plugin_alias 	= IZC_Functions::format_name($config->plugin_name);
		
		$db->plugin_table 	=  $config->plugin_alias;
		$custom_fields 		= $db->get_foreign_fields($plugin_alias);
		
		foreach($custom_fields as $custom_field)
			{
			$explode_column_name = explode('__',$custom_field);
			$prefix				 = $explode_column_name[0];
			
			$old_column = $prefix.'__'.$_POST['grouplabel'].'__'.$_POST['old_value'];
			$new_column = $prefix.'__'.$_POST['grouplabel'].'__'.$_POST['new_value'];
			
			$wpdb->query('ALTER TABLE '.$wpdb->prefix .'wap_'. $config->plugin_alias.' 
			CHANGE COLUMN `'.IZC_Functions::format_name($old_column).'` `'.IZC_Functions::format_name($new_column).'` text');
			}
		}
	
	/***************************************/
	/*********   Custom Fields   ***********/
	/***************************************/
	public function get_custom_fields(){
	
		if(is_array(get_option('iz-forms-custom-fields')))
				{	
				$field_groups = array_reverse(get_option('iz-forms-custom-fields'));
				$fieldtype = array('Text fields'=>'text','Text areas'=>'textarea','Dropdown list'=>'dropdown','Radio button groups'=>'radio');
				foreach($fieldtype as $label=>$type)
					{
						
					$output .= '<div class="widgets-holder-wrap iz-holder" id="available-widgets">';
					$output .= '<div class="sidebar-name">';
						$output .= '<div class="sidebar-name-arrow">';
						$output .= '<br>';
						$output .= '</div>';
							$output .= '<h3 class="field_type_heading">';
								$output .= $label;
							$output .= '</h3>';
					$output .= '</div>';

					$output .= '<div class="widget-holder">';
						//$output .= '<p class="description">Drag a form element from here to the form on the left to add and order them.</p>';
						$output .= '<div id="widget-list" class="iz-custom-form-fields">';

						foreach($field_groups as $group=>$val)
						{
						if($type==$val['type'])
							{
							$output .= '<div data-group-label="'.$group.'" class="widget rightDrag  iz-draggable" id="">	';
								$output .= '<div class="widget-top">';
									$output .= '<div class="widget-title">';
										$output .= '<h4 style="float:left;">'.IZC_Functions::view_excerpt(ucfirst($group),20).'</h4><div class="custom-field-actions"><span class="edit"><span data-group-label="'.$group.'">Edit</span></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="delete"><span data-group-label="'.$group.'" href="#">Delete</span></span></div>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
							}
						}
												
						$output .= '</div>';
						$output .= '<br class="clear">';
					$output .= '</div>';
				$output .= '</div>';	
						
					//$output .= '<div class="field_type_heading">'.$label.'</div>';
					
					}
				}
				
			if($_POST['action']=='populate_custom_fields' ) { echo  $output; die(); } else { return $output; }
			
	}
	
	public function edit_custom_field(){
		$field_groups = get_option('iz-forms-custom-fields',array());
		echo json_encode($field_groups[$_POST['grouplabel']]);
		die();	
	}
	
	public function delete_custom_field(){

		global $wpdb;
		$config 		= new WAFormBuilder_Config();
		$forms		 	= $wpdb->get_results('SELECT Id,form_fields FROM '.$wpdb->prefix . $config->plugin_table);
		$plugin_alias 	= $config->plugin_alias;
		
		//Delete and add to archive
		$column = $plugin_alias.'__'.$_POST['grouplabel'];
		$fields = $wpdb->get_results("SHOW FIELDS FROM " . $wpdb->prefix . 'wap_'. $config->plugin_alias . " LIKE '%".$column."%'");
		foreach($fields as $field)
			{
			$wpdb->query('ALTER TABLE '.$wpdb->prefix .'wap_'. $config->plugin_alias.' 
			CHANGE COLUMN `'.$field->Field.'` `'.((strstr($field->Field,'__archive')) ?  $field->Field : $field->Field.'__archive').'` text');
			}
			
			
		//Delete from forms
		foreach($forms as $form){
			$form_fields = json_decode($form->form_fields,true);
			unset($form_fields[$_POST['grouplabel']]);
			$update = $wpdb->update 
				( 
				$wpdb->prefix . $config->plugin_table, 
				array('form_fields' => json_encode($form_fields)), 
				array('Id' => $form->Id),												 
				array('%s'), 
				array('%d')
				);
		}

		//Delete from Custom fields
		$field_groups = get_option('iz-forms-custom-fields',array());
		unset($field_groups[$_POST['grouplabel']]);
		update_option('iz-forms-custom-fields',$field_groups);
		die();
	}
	
	public function get_default_fields(){
		
		$config = new WAFormBuilder_Config();
		
		$default_fields = get_option( 'iz-default-fields', array());
		
		if(is_array($default_fields[IZC_Functions::format_name($config->plugin_alias)]))
				{	
				$field_groups = array_reverse($default_fields[IZC_Functions::format_name($config->plugin_alias)]);
				foreach($field_groups as $group=>$val)
					{
					$output .= '<div data-group-label="'.$group.'" class="widget rightDrag  iz-draggable" data-field-origen="default" id="">	';
						$output .= '<div class="widget-top">';
							$output .= '<div class="widget-title">';
								$output .= '<h4 style="float:left;">'.IZC_Functions::unformat_name($group).'</h4>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';		
					}
				}
			return $output;
	}
	
	public function get_form_Id(){
		global $wpdb;
		$id = $wpdb->get_var('SELECT dynamic_forms_Id FROM '.$wpdb->prefix.'wap_'. $_POST['table'] .' WHERE Id = '.$_POST['Id']);
		echo $id;
		die();
	}
}



function get_link_purpose($plugin_name)
		{	
		$config = new WAFormBuilder_Config();
		
		//Generate JS
		$filters = get_option('iz-filters',array());
		
		foreach($filters[$config->plugin_alias] as $filter=>$val)
			{
			if(!array_key_exists($filter,$config->link_to_modules))
				{
				$get_filters .= 'var args = new Array();
								';
				if($val['type']=='text')
					{
					$get_filters .= '	jQuery(\'input[name="'.$filter.'"]\').val(jQuery(\'input[name="get_'.$filter.'"]\').val());
									';
					}
				elseif($val['type']=='textarea')
					{
					$get_filters .= '	jQuery(\'textarea[name="'.$filter.'"]\').val(jQuery(\'input[name="get_'.$filter.'"]\').val());
									';
					}
				else
					{
					$get_filters .= '	args[\'filter_name\'] = \''.$filter.'\';
									 	core_object.trigger(\'get_filter_Id\', args);
									';
					}
				}
			}
		
		$js = ' jQuery(\'div.iz-forms-holder div.form-fields\').html(\'\');
					
				jQuery(\'select[name="'.$plugin_name.'_Id"]\').html(\'<option>  loading...</option>\');
				var data = 	{
					action	 	: \'populate_dropdown\',
					table		: \''.$plugin_name.'\',
					ajax	 	: true
					};
					
				jQuery.post(ajaxurl, data, function(response)
						{
						jQuery(\'select[name="'.$plugin_name.'_Id"]\').html(response);
						
						var args = new Array();
						args[\'filter_name\'] = \''.$plugin_name.'\';
						core_object.trigger(\'get_filter_Id\', args);
						}
					);

				//jQuery(\'div.iz-forms-holder div.module-fields\').html(\'<fieldset class="dropdown '.$plugin_name.'"><legend>'.$plugin_name.'</legend><div class="iz-form-item"><select name="'.$plugin_name.'_Id"></select></div></fieldset>\');
				
				
				jQuery(\'select[name="'.$plugin_name.'_Id"]\').change(
				function()
					{
					if(jQuery(this).val()==0)
						{
						jQuery(\'div.form-fields\').hide();
						}
					else
						{
						jQuery(\'div.form-fields\').show();
						get_form_id_from_'.$plugin_name.'(jQuery(this).val());
						}
					}
				);
				
				
				function get_form_id_from_'.$plugin_name.'('.$plugin_name.'_Id){
					if('.$plugin_name.'_Id!=0)
						{
						var data = 	{
						action	 	: \'get_form_Id\',
						Id	 		: '.$plugin_name.'_Id,
						table	 	: \''.$plugin_name.'\'
						};
					
						jQuery.post
							(ajaxurl, data, function(form_id)
								{
								var data = 	{
									action	 	: \'populate_form_fields\',
									form_Id	 	: form_id,
									edit_Id		: jQuery(\'div#edit_Id\').text(),
									plugin_table: jQuery(\'div#filter_plugin_alias\').text()
									};
									jQuery(\'div.iz-forms-holder div.form-fields\').html(\'<small>Loading...  </small>\');			
									
									jQuery.post(ajaxurl, data, function(response)
										{
										jQuery(\'div.iz-forms-holder div.form-fields\').html(response);
										'.$get_filters.'
										jQuery(\'input[type="file"]\').trigger(\'focus\');
										}
									);
								}
							);
						}
				}';	
		return $js;
		}


?>