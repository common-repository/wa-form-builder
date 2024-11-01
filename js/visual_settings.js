// JavaScript Document

function colorToHex(color) {
    if (color.substr(0, 1) === '#') {
        return color;
    }
    var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);
    
    var red = parseInt(digits[2]);
    var green = parseInt(digits[3]);
    var blue = parseInt(digits[4]);
    
    var rgb = blue | (green << 8) | (red << 16);
    return digits[1] + '#' + rgb.toString(16);
};
var val = '';

var currnet_top = 0;
var currnet_left = 0;
var currnet_width = 0;
var currnet_height = 0;

var minimize_button = '';

function strstr(haystack, needle, bool) {
    var pos = 0;

    haystack += "";
    pos = haystack.indexOf(needle); if (pos == -1) {
       return false;
    } else {
       return true;
    }
}
jQuery(document).ready(
function()
	{
	jQuery('input.revert_default').click(
		function()
			{
			var params = '';
			var url = jQuery('input[name="current_page"]').val();
			
			if(jQuery('input[name="save_for"]:checked').val()=='all')
				{
				if(confirm('Are your sure you want restore defaults for ALL forms!\n\n Note: This will undo saved styling for ALL forms added on all pages accross the site!\n\n If you want to restore defaults for the current form only:\n 1: click "Cancel"\n 2: Select "this form only"\n 3: Click "Restore Defualts" agian.'))
					{
					if(strstr(url,'?'))
						params = '&default=true&restore=all';
					else
						params = '?default=true';
						document.location.href = url + params;	
					}
				}
			else
				{
				if(confirm('Are your sure you want restore defaults for current forms!\n\n Note: This will undo styling saved on this form only!'))
					{
					if(strstr(jQuery('input[name="current_page"]').val(),'?'))
						params = '&default=true&restore=current_form';
					else
						params = '?default=true';
						document.location.href = jQuery('input[name="current_page"]').val() + params;	
					}
				
				}
			}
		);
		
	jQuery('div.minimize').toggle(
		function()
			{
			currnet_top = jQuery('div.visual_form_settings').css('top').replace('px','');
			currnet_left = jQuery('div.visual_form_settings').css('left').replace('px','');
			currnet_width = jQuery('div.visual_form_settings').css('width').replace('px','');
			currnet_height = jQuery('div.visual_form_settings').css('height').replace('px','');
			
			var minimize_button = jQuery(this).css('background');
			
			jQuery(this).css('background','none');
			jQuery(this).attr('title','Restore');
			
			jQuery('div.visual_form_settings').css('overflow','hidden');
			jQuery('div.visual_form_settings').animate(
					{
					top: window.innerHeight-38,
					left:0,
					width: 45
					},200
				);
			},
		function()
			{
			jQuery('div.visual_form_settings').css('overflow','visible');
			
			jQuery(this).css('background',minimize_button);
			
			jQuery(this).attr('title','Minimize');
			
				jQuery('div.visual_form_settings').animate(
					{
					top: currnet_top,
					left:currnet_left,
					width: currnet_width
					},300
				);
				
			}
		);
	jQuery('input.color').focus(
		function()
			{
				var offset = jQuery(this).offset();
				var width = jQuery(this).width();
				var height = jQuery('div.color_pallet').height();
				
				jQuery(this).addClass('current');
				
				jQuery('div.color_pallet').show();
				jQuery('div.color_pallet').css('left',offset.left);
				jQuery('div.color_pallet').css('top',offset.top + 153);
				jQuery('div.color_pallet').css('z-index','1001');
			}
		);
	jQuery('input.color').blur(
		function()
			{	
			setTimeout(function(){ jQuery('div.color_pallet').hide();  }, 100);
			}
		);
	jQuery('div.theme_color').each(
		function()
			{
			jQuery(this).click(
				function()
					{
					jQuery('input.current').css('background','#'+jQuery(this).data('color'));
					jQuery('input.current').val(''+jQuery(this).data('color'));	
					jQuery('div.color_pallet').hide();
					jQuery('input.color').trigger('change');
					jQuery('input.color').removeClass('current');
					}
				);
			}
		);
	
	
	
	 jQuery( "#visual_settings" ).accordion({
		collapsible: true,
		heightStyle: "content",
		active: false
	});
	
	jQuery('div.visual_form_settings').draggable(
			{
			stop: function( event, ui )
				{ 
				var data = 
					{
					action	 	: 'save_visual_settings_panel_position',
					position	: jQuery(this).css('top')+','+jQuery(this).css('left')
					};
				
				jQuery.post
					(
					jQuery('input[name="ajaxurl"]').val(), data, function(response)
						{
						
						}
					);			
				}
			}
        );
	
		
	var settings_array = new Array('padding-top', 'padding-right', 'padding-bottom', 'padding-left', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left', 'font-size','border', 'font-family', 'width', 'height', 'border-width', 'border-style', 'color', 'background-color', 'border-color', 'border', 'border-radius', 'font-weight', 'font-style', 'text-decoration', 'line-height', 'text-align', 'letter-spacing', 'text-transform', 'float');
	var css = '';
	var style_keys = new Array(); 
	var style_values = new Array(); 
	jQuery('input[name="save_visual_settings"]').click(
		function()
			{
			css = '';
			jQuery(	'div.wa_form_wrap div.wa_form_ui, div.wa_form_wrap fieldset, div.wa_form_wrap fieldset label, div.wa_form_wrap legend, div.wa_form_wrap input[type="text"],div.wa_form_wrap input.submit_form_entry, div.wa_form_wrap select, div.wa_form_wrap textarea, div.wa_form_wrap p.form_description, p.confirmation_message, div.wa_form_wrap h2').each(
				function()
					{
					css += ' #'+jQuery(this).attr('id') +', .'+jQuery(this).attr('id') +'{';
					
					if(jQuery(this).attr('style'))
						{	
						var current_style = jQuery(jQuery(this)).attr('style');
						var style_array = current_style.split(';');
						
						for (var i = 0; i < style_array.length; i++)
							{
							if(style_array[i])
								{
								var attribute = style_array[i].split(':');
								var key = attribute[0];
								var val = attribute[1];
								style_keys[i] = key;
								style_values[i] = val;
								
								css += style_array[i] +';';
								}
							}
						}
					for (var j = 0; j < settings_array.length; j++)
						{
						var in_array = jQuery.inArray(settings_array[j],style_keys);
						if(in_array)
							{
							if( settings_array[j] == 'border')
								{
								css += 'border-style' +':' + jQuery(this).css('border-top-style') + ';';
								css += 'border-width' +':' + jQuery(this).css('border-top-width') + ';';
								css += 'border-color' +':' + jQuery(this).css('border-top-color') + ';';	
								}
							else if( settings_array[j] == 'height')
								{
								css += 'height' +':auto;';
								}
							else
								css += settings_array[j] +':' + jQuery(this).css(settings_array[j]) + ';';							
							}
						}
					css += '} ';
					}
				);
			
			jQuery('div.css').html(css);
				
				
			var data = 
				{
				action	 	: 'save_visual_settings',
				css			: jQuery('div.css').html(),
				form_Id		: jQuery('input[name="wa_forms_Id"]').val(),
				save_for	: jQuery('input[name="save_for"]:checked').val()
				};
			if(jQuery('input[name="save_for"]:checked').val()=='all')
				{
				if(confirm('Are your sure you want to save these visual settings for all forms?'))
					{	
					jQuery(this).val('Saving...');
				
					jQuery.post
						(
						jQuery('input[name="ajaxurl"]').val(), data, function(response)
							{
							jQuery('div, fieldset, input, legend, select, textarea, p, h2').removeClass('show_outline');
							//jQuery('div.drop-sort').html(response);
							jQuery('input[name="save_visual_settings"]').val('    Your settings have been saved!    ');
							setTimeout(function() { jQuery('input[name="save_visual_settings"]').val('    Save Settings    ') }, 2500);
							}
						);
					}
				}
			else
				{
				jQuery(this).val('Saving...');
			
				jQuery.post
					(
					jQuery('input[name="ajaxurl"]').val(), data, function(response)
						{
						jQuery('div, fieldset, input, legend, select, textarea, p, h2').removeClass('show_outline');
						jQuery('input[name="save_visual_settings"]').val('    Your settings have been saved!    ');
						setTimeout(function() { jQuery('input[name="save_visual_settings"]').val('    Save Settings    ') }, 2500);
						}
					);
				}
			}
		);
	
	jQuery(	'div.wa_form_wrap div.wa_form_ui, div.wa_form_wrap fieldset, div.wa_form_wrap legend, div.wa_form_wrap input[type="text"],div.wa_form_wrap  input.submit_form_entry, div.wa_form_wrap fieldset label, div.wa_form_wrap select, div.wa_form_wrap textarea, div.wa_form_wrap p.form_description, p.confirmation_message, div.wa_form_wrap h2').each(
		function()
			{
			jQuery(this).hover(
				function()
					{
					//remove parent box-shadows
					jQuery('div, fieldset, input, legend, select, textarea, p, h2').removeClass('show_outline');
					jQuery(this).addClass('show_outline');
					
					},
				function()
					{
					jQuery(this).removeClass('show_outline');
					}
				);
			}
		);
	
	jQuery('.show_outline').live('click',
						function(e)
							{
							//jQuery('input.save_visual_settings').attr('disabled',false);
							//e.preventDefault();
							jQuery('div, fieldset, input, legend, select, textarea, p, h2').removeClass('current_selection');
							jQuery('div, fieldset, input, legend, select, textarea, p, h2').removeClass('faded');
							
							jQuery('div.current_object').text(jQuery(this).attr('id'));
							jQuery('div.element_title').text(jQuery('div.current_object').text().replace('_',' '));
							jQuery('.' + jQuery('div.current_object').text()).addClass('current_selection');
							
							
							jQuery( "#slider-height" ).slider({
								range: "max",
								min: 0,
								max: (jQuery('.' + jQuery('div.current_object').text()).height()>500) ? jQuery('.' + jQuery('div.current_object').text()).height()*2 : 500,
								value: 0,
								slide: function( event, ui ) {
								jQuery( 'input[name="height"]' ).val( ui.value );
								jQuery('.' + jQuery('div.current_object').text()).each(
									function()
										{
											jQuery(this).css('height',ui.value +'px');
										}
									);
								}
							});
							
							setTimeout(function() { jQuery('.' + jQuery('div.current_object').text()).addClass('faded'); }, 2500);
							
							for (var j = 0; j < settings_array.length; j++)
								{
								var num_current_val = 0;
								var current_val = jQuery('.' + jQuery('div.current_object').text()).css(settings_array[j]);
								if(current_val)
									num_current_val = current_val.replace('px','').replace('%','')
								
									if(settings_array[j]=='font-family')
										{
										var get_font = num_current_val.replace('"','');
										var set_font = get_font.split(',');
										
										if(!set_font[0])
											{
											jQuery('select[name="'+ settings_array[j] +'"] option[value="'+ get_font.replace('"','') +'"]').attr('selected',true);	
											}
										else
											jQuery('select[name="'+ settings_array[j] +'"] option[value="'+ set_font[0].replace('"','') +'"]').attr('selected',true);
										}
										
									else if(settings_array[j]=='text-align')
										{
										jQuery('select[name="'+ settings_array[j] +'"] option[value="'+ num_current_val +'"]').attr('selected',true);	
										}
									else if(settings_array[j]=='text-transform')
										{
										jQuery('select[name="'+ settings_array[j] +'"] option[value="'+ num_current_val +'"]').attr('selected',true);	
										}
									else if(settings_array[j]=='display')
										{
										jQuery('select[name="'+ settings_array[j] +'"] option[value="'+ num_current_val +'"]').attr('selected',true);	
										}
									else if(settings_array[j]=='float')
										{
										jQuery('select[name="'+ settings_array[j] +'"] option[value="'+ num_current_val +'"]').attr('selected',true);	
										}
									
									else if(settings_array[j]=='border')
										{
										jQuery('select[name="border-style"] option[value="'+ jQuery('.' + jQuery('div.current_object').text()).css('border-top-style') +'"]').attr('selected',true);
										jQuery( 'input[name="border-color"]' ).attr('style','background-color:'+ colorToHex(jQuery('.' + jQuery('div.current_object').text()).css('border-top-color')) +';');
										jQuery( 'input[name="border-color"]' ).val(colorToHex(jQuery('.' + jQuery('div.current_object').text()).css('border-top-color')));
										
										jQuery( "#slider-border-width" ).slider({value: jQuery('.' + jQuery('div.current_object').text()).css('border-top-width').replace('px','').replace('%','') || 0});
										jQuery( 'input[name="border-width"]' ).val( jQuery('.' + jQuery('div.current_object').text()).css('border-top-width').replace('px','').replace('%','') || 0 );
									
										jQuery( "#slider-border-radius" ).slider({value: jQuery('.' + jQuery('div.current_object').text()).css('border-radius-topleft') || 0});
										jQuery( 'input[name="border-radius"]' ).val( jQuery('.' + jQuery('div.current_object').text()).css('border-radius-topleft') || 0 );
										}
											
										
									
									else if(settings_array[j]=='font-weight')
										{
										if(num_current_val>=700 || num_current_val=='bold')
											jQuery('div.font-weight').addClass('selected');
										else
											jQuery('div.font-weight').removeClass('selected');
										}
									else if(settings_array[j]=='font-style')
										{
										if(num_current_val=='italic')
											jQuery('div.font-style').addClass('selected');
										else
											jQuery('div.font-style').removeClass('selected');
										}
									else if(settings_array[j]=='text-decoration')
										{
										if(num_current_val=='underline')
											jQuery('div.text-decoration').addClass('selected');
										else
											jQuery('div.text-decoration').removeClass('selected');
										}
									else if(settings_array[j]=='width')
										{
											var width = ( 100 * parseFloat(jQuery('.' + jQuery('div.current_object').text()).css('width')) / parseFloat(jQuery('.' + jQuery('div.current_object').text()).parent().css('width')) );
											jQuery( "#slider-"+settings_array[j] ).slider({value: Math.floor(width)|| 0});
											jQuery( 'input[name="'+settings_array[j]+'"]' ).val(  Math.floor(width) || 0 );
										}
								
								
									else if(settings_array[j]=='color' || settings_array[j]=='background-color')
										{
										if(num_current_val!='transparent' && num_current_val!='')
											{
											jQuery( 'input[name="'+settings_array[j]+'"]' ).attr('style','background-color:'+ colorToHex(num_current_val) +';');
											jQuery( 'input[name="'+settings_array[j]+'"]' ).val(colorToHex(num_current_val));
											}
										else
											{
											jQuery( 'input[name="'+settings_array[j]+'"]' ).attr('style','background-color:transparent;');
											jQuery( 'input[name="'+settings_array[j]+'"]' ).val('transparent');
											}
										}
									else
										{
										jQuery( "#slider-"+settings_array[j] ).slider({value: num_current_val || 0});
										jQuery( 'input[name="'+settings_array[j]+'"]' ).val( num_current_val || 0 );
										}
								
								}
								
							
								
							if(jQuery('.' + jQuery('div.current_object').text()).attr('style'))
								{ //'undefined'
								var current_style = jQuery('.' + jQuery('div.current_object').text()).attr('style');
								var style_array = current_style.split(';');
								
								for (var i = 0; i < style_array.length; i++)
									{
									var attribute = style_array[i].split(':');
									var key = attribute[0];
									var val = attribute[1];
									//alert(key);
									if(val)
										{
										var value =  val.replace('px','').replace('%','');
										for (var k = 0; k < settings_array.length; k++)
											{
											
											if(key==settings_array[k])
												{
												//console.log("#slider-"+settings_array[k]);
												//console.log("value"+value);
												jQuery( "#slider-"+settings_array[k] ).slider({value: value});
												jQuery( 'input[name="'+settings_array[k]+'"]' ).val( value );
												break;
												}
											if(key=='color')
												{
												jQuery( 'input[name="color"]' ).attr('style','background-color:'+ colorToHex(value) +';');
												jQuery( 'input[name="color"]' ).val( colorToHex(value) );
												}
											if(key=='background-color')
												{
												jQuery( 'input[name="background-color"]' ).attr('style','background-color:'+ colorToHex(value) +';');
												jQuery( 'input[name="background-color"]' ).val( colorToHex(value) );
												}
											if(key=='border-color')
												{
												jQuery( 'input[name="border-color"]' ).attr('style','background-color:'+ colorToHex(value) +';');
												jQuery( 'input[name="border-color"]' ).val( colorToHex(value) );
												}
											}
										}
									}
								}
							
							}
						);
			
	//Figure this out
	/*for (var k = 0; k < settings_array.length; k++)
		{
		jQuery( "#slider-"+settings_array[k] ).slider
			({
				range: "max",
				min: 0,
				max: 100,
				value: 0,
				slide: function( event, ui ) {
				jQuery( 'input[name="'+settings_array[k]+'"]' ).val( ui.value );
				jQuery('.' + jQuery('div.current_object').text()).each(
					function()
						{
							jQuery(this).css(settings_array[k],ui.value +'px');
						}
					);
				}
			});
		}*/
	
	jQuery('input[name="color"]').change(
		function()
			{
				jQuery('input[name="color"]').css('background-color',jQuery('input[name="color"]').val())
				jQuery('.' + jQuery('div.current_object').text()).each(
					function()
						{
						jQuery(this).css('color',jQuery('input[name="color"]').val());
						}
					);
			}
		);
	jQuery('input[name="background-color"]').change(
		function()
			{
				jQuery('input[name="background-color"]').css('background-color',jQuery('input[name="background-color"]').val())
				jQuery('.' + jQuery('div.current_object').text()).each(
					function()
						{
						jQuery(this).css('background-color',jQuery('input[name="background-color"]').val());
						}
					);
			}
		);
	jQuery('input[name="border-color"]').change(
		function()
			{
				jQuery('input[name="border-color"]').css('background-color',jQuery('input[name="border-color"]').val())
				jQuery('.' + jQuery('div.current_object').text()).each(
					function()
						{
						jQuery(this).css('border-color',jQuery('input[name="border-color"]').val());
						}
					);
			}
		);
	jQuery('select[name="text-transform"]').change(
		function()
			{
				jQuery('.' + jQuery('div.current_object').text()).each(
					function()
						{
						jQuery(this).css('text-transform',jQuery('select[name="text-transform"]').val());
						}
					);
			}
		);	
	jQuery('select[name="border-style"]').change(
		function()
			{
				jQuery('.' + jQuery('div.current_object').text()).each(
					function()
						{
						jQuery(this).css('border-style',jQuery('select[name="border-style"]').val());
						}
					);
			}
		);
	jQuery('select[name="font-family"]').change(
		function()
			{
				jQuery('.' + jQuery('div.current_object').text()).each(
					function()
						{
						jQuery(this).css('font-family',jQuery('select[name="font-family"]').val());
						}
					);
			}
		);
	jQuery('select[name="text-align"]').change(
		function()
			{
			jQuery('.' + jQuery('div.current_object').text()).each(
				function()
					{
					jQuery(this).css('text-align',jQuery('select[name="text-align"]').val());
					}
				);
			}
		);	
	
	jQuery('select[name="display"]').change(
		function()
			{
			jQuery('.' + jQuery('div.current_object').text()).each(
				function()
					{
					jQuery(this).css('display',jQuery('select[name="display"]').val());
					}
				);
			}
		);	
		
	jQuery('select[name="float"]').change(
		function()
			{
			jQuery('.' + jQuery('div.current_object').text()).each(
				function()
					{
					jQuery(this).css('float',jQuery('select[name="float"]').val());
					}
				);
			}
		);	
		
		
	var font_style = 'normal';
	var font_weight = 'normal';
	var text_decoration = 'none';
	jQuery('div.font-weight').click(
		function()
			{
			if(jQuery('div.font-weight').hasClass('selected'))
				{
				font_weight = 'normal';
				jQuery('div.font-weight').removeClass('selected')
				}
			else
				{
				font_weight = 'bold';
				jQuery('div.font-weight').addClass('selected')
				}
			jQuery('.' + jQuery('div.current_object').text()).each(
				function()
					{
					jQuery(this).css('font-weight',font_weight);
					}
				);
			}
		);
	jQuery('div.font-style').click(
		function()
			{
			
			if(jQuery(this).hasClass('selected'))
				{
				font_style = 'normal';
				jQuery(this).removeClass('selected')
				}
			else
				{
				font_style = 'italic';
				
				jQuery(this).addClass('selected')
				}
			jQuery('.' + jQuery('div.current_object').text()).each(
				function()
					{
					jQuery(this).css('font-style',font_style);
					}
				);
			}
		);
	jQuery('div.text-decoration').click(
		function()
			{
			
			if(jQuery(this).hasClass('selected'))
				{
				text_decoration = 'none';				
				jQuery(this).removeClass('selected')
				}
			else
				{
				text_decoration = 'underline';
				jQuery(this).addClass('selected')
				}
			jQuery('.' + jQuery('div.current_object').text()).each(
				function()
					{
					jQuery(this).css('text-decoration',text_decoration);
					}
				);
			}
		);
	
	jQuery( "#slider-padding-top" ).slider({
		range: "max",
		min: 0,
		max: 100,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="padding-top"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('padding-top',ui.value +'px');
				}
			);
		}
	});
	
	jQuery( "#slider-padding-right" ).slider({
		range: "max",
		min: 0,
		max: 100,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="padding-right"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('padding-right',ui.value +'px');
				}
			);
		}
	});
	
	jQuery( "#slider-padding-bottom" ).slider({
		range: "max",
		min: 0,
		max: 100,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="padding-bottom"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('padding-bottom',ui.value +'px');
				}
			);
		}
	});
	
	jQuery( "#slider-padding-left" ).slider({
		range: "max",
		min: 0,
		max: 100,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="padding-left"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('padding-left',ui.value +'px');
				}
			);
		}
	});
	
	jQuery( "#slider-margin-top" ).slider({
		range: "max",
		min: 0,
		max: 100,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="margin-top"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('margin-top',ui.value +'px');
				}
			);
		}
	});
	
	jQuery( "#slider-margin-right" ).slider({
		range: "max",
		min: 0,
		max: 100,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="margin-right"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('margin-right',ui.value +'px');
				}
			);
		}
	});
	
	jQuery( "#slider-margin-bottom" ).slider({
		range: "max",
		min: 0,
		max: 100,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="margin-bottom"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('margin-bottom',ui.value +'px');
				}
			);
		}
	});
	
	jQuery( "#slider-margin-left" ).slider({
		range: "max",
		min: 0,
		max: 100,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="margin-left"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('margin-left',ui.value +'px');
				}
			);
		}
	});
	
	
	jQuery( "#slider-border-width" ).slider({
		range: "max",
		min: 0,
		max: 30,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="border-width"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('border-width',ui.value +'px');
				}
			);
		}
	});
	

		/*var border_style_select = jQuery( 'select[name="border-style"]' );
		var slider = jQuery( "<div id='slider-border-style'></div>" ).insertAfter( border_style_select ).slider({
		min: 1,
		max: 6,
		range: "min",
		value: border_style_select[ 0 ].selectedIndex + 1,
		slide: function( event, ui ) {
		border_style_select[ 0 ].selectedIndex = ui.value - 1;
		}
		});
		border_style_select.change(function() {
		slider.slider( "value", this.selectedIndex + 1 );
		});*/
	
	jQuery( "#slider-width" ).slider({
		range: "max",
		min: 0,
		max: 100,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="width"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('width',ui.value +'%');
				}
			);
		}
	});
	
	
	
	jQuery( "#slider-font-size" ).slider({
		range: "max",
		min: 10,
		max: 50,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="font-size"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('font-size',ui.value +'px');
				}
			);
		}
	});
	
	jQuery( "#slider-letter-spacing" ).slider({
		range: "max",
		min: 0,
		max: 30,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="letter-spacing"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('letter-spacing',ui.value +'px');
				}
			);
		}
	});

	jQuery( "#slider-line-height" ).slider({
		range: "max",
		min: 14,
		max: 54,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="line-height"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('line-height',ui.value +'px');
				}
			);
		}
	});
	
	jQuery( "#slider-border-radius" ).slider({
		range: "max",
		min: 0,
		max: 40,
		value: 0,
		slide: function( event, ui ) {
		jQuery( 'input[name="border-radius"]' ).val( ui.value );
		jQuery('.' + jQuery('div.current_object').text()).each(
			function()
				{
					jQuery(this).css('border-radius',ui.value +'px');
				}
			);
		}
	});
	
	jQuery("div.visual_form_settings").show();

});