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
			
			console.log(step);
			if(step==1)
				add_tutorial_popup(jQuery('fieldset.confirmation_mail'),'<strong>Send Confirmation Email</strong><br />You can choose wheter to send the the user completing the form on your site the same confirmation mail you (specified email addresses)  receive.<br><br> <div class="spacer"></div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==2)
				add_tutorial_popup(jQuery('fieldset.mail_to_user_address'),'<strong>Select fields to be set to get user email address </strong><br />The list below contians the fields created which are set to be formated as email addresses. If no such fields exist field names with refference to email will be listed. If there are no items in the list simply create an field called for example "Email" and it will apear here. If a field is selected the address the user then enter in the field will receive the email specified.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==3)
				add_tutorial_popup(jQuery('fieldset.display_title'),'<strong>Display Title</strong><br />Here you can choose to display the form title on the front-end or not to. If you choos yes, the title will appear at the very top of the from container.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==4)
				add_tutorial_popup(jQuery('fieldset.display_description'),'<strong>Display Description</strong><br />Here you can choose to display the form desciption on the front-end or not to. If you choos yes, the desciption will appear below the form title.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==5)
				add_tutorial_popup(jQuery('fieldset.mail_to'),'<strong>Mail to</strong><br>Here you can add mail addresses seperated by commas to which the forms entries sould be mail to i.e. you@yourcompany.com, yourcolleague@yourcompany.com. <div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==6)
				add_tutorial_popup(jQuery('fieldset.confirmation_mail_subject'),'<strong>Mail Subject</strong><br>This is where you enter the subject line of the email. The email addresse(s) specified in the "mail to" field above will receive the email with this subject line.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==7)
				add_tutorial_popup(jQuery('fieldset.confirmation_mail_body'),'<strong>Email Body</strong><br>What you enter in here will be emailed to the specified email addresses.<br></br>We can handle this in 3 different ways described below (Default, Plain text and HTML)<br><br><ul><li><strong><em>Default(Blank)</em></strong><br>If this field is left blank, by default the mail will only contain the form fields and the values entered by the user.<br><br> For example if your form had only a "name" and a "email" to complete, the mail sent to the specified email addresses will look something like this:<br><br><ul><li><strong>"The submitted form\'s  title will come here"</strong><br><br><em>User\'s Detials:</em><br><strong>Name:</strong> "the value the user has entered in the name field comes here"<br><strong>Email</strong>: "the value the user has entered in the email field comes here"</li></ul> </li><li><strong><em>Just Plain Text</em></strong><br>You can add some plain text here to make the email more personal, for example, simply adding:<br><br> <ul><li>"Thank you for completing the form.<br><br>Kind regards<br>Your/your company name".</li></ul>If you choose to go this route the following is important to remember:<br><br>In your message you need to put in [user_data] were you need the users form entry values to be displayed. So using my example, the message should look like this:<br><br><ul><li>"Thank you for completing the form.<br><br>[user_data]<br><br>Kind regards<br>Your/your company name"</li></ul>So if your form had only a "name" and a "email" field in your form to complete, [user_data] will be replaced with this:<br><br><ul><li><strong>Name:</strong> "the value the user has entered in the name field comes here"<br><strong>Email</strong>: "the value the user has entered in the email field comes here"</li></ul></li><li><strong><em>HTML</em></strong><br>If you choose to modify the look and feel of the email whith some fancy HTML and CSS this field is were you be putting it (from &lt;HTML&gt; to &lt;/HTML&gt with style tags and all).<br><br>Now, same as with "plain text emails" the following is important to remember:<br><br>In your HTML you need to put in [user_data] were you need the users form entry to be displayed. I.o.w if your form had only a "name" and a "email" field in your form to complete, [user_data] will be replaced with this(in table format):<br><br><ul><li><strong>Name:</strong> "the value the user has entered in the name field comes here"<br><strong>Email</strong>: "the value the user has entered in the email field comes here"</li></ul></li></ul><div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Got it, whats next</div><div class="spacer"></div>','',chef_piont_left,550,extra_top,700);
			if(step==8)
				add_tutorial_popup(jQuery('fieldset.from_address'),'<strong>From Address</strong><br>This is where you specify the email address from where the confirmation emails comes from. <br><br>If someone should reply to an email recieved from a form submission on this form, the email address entered here would be were the reply goes.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==9)
				add_tutorial_popup(jQuery('fieldset.from_name'),'<strong>From Name</strong><br>This is the name the user will see as the person/organitation that sent the mail.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==10)
				add_tutorial_popup(jQuery('fieldset.on_screen_confirmation_message'),'<strong>On Screen Confirmation Message</strong><br>After a user submits a form a thank you message will appear above the form.<br><br> You can customise this message by entering it here, for example:<br><br><ul><li> "Thank you form completing the form, we have recieved your request and will get back to you within the next 24 business hours"</li></ul><div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==11)
				add_tutorial_popup(jQuery('fieldset.google_analytics_conversion_code'),'<strong>Google Analytics Conversion Code</strong> When using Google Analytics copy and paste the conversion code here which will be added after a form submission.<div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="nextstep next">Next</div><div class="spacer"></div>','',chef_piont_left,extra_left,extra_top);
			if(step==12)
				add_tutorial_popup(jQuery('#submit'),'<strong>Good!</strong>Click the save button and your configuration is set.<br><div class="spacer"></div><div class="nextstep back">Wait, go back</div><div class="spacer"></div>','',chef_piont_left,415,10);
			
			/*if(step==13)
				add_tutorial_popup(jQuery('.iz-forms-holder'),'<strong>Congratulations!</strong> You have completed this tutorial. Also visit these tutorials:<br /><br /><a href="?page=WA-wa_form_builder-main&amp;tutorial=true">Creating form fields</a><br /><a href="'+ jQuery('div#site_url').text() +'/wp-admin/post-new.php?post_type=page&amp;tutorial=true">Adding a form to a page</a><br /><a href="?page=WA-wa_form_builder-forms-data&tutorial=true">Viewing form entries</a><br /><a href="?page=WA-wa_form_builder-forms-settings&tutorial=true">General settings</a><div class="spacer"></div><div class="nextstep next" data-step="1">Restart Tutorial</div><div class="spacer"></div>','',chef_normal);
			*/
			}
		);
	
	if(step==0)
		add_tutorial_popup(jQuery('.iz-forms-holder'),'<strong>Welcome!</strong> In this tutorial Im going to show you how to configure your default settings. <br><br>These default settings will be used wherever a form lacks the corresponding setting, for example, if a form dose not have a mail to address the mail to specified here will be used. <div class="spacer"></div><div class="nextstep next">Okay, Lets start</div><div class="spacer"></div>','',chef_normal);
	
	
	jQuery('#submit').click
		(
		function()
			{
				add_tutorial_popup(jQuery('.iz-forms-holder'),'<strong>Congratulations!</strong> You have completed this tutorial. Also visit these tutorials:<br /><br /><a href="?page=WA-wa_form_builder-main&amp;tutorial=true">Creating form fields</a><br /><a href="'+ jQuery('div#site_url').text() +'/wp-admin/post-new.php?post_type=page&amp;tutorial=true">Adding a form to a page</a><br /><a href="?page=WA-wa_form_builder-forms-data&tutorial=true">Viewing form entries</a><br /><a href="?page=WA-wa_form_builder-view-forms&tutorial=true">Configuring Forms</a><div class="spacer"></div><div class="nextstep next" data-step="1">Restart Tutorial</div><div class="spacer"></div>','',chef_normal);
			}
		);
		
	}
);


