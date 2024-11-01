// JavaScript Document

function add_tutorial_popup(obj,text,subtext,chef_stance,extra_left,extra_top,extra_width){

  	var obj_pos	= jQuery(obj).offset();
	if(!obj_pos)
		return false;

	if(!extra_left)
		extra_left = 0;
	if(!extra_top)
		extra_top = 0;
	if(!chef_stance)
		chef_stance = 'point_left';
	if(!extra_width)
		extra_width = (window.innerWidth/4);
		
	if(jQuery('div.tutorial').length<1)
		jQuery('body').prepend('<div class="tutorial" style="display:none;">			<div class="chef"></div>			<div class="speach_bubble">	<!--<div class="dismiss">X</div>-->			<p class="help_text"></p>				<div class="bubble_arrow"></div>				<div class="sub_bubble">					<p class="help_text"></p>					<div class="bubble_arrow"></div></div></div></div>');
	
	
	
		jQuery('div.chef').removeClass('point_left');
		jQuery('div.chef').removeClass('normal');
		
		jQuery('div.chef').addClass(chef_stance);
		
		jQuery('div.tutorial').fadeOut('fast',
		
		function()
			{
			jQuery('div.tutorial div.speach_bubble div.sub_bubble').hide().removeClass('open');
			
			
			jQuery('div.tutorial').css('max-width',(window.innerWidth/5) +'px');
			
			if(extra_width)
				jQuery('div.tutorial').css('max-width',extra_width +'px');
			
			
			
			jQuery('div.tutorial').css('position','absolute');
			jQuery('div.tutorial div.speach_bubble > p.help_text').html(text);
			jQuery('div.tutorial div.speach_bubble div.sub_bubble > p.help_text').html(subtext);
			jQuery('div.tutorial').css('z-index','999');
			jQuery('div.tutorial').css('left',obj_pos.left+extra_left);
			jQuery('div.tutorial').css('top',obj_pos.top-jQuery('div.chef').outerHeight()+extra_top);
			
			if(!jQuery('div.tutorial').hasClass('exit'))
				{
				jQuery('div.tutorial').fadeIn('fast');
				}
			var chef_pos	= jQuery(jQuery('div.tutorial')).offset();
			
			jQuery("html, body").animate(
								{
									scrollTop:obj_pos.top-jQuery('div.chef').outerHeight()-extra_top-150
								},700
							)
			}
		);
}

jQuery(document).ready(
function()
	{
	jQuery('div.exit-tutorial a').toggle(
		function()
			{
			jQuery(this).addClass('exit');
			jQuery('div.tutorial').addClass('exit');
			jQuery('div.tutorial').fadeOut('slow');
			},
		function()
			{
			jQuery(this).removeClass('exit');
			jQuery('div.tutorial').removeClass('exit');
			jQuery('div.tutorial').fadeIn('fast');
			}
		);		
		
	jQuery('div.extra_help').live('click',
		function()
			{
			var sub_bubble =jQuery(this).closest('div.speach_bubble').find('div.sub_bubble');
			
			if(sub_bubble.hasClass('open'))
				{
					//sub_bubble.slideUp('fast');
					if(jQuery(this).hasClass('go_right'))
						sub_bubble.animate({width:0,right:200},300,function(){ sub_bubble.hide(); });
					if(jQuery(this).hasClass('go_below'))
						{
						
						sub_bubble.slideUp('fast',function(){ 
							sub_bubble.css('width', 0);
							sub_bubble.css('top', -40);
							sub_bubble.css('left', 305);
						 });
						}
					sub_bubble.removeClass('open');
					jQuery('div.extra_help div.confirm').remove();
					
				}
			else
				{
					//sub_bubble.fadeIn('fast');
					jQuery('div.extra_help').append('<div class="confirm">Okay, I\'ve got it!</div>')
					
					
					if(jQuery(this).hasClass('go_right'))
						{
						sub_bubble.animate({width:400,right:-440});
						sub_bubble.show();
						}
					if(jQuery(this).hasClass('go_below'))
						{
						sub_bubble.css('width', jQuery(this).closest('div.speach_bubble').width());
						sub_bubble.css('left', 30);
						sub_bubble.css('top', jQuery(this).closest('div.speach_bubble').outerHeight()-15);
						sub_bubble.slideDown('fast');
						}
					
					
					sub_bubble.addClass('open');
				}
				
			}
		);
		
  

/*************************/
/**** Form validation ****/
/*************************/
	jQuery('input.submit_form_entry').click(
		
		function() {
			
			validate_form();
			}
		);
	}
);


			
function onError(i, v, msg) {
				if(!i.hasClass('has_errors')){
					i.closest('fieldset').prepend('<div class="error">'+ msg +'</div>');
					i.addClass('has_errors');
				}
			}
