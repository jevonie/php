<?php
// Register and load the widget
function wpb_load_widget() {
		    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

		// Creating the widget

class wpb_widget extends WP_Widget {
		 
	  function __construct() {
            parent::__construct(
                'wpb_widget',
                __('Maximizer Widget', 'wpb_widget_domain'),
                array( 'description' => __( 'Provides a "Register CV" button which launches a pop-up', 'wpb_widget_domain' ), )
            );
        }
			
			// Creating widget front-end 
	public function widget( $args, $instance ) {
			$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		 	$title = apply_filters( 'widget_title', $instance['title'] );
	           	echo $args['before_widget'];
				if ( ! empty( $title ) ) {
					echo $args['before_title'] . $title . $args['after_title'];
				} ?>
					<div class="textwidget"><?php echo do_shortcode('[show-form generated_id="'.$text.'"]'); ?></div>
				<?php
				echo $args['after_widget'];
			}   
		// Widget Backend

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
		$text = esc_textarea($instance['text']);
		// Widget admin form
		?>
			 <p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo  esc_attr($title); ?>" />
			 </p>
			 <p>
			 	<labelfor="<?php echo $this->get_field_id( 'text' ); ?>">Generated ID</label>
			 	<input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo  esc_attr($text); ?>" />
			 </p>
		<?php
		}
		// Updating widget replacing old instances with new

	public function update( $new_instance, $old_instance ) {
		

       $instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = ! empty( $new_instance['filter'] );
		return $instance;
		}
	} // Class wpb_widget ends here
?>