<?php
/**
 * Call To Action Widget
 *
 * @package Surplus_Essentials
 */

// register Surplus_Essentials_Cta widget
function surplus_essential_register_cta_widget(){
    register_widget( 'Surplus_Essentials_Cta' );
}
add_action('widgets_init', 'surplus_essential_register_cta_widget');
 /**
 * Adds Surplus_Essentials_Cta widget.
 */
class Surplus_Essentials_Cta extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ), 9999 );
        add_action( 'load-widgets.php', array( $this, 'ste_load_cta_colorpicker' ) );
        parent::__construct(
            'ste_cta_widget', // Base ID
            __( 'SE: Call To Action', 'surplus-essentials' ), // Name
            array( 'description' => __( 'A Call To Action Widget.', 'surplus-essentials' ), ) // Args
        );
    }

    function surplus_essentials_cta_button_alignment()
    {
        $array = array(
            'right'     => __('Right', 'surplus-essentials'),
            'left'     => __('Left', 'surplus-essentials'),
            'centered'  => __('Centered', 'surplus-essentials')
        );
        return apply_filters('ste_cta_button_alignment',$array);
    }

    function surplus_essentials_cta_button_numbers()
    {
        $array = array(
            '1'      => '1',
            '2'      => '2',
        );
        return apply_filters('ste_cta_button_numbers',$array);
    }

    function ste_load_cta_colorpicker() {    
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
        
        $obj              = new Surplus_Essentials_Functions();
        $title            = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $content          = ! empty( $instance['content'] ) ? $instance['content'] : '';
        $button_alignment = ! empty( $instance['button_alignment'] ) ? $instance['button_alignment'] : '';
        $button_number    = ! empty( $instance['button_number'] ) ? $instance['button_number'] : '1';
        $button1_text     = ! empty( $instance['button1_text'] ) ? $instance['button1_text'] : '' ;
        $button2_text     = ! empty( $instance['button2_text'] ) ? $instance['button2_text'] : '' ;
        $button1_url      = ! empty( $instance['button1_url'] ) ? $instance['button1_url'] : '' ;
        $button2_url      = ! empty( $instance['button2_url'] ) ? $instance['button2_url'] : '' ;
        $bgcolor          = apply_filters('ste_cta_bg_color','#fff');
        $widget_bg_color  = ! empty($instance['widget-bg-color']) ? esc_attr($instance['widget-bg-color']):$bgcolor;
        $widget_bg_image  = !empty($instance['widget-bg-image']) ? esc_attr($instance['widget-bg-image']):'';
        $target = 'target="_self"';
        $widget_bg_option        = !empty($instance['background-option']) ? esc_attr($instance['background-option']): apply_filters('ste_widget_cta_bg','photo');
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_blank"';
        }
        $target1 = 'target="_self"';
        if( isset($instance['target1']) && $instance['target1']!='' )
        {
            $target1 = 'target="_blank"';
        }
        echo $args['before_widget'];
        ob_start(); 
        if( $widget_bg_option == 'photo' ){
            /** Added to work for demo content compatible */   
            $attachment_id = $widget_bg_image;
            $cta_img_size = apply_filters('ste_cta_img_size','full');

            if ( !filter_var( $widget_bg_image, FILTER_VALIDATE_URL ) === false ) {
                $attachment_id = $obj->ste_get_attachment_id( $widget_bg_image );
            }

            $image_url   = wp_get_attachment_image_url( $attachment_id, $cta_img_size);
                        
            $ctaclass = ' ste-cta-bg';
            $bg = ' style="background:url(' . esc_url( $image_url ) . ') no-repeat; background-size: cover; background-position: center"';
        }else{
            $ctaclass = ' text';
            $bg = ' style="background:' . sanitize_hex_color( $widget_bg_color ) . '"';
        }
        ?>        
        <div class="<?php echo esc_attr( $button_alignment . $ctaclass ); ?>"<?php echo $bg;?>>
            <div class="ste-cta-container">
                <?php
                if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title']; ?>
                <div class="text-holder">
                        <?php if( $content ) echo wpautop( wp_kses_post( $content ) ); ?>
                    <div class="button-wrap">
                        <?php
                            if( $button_number == '1' )
                            {
                                if( isset( $button1_text ) && $button1_url!='' ) echo '<a '.$target. 'href="' . esc_url( $button1_url ) . '" class="btn-cta btn-1">' . esc_html( $button1_text ) . '</a>';
                            }
                            if( $button_number == '2' )
                            {
                                if( isset( $button1_text ) && $button1_url!='' ) echo '<a '.$target. ' href="' . esc_url( $button1_url ) . '" class="btn-cta btn-1">' . esc_html( $button1_text ) . '</a>';
                                if( isset( $button2_text ) && $button2_url!='' ) echo '<a '.$target1. '  href="' . esc_url( $button2_url ) . '" class="btn-cta btn-2">' . esc_html( $button2_text ) . '</a>';
                            }
                        ?>
                    </div>
                </div>
            </div> 
        </div>        
        <?php 
        $html = ob_get_clean();
        echo apply_filters( 'ste_cta_widget_filter', $html, $args, $instance );
        echo $args['after_widget'];
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
    public function form( $instance ) {
        $obj          = new Surplus_Essentials_Functions();
        $title        = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $content      = ! empty( $instance['content'] ) ? $instance['content'] : '';
        $button_alignment     = ! empty( $instance['button_alignment'] ) ? $instance['button_alignment'] : '';
        $button_number     = ! empty( $instance['button_number'] ) ? $instance['button_number'] : '';
        $button1_text = ! empty( $instance['button1_text'] ) ? $instance['button1_text'] : '' ;
        $button2_text = ! empty( $instance['button2_text'] ) ? $instance['button2_text'] : '' ;
        $button1_url  = ! empty( $instance['button1_url'] ) ? $instance['button1_url'] : '' ;
        $button2_url  = ! empty( $instance['button2_url'] ) ? $instance['button2_url'] : '' ;
        $bgcolor = apply_filters('ste_cta_bg_color','#ffffff');
        $widget_bg_option        = !empty($instance['background-option']) ? esc_attr($instance['background-option']): apply_filters('ste_widget_cta_bg','photo');
        $widget_bg_color        = ! empty($instance['widget-bg-color']) ? esc_attr($instance['widget-bg-color']):$bgcolor;
        $widget_bg_image        = !empty($instance['widget-bg-image']) ? esc_attr($instance['widget-bg-image']):'';
        $target     = ! empty( $instance['target'] ) ? $instance['target'] : '';
        $target1     = ! empty( $instance['target1'] ) ? $instance['target1'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />            
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php esc_html_e( 'Description', 'surplus-essentials' ); ?></label>
            <textarea name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php print $content; ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_number' ) ); ?>"><?php esc_html_e( 'Number of Call-to-Action Buttons:', 'surplus-essentials' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'button_number' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'button_number' ) ); ?>" class="widefat cta-button-number">
                <?php
                $button_number_options = $this->surplus_essentials_cta_button_numbers();
                foreach ( $button_number_options as $option ) { ?>
                    <option value="<?php echo $option; ?>" id="<?php echo esc_attr( $this->get_field_id( $option ) ); ?>" <?php selected( $option, $button_number ); ?>><?php echo $option; ?></option>
                <?php } ?>
            </select>
        </p>
        <div class="button-one-info">
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'button1_text' ) ); ?>"><?php esc_html_e( 'Button 1 Label', 'surplus-essentials' ); ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'button1_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button1_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button1_text ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'button1_url' ) ); ?>"><?php esc_html_e( 'Button 1 Link', 'surplus-essentials' ); ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'button1_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button1_url' ) ); ?>" type="text" value="<?php echo esc_url( $button1_url ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in new Tab', 'surplus-essentials' ); ?> </label>
            </p> 
        </div>

        <div class="button-two-info" <?php if( $button_number=='' || isset($button_number) && $button_number == 1 ) { echo "style='display:none;'";} ?>>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'button2_text' ) ); ?>"><?php esc_html_e( 'Button 2 Label', 'surplus-essentials' ); ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'button2_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button2_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button2_text ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'button2_url' ) ); ?>"><?php esc_html_e( 'Button 2 Link', 'surplus-essentials' ); ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'button2_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button2_url' ) ); ?>" type="text" value="<?php echo esc_url( $button2_url ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'target1' ) ); ?>">
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target1' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target1' ) ); ?>" type="checkbox" value="1" <?php echo checked($target1,1);?> /><?php esc_html_e( 'Open in new Tab', 'surplus-essentials' ); ?> </label>
            </p> 
        </div>        
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_alignment' ) ); ?>"><?php esc_html_e( 'Button Alignment:', 'surplus-essentials' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'button_alignment' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'button_alignment' ) ); ?>" class="widefat cta-button-alignment">
                <?php
                $align_options = $this->surplus_essentials_cta_button_alignment();
                foreach ( $align_options as $options => $key ) { ?>
                    <option value="<?php echo $options; ?>" id="<?php echo esc_attr( $this->get_field_id( $options ) ); ?>" <?php selected( $options, $button_alignment ); ?>><?php echo $key; ?></option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label><?php _e('Use Background Color/Image:','surplus-essentials'); ?></label>
            <input class="ste-cta-bg-option" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'background-option' ) );?>" id="<?php echo esc_attr( $this->get_field_id( 'background-option' . '-color' ) );?>" value="color" <?php if( $widget_bg_option == 'color' ) echo 'checked'; ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'background-option' ) . '-color' );?>" class="radio-btn-wrap"><?php _e('Color','surplus-essentials');?></label>
            <input class="ste-cta-bg-option" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'background-option' ) );?>" id="<?php echo esc_attr( $this->get_field_id( 'background-option' . '-photo' ) );?>" value="photo" <?php if( $widget_bg_option == 'photo' ) echo 'checked'; ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'background-option' ) . '-photo' );?>" class="radio-btn-wrap"><?php _e('Image','surplus-essentials');?></label>
        </p>

        <div class="background-option-color">
            <p><label for="<?php echo esc_attr( $this->get_field_id( 'widget-bg-color' ) ); ?>"><?php esc_html_e( 'Background Color', 'surplus-essentials' ); ?></label></p>
            <input type="text" class="my-widget-color-field" name="<?php echo esc_attr( $this->get_field_name( 'widget-bg-color' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'widget-bg-color' ) ); ?>" value="<?php echo esc_attr( $widget_bg_color ); ?>" />
        </div>
        <div class="background-option-image">
        <?php
            $obj->ste_get_image_field( $this->get_field_id( 'widget-bg-image' ), $this->get_field_name( 'widget-bg-image' ),  $widget_bg_image, __( 'Background Image', 'surplus-essentials' ) );
            ?>
            <?php
        echo '</div>';
    echo 
    '<script>
    jQuery(document).ready(function($){
        $(".ste-cta-bg-option").change(function() {
            if( $(this).val()== "photo" )
            {
                $(".background-option-image").fadeIn();
                $(".background-option-color").hide();
            }
            else{
                $(".background-option-color").fadeIn();
                $(".background-option-image").hide();
            }
        });
        $(".cta-button-number").change(function() {
            if( $(this).val()== 2 )
            {
                $(this).parent().siblings(".button-one-info, .button-two-info").show();
            }
            else{
                $(this).parent().siblings(".button-two-info").fadeOut();
            }
        });
    });
    </script>';

        if( $widget_bg_option == 'photo' )
        {
            echo 
            '<style>
                .background-option-color{
                    display: none;
                }
                .background-option-image{
                    display: block;
                }
            </style>';
        }
        else{
            echo 
            '<style>
                .background-option-image{
                    display: none;
                }
                .background-option-color{
                    display: block;
                }
            </style>';
        }
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
        $instance['title']        = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '' ;
        $instance['content']      = ! empty( $new_instance['content'] ) ? wp_kses_post( $new_instance['content'] ) : '';
        $instance['button_number']     = ! empty( $new_instance['button_number'] ) ? esc_attr( $new_instance['button_number'] ) : '';
        $instance['button_alignment']     = ! empty( $new_instance['button_alignment'] ) ? esc_attr( $new_instance['button_alignment'] ) : 'center';
        $instance['button1_url']  = ! empty( $new_instance['button1_url'] ) ? esc_url_raw( $new_instance['button1_url'] ) : '';
        $instance['button2_url']  = ! empty( $new_instance['button2_url'] ) ? esc_url_raw( $new_instance['button2_url'] ) : '';
        $instance['button1_text'] = ! empty( $new_instance['button1_text'] ) ? sanitize_text_field( $new_instance['button1_text'] ) : '';
        $instance['button2_text'] = ! empty( $new_instance['button2_text'] ) ? sanitize_text_field( $new_instance['button2_text'] ) : '';
        $bgcolor = apply_filters('ste_cta_bg_color','#fff');
        $instance['widget-bg-color']        = isset($new_instance['widget-bg-color']) ? esc_attr($new_instance['widget-bg-color']):$bgcolor;
        $instance['widget-bg-image']        = isset($new_instance['widget-bg-image']) ? esc_attr($new_instance['widget-bg-image']):'';
        $instance['target']                  = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';
        $instance['target1']                  = ! empty( $new_instance['target1'] ) ? esc_attr( $new_instance['target1'] ) : '';
        $instance['background-option']                  = ! empty( $new_instance['background-option'] ) ? esc_attr( $new_instance['background-option'] ) : '';

        
        return $instance;
    }
    
}