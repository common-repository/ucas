<?php 
 /*
 * Plugin Name: Ucas
 * Plugin URI: https://www.espeaky.com
 * Description: A wordpress plugin that counts the characters and lines for a UCAS personal statement.
 * Author: Scott Johnston
 * Author URI: https://www.linkedin.com/in/scott8johnston/
 * Version: 1.0.0
 * License: GPLv2 or later
 */

/**
  * @author Scott Johnston
  * @license https://www.gnu.org/licenses/gpl-3.0.html
  * @package Ucas
  * @version 1.0.0
 */

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

class Ucas {	

	public function __construct(){		
		register_activation_hook(__FILE__, array($this,'plugin_activate')); 
		register_deactivation_hook(__FILE__, array($this,'plugin_deactivate')); 				
	}		

	public function plugin_activate(){
		flush_rewrite_rules();		
	}

	public function plugin_deactivate(){
		flush_rewrite_rules();
	}
}

include(plugin_dir_path(__FILE__) . 'ucas-shortcode.php');

include(plugin_dir_path(__FILE__) . 'ucas-widget.php');

include(plugin_dir_path(__FILE__) . 'ucas-admin.php');

$ucas = new Ucas;
?>