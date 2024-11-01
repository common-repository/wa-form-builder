// JavaScript Document
if(tutorial == 'undefined' || tutorial==null)
	var tutorial = false;


jQuery(document).ready(
function()
	{
		
/*************************/
/***** Ready state *******/
/*************************/
	
	
	var pagination_data = {	
				table	 		: jQuery('div#component_table').text(),
				table_headers	: jQuery('input[name="fields"]').val(),
				page	 		: jQuery('input[name="page"]').val(),
				orderby	 		: jQuery('input[name="orderby"]').val(),
				order	 		: jQuery('input[name="order"]').val(),
				current_page	: jQuery('input[name="current_page"]').val(),
				form_Id			: jQuery('input[name="wa_form_Id"]').val()		
			}
	
	jQuery('a.wafb-next-page').live('click',
		function()
			{
			if((parseInt(pagination_data.current_page)+2) > parseInt(jQuery('span.total-pages').html()))
				 return false;
				 
			pagination_data.current_page ++;
			jQuery('input[name="current_page"]').val(pagination_data.current_page);
			//function populate_list(args,table,page,plugin_alias,additional_params)
			populate_form_data_list(pagination_data.table_headers,pagination_data.table,pagination_data.page,'','',jQuery('input[name="wa_form_Id"]').val());
			}
		);
	
	jQuery('a.wafb-prev-page').live('click',
		function()
			{
			if(parseInt(pagination_data.current_page)<=0)
				 return false;
			
			pagination_data.current_page--;
			jQuery('input[name="current_page"]').val(pagination_data.current_page);
			populate_form_data_list(pagination_data.table_headers,pagination_data.table,pagination_data.page,'','',jQuery('input[name="wa_form_Id"]').val());
			}
		);
	jQuery('a.wafb-first-page').live('click',
		function()
			{
			pagination_data.current_page = 0;
			jQuery('input[name="current_page"]').val(pagination_data.current_page);
			populate_form_data_list(pagination_data.table_headers,pagination_data.table,pagination_data.page,'','',jQuery('input[name="wa_form_Id"]').val());
			}
		);
		
	jQuery('a.wafb-last-page').live('click',
		function()
			{
			pagination_data.current_page = parseInt(jQuery('span.total-pages').html())-1;
			jQuery('input[name="current_page"]').val(pagination_data.current_page);
			populate_form_data_list(pagination_data.table_headers,pagination_data.table,pagination_data.page,'','',jQuery('input[name="wa_form_Id"]').val());
			}
		);

	core_object.bind
		('update_form_entry',
		function(args)
			{
			
			var data = 	
				{
				action	 			: 'from_entries_table_pagination',
				plugin_alias		: jQuery('input[name="plugin_alias"]').val(),
				wa_form_Id 			: jQuery('input[name="wa_form_Id"]').val(),
				table_headers		: jQuery('input[name="fields"]').val(),
				page	 			: jQuery('input[name="page"]').val(),
				orderby	 			: jQuery('input[name="orderby"]').val(),
				order	 			: jQuery('input[name="order"]').val(),
				current_page		: jQuery('input[name="current_page"]').val(),
				additional_params	: jQuery('input[name="additional_params"]').val()
				};
				
			
			//jQuery('tbody#the-list').html('<small>Loading...  </small><img align="absmiddle" src="../wp-content/plugins/Core/images/icons/wpspin_light.gif"></td></tr>');			
			jQuery.post
				(
				ajaxurl, data, function(response)
					{
					jQuery('div.tablenav-pages').html(response);
					args = new Array();
		args['filter_name'] = 'featured_image';
		core_object.trigger('get_filter_Id', args);
					}
				);
			}
		);

	//show_iz_tooltip(jQuery('select#wap_wa_form_builder_Id'), 'Select a form to edit.');	
	
	jQuery('select[name="wap_wa_form_builder_Id"] option[value="0"]').attr('selected', true);
	
	disable_form_editor();
	
	//Show hide panels
	jQuery('div.iz-holder div.sidebar-name').toggle
		(
		function()
			{
			jQuery(this).next().hide();	
			},
		function()
			{
			jQuery(this).next().show();	
			}
		);

	jQuery('div.gears').live('click',
		function()
			{
			jQuery('div.group_element_attr').hide();
			
				
			var obj_pos	= jQuery(this).position();
				if(!obj_pos)
					return false;
				
				element_attr = jQuery(this).next('div.group_element_attr');
				
				element_attr.css({
					position:'absolute'
					});
				element_attr.css('left',(obj_pos.left+jQuery(this).outerWidth())-jQuery('div.group_element_attr').outerWidth());
				element_attr.css('top',obj_pos.top-jQuery('div.group_element_attr').outerHeight()-2);

			if(jQuery(this).hasClass('show'))
				{
				jQuery(this).removeClass('show')
				element_attr.hide();
				}
			else
				{
				jQuery('div.gears').each(
				function()
					{
					jQuery(this).removeClass('show');
					}
				);		
				element_attr.show();
				jQuery(this).addClass('show');
				}
			}
		);
			
	
				
/********************/

	
/********************/
/* Custom triggers  */
/********************/
	//Custom Event "update_list": Populate dropdown on insert, edit or delete with updated data
	/*core_object.bind
		(
		"update_list", function(args)
			{
			jQuery('select[name="parent_Id"]').html('<option>  loading...</option>');
		   
			var data =
				{
				action	 : 'populate_dropdown',
				table	 : document.addItem.table.value,
				ajax	 : true
				};
				
			jQuery.post
				(
				ajaxurl, data, function(response)
					{
					 jQuery('select[name="parent_Id"]').html(response);
					 jQuery('select[name="parent_Id"] option[value='+jQuery('input[name="selected_Id"]').val() +']').attr('selected',true);
					}
				);
			}
		);*/
	
	//Custom Event "update_custom_fields": Populate custom field widgets on edit, insert with updated data
	core_object.bind
		(
		"update_custom_fields", function(args)
			{
			show_iz_tooltip(jQuery('div.iz-custom-form-fields div.rightDrag:first'), (args['db_action']=='save') ? 'New field added.' : 'Field Updated');
			
			
			jQuery('div.widget').each
				(
				function()
					{
					if(jQuery(this).attr('data-group-label')==args['label_name'])
						{
						jQuery(this).children('div.widget-top').css('position','relative');
						jQuery(this).find('h4').css('position','relative');						
						jQuery(this).find('h4').css('z-index','3');
						jQuery(this).children('div.widget-top').prepend('<div class="highlight"></div>');
						jQuery(this).children('div.widget-top').find('div.highlight').fadeIn(800,
							function()
								{
								if(tutorial)
				add_tutorial_popup(jQuery('div.widget-liquid-right'),'<strong>Excelent!</strong> Now we have our first field. Note that new fields will always be added at the top of its type/category. <br /><br /><strong>Next</strong> we are going to add this field to a form.<br /><br /> Click on the field, hold and drag it over to the left-hand panel and release.<div class="spacer"></div><div class="extra_help go_below next nextstep">How do I edit or delete the field I\'ve just created?</div><div class="spacer"></div><div class="spacer"></div>', '<strong>Editing and deleting</strong><br /><br /><em>Editing a field</em><br />To edit a field, simply hover over it and click on "edit".<br/ > Then follow the same steps as when creating a new field.<br /><em>Note: This field will be changed on all forms saved!<br />Also note that fields just edited will always be added at the top of its type/category.</em><br /><br /><em>Deleting a Field</em><br />To delete a field, hover over it and click delete.<em>Note: This field will be deleted on all forms saved!</em>','',-50);
			
								jQuery(this).fadeOut(2500);
								}
							);
						}
					}
				);
			}
		);
	
	//Custom Event "update_form_fields": Populate form fields on edit, delete with updated data
	core_object.bind
		(
		"update_form_fields", function(args)
			{
			var data = 
				{
				action	 	: 'populate_form_fields',
				form_Id		: jQuery('div.form_Id').text(),
				edit_Id		: jQuery('div#edit_Id').text()
				};
			
			jQuery('div.drop-sort').html('<p class="description no-fields">Loading... </p>');
		
			jQuery.post
				(
				ajaxurl, data, function(response)
					{
					jQuery('div.drop-sort').html(response);	
					}
				);
			}
		);	
		
	core_object.bind
		(
		"update_default_fields", function(args)
			{
			var data = 
				{
				action	 	: 'populate_default_fields',
				plugin		: jQuery('div.form_Id').text()
				};
			//alert(jQuery('select#wap_wa_form_builder_Id').val());
			jQuery('div.drop-sort').html('<p class="description no-fields">Loading... </p>');
		
			jQuery.post
				(
				ajaxurl, data, function(response)
					{
					jQuery('div.drop-sort').html(response);	
					}
				);
			}
		);	
	core_object.bind
		(
		"update_module_fields", function(args)
			{
			var data = 
				{
				action	 	: 'populate_form_fields',
				form_Id		: jQuery('div.form_Id').text(),
				edit_Id		: jQuery('div#edit_Id').text()
				};
			
			jQuery('div.drop-sort').html('<p class="description no-fields">Loading... </p>');
		
			jQuery.post
				(
				ajaxurl, data, function(response)
					{
					jQuery('div.drop-sort').html(response);	
					}
				);
			}
		);	
/********************/
	
	
/*******************************************/
/* Creating / Edit / Delete custom fields  */
/*******************************************/
	//Reset selection
	jQuery('select[name="field-type"] option[value="0"]').attr('selected', true);
	
	
	if(tutorial)
		add_tutorial_popup(jQuery('.custom-fields.left'),'<strong>Welcome!</strong> First we are going to choose the<br> field type we want to create.<div class="spacer"></div><div class="extra_help go_right nextstep next">Thats great! but how do I know what field type to choose?</div><div class="spacer"></div><div class="spacer"></div><div class="spacer"></div>','When choosing a field type you need to consider the question your asking the user that will complete the form.<br /><br />Described below are the different field types you can create.</em><ul><li><strong>Text field</strong>:<br /> Textfields are used to capture specific information that only the user can provide i.e. "name" or "surname"<br /><br /><input type="text" value="Please enter your name" /></li><li><strong>Text Area</strong>:<br /> Text areas are used to capture large quantities information that only the user can provide i.e. "Biography" or "Residensial Address"<br /><br /><textarea>Please enter your Residensial Address</textarea></li><li><strong>Dropdown List</strong>:<br /> Dropdown list are used to capture a single value from a predifined option list i.e. "Gender" with options "male" and "female"<br /><br /><select><option>--- Select Gender ---</option><option>Male</option><option>Female</option></select></li><li><strong>Radio Button Group</strong>:<br /> Same as a dropdown list but displayes all options available i.e. "Gender" with options "male" and "female"<br /><br />Select Gender:<br /><input type="radio" name="gender"> Male<br /><input type="radio" name="gender"> Female<br /></li><li><strong>Check Box Group</strong>:<br /> Check boxes are used to capture multiple values of yes(checked) and no(unchecked) from a predifined list i.e. "House Features" with check fields such as "Pool","Garage","Remote Gate" etc<br /><br />House Features:<br /><input type="checkbox"> Pool<br /><input type="checkbox"> Garage<br /><input type="checkbox"> Remote Gate<br /></li><li><strong>Text Field Group</strong>:<br /> Text field groups are the same as text fields but are grouped together i.e. "Personal Details" with text fields such as "Name","Surname","Email" etc<br /><br />Personal details:<br /><input type="text" value="Name"><br /><input type="text" value="Surname"><br /><input type="text" value="Email"></li></ul>');
	
	//STEP 1 - Choose form element type ////////////////////////////////////////////////////////////////
	jQuery('select[name="field-type"]').change(
		function()
			{
			
			//Restore values on change
			if(jQuery(this).val()!='textgroup'){

				jQuery('div.gears').each
					(
					function()
						{
						jQuery(this).hide();
						}
					);	
				}
			else
				{
				jQuery('div.gears').each
					(
					function()
						{
						jQuery(this).show();
						}
					);		
				}
			get_backup();
			//Save new values on change
			save_backup();
			
			//Check for selection
			if(jQuery(this).val()!=0)					
				{
				
				//Display STEP 2 - Create element group //////////////////////////////////////////////////////////
				jQuery('.custom-fields-container.mid').addClass('active');
				jQuery('.custom-fields.right').html('');
				jQuery('.custom-fields-container.right').removeClass('active');
				
				//Get element group attributes
				var data = 
					{
					action	 			: 'create_group',
					input	 			: jQuery(this).val(),
					old_value	 		: jQuery('div.backup div.old-group-label').text()
					};		
				
				jQuery('.custom-fields.mid').html('<small>Loading...  </small>');
				
				jQuery.post
					(
					ajaxurl, data, function(response)
						{
						jQuery('.custom-fields.mid').html(response);
						jQuery('.custom-fields.mid input[type="text"]').val(format_illegal_chars(jQuery('div.backup div.group-label').html()));
						if(tutorial)
							add_tutorial_popup(jQuery('.custom-fields.mid'),'<strong>Very Good!</strong>...Now choose a name for your field and hit enter. <div class="spacer"></div><div class="extra_help go_right nextstep next">What is the "gear" icon for?</div><div class="spacer"></div><div class="spacer"></div>','<strong>Field Attributes!</strong><br /><span class="gears_example"></span><br />By clicking on the gears (settings) you\'ll find a few attributes that can be added to your field for form validation purposes:<ul><li><strong>Reqiured: (default "No")</strong><br />by selecting "yes" users must:<br /><br /><ul><li>Enter a value in case of a text field;</li><li>Enter a value in case of a text area;</li><li>Select a option value in case of a dropdown list</li><li>Select a option in case of a radio button group</li></ul><br /><em>Note: This is not avalable for check box groups</em></li><li><strong>Format: (default "text")</strong><br /><br /><ul><li><strong>Text</strong> - Allows all characters to be entered into field</li><li><strong>Email</strong> - Only a valid email address will be accepted by the field</li><li><strong>Number</strong> - Only a numbers will be accepted by the field.</li></ul><br /><em>Note: Only available for text fields and for each text field group item</em></ul>');
						jQuery('.custom-fields.mid input[type="text"]').focus();
						
						//Check input
						if(jQuery('input[name="group-label"]').val() !='')
							{
							//Display STEP 3 - Create element group options //////////////////////////////////////////////////////////
							add_group_options();
							add_new_option('');
							}
						}
					);
				}
			else
				{
				//Reset steps
				jQuery('.custom-fields.mid').html('');
				jQuery('.custom-fields.right').html('');
				jQuery('.custom-fields-container.mid').removeClass('active');
				jQuery('.custom-fields-container.right').removeClass('active');
				}
			}
		);
		
	//Delete custom field	
	jQuery('div.custom-field-actions span.delete span').click
		(
		function()
			{
			delete_custom_field( jQuery(this) );
			}
		);
	
	//Edit custom field	
	jQuery('div.custom-field-actions span.edit span').click
		(
		function()
			{
			clear_all();
			jQuery('span.db_action').text('Edit');
			edit_custom_field(jQuery(this));
				var obj_pos	= jQuery('div.widgets-holder-wrap').offset();
			jQuery("html, body").animate(
							{
								scrollTop:obj_pos.top
							},300
						)
			//Trigger custom event
			//core_object.trigger("update_form_fields");
			}
		);
/**********************************/
		
	
/*********************/
/* Selecting a from  */
/*********************/
	jQuery('select[name="wap_wa_form_builder_Id"]').change(
		function()
			{
			show_iz_tooltip(jQuery('div#available-widgets div.widget-holder p.description'), 'Drag a form element from here to the <br /> form on the left to add and order them.');	
			//Check selection	
			if(jQuery(this).val()=='0')
				{
				disable_form_editor();
				}
			else
				{
				enable_form_editor();
				jQuery('div.form_Id').text(jQuery(this).val());
				//Trigger custom event
				//alert('test 0');
				core_object.trigger("update_form_fields");
				//alert('test 1');
				core_object.trigger("update_default_fields");
				//alert('test 2');
				core_object.trigger("update_module_fields");
				//alert('test 3');
				}
			}
		);
/*********************/		


/*************************/
/* Drag / Drop and Sort  */
/*************************/	
		//Form creator
        var dropSort 	= jQuery('div.drop-sort');
        var rightDrag 	= jQuery('div#widgets-right div#widget-list .iz-draggable');
		
		//Form Data
		var formDrop 	= jQuery('div.col-wrap');
		var formDrag 	= jQuery('div#widgets-right .draggable-form');
        
		//Drag
        rightDrag.draggable(
			{
			stack  : '.draggable',
			revert : 'invalid', //Go back to where it started if not dropped on to a droppable                    
			accept : '.leftDrag',
			helper : 'clone'
			}
        );
		
		formDrag.draggable(
			{
			stack  : '.draggable',
			revert : 'invalid', //Go back to where it started if not dropped on to a droppable                    
			accept : '.leftDrag',
			helper : 'clone'
			}
        );
		    
		//Drop      
        dropSort.droppable(
        	{
            drop   		: function(event, ui)
							{ 
							jQuery('p.description.no-fields').remove();
							if(move_to_container(ui.draggable, jQuery(this)))
								{
								build_field_group(ui.draggable, ui.draggable.attr('data-form-id'), ui.draggable.attr('data-group-label'), newId, ui.draggable.attr('data-filter-type'), ui.draggable.attr('data-filter-name'), ui.draggable.attr('data-field-origen')); 
								
								if(tutorial)
									add_tutorial_popup(jQuery('div.save_form_as'),'<strong>Well Done!</strong> Click on "Save form" button below and your first form is ready to into a page! <div class="spacer"></div><div class="extra_help go_below next nextstep">What happens after I save this form?</div><div class="spacer"></div><div class="spacer"></div>','<strong>Opening and editing saved forms</strong><br /><br /><em>Re-opening an existing form</em><br />All forms are accesable from the dropdown list labled "Select a form to edit". Simply select the form you want to view/edit and the forms panel/canvas will automaticaly populate the saved form fields.<br /><br /> If "Create a New Form" is selected you can drag and drop existing fields to the empty forms panel. Enter a form name next to "Save form as" and click save.<br /><br /><em>Editing an existing form:</em><br />Open the form as described above. You can now drag existing fields to the form. Note that new fields will be added to the botton of the form and can be sorted up and down the order. Remove form field by clicking on the "X" on the top right corner. Sort fields by dragging them up or down. <br /><br />And remember...after your done click the save button!','',200);

								}
							jQuery(this).removeClass('over');							
						  	},
            over        : function(){jQuery(this).addClass('over')},
            out         : function(){jQuery(this).removeClass('over')},	  
            tolerance 	: 'fit',
			helper 		: 'clone'	
        });
		
		
		formDrop.droppable(
        	{
            drop   		: function(event, ui)
							{ 
							//alert(ui.draggable.attr('data-form-id'));
							if(tutorial)
							add_tutorial_popup(jQuery('.ui-droppable'),'<strong>And presto!</strong> All form entries are populated in the table below. <br><br>Also visit these tutorials:<br /><br /><a href="?page=WA-wa_form_builder-main&amp;tutorial=true">Creating form fields</a><br /><a href="'+ jQuery('div#site_url').text() +'/wp-admin/post-new.php?post_type=page&amp;tutorial=true">Adding a form to a page</a><br /><a href="?page=WA-wa_form_builder-view-forms&tutorial=true">Configuring forms</a><br /><a href="?page=WA-wa_form_builder-forms-settings&tutorial=true">General settings</a><div class="spacer"></div><div class="next nextstep close_chef">Dismiss</div><div class="spacer"></div><div class="spacer"></div>','','','',130);
	
							var data = 	
								{
								action	 	: 'populate_form_data_table',
								form_Id		: ui.draggable.attr('data-form-id')
								};
							jQuery('div.drop_area p').text('loading...');
							jQuery.post
								(
								ajaxurl, data, function(response)
									{ 
									jQuery('div.col-wrap').html( response);
									jQuery('div.col-wrap').removeClass('over');
									}
								);
													
						  	},
            over        : function(){jQuery(this).addClass('over')},
            out         : function(){jQuery(this).removeClass('over')},	  
            tolerance 	: 'fit',
			helper 		: 'clone'	
        });
		
		//Sort
        dropSort.sortable(
			{
			stop : function(event, ui){ build_field_group(ui.item, ui.item.attr('data-form-id'), ui.item.attr('data-group-label'), ui.item.attr('id'), ui.item.attr('data-filter-type'), ui.item.attr('data-filter-name'), ui.item.attr('data-field-origen')) },           
			placeholder: 'iz-placeholder',
			forcePlaceholderSize : true
			}
		);	
/*************************/
		
	}
);


