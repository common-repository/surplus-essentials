<?php
/**
 * Twitter Feeds widget
 *
 * @package ste
 */
 
 // register Surplus_Essentials_Twitter_Feeds_Widget widget.
function ste_register_twitter_feeds_widget(){
    register_widget( 'Surplus_Essentials_Twitter_Feeds_Widget' );
}
add_action('widgets_init', 'ste_register_twitter_feeds_widget');


 /**
 * Adds Surplus_Essentials_Twitter_Feeds_Widget widget.
 */
class Surplus_Essentials_Twitter_Feeds_Widget extends WP_Widget {
    
    /**
    * Get themes for widget
    *
    * @return array of themes
    */
    public function ste_get_theme_options() {

        $themes = array(
            'light' => __('Light', 'surplus-essentials'),
            'dark'  => __('Dark', 'surplus-essentials'),
            );
        $themes = apply_filters( 'ste_get_theme_options', $themes );
        return $themes;
    }
    public function __construct() {
        add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ), 9999 );
        add_action( 'load-widgets.php', array( $this, 'ste_load_colorpicker' ) );
        parent::__construct(
            'ste_twitter_feeds_widget', // Base ID
            __( 'SE: Twitter Feed', 'surplus-essentials' ), // Name
            array( 'description' => __( 'A widget that shows latest tweets', 'surplus-essentials' ), ) // Args          
        );
    }
    
    //load wp color picker
    function ste_load_colorpicker() {    
        wp_enqueue_style( 'wp-color-picker' );        
        wp_enqueue_script( 'wp-color-picker' );    
    }
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
	public function widget( $args, $instance ) {
		
        extract( $args );
        if( !empty( $instance['title'] ) ) $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
          
		echo $before_widget;
        ob_start();
          				
		if ( ! empty( $title ) ) echo $before_title. $title . $after_title; 

        if(! empty($instance['username'])): ?>

            <div class="tw-feed">
            <a class="twitter-timeline" href="https://twitter.com/<?php echo esc_attr($instance['username']);?>" data-theme="<?php echo esc_attr($instance['theme']);?>" data-link-color="<?php echo esc_attr( $instance['widget-link'] ); ?>" data-border-color="<?php echo esc_attr( $instance['widget-bg'] ); ?>" border-radius="1" data-chrome="footer borders" data-screen-name="<?php echo esc_attr($instance['username']);?>" data-show-replies="True" data-tweet-limit="<?php echo esc_attr( $instance['tweetstoshow'] ); ?>">@Twitter Feed</a>
        	<?php
            echo '<script>
            jQuery(document).ready(function($){
            !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p="https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
            });</script>';
            ?>
            </div>                      
    	<?php
        endif;
        $html = ob_get_clean();
        echo apply_filters( 'ste_twitter_feeds_widget_filter', $html, $args, $instance );
		echo $after_widget;
	}
    
    public function print_scripts() {
        ?>
        <script>
            jQuery(document).ready(function($){

                function initColorPicker( widget ) {
                    widget.find( '.my-widget-color-field' ).wpColorPicker( {
                        change: _.throttle( function() { // For Customizer
                            $(this).trigger( 'change' );
                        }, 3000 )
                    });
                }

                function onFormUpdate( event, widget ) {
                    initColorPicker( widget );
                }

                $( document ).on( 'widget-added widget-updated', onFormUpdate );

                $( document ).ready( function() {
                    $( '#widgets-right .widget:has(.my-widget-color-field)' ).each( function () {
                        initColorPicker( $( this ) );
                    } );
                } );
            });

        </script>
        <?php
    }
    		
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ){
		$html = '';
        $defaults = array( 
            'title'             => '', 
            'widget-bg'         => apply_filters('ste_twitter_bg_color','#ffffff'), 
            'widget-link'       => apply_filters('ste_twitter_link_color','#ffffff'), 
            'username'          => '', 
            'tweetstoshow'      => 3,
            'theme'             => 'light',
        );
		
        $instance = wp_parse_args( (array) $instance, $defaults );
    ?>
    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'surplus-essentials' ); ?></label>
        <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
    </p>
    <p>    
        <label for="<?php echo esc_attr( $this->get_field_id( 'theme' ) ); ?>"><?php esc_html_e( 'Theme', 'surplus-essentials' ); ?></label>
        <select id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'theme' ) ); ?>" data-placeholder="<?php esc_attr_e( 'Choose a theme&hellip;', 'surplus-essentials' ); ?>">
            <option value=""><?php _e( 'Choose a theme&hellip;', 'surplus-essentials' ); ?></option>
            <?php
            $themes = $this->ste_get_theme_options();
            $selected_theme = $instance['theme'];
            foreach ( $themes as $key => $val ) {
                echo '<option value="' .( !empty($key)?esc_attr( $key ):"Please select" ). '" ' . selected( $selected_theme, $key, false ) . '>' . esc_html($val) . '</option>';
            }
            ?>
        </select>
    </p>
    <div class="twitter-widget-color-fields">
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Twitter Username', 'surplus-essentials' ); ?></label>
            <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" value="<?php echo esc_attr( $instance['username'] ); ?>" class="widefat" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'widget-bg' ) ); ?>"><?php esc_html_e( 'Border Color', 'surplus-essentials' ); ?></label>
            <input type="text" class="my-widget-color-field" name="<?php echo esc_attr( $this->get_field_name( 'widget-bg' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'widget-bg' ) ); ?>" value="<?php echo esc_attr( $instance['widget-bg'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'widget-link' ) ); ?>"><?php esc_html_e( 'Link Color', 'surplus-essentials' ); ?></label>
            <input type="text" class="my-widget-color-field" name="<?php echo esc_attr( $this->get_field_name( 'widget-link' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'widget-link' ) ); ?>" value="<?php echo esc_attr( $instance['widget-link'] ); ?>" />
        </p>
    </div>
    
    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'tweetstoshow' ) ); ?>"><?php esc_html_e( 'Number of tweets', 'surplus-essentials' ); ?></label>
        <input type="number" min="1" step="1" name="<?php echo esc_attr( $this->get_field_name( 'tweetstoshow' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" value="<?php echo esc_attr( $instance['tweetstoshow'] ); ?>" class="widefat" />
    </p>
    <?php
    }
    
    
    /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */ 
	public function update( $new_instance, $old_instance ) {				
		$instance = array();
		$instance['title']            = strip_tags( $new_instance['title'] );
        $instance['username']         = strip_tags( $new_instance['username'] );
		$instance['widget-bg']        = isset($new_instance['widget-bg']) ? esc_attr($new_instance['widget-bg']):'#ccc00333';
        $instance['widget-link']      = isset($new_instance['widget-link']) ? esc_attr($new_instance['widget-link']):'#00000000';
		$instance['tweetstoshow']     = ! empty( $new_instance['tweetstoshow'] ) ? absint( $new_instance['tweetstoshow'] ) : 3;
        $instance['theme']            =  isset($new_instance['theme']) ? esc_attr($new_instance['theme']):'light';
		
        if( $old_instance['username'] != $new_instance['username'] ) delete_option( 'ste_last_cache_time' );
		
        return $instance;
	}	
} 