function onSuccess(i, v) {
				i.removeClass('has_errors');
				i.closest('fieldset').find('div.error').remove();
			}

function isNumber(n) {
				   if(n!='')
                  		return !isNaN(parseFloat(n)) && isFinite(n);
					
					return true;
                }

                function IsValidEmail(email){
                  
				  if(email!=''){
				    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
                    return filter.test(email);
				  }
					return true;
                }

                function allowedChars(input_value, accceptedchars){
                    var aChars = ' -_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    if(accceptedchars) {
                        switch(accceptedchars) {
                            case 'tel': aChars = '1234567890-+() '; break;
                            case 'text': aChars = ' -_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
                            default: aChars = accceptedchars; break;
                        }
                    }

                    var valid = false;
                    var txt = input_value.toString();

                    for(var i=0;i<txt.length;i++) {
                        if (aChars.indexOf(txt.charAt(i)) != -1) {
                            valid = true;
                        } else {
                            valid = false;
                            break;
                        }
                     }
                    return valid;
                }

                function isValidDate(input_value){
                    var regex = new RegExp(/[0-9]{4}\/[0-9]{2}\/[0-9]{2}/);
                    var date = input_value.search(regex);
                    return !date;
                }

function changeCss(input, styles) {                   
                   
                   if(!styles || styles === true) {
                       //revert to the old styles
                       var style = input.data('jb-oldstyle');
                       if(style)
                        input.css( style );
                        return;
                   }
                   
                   //otherwise change the styles to the ones provided                   
//                   var oldstyles = styles;      //this is neccesary because I want to (later) revert only the styles I changed and keep all styles intact
                   var oldstyles = $.extend(oldstyles, styles);      //this is neccesary because I want to (later) revert only the styles I changed and keep all styles intact
//                   var oldstyles = {};                   
                   
                   input.css( styles )
                   for(var key in styles){
                      var oldstyle = input.css(key);
                      oldstyles[key] = oldstyle;
                      input.data('jb-oldstyle', oldstyles )                      
                   }
               }