/*******************************************/
/************* Form Functions **************/
/*******************************************/

function delete_form_entry(last_update,Id){
	
	var get_color = jQuery('tr#tag-'+Id).css('background-color');
	jQuery('tr#tag-'+Id).css('background-color','#FFEBE8');
	if(confirm('Are your sure you want to permanently delete this record?'))
		{
		jQuery('tr#tag-'+Id).fadeOut('slow', function()
			{
			jQuery('tr#tag-'+Id).remove();	
			}
		);
		
		var data = 	
			{
			action	 	: 'delete_form_entry',
			last_update	: last_update
			};

		jQuery.post
			(
			ajaxurl, data, function(response)
				{ 
				core_object.trigger("update_form_entry"); 
				}
			);
		}
	else
		{
		jQuery('tr#tag-'+Id).css('background-color',get_color);
		}
}

function populate_form_data_list(args,table,page,plugin_alias,additional_params,form_Id){
	/*
	action	 			: 'populate',
		args	 			: args,
		page	 			: page,
		table				: table,
		orderby				: orderby,
		order				: order,
		current_page 		: current_page,
		additional_params	: additional_params
	*/
	//alert(form_Id);
	var data = 	
		{
		plugin_alias		: (jQuery('input[name="plugin_alias"]').val()) ? jQuery('input[name="plugin_alias"]').val() : plugin_alias,
		action	 			: 'populate_form_data_list',
		table	 			: (jQuery('input[name="table"]').val()) ? jQuery('input[name="table"]').val() : table,
		args				: (jQuery('input[name="fields"]').val()) || jQuery('input[name="table_headers"]').val() || args,
		page	 			: (jQuery('input[name="page"]').val()) ? jQuery('input[name="page"]').val() : page,
		order	 			: jQuery('input[name="order"]').val(),
		orderby	 			: jQuery('input[name="orderby"]').val(),
		current_page		: jQuery('input[name="current_page"]').val(),
		additional_params	: (jQuery('input[name="additional_params"]').val()) ? jQuery('input[name="additional_params"]').val() : additional_params,
		form_Id				: form_Id
		//args				: (jQuery('input[name="table_headers"]').val()) ? jQuery('input[name="table_headers"]').val() : args
		};
	jQuery('tbody#the-list').html('<tr><td></td><td colspan="2"><small>Loading...  </small></td></tr>');			
	jQuery.post
		(
		ajaxurl, data, function(response)
			{
			jQuery('tbody#the-list').html(response);
			core_object.trigger("update_form_entry");
			refreshHiddenColumns();
			//Reset form on insert or update
			
			resetSliderPositions(jQuery('table.resiable-columns'));
			if(jQuery('input[name="edit_Id"]').val()=='' || jQuery('input[name="edit_status"]').val()=='done')
				{
				//reset_form();
				}
			}
		);
}

