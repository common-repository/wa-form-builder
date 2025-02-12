<?php
if(!class_exists('IZC_Functions'))
	{
	class IZC_Functions{
		
		public function format_date($str){
			
			$datetime = explode(' ',$str);
			
			$time = explode(':',$datetime[1]);
			
			$date = explode('/',$datetime[0]);
			//print_r($date);
			//echo $time[0];
			return date(get_option('date_format'),mktime('0','0','0',$date[0],$date[1],$date[2]));
		}
		
		public function format_name($str){
			$str = strtolower($str);		
			$str = str_replace('  ',' ',$str);
			$str = str_replace(' ','_',$str);
			return trim($str);
		}
		
		public function unformat_name($str){
			
			$str = IZC_Functions::format_name($str);
			
			$str = str_replace('u2019','\'',$str);
			$str = str_replace('_',' ',$str);
			$str = ucwords($str);
			return trim($str);
		}
			
		public function view_excerpt($content,$chars=0){
			$content = strip_tags($content);
			for($i=0;$i<$chars;$i++){
				$excerpt .= substr($content,$i,1);
			}
			return (strlen($content)>$chars) ? $excerpt.'&hellip;' : $excerpt;
		}
		
		public function print_message($type,$msg){
			echo '<div class="'.$type.' below-h2" id="message"><p>'.$msg.'.</p></div>';
		}
		public function return_message($type,$msg){
			return '<div class="'.$type.' below-h2" id="message"><p>'.$msg.'.</p></div>';
		}
		
		public function add_js_function($js_function){
			//Used for calling a JS function with PHP values passed to it
			echo '<script type="text/javascript">'.$js_function.'</script>';
		}
		
		public function sanitize_class_name($dirty_string) {
			
			$known_dirty_words = array (
				'iz-draggable', 'leftDrag', 'ui-draggable', 'ui-draggable-dragging', 'widget-borders'
			);
			
			for($i=0;$i<=count($known_dirty_words);$i++)
				{
				$dirty_string = str_replace($known_dirty_words[$i],'',$dirty_string);
				}
			
			return $dirty_string;
		}
		
		
		public function get_file_headers($file){
				
			$default_headers = array(			
				'Module Name' 		=> 'Module Name',
				'For Plugin' 		=> 'For Plugin',
				'Module Prefix'		=> 'Module Prefix',
				'Module URI' 		=> 'Module URI',
				'Module Scope' 		=> 'Module Scope',
				
				'Plugin Name' 		=> 'Plugin Name',
				'Plugin TinyMCE' 	=> 'Plugin TinyMCE',
				'Plugin Prefix'		=> 'Plugin Prefix',
				'Plugin URI' 		=> 'Plugin URI',
				'Module Ready' 		=> 'Module Ready',
				
				'Version' 			=> 'Version',
				'Description' 		=> 'Description',
				'Author' 			=> 'Author',
				'AuthorURI' 		=> 'Author URI'
			);
			return get_file_data($file,$default_headers,'module');
		}
	
	}
}
?>