function validate_form(){
	
	var formdata = {
                   radios : [], //an array of already validate radio groups
                   checkboxes : [], //an array of already validate checkboxes
                   runCnt : 0, //number of times the validation has been run
                   errors: 0
               }
	
	var defaultErrorMsgs = {
                    email : 'Not a valid email address',
                    number: 'Not a valid phone number',
                    required: 'Please enter a value',
                    text: 'Only text allowed'
                }
	
	var settings = {
                'requiredClass': 'required',
                'customRegex': false,
				'errors' : 0,
                'checkboxDefault': 0,
                'selectDefault': 0,
                'beforeValidation': null,
                'onError': null,
                'onSuccess': null,
                'beforeSubmit': null, 
                'afterSubmit': null,
                'onValidationSuccess': null,
                'onValidationFailed': null
            };
	
	
	jQuery('#add_form_entry').find('input, select.required, textarea.required').each( function() {
                        var input = jQuery(this);
                        var val = input.val();                                                                                
                        var name = input.attr('name');
                        
                       // console.log(input)
                        
                        if(input.is('input')) {
                            var type = input.attr('type');
                            
                            switch(type) {
								
								
                                case 'text':
									if(input.hasClass('required')) {
                                        if(val.length < 1) {
                                            settings.errors++;                                            
                                            onError(input, val, defaultErrorMsgs.required);
                                            break;
                                        }
										 else
									   	{
											onSuccess(input, val);
											if(input.hasClass('email')) {
                                       
												   if(!IsValidEmail(val)) { 
														//It's not a valid email address
			//                                           console.log(name + ' is not an email')  
														settings.errors++;
														
														onError(input, val, defaultErrorMsgs.email);                                            
													   // changeCss(input, settings.errorCss.email);
														break;
												   }
													else
													{
														onSuccess(input, val);
														break;
													}
													
												}
										if(input.hasClass('number')) {
                                        
													   if(!allowedChars(val, 'tel')) {
														   //It's not a valid number
				//                                           console.log(name + ' not a number')
															settings.errors++;                                           
															
															onError(input, val, defaultErrorMsgs.number);
															//changeCss(input, settings.errorCss.text);
															break;
													   } 
														else
														{
															onSuccess(input, val);
															break;
														}                                      
														
													}
										}
                                    }
								
                                    //validate email inputs first
                                    if(input.hasClass('email')) {
                                       
                                       if(!IsValidEmail(val)) { 
                                            //It's not a valid email address
//                                           console.log(name + ' is not an email')  
                                            settings.errors++;
                                            
                                            onError(input, val, defaultErrorMsgs.email);                                            
                                           // changeCss(input, settings.errorCss.email);
                                            break;
                                       }
									    else
									   	{
											onSuccess(input, val);
											break;
										}
                                        
                                    }
                                    //no email.. number perhaps?
                                    else if(input.hasClass('number')) {
                                        
                                       if(!allowedChars(val, 'tel')) {
                                           //It's not a valid number
//                                           console.log(name + ' not a number')
                                            settings.errors++;                                           
                                            
                                            onError(input, val, defaultErrorMsgs.number);
                                            //changeCss(input, settings.errorCss.text);
                                            break;
                                       } 
									    else
									   	{
											onSuccess(input, val);
											break;
										}                                      
                                        
                                    }
                                    //no email.. no number.. then just text
                                   /* else if(input.hasClass('text')) {
                                        
                                       if(!allowedChars(val, 'text')) {
                                           //It's not just plain text
//                                           console.log(name + ' is not text')
                                            settings.errors++;   
                                            
                                            onError(input, val, defaultErrorMsgs.text);
                                            //changeCss(input, settings.errorCss.text);
                                            break;
                                       }
									   else
									   	{
											onSuccess(input, val);
											break;
										}
                                    }*/
                                    
                                    //@TODO: The below is not neccesary (it should have class required if it reached this point, just remove the if?
                                    //ALWAYS validate required inputs
                                    
                                    
                                    //Everything passed
                                    onSuccess(input, val);
                                    //changeCss(input);
                                    
                                    //end case 'text'
                                    break;
                                case 'radio':
                                    //avoid checking the same radio group more than once                                    
                                    var radioData = formdata.radios;
                                    //console.log(radioData);
                                    
                                    if(radioData) {
                                        if(jQuery.inArray(name, radioData) >= 0) {
                                            //don't try validating it, it was already validated
                                            break;
                                        } else {
                                            //do the validation
                                            //get all radios with the same, at least 1 must be checked
                                            var radios = input.closest('form').find('input[name="'+name+'"]:checked');
                                            if(radios.length < 1) {
                                                //no checked radio was found                                                
//                                                input.css('width', '250px');
                                                settings.errors++;
                                                
                                                onError(input, val, defaultErrorMsgs.required);
                                            }
											 else
									   	{
											onSuccess(input, val);
											break;
										}                                           
                                            radioData.push(name);
                                        }                                        
                                    }                                    
                                    //update the data
                                    //$theForm.data('jbvalidation', data);                                    
                                    
                                    //end case 'radio'
                                    break;
                                case 'checkbox':
                                    //PLEASE NOTE
                                    //checkboxes are optional by nature
                                    //If however you want to validate checkboxes, uncomment the lines below (NOT TESTED)
                                    
                                    /*
                                        //avoid checking the same radio group more than once                                    
                                        var checkData = formdata.checkboxes;
                                        console.log(checkData);

                                        if(checkData) {
                                            if($.inArray(name, checkData) >= 0) {
                                                //don't try validating it, it was already validated
                                                break;
                                            } else {
                                                //do the validation
                                                //get all radios with the same, at least 1 must be checked
                                                var checkboxes = input.closest('form').find('input[name="'+name+'"]:checked');
                                                if(checkboxes.length < 1) {
                                                    //no checked radio was found
                                                    settings.errors++;
                                                    input.css('width', '250px');
                                            
                                                    trigger('onError', input, val, settings.errorMsgs.required);
                                                }                                            
                                                data.checkboxes.push(name);
                                            }                                        
                                        }                                    
                                        //update the data
                                        $theForm.data('jbvalidation', data); 
                                    */
                                    
                                    //end case 'checkbox'
                                    break;
                            }
                        }
                        
                        if(input.is('select')) {
                            if(val == settings.selectDefault) {
                                //It is a required field and the default value is still selected
                                //Throw error
//                                input.css('background', 'red')
                                settings.errors++;
                                
                                onError(input, val, defaultErrorMsgs.required);
                            }
							 else
									   	{
											onSuccess(input, val);
										}
                            
                        }
						
						if(input.is('textarea')) {
                            if(val == '') {
                                //It is a required field and the default value is still selected
                                //Throw error
//                                input.css('background', 'red')
                                settings.errors++;
                                
                                onError(input, val, defaultErrorMsgs.required);
                            }
							 else
									   	{
											onSuccess(input, val);
										}
                            
                        }
                    
                    })                       
                       
                   if(settings.errors == 0) { 
					   document.forms["add_form_entry"].submit(
					   /*function()
					   	{
							alert('test');
							jQuery.post(jQuery("#add_form_entry").attr("action"), jQuery("#add_form_entry").serialize(), function(data){
								//do stuff here...
								alert('test');
							});
							//Important. Stop the normal POST
							return false;
						}*/
					   
					   );
                   } else {
					   var offset = jQuery('div.error').offset();
					
					//scroll to the first error message
						jQuery("html, body").animate(
							{
								scrollTop:offset.top-50
							},700
						)
						return false;
				   }
}