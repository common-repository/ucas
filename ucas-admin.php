<?php
/**
  * @author Scott Johnston
  * @license https://www.gnu.org/licenses/gpl-3.0.html
  * @package Ucas
  * @version 1.0.0
 */

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

class UcasAdmin{		

    private $option_group =  'ucas-config-group';

	public function __construct(){
        add_action( 'admin_menu', array($this,'add_menu') );
        add_action( 'admin_init', array($this, 'register_configure_parameters') );
    }
    
    function register_configure_parameters() {         
        register_setting( $this->option_group, 'textarea_rows', array('integer', 'message textarea rows',null ,false , 3) ); 
        register_setting( $this->option_group, 'button_text', array('string', 'button text',null ,false , "Calculate") );  
        register_setting( $this->option_group, 'placeholder_text', array('string', 'placeholder text',null ,false , "paste text here") ); 
     }    

	function add_menu() {
        $menu_title = 'ucas-info-page';
        $capability = 'manage_options';
		add_menu_page( 'Info', 'Ucas', $capability, $menu_title, array($this, 'add_info_page'), 'dashicons-performance', 4 );        
        add_submenu_page( $menu_title, 'Ucas Look and Feel', 'Configuration', $capability, 'ucas-configuration-page' , array($this, 'add_configuration_page') );	        				
	}

	public function add_info_page(){
        $plugin_data = get_plugin_data( plugin_dir_path(__FILE__).'ucas.php') ;
        echo "<h1>".$plugin_data["Name"]." Info</h1>";       
		echo "<p>".$plugin_data["Description"]."</p>";        
        ?>          
        <h2>Examples</h2>
        <ul>
            <li><code>[ucas commentary='false']</code></li>  
        </ul>        
        <h2>Plugin</h2>
        <ul>        
            <li>Version:<?php echo $plugin_data["Version"];  ?></li> 
            <li>URL: <a href='<?php echo $plugin_data["PluginURI"];  ?>'><?php echo $plugin_data["Name"] ?></a></li>
        </ul>
        <?php 
       
	}
    
    public function add_configuration_page(){	
        ?>

        <h1>Ucas Configure</h1>
            <form method='post' action='options.php'>	
            <?php settings_fields( $this->option_group ); ?>
            <?php do_settings_sections( $this->option_group ); ?>	
                <h2>Look and feel</h2>
                
                Textarea rows<br>
                <input  name="textarea_rows" type='number' value="<?php 
                    echo (!empty(get_option('textarea_rows'))) ? get_option('textarea_rows') : "5"; 
                ?>" placeholder = "textarea rows"/><br>	

                Placeholder text<br>
                <input  name="placeholder_text" type='text' value="<?php 
                    echo (!empty(get_option('placeholder_text'))) ? get_option('placeholder_text') : "paste text here"; 
                ?>" placeholder = "placeholder text"/><br>	 
                              
                Button text<br>
                <input  name="button_text" type='text' value="<?php 
                    echo (!empty(get_option('button_text'))) ? get_option('button_text') : "Calculate"; 
                ?>" placeholder = "button text"/><br>	    

                <?php submit_button(); ?>			
            </form>

         <?php
	}
}

$ucasAdmin = new UcasAdmin;
?>