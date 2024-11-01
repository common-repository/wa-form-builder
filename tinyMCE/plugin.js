(function(){
	tinymce.create('tinymce.plugins.wa_form_builder', {
		init : function(ed, url){
			ed.addButton('wa_form_builder', {
				title	: 'Insert WA Form',
				image	: url + '/button.png',
				cmd		: 'popup_window'
			});
			
			ed.addCommand('Add_shortcode', function(){
				ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
				tinyMCE.activeEditor.selection.setContent('[ADD_wa_form_builder]');
			});
			
			ed.addCommand('popup_window', function(){
				ed.windowManager.open({
					file 		: ajaxurl + '?action=WAFormBuilder_tinymce_window',
					width 		: 400,
					height 		: 150,
					inline 		: 1
				}, {
					plugin_url 	: url // Plugin absolute URL
				});
			});
		},
	});
tinymce.PluginManager.add('wa_form_builder', tinymce.plugins.wa_form_builder);
})();