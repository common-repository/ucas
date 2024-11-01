<?php
/**
  * @author Scott Johnston
  * @license https://www.gnu.org/licenses/gpl-3.0.html
  * @package Ucas
  * @version 1.0.0
 */

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

class UcasWidget extends WP_Widget{

	public function __construct(){
		parent::__construct(
			'UcasWidget',
			'Ucas Widget', 
			array('description' => 'A wordpress plugin that counts the characters and lines for UCAS.'));	
	}	

	public function form( $instance ) {	
		isset( $instance[ 'commentary' ] ) ? $commentary = $instance[ 'commentary' ] : $commentary = false;	
		?>
		<div>
			<label>Commentary</label><br>                
			<select id="<?php echo $this->get_field_id( 'commentary' ); ?>" name="<?php echo $this->get_field_name( 'commentary' ); ?>"  value = '<?php  echo esc_attr($commentary); ?>' width ="100%">
				<option value='true' <?php if($type == 'true'): ?> selected='selected'<?php endif; ?>>True</option>	
				<option value='false' <?php if($type == 'false'): ?> selected='selected'<?php endif; ?>>False</option>					                  
			</select>			
		</div>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['commentary'] = strip_tags( $new_instance['commentary'] );	
		return $instance;
    }
	
	public function widget( $args, $instance ) {
		extract( $args );	
		$commentary = apply_filters( 'commentary', $instance['commentary'] );		
		if ($commentary == true){
			echo do_shortcode("[ucas commentary='true']"); 
		} else {
			echo do_shortcode("[ucas commentary='false']"); 
		}
	}
}

function ucas_widget_init(){
	register_widget( 'UcasWidget' );
}
add_action('widgets_init','ucas_widget_init');
?>