function save_form(obj){
	
	var data_array 		= new Array();
	var data_sub_array 	= new Array();
	var form_fields		= new Array();
	var get_button_text = jQuery(obj).val();
	
	if(jQuery(obj).parent().find('input[name="form_title"]').val()=='' && jQuery('select[name="wap_wa_form_builder_Id"] option[value="0"]').attr('selected') == 'selected')
		{
			alert('Please give your form a name.');
			return show_iz_tooltip(jQuery('#wap_wa_form_builder_Id'),'Please give your form a name.');
			//return false;
		}
	
	jQuery(obj).val('   Saving...   ');
	
	//Get field data for each field dropped in form
	jQuery('div.drop-sort div.data-array').each
		(
		function(index)
			{
			form_fields[index] = jQuery(this).text();
			}
		);
	
	var db_action = 'update';
	
	if(jQuery(obj).hasClass('new_form'))
		{
			var db_action = 'insert';
		}
	
	var data = 	
		{
		action	 		: 'save_form',			
		form_Id			: jQuery('div.form_Id').text(),
		form_fields		: form_fields,
		db_action		: db_action,
		save_as			: jQuery(obj).parent().find('input[name="form_title"]').val()
		};
	
	jQuery.post
		(
		ajaxurl, data, function(response)
			{
			//reset button text and stamp
			jQuery(obj).val(get_button_text);
			//stamp_succesfull_save(obj);
			
			if(tutorial)
				add_tutorial_popup(jQuery('#wap_wa_form_builder_Id'),'<strong>Congratulations!</strong> You have completed the tutorial! <br><br> Note that your form has been added to the list below. <br><br>Also visit these tutorials:<br /><br /><a href="'+ jQuery('div#site_url').text() +'/wp-admin/post-new.php?post_type=page&amp;tutorial=true">Adding a form to a page</a><br /><a href="?page=WA-wa_form_builder-forms-data&tutorial=true">Viewing form entries</a><br /><a href="?page=WA-wa_form_builder-view-forms&tutorial=true">Form configuration</a><br /><a href="?page=WA-wa_form_builder-forms-settings&tutorial=true">General settings</a><br />','','',350);

			
			if(db_action=='insert')
				{
				jQuery('select[name="wap_wa_form_builder_Id"]').html('<option>  loading...</option>');
				var data = 	{
					action	 	: 'populate_dropdown',
					plugin_alias: 'form_builder_Id',
					table		: 'wap_wa_form_builder',
					ajax	 	: true
					};
					
				jQuery.post(ajaxurl, data, function(response)
						{
						jQuery('select[name="wap_wa_form_builder_Id"]').html('<option value="0" selected="selected">--- Create a New Form ---</option>' + response);
						}
					);
				}
			
			
			}
		);
}

function build_field_group(theDraggable, form_Id, group_label, Id, filter_type, filter_name, field_origen) {  
		
	   var data = 	{
					action	 	: 	'build_field_group',
					form_Id		: 	jQuery('div.form_Id').text(),
					group_label	:	format_illegal_chars(group_label),
					filter_type	: 	filter_type,
					filter_name	: 	filter_name,
					field_origen:	field_origen
					};					
							
		if(theDraggable.hasClass('newest'))
			{
			jQuery('#'+theDraggable.attr("id")).html('<small>Loading...  </small>');
			}
		
		jQuery.post
			(
			ajaxurl, data, function(response)
				{ 	                    
				if(!response)
					{
					jQuery('#'+Id).html('Loading...');
					}
				else
					{
					jQuery('#'+Id).html(response);
					}
				}
			);              
	}

function move_to_container(theObj, newContainer) {
	
	var bool = true;
	
	//Loop through the panel and check for duplicates
	jQuery('div.drop-sort div.iz-sortable').each
		(
		function(index)
			{
			if(theObj.attr('data-group-label').toLowerCase() ==  jQuery(this).attr('data-group-label').toLowerCase())
				{
				show_iz_tooltip(jQuery('div.drop-sort'),'Duplicate fields not allowed.');
				bool = false;
				}
			}
		);
		
	if(!bool) 
		return bool
	
	//Clone draggable
	newObj = theObj.clone();	
	newObj.attr('class', 'iz-sortable newest');
	newObj.attr('style', '');

	var id = jQuery(newObj).attr('id');
	
	newId = '_' + Math.round(Math.random()*99999);
	
	//Creat unique identifier
	jQuery(newObj).attr('id', newId);
	jQuery(newObj).addClass(id);
	
	//Add new field
	newContainer.append(newObj);
	    
	setup_draggable(newObj); 
	
	return true;         
}	

function setup_draggable(newObj) {
	
	//Add a 'remove' button for the deletion of the object/field group
	newObj.find('div.widget-title-action').empty();
	var leftDrag  = jQuery('div#widgets-left div.widgets-holder-wrap div.leftDrag');
	//Remove the object if the remove button is clicked
	jQuery('div#widgets-left div.widgets-holder-wrap div.leftDrag div.removeObj a').click
		(
		function()
			{
			var theDraggable = jQuery(this).parent().parent();
			var panel		 = jQuery(this).parent().parent().parent();
	
			theDraggable.remove();
			removeCoords(theDraggable, panel);
			}
		);
}

function disable_form_editor(){
	jQuery('div.iz-custom-form-fields div.ui-draggable').removeClass('rightDrag');
	jQuery('span.save_form_as').fadeIn('fast');
	jQuery('div.drop-sort').html('<p class="description no-fields  no-form"><strong>Create</strong> a <strong>new form</strong> by simply <strong>dragging fields</strong> from the Custom fields sidebar on the right and <strong>dropping them here</strong>. Give the form a name and click <strong> "Save" </strong> above!</p>');
	jQuery('input#iz-save-form').removeClass('editing');
	jQuery('input#iz-save-form').addClass('new_form');
	
}

function enable_form_editor(){
	jQuery('div.iz-custom-form-fields div.ui-draggable').addClass('rightDrag');
	jQuery('span.save_form_as').fadeOut('fast');
	jQuery('input#iz-save-form').removeClass('new_form');
	jQuery('input#iz-save-form').addClass('editing');
	//jQuery('input#iz-save-form').attr('disabled',false);
}

function remove_custom_group(obj){
	jQuery(obj).parent().remove();
}
/****************************************************/




/****************************************************/
/************* Custom Field Functions ***************/
/****************************************************/

//STEP 2 - Create group
function add_group_options(){
	
	var group_label = format_illegal_chars(jQuery('input[name="group-label"]').val());
	var label_exists = false;
	var db_action = jQuery('span.db_action').text();

	if(group_label !='')
		{
		if(db_action!='Edit')
			{
			jQuery('div#widgets-right div.widget-top div.widget-title h4').each(
				function(index)
					{
					var current_label = jQuery(this).text();
					if(current_label.toLowerCase() == group_label.toLowerCase())
						{
						label_exists = true;
						show_iz_tooltip(jQuery('input[name="group-label"]'),'Sorry, Group with label name "'+ group_label +'" already exists');
						}
					}
				);
			}
		
		if(label_exists==false)
			{
			
				///Restore values on change
				get_backup();
				//Save new values on change
				save_backup();
				
				jQuery('.custom-fields-container.right').addClass('active');
				
				var data = 
					{
					action	 	: 'add_group_options',
					fieldtype	: jQuery('select[name="field-type"]').val(),
					required	: jQuery('input[name="field-req"]').val(),
					label 		: format_illegal_chars(jQuery('input[name="group-label"]').val())
					};		
				
				jQuery('.custom-fields.right').html('<small>Loading...  </small>');
					
				if(tutorial)
					{
					if(jQuery('select[name="field-type"]').val()=='text' || jQuery('select[name="field-type"]').val()=='textarea')
						add_tutorial_popup(jQuery('.custom-fields-container.right'),'<strong>Well done!</strong>...Now click the "Save Field" button below and presto!');
					if(jQuery('select[name="field-type"]').val()=='dropdown')
						add_tutorial_popup(jQuery('.custom-fields-container.right'),'<strong>Well done!</strong>...You\'ve you selected a dropdown list and therefor you\'ll need to add some options for the user to select.<br /><br />To add a option type a n value in the field labeled "Add New" below and hit enter.<br /><br /> When your done click on "Save Field" at the bottom.<div class="spacer"></div><div class="extra_help go_below next nextstep">What else should I know?</div><div class="spacer"></div><div class="spacer"></div>','<strong>Sorting and removing</strong><br />After creating a field, you\'ll notice an "up/down arrow icon" and a "X".<br /><br /><em>Sorting</em><br />You can sort the order of these field items if you click,hold then drag the item up and down using the "up/down icon"<br /><br /><em>Removing</em><br />Remove unwanted field items by clicking the "X" next to it.','',0,-30);
					if(jQuery('select[name="field-type"]').val()=='radio')
						add_tutorial_popup(jQuery('.custom-fields-container.right'),'<strong>Well done!</strong>...You\'ve you selected a radio button list and therefor you\'ll need to add some radio buttons for the user to choose from.<br /><br />To add a button type a n value in the field labeled "Add New" below and hit enter.<br /><br /> When your done click on "Save Field" at the bottom.<div class="spacer"></div><div class="extra_help go_below next nextstep">What else should I know?</div><div class="spacer"></div><div class="spacer"></div>','<strong>Sorting and removing</strong><br />After creating a field, you\'ll notice an "up/down arrow icon" and a "X".<br /><br /><em>Sorting</em><br />You can sort the order of these field items if you click,hold then drag the item up and down using the "up/down icon"<br /><br /><em>Removing</em><br />Remove unwanted field items by clicking the "X" next to it.','',0,-30);
					if(jQuery('select[name="field-type"]').val()=='textgroup')
						add_tutorial_popup(jQuery('.custom-fields-container.right'),'<strong>Well done!</strong>...You\'ve you selected a Textfield group and therefor you\'ll need to add some text fields for the user to complete.<br /><br />To add a textfield type a n value in the field labeled "Add New" below and hit enter.<br /><br /> When your done click on "Save Field" at the bottom.<div class="spacer"></div><div class="extra_help go_below next nextstep">What else should I know?</div><div class="spacer"></div><div class="spacer"></div>','<strong>Sorting and removing</strong><br />After creating a field, you\'ll notice an "up/down arrow icon" and a "X".<br /><br /><em>Sorting</em><br />You can sort the order of these field items if you click,hold then drag the item up and down using the "up/down icon"<br /><br /><em>Removing</em><br />Remove unwanted field items by clicking the "X" next to it.','',0,-30);
					if(jQuery('select[name="field-type"]').val()=='check')
						add_tutorial_popup(jQuery('.custom-fields-container.right'),'<strong>Well done!</strong>...You\'ve selected a Checkbox group and therefor you\'ll need to add some check boxes for the user to tick.<br /><br />To add a check box type a n value in the field labeled "Add New" below and hit enter.<br /><br /> When your done click on "Save Field" at the bottom.<div class="spacer"></div><div class="extra_help go_below next nextstep">What else should I know?</div><div class="spacer"></div><div class="spacer"></div>','<strong>Sorting and removing</strong><br />After creating a field, you\'ll notice an "up/down arrow icon" and a "X".<br /><br /><em>Sorting</em><br />You can sort the order of these field items if you click,hold then drag the item up and down using the "up/down icon"<br /><br /><em>Removing</em><br />Remove unwanted field items by clicking the "X" next to it.','',0,-30);
					}
					
				jQuery.post
					(
					ajaxurl, data, function(response)
						{
						if(response.length>200 && jQuery('div.backup div.group-options').html()!='')
							{
							jQuery('.custom-fields.right').html(jQuery('div.backup div.group-options').html());
							} 
						else
							{
							jQuery('.custom-fields.right').html(response);
							}
						jQuery('.custom-fields.right').show();
						add_new_option('');
						jQuery('.custom-fields.right input[type="text"]:first').focus();
						}
					);	
				
			
			}
		else
			{
			jQuery('.custom-fields.right').hide();
			jQuery('.custom-fields-container.right').removeClass('active');
			}
		}
	else
		{
		jQuery('.custom-fields.right').hide();
		jQuery('.custom-fields-container.right').removeClass('active');
		}
}

//STEP 3 - Add group options
function add_new_option(input) {
	
	if(input)
		{
		
			var unique_Id = '__' + Math.round(Math.random()*99999) +'__';
			var custum_field_items = '';
				custum_field_items += '<div class="group_element_attr" style="display:none;">';
				custum_field_items += '<label>Required</label> <input data-attr="required" type="radio" name="'+ unique_Id +'field-req" value="required">&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;';
				custum_field_items += '<input data-attr="required" type="radio" name="'+ unique_Id +'field-req" value="" checked="checked">&nbsp;&nbsp;No<br />';
				custum_field_items += '<div class="devider"></div>';
				///custum_field_items += '<label>Visibility</label><input data-attr="visibility" type="radio" name="'+ unique_Id +'field-visibility" value="Private">&nbsp;&nbsp;Private&nbsp;&nbsp;&nbsp;&nbsp;';
				////custum_field_items += '<input data-attr="visibility" type="radio" name="'+ unique_Id +'field-visibility" value="Public" checked="checked">&nbsp;&nbsp;Public<br />';
				//custum_field_items += '<div class="devider"></div>';
				custum_field_items += '<label>Format</label><input data-attr="format" type="radio" name="'+ unique_Id +'field-format" value="text" checked="checked">&nbsp;&nbsp;Text&nbsp;&nbsp;&nbsp;&nbsp;';
				custum_field_items += '<input data-attr="format" type="radio" name="'+ unique_Id +'field-format" value="email">&nbsp;&nbsp;Email&nbsp;&nbsp;&nbsp;&nbsp;';
				custum_field_items += '<input data-attr="format" type="radio" name="'+ unique_Id +'field-format" value="number">&nbsp;&nbsp;Number';
				custum_field_items += '</div>';	
				
			jQuery('ul.opt-list').append('<li class="iz-option"><input style="'+((jQuery('select[name="field-type"]').val()=="textgroup") ? 'width:165px' : 'width:200px;' )+'" data-old-val="'+ jQuery(input).val() +'" type="text" value="'+ jQuery(input).val() +'">'+ ((jQuery('select[name="field-type"]').val()=="textgroup") ? '<div class="gears"></div>'+ custum_field_items : '') +'<div title="Remove option" class="remove"></div><div title="Drag to position" class="sorthandle"></div></li>');
			jQuery(input).val('');
			jQuery('ul.opt-list').keypress
				(
				function(event)
					{
					if(event.which==13)
						{
						event.preventDefault();	
						}
					}
				);
			
		}
	
	jQuery('div.custom-fields-container.right ul.opt-list li div.remove').click
		(
		function()
			{
			if((jQuery('select[name="field-type"]').val() == 'check' || jQuery('select[name="field-type"]').val() == 'textgroup') && jQuery('span.db_action').text()=='Edit')
				{
				jQuery(this).parent().hide();
				jQuery(this).parent().find('input[type="text"]').addClass('archive');
				}
			else
				{
				jQuery(this).parent().remove();
				}
			}
		);
	jQuery('div.custom-fields-container.right ul.iz-sortable').sortable();
	jQuery('ul.opt-list').scrollTop(10000);
}




function save_field(obj,db_action,label_name){
	
	var colors 			= ["#FF0000","#FFBF00", "#8F49FF", "#FF00BF", "#F3FF35", "#A5FF4B", "#00FF40", "#0F5BFF", "#76FFE9", "#FF5F49", "#3EBDFF"];
	var errors 			= 0;
	var opt_array 		= new Array();
	var opt_obj 		= new Object();
	
	var old_opt_array 	= new Array();
	var to_be_archived 	= new Array();
	var get_button_text	= jQuery(obj).val();
		
	
	
	/*******************************************************************************************/
	/* Process option values in case of textgroups, check groups, dropdowns and radio groups   */
	/*******************************************************************************************/		
	
	if(jQuery('select[name="field-type"]').val()!='text' && jQuery('select[name="field-type"]').val()!='textarea')
		{
		//Setup fields to be archived
		jQuery('div.custom-fields-container.right ul.opt-list li input[type="text"]').each
				(
				function(index)
					{
					if(jQuery(this).hasClass('archive'))
						{
						to_be_archived[index] = jQuery(this).attr('data-old-val');
						jQuery(this).parent().remove();
						}
					}
				);
		//Loop through options created
		jQuery('div.custom-fields-container.right ul.opt-list li input[type="text"]').each
			(
			function(index)
				{
				/*if(!allowed_label_Chars(jQuery(this).val()))
					{
					show_iz_tooltip(jQuery(this),'Invalid characters found!');
					jQuery(this).focus();
					errors++;
					}*/
				
				var input_val =  jQuery(this).val();				
				
				if(jQuery('select[name="field-type"]').val()=='textgroup')
					{
					
					old_opt_array[index]= jQuery(this).attr('data-old-val');
					
					jQuery(this).siblings('div.group_element_attr').each
						(
						function(field_attr)
							{
								
								var _name = jQuery(this).children('input[data-attr="required"]:checked').attr('name');
								
								var tempname = _name.split("__");
								var _opt_id = tempname[1];
								
								var _val = jQuery(this).prevAll('input[type="text"]').val();
								var _req = jQuery(this).children('input[data-attr="required"]:checked').val();
								var _visib = jQuery(this).children('input[data-attr="visibility"]:checked').val();
								var _format = jQuery(this).children('input[data-attr="format"]:checked').val();
								
								//Create a new object for each of the input field's options and store the object in an array
								opt_array[index] = new optionObject(_opt_id, _val, _req, _visib, _format );
													
							//old_opt_array[jQuery(this).attr('data-old-val')][field_attr]= jQuery(this).attr('data-old-val');
									
							}
						);						
					}
				else
					{
					opt_array[index] = jQuery(this).val();
					old_opt_array[index]= jQuery(this).attr('data-old-val');
					}
				}
			);
			
		//console.log("opt_array = ");
		//console.log(opt_array);
		
		//Now loop through the options array and retrieve the option for each input
		for(var x=0; x<opt_array.length; x++) {
			
			var ob = opt_array[x];
					
			//console.log("ID: 		 " + ob.id);
			//console.log("Val: 		 " + ob.val);
			//console.log("Required:   " + ob.required || "wowItWORKS!!!");
			//console.log("Visibility: " + ob.visibility);
			//console.log("Format: 	 " + ob.format);
				
		}
		
		
		var duplicates = find_duplicates(opt_array);
		
		//Check for empty group element option array
		if(opt_array.length==0)
			{
			show_iz_tooltip(jQuery('div.custom-fields-container.right input[name="save-field-button"]'),'Option list can not be empty!');
			errors++;
			}
		
		//Check duplicate option values in options
		if(duplicates.length>0)
			{
			show_iz_tooltip(jQuery('div.custom-fields-container.right input[name="save-field-button"]'),'Duplicate options found!');

			jQuery('div.custom-fields-container.right ul.opt-list li input[type="text"]').each
				(
				function(index)
					{
					var dup_match = jQuery.inArray(jQuery(this).val(),duplicates);
					
					if(dup_match>=0)	
						jQuery(this).css('border','1px solid ' + colors[dup_match]);
					else 
						jQuery(this).css('border','1px dashed #E7E7E7');	
					}
				);
			errors++;
			}
		}
		
	//Break on error
	if(errors>0)
		return false;
	
	/***************************************************************************/
	
	jQuery(obj).val('   Saving...   ');	
	
	var data = 	
		{
		action	 		: 'save_field',			
		form_Id			: jQuery('div.form_Id').text(),
		type	 		: jQuery('select[name="field-type"]').val(),
		required 		: jQuery('input[name="field-req"]:checked').val(),
		visibility 		: jQuery('input[name="field-visibility"]:checked').val(),
		format	 		: jQuery('input[name="field-format"]:checked').val(),
		grouplabel 		: format_illegal_chars(jQuery('input[name="group-label"]').val()),				
		items			: opt_array,
		old_items		: old_opt_array,		
		old_grouplabel	: format_illegal_chars(jQuery('div.backup div.old-group-label').text()),
		to_be_archived	: to_be_archived		
		};
		
	jQuery('div.backup div.old-group-label').text(jQuery('input[name="group-label"]').val());

	jQuery.post
		(
		ajaxurl, data, function(response)
			{ 
			var customfields =
				{
				action	: 'populate_custom_fields',
				};
			jQuery.post
				(
				ajaxurl, customfields, function(response2)
					{ 
					jQuery('div.iz-custom-form-fields').html(response2);	

					var rightDrag = jQuery('div#widgets-right div#widget-list .iz-draggable');
					rightDrag.draggable
						({
						stack  : '.draggable',
						revert : 'invalid',
						helper : 'clone'
						}); 
					
					//Set arguments for custom event handler
					var args 			= new Array();
					args['db_action'] 	= db_action;
					args['label_name'] 	= format_illegal_chars(label_name);
					
					//Trigger custom Events
					core_object.trigger("update_custom_fields",args);
					if(data.old_grouplabel)
						core_object.trigger("update_form_fields");
					
					
					//START: WTF ?? ////////////////////////////////////////////////////////////////
					jQuery('div.custom-field-actions span.delete span').click
						(
						function()
							{
							delete_custom_field(jQuery(this));
							}
						);
					jQuery('div.custom-field-actions span.edit span').click
						(
						function()
							{
							jQuery('span.db_action').text('Edit');
							edit_custom_field(jQuery(this));
							}
						);
					//END: WTF ?? //////////////////////////////////////////////////////////////////
					
					
						
					//reset button text and stamp
					jQuery(obj).val(get_button_text);
					clear_all();
					//stamp_succesfull_save(obj);
					}
				); 
			
			if(response)
				{
				jQuery('div.custom-fields-container.right ul.opt-list li input[type="text"]').each
					(
					function(index)
						{
						update_db_field(jQuery(this));
						}
					);
				clear_all();
				}
			}
		); 		
}

//This is where the option object gets created
function optionObject(_opt_id, _val, _req, _visib, _format) {
	this.id 	   = _opt_id;
	this.val 	   = _val;
	this.required  = _req;
	this.visibility = _visib;
	this.format    = _format;	
}


//Check that no speacial chars are entered into field labels
function allowed_label_Chars(input_value){
   
    var aChars = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ _-+=!@#$%^&*()*{}[]:;<>,.?~`|/';
    for(i=0;i<input_value.length;i++)
		{
		if (aChars.indexOf(input_value.charAt(i)) != -1)
			{
			num = true;
			}
		else
			{
			num = false;
			break;
			}
		}
    return num;
}
function format_illegal_chars(input_value){
	
	//input_value = input_value.replace('"','&quot;');
	//input_value = input_value.replace('\'','&quot;');
	//input_value = input_value.replace('\\','&#92;');
	
	
	for(i=0;i<input_value.length;i++)
		{
		if (input_value.charAt(i) == '"')
			{
			input_value = input_value.replace('"','&quot;');
			}
		if(input_value.charAt(i) == '\'')
			{
			input_value = input_value.replace('\'','&quot;');
			}
		if(input_value.charAt(i) == '\\')
			{
			input_value = input_value.replace('\\','&#92;');
			}
		}
	
	return input_value;	
}
//Check for duplicate option values
function find_duplicates(arr) {
	var len			= arr.length,
		duplicates	= [],
		counts		= {};
	
	for (var i=0;i<len;i++)
		{
		if(arr[i].val)
			var item 	= arr[i].val; //I just changed this from arr[i] to arr[i].val, because we now pass it an object and not an array
		else
			var item 	= arr[i];
		
		var count 	= counts[item];
		
		counts[item] = counts[item] >= 1 ? counts[item] + 1 : 1;
		}
	
	for(var item in counts)
		{
		if(counts[item] > 1)
	  		duplicates.push(item);
		}
	return duplicates;
}

//Get input values and save
function save_backup(){
	jQuery('div.backup div.group-label').html( jQuery('input[name="group-label"]').val() );
	if(jQuery('.custom-fields.right').html().length>200)
		{
		jQuery('div.backup div.group-options').html( jQuery('.custom-fields.right').html() );
		}
}

//Restore in put values on change or edit
function get_backup(){
	if(jQuery('div.backup div.group-options').html()!='')
		{
		jQuery('.custom-fields.right').html(jQuery('div.backup div.group-options').html());
		jQuery('.custom-fields.right').show();
		}
	if(jQuery('div.backup div.group-label').html()!='')
		{
		if(jQuery('input[name="group-label"]').val()==jQuery('div.backup div.group-label').html())
			{
			jQuery('input[name="group-label"]').val( jQuery('div.backup div.group-label').html() );
			}
		}
}

//Populates all steps with saved data
function edit_custom_field(obj){
	
	//clear_all();
	
	jQuery('select[name="field-type"] option[value="0"]').attr('selected', true);
	
	var data = 	
		{
		action	 	: 'edit_custom_field',
		grouplabel 	: format_illegal_chars(obj.attr("data-group-label"))
		};
	
	jQuery.post
		(
		ajaxurl, data, function(response)
			{ 
			var field = jQuery.parseJSON(response); 
			
			jQuery('div.backup div.old-group-label').text(field.grouplabel);
			 
			jQuery('div.backup div.group-label').text(field.grouplabel);
			jQuery('input[name="group-label"]').val(field.grouplabel);
			
			switch(	field.type )
				{
				case 'textgroup':
				case 'check':
					jQuery('select[name="field-type"] optgroup.single-fields').attr('disabled',true);
				break;	
				default:
					jQuery('select[name="field-type"] optgroup.multi-fields').attr('disabled',true);
				break;
				}
				
			jQuery('select[name="field-type"] option[value="'+field.type+'"]').attr('selected', true);
			jQuery('select[name="field-type"]').trigger('change');
			
			var unique_Id;
			
			var custum_field_items  = '';
			
			var defualt_field  	 = '';
				defualt_field 	+= '<input name="iz-add-option" class="iz-add-options" type="text" placeholder="'+ ((field.type=='textgroup' || field.type=='check') ? 'Add new item and hit enter' : 'Add new option and hit enter' ) + '" onfocus="if(this.value==\'\'){ show_iz_tooltip(this,\'Enter a value and hit enter\'); }" onchange="add_new_option(this);">';
				defualt_field 	+= '<div class="iz-spacer"></div>';
				defualt_field 	+= '<ul class="opt-list iz-sortable ui-sortable"></ul>';
				defualt_field 	+= '<div class="iz-spacer"></div><input name="save-field-button" class="button" type="button" value="   Update Field   " onclick="save_field(this,\'update\',format_illegal_chars(jQuery(\'input[name=group-label]\').val()));">  <input class="button" type="button" value=" Cancel " onclick="clear_all();"><div style="float:right;padding-top:4px;"><a style="text-decoration:none;" href="javascript:clear_option_list();">Clear List</a></div>';

			jQuery('div.group-options').html(defualt_field);
			
			if(field.items)
				{
				for(option in field.items.reverse() )
					{
					if(field.items[option].val)
						{
						unique_Id = '__' + Math.round(Math.random()*99999) +'__';
						//console.log(field.items[option].visibility);
						//alert(field.items[option].required	);
						custum_field_items += '<div class="group_element_attr" style="display:none;">';
						custum_field_items += '<label>Required</label>';
						custum_field_items += '<input data-attr="required" '+((field.items[option].required		=='required') 	? 	'checked="checked"' : '' )+' type="radio" name="'+ unique_Id +'field-req" value="required">&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;';
						custum_field_items += '<input data-attr="required" '+((field.items[option].required		=='') 			? 	'checked="checked"' : '' )+' type="radio" name="'+ unique_Id +'field-req" value="">&nbsp;&nbsp;No<br />';
						custum_field_items += '<div class="devider"></div>';
						custum_field_items += '<label>Visibility</label>';
						custum_field_items += '<input data-attr="visibility" '+((field.items[option].visibility	=='Private') 	? 	'checked="checked"' : '' )+' type="radio" name="'+ unique_Id +'field-visibility" value="Private">&nbsp;&nbsp;Private&nbsp;&nbsp;&nbsp;&nbsp;';
						custum_field_items += '<input data-attr="visibility" '+((field.items[option].visibility	=='Public') 	? 	'checked="checked"' : '' )+' type="radio" name="'+ unique_Id +'field-visibility" value="Public">&nbsp;&nbsp;Public<br />';
						custum_field_items += '<div class="devider"></div>';
						custum_field_items += '<label>Format</label>';
						custum_field_items += '<input data-attr="format" '+((field.items[option].format			=='text') 		? 	'checked="checked"' : '' )+' type="radio" name="'+ unique_Id +'field-format" value="text">&nbsp;&nbsp;Text&nbsp;&nbsp;&nbsp;&nbsp;';
						custum_field_items += '<input data-attr="format" '+((field.items[option].format			=='email') 		? 	'checked="checked"' : '' )+' type="radio" name="'+ unique_Id +'field-format" value="email">&nbsp;&nbsp;Email&nbsp;&nbsp;&nbsp;&nbsp;';
						custum_field_items += '<input data-attr="format" '+((field.items[option].format			=='number') 	? 	'checked="checked"' : '' )+' type="radio" name="'+ unique_Id +'field-format" value="number">&nbsp;&nbsp;Number';
						custum_field_items += '</div>';	
							
						jQuery('div.group-options ul.opt-list').prepend('<li class="iz-option"><input data-old-val="'+ field.items[option].val +'" style="'+((jQuery('select[name="field-type"]').val()=="textgroup") ? 'width:165px' : 'width:200px;' )+'"'+ ((field.type=='textgroup' || field.type=='check') ? '' : '' ) +'  type="text" value="'+ field.items[option].val +'">'+ ((field.type=="textgroup") ? '<div class="gears"></div>' + custum_field_items : '') +'<div title="Remove option" class="remove"></div><div title="Drag to position" class="sorthandle"></div></li>');	
						custum_field_items  = '';
						unique_Id = '';
						}
					else
						{
						jQuery('div.group-options ul.opt-list').prepend('<li class="iz-option"><input data-old-val="'+ field.items[option] +'" style="width:200px;" '+ ((field.type=='textgroup' || field.type=='check') ? '' : '' ) +'  type="text" value="'+ field.items[option] +'">'+ ((field.type=="textgroup") ? '<div class="gears"></div>' : '') +'<div title="Remove option" class="remove"></div><div title="Drag to position" class="sorthandle"></div></li>');
						}
					}
				}
			}
		);   	
}

//Rename database column/field
function update_db_field(obj){
	var data = 
		{
		action	 		: 'update_db_field',
		old_value		: jQuery(obj).attr('data-old-val'),			
		old_grouplabel	: format_illegal_chars(jQuery('div.backup div.old-group-label').text()),	
		grouplabel 		: format_illegal_chars(jQuery('input[name="group-label"]').val()),		
		new_value		: jQuery(obj).val()
        };
		
	if(data.old_value!=data.new_value)
		{			
		jQuery.post
			(
			ajaxurl, data, function(response)
				{
				 jQuery(obj).val(data.new_value);
				}
			);
		}	
}

//Removes fields from froms and custom panel
//Adds archive flag to database field
function delete_custom_field(obj){
	
	var get_color = obj.parent().parent().parent().parent().css('background-color');	
	
	obj.parent().parent().parent().parent().css('background','#FFEBE8');	
		
	if(confirm('Are your sure you want to permanently delete this field?'))
		{
		clear_all();
		
		var data = 
			{
			action	 		: 'delete_custom_field',
			grouplabel 		: format_illegal_chars(obj.attr("data-group-label"))
			};
		
		obj.parent().parent().parent().parent().fadeOut
			(
			'slow', function()
				{
				jQuery(this).parent().remove();
				}
			); 
			
		jQuery.post
			(
			ajaxurl, data, function(response)
				{
				core_object.trigger("update_form_fields");
				}
			);   
		}
	else
		{
		obj.parent().parent().parent().parent().css('background',get_color);
		}	
}

//Delete all element group options
function clear_option_list(){
	jQuery('ul.opt-list li.iz-option').each
		(
		function()
			{	
			jQuery(this).find('div.remove').trigger('click');
			}
		);
}

//Clear all steps
function clear_all(){
	//Backup
	jQuery('div.backup div.group-options').html('');
	jQuery('div.backup div.group-label').html('');
	jQuery('div.backup div.old-group-label').text('');
	
	//Current save
	jQuery('.custom-fields.right').html('');
	jQuery('input[name="group-label"]').val('');
	
	//DB action
	jQuery('span.db_action').text('Add');
	
	//Set step 1 back to select fields
	jQuery('select[name="field-type"] option[value="0"]').attr('selected', true);
	jQuery('select[name="field-type"]').trigger('change');
	jQuery('select[name="field-type"] optgroup.single-fields').attr('disabled',false);	
	jQuery('select[name="field-type"] optgroup.multi-fields').attr('disabled',false);
}


function show_textgroup_attribs(obj){
	
			/*var obj_pos	= jQuery(obj).position();
			if(!obj_pos)
				return false;
			
			element_attr = jQuery(obj).next('div.group_element_attr');
			
			element_attr.css({
				position:'absolute'
				});
			element_attr.css('left',(obj_pos.left+jQuery(obj).outerWidth())-jQuery('div.group_element_attr').outerWidth());
			element_attr.css('top',obj_pos.top-jQuery('div.group_element_attr').outerHeight());
			element_attr.show();*/

			
}


