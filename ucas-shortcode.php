<?php
/**
  * @author Scott Johnston
  * @license https://www.gnu.org/licenses/gpl-3.0.html
  * @package Ucas
  * @version 1.0.0
 */

class UcasShortcode{
	
	public function __construct(){		
		add_action('init', array($this,'registerUcasShortcodes')); 				
		add_action('wp_enqueue_scripts', array($this,'__script_and_style'));
	}

	public function __script_and_style(){	
		wp_register_script('ucasScript', plugins_url( '/js/ucas.js', __FILE__ ), array(), '1.0',	true);
		wp_enqueue_script('ucasScript');	
	
		wp_register_style('ucasStyle', plugins_url( '/css/ucas.min.css', __FILE__ ), array(), '1.0',	'all');
		wp_enqueue_style('ucasStyle');

		wp_register_style('w3Style', plugins_url( '/css/w3.css', __FILE__ ), array(), '1.0',	'all');
		wp_enqueue_style('w3Style');	
	}

	public function registerUcasShortcodes( $atts ) {		
		add_shortcode( 'ucas', array($this ,'shortcode_ucas' ) );			
	}	
	
	public function shortcode_ucas( $atts ) {	
		//Get look and feel options		
		$placeholder_text = !empty(get_option('placeholder_text')) ? get_option('placeholder_text') : 'paste text here'; 
		$textarea_rows = !empty(get_option('textarea_rows')) ? get_option('textarea_rows') : '5'; 
		$button_text = !empty(get_option('button_text')) ? get_option('button_text') : 'Calculate'; 

		//Extract lowercase only parameters from shortcode	
		$atts = shortcode_atts( array(
			'commentary' => null,
		), $atts, 'ucas' );
		$commentary = filter_var($atts['commentary'], FILTER_VALIDATE_BOOLEAN);
		
		//Results		
		if(isset($_POST['txtUcasDocument'])){
			$characters = $this->countCharacters($_POST['txtUcasDocument']);
			$lines = $this->countLines($_POST['txtUcasDocument'], $commentary);
			$html = "<div class='w3-container'>";		
			
			if ($characters > 4000){
				$html .= "<b class='ucasNegative'>Characters: ".$characters." / 4000</b><br>";
			} else {
				$html .= "<b class='ucasPostive'>Characters: ".$characters." / 4000</b><br>";
			}

			if ($lines > 47){
				$html .= "<b class='ucasNegative'>Lines: ".$lines." / 47</b><br>";
			} else {
				$html .= "<b class='ucasPostive'>Lines: ".$lines." / 47</b><br>";
			}

			if (($characters > 4000) && ($lines > 47)){
				$html .= "<span class='ucasNegative'>Your personal statement is too long.</span><br>";
			} else {
				$html .= "<span class='ucasPostive'>Your personal statement is the correct length.</span><br>";
			}
		
			$html .= "</div>";
		} 
		
		//Form
		$html .= "<div class='w3-container'>".		
			"<form action='".esc_url( $_SERVER['REQUEST_URI'] )."' method='post' id='frmUcas'>";
			if(isset($_POST['txtUcasDocument'])){				
				$html .="<textarea id='txtUcasDocument' name='txtUcasDocument' class='ucasTextArea' rows='".$textarea_rows."' cols='94' maxlength='10000' placeholder='".$placeholder_text."'>".$_POST['txtUcasDocument']."</textarea>";					
			} else {
				$html .="<textarea id='txtUcasDocument' name='txtUcasDocument' class='ucasTextArea'rows='".$textarea_rows."' cols='94' maxlength='10000' placeholder='".$placeholder_text."'></textarea>";					
			}
		$html .="</form>".
			"<br>".			
			"<button id='btnPostUcas' name='btnPostUcas' type='submit' form='frmUcas'>".$button_text."</button>".
		"</div>";		 

		return $html;
	}	
	
	public function countLines($str, $commentary){
		$line = "";
		$lines = 0; 

		$paragraphs = explode(PHP_EOL, $str);			
		for ($i = 0; $i < sizeof($paragraphs); $i++){

			$words = explode(" ", $paragraphs[$i]);	
			for ($j = 0; $j < sizeof($words); $j++){

				if ((mb_strlen($line) + mb_strlen($words[$j])) > 94) {
					//if line more than 94 characters increment line
					$lines++;
					if ($commentary){					
						echo $lines.":".$line."<br>";
					}				
					$line = " ".$words[$j];					
				} else {
					//else if word fits so just add it to the current line being built
					$line .= " ".$words[$j];
				}
			}

			//EOL empty buffer
			$lines++;
			if ($commentary){	
				echo $lines.":".$line."<br>";	
			}
			$line = "";						
		}
		
		return $lines;
	}

	public function countCharacters($str){		
		return mb_strlen($str); 
	}
}	

$ucasShortcode = new UcasShortcode();
?>