// JavaScript Document
var step = 0;
var tutorial = true;
jQuery(document).ready(
function()
	{
	var chef_normal = 'normal';
	var chef_piont_left = 'point_left';
	
	var extra_left = 300;
	var extra_top = 50;
	
	jQuery('div.nextstep').live('click',
		function()
			{
			if(jQuery(this).data('step'))
				step = jQuery(this).data('step');
			else if(jQuery(this).hasClass('back'))
				step --;
			else
				step ++;
			
			if(step==1)
				add_tutorial_popup(jQuery('fieldset.title'),'<strong>Title</strong><br />This is were we enter the form title which will be your idetifier for this form throughout the WA Forms Builder interface. Dont leave this field blank! <br> The title will appear at the top of the form on your web page if your general settings specifies it.<br/ ><br/ >Lets Enter a name and go to the next step!<div class="spacer"></div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==2)
				add_tutorial_popup(jQuery('fieldset.description'),'<strong>Description</strong><br />The description of a form will display under the form title on your web page if your general settings specifies it.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==3)
				add_tutorial_popup(jQuery('fieldset.mail_to'),'<strong>Mail to</strong><br>Here you can add mail addresses seperated by commas to which the forms entries sould be mail to i.e. you@yourcompany.com, yourcolleague@yourcompany.com. <br><br>Note: if you choose to leave this field empty the email addresses specified in your general settings will receive it.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==4)
				add_tutorial_popup(jQuery('fieldset.confirmation_mail_subject'),'<strong>Mail Subject</strong><br>This is where you enter the subject line of the email. The email addresse(s) specified in the "mail to" field above will receive the email with this subject line.<br><br>Note: if you choose to leave this field empty the mail subject specified in your general settings will be used.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==5)
				add_tutorial_popup(jQuery('fieldset.confirmation_mail_body'),'<strong>Email Body</strong><br>What you enter in here will be emailed to the specified email addresses.<br></br>We can handle this in 3 different ways described below (Default, Plain text and HTML)<br><br><ul><li><strong><em>Default(Blank)</em></strong><br>If this field is left blank, by default the mail will only contain the form fields and the values entered by the user.<br><br> For example if your form had only a "name" and a "email" to complete, the mail sent to the specified email addresses will look something like this:<br><br><ul><li><strong>"The submitted form\'s  title will come here"</strong><br><br><em>User\'s Detials:</em><br><strong>Name:</strong> "the value the user has entered in the name field comes here"<br><strong>Email</strong>: "the value the user has entered in the email field comes here"</li></ul> </li><li><strong><em>Just Plain Text</em></strong><br>You can add some plain text here to make the email more personal, for example, simply adding:<br><br> <ul><li>"Thank you for completing the form.<br><br>Kind regards<br>Your/your company name".</li></ul>If you choose to go this route the following is important to remember:<br><br>In your message you need to put in [user_data] were you need the users form entry values to be displayed. So using my example, the message should look like this:<br><br><ul><li>"Thank you for completing the form.<br><br>[user_data]<br><br>Kind regards<br>Your/your company name"</li></ul>So if your form had only a "name" and a "email" field in your form to complete, [user_data] will be replaced with this:<br><br><ul><li><strong>Name:</strong> "the value the user has entered in the name field comes here"<br><strong>Email</strong>: "the value the user has entered in the email field comes here"</li></ul></li><li><strong><em>HTML</em></strong><br>If you choose to modify the look and feel of the email whith some fancy HTML and CSS this field is were you be putting it (from &lt;HTML&gt; to &lt;/HTML&gt with style tags and all).<br><br>Now, same as with "plain text emails" the following is important to remember:<br><br>In your HTML you need to put in [user_data] were you need the users form entry to be displayed. I.o.w if your form had only a "name" and a "email" field in your form to complete, [user_data] will be replaced with this(in table format):<br><br><ul><li><strong>Name:</strong> "the value the user has entered in the name field comes here"<br><strong>Email</strong>: "the value the user has entered in the email field comes here"</li></ul></li></ul><div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Got it, whats next</div><div class="spacer"></div>','',chef_piont_left,550,extra_top,700);
			if(step==6)
				add_tutorial_popup(jQuery('fieldset.from_address'),'<strong>From Address</strong><br>This is where you specify the email address from where the confirmation emails comes from. <br><br>If someone should reply to an email recieved from a form submission on this form, the email address entered here would be were the reply goes.<br><br>Note: if you choose to leave this field empty the return email address specified in your general settings will receive the replies.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==7)
				add_tutorial_popup(jQuery('fieldset.from_name'),'<strong>From Name</strong><br>This is the name the user will see as the person/organitation that sent the mail.<br><br>Note: if you choose to leave this field empty the From Name specified in your general settings will be used.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==8)
				add_tutorial_popup(jQuery('fieldset.on_screen_confirmation_message'),'<strong>On Screen Confirmation Message</strong><br>After a user submits a form a thank you message will appear above the form.<br><br> You can customise this message by entering it here, for example:<br><br><ul><li> "Thank you form completing the form, we have recieved your request and will get back to you within the next 24 business hours"</li></ul><div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==9)
				add_tutorial_popup(jQuery('fieldset.google_analytics_conversion_code'),'<strong>Google Analytics Conversion Code</strong>When using Google Analytics copy and paste the conversion code here which will be added after a form submission.<br><br>Note:  if this field is left empty the conversion code specified in general settings will be used.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==10)
				add_tutorial_popup(jQuery('#submit'),'<strong>Good!</strong> Now that you\'ve entered all your needed form information, you can click this button to create it.<br><div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="spacer"></div>','',chef_piont_left,415,10);
			
			if(step==11)
				add_tutorial_popup(jQuery('.iz-forms-holder'),'<strong>Congratulations!</strong> You have completed this tutorial. Also visit these tutorials:<br /><br /><a href="?page=WA-wa_form_builder-main&amp;tutorial=true">Creating form fields</a><br /><a href="'+ jQuery('div#site_url').text() +'/wp-admin/post-new.php?post_type=page&amp;tutorial=true">Adding a form to a page</a><br /><a href="?page=WA-wa_form_builder-forms-data&tutorial=true">Viewing form entries</a><br /><a href="?page=WA-wa_form_builder-forms-settings&tutorial=true">General settings</a><div class="spacer"></div><div class="nextstep next" data-step="1">Restart Tutorial</div><div class="spacer"></div>','',chef_normal);
			}
		);
	
	if(step==0)
		add_tutorial_popup(jQuery('.iz-forms-holder'),'<strong>Welcome!</strong> In this tutorial Im going to show you how to add and customize/configure a form using the form you see here below me. <br><br>Note: Forms added from the Form creator canvas can be configured here and visa versa.<br /><br />First we are going to create a new form using the "Add Item" form below <div class="spacer"></div><div class="nextstep next">Okay, Lets go</div><div class="spacer"></div>','',chef_normal);
	
	
	jQuery('#submit').click
		(
		function()
			{
			add_tutorial_popup(jQuery('.wp-list-table'),'<strong>Well Done!</strong> Your new form is ready and will show up at the top of the list below.<br><br>You can now edit or delete the form by hovering over it and then click on "edit" or "delete".<br><br>Note: this form is now added to the dropdown list (labeled "Select a form to edit") on the form creator page where you can select it and then drag form fields to it.<br><br><a href="?page=WA-wa_form_builder-main&amp;tutorial=true">View form creator tutorial</a><div class="spacer"></div><div class="nextstep next" data-step="11">Are we finished yet?</div><div class="spacer"></div>','',chef_piont_left);
			}
		);
		
	}
);


