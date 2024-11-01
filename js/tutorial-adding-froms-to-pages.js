// JavaScript Document
jQuery(document).ready(
function()
	{
	add_tutorial_popup(jQuery('div#wp-content-editor-container'),'<strong>Welcome!</strong> To add a form that you\'ve created click on the button that looks like this:<span class="mce_button_example"></span> and is situated on the bar I\'m pointing at.<br><br>You\'ll notice a popup with heading "WA Forms". Select the form you like to insert from the dropdown labeled "Select form to insert:".<br /><br />Click on "insert into post" and thats that!<div class="spacer"></div><div class="nextstep next close_chef">I got it, thank you.</div><div class="spacer"></div>','','point_left',0,30);
	
	jQuery('div.close_chef').live('click',
		function()
			{
			jQuery('div.tutorial').fadeOut('slow');
			}
		);
	}
);


