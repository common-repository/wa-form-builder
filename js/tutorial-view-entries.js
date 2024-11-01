// JavaScript Document
var step = 0;
var tutorial = true;
jQuery(document).ready(
function()
	{
	add_tutorial_popup(jQuery('#widgets-right'),'<strong>Welcome!</strong> In this tutorial Im going to show you how to view your form entries. <br><br> First, click, hold and drag a form below to the area marked as "Drop here"','','');

	jQuery('div.close_chef').live('click',
		function()
			{
			jQuery('div.tutorial').fadeOut('slow');
			}
		);	
	}
);


