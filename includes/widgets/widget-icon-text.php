<?php
/**
 * Icon Text Widget
 *
 * @package Surplus_Essentials
 */

// register Surplus_Essentials_Icon_Text_Widget widget
function ste_register_icon_text_widget(){
    register_widget( 'Surplus_Essentials_Icon_Text_Widget' );
}
add_action('widgets_init', 'ste_register_icon_text_widget');
 
 /**
 * Adds Surplus_Essentials_Icon_Text_Widget widget.
 */
class Surplus_Essentials_Icon_Text_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'ste_icontext_widget', // Base ID
            __( 'SE: Icon Text', 'surplus-essentials' ), // Name
            array( 'description' => __( 'An Icon Text Widget.', 'surplus-essentials' ), ) // Args
        );
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
        
        $obj       = new Surplus_Essentials_Functions();
        $title     = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $img_size  = ! empty( $instance['size'] ) ? $instance['size'] : apply_filters(  'ste_widget_itw_img_size','full' );        
        $content   = ! empty( $instance['content'] ) ? $instance['content'] : '';
        $icon      = ! empty( $instance['icon'] ) ? $instance['icon'] : '';
        $image     = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $link      = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $more_text = ! empty( $instance['more_text'] ) ? $instance['more_text'] : '';
        $icon_option = !empty($instance['icon-option']) ? esc_attr($instance['icon-option']): apply_filters('ste_widget_itw_icon','icon');

        $target = 'target="_self"';
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_blank"';
        }

        if( $image ){
            /** Added to work for demo content compatible */
            $attachment_id = $image;
            if ( !filter_var( $image, FILTER_VALIDATE_URL ) === false ) {
                $attachment_id = $obj->ste_get_attachment_id( $image );
            }
            $icon_img_size = $img_size;
        }
        
        echo $args['before_widget'];
        ob_start(); 
        ?>
        
            <div class="flexbox-wrapper">
                <?php if( $image && $icon_option=='photo'  ){ ?>
                    <div class="image-wrapper">
                        <?php echo wp_get_attachment_image( $attachment_id, $icon_img_size, false, 
                                    array( 'alt' => esc_attr( $title )));?>
                    </div>
                <?php }elseif( $icon && $icon_option=='icon' ){ ?>
                    <div class="image-wrapper">
                        <span class="<?php echo esc_attr( $icon ); ?>"></span>
                    </div>
                <?php }?>
                <div class="icontext-content-wrapper">
                    <?php 
                        if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];                
                        if( $content ) echo '<div class="icontext-content">'.wpautop( wp_kses_post( $content ) ).'</div>';
                        if( isset( $link ) && $link!='' && isset( $more_text ) && $more_text!='' ){
                            echo '<div class="button-wrap"><a '.$target.' class="link-button" href="'.esc_url($link).'">'.esc_attr($more_text).'</a></div>';
                        }
                    ?>                              
                </div>
            </div>
        <?php 
        $html = ob_get_clean();
        echo apply_filters( 'ste_icontext_widget_filter', $html, $args, $instance );   
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        
        $obj       = new Surplus_Essentials_Functions();
        $title     = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $content   = ! empty( $instance['content'] ) ? $instance['content'] : '';
        $icon      = ! empty( $instance['icon'] ) ? $instance['icon'] : apply_filters('ste_widget_itw_select_icon','fab fa-wordpress');;
        $image     = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $link      = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $more_text = ! empty( $instance['more_text'] ) ? $instance['more_text'] : '';
        $target    = ! empty( $instance['target'] ) ? $instance['target'] : '';
        $img_size    = ! empty( $instance['size'] ) ? $instance['size'] : apply_filters(  'ste_widget_itw_img_size','full' );
        if( isset( $instance['icon-option'] ) && $instance['icon-option']!='' )
        {
            $widget_icon_opt = esc_attr( $instance['icon-option'] );
        }
        else{
            $widget_icon_opt = apply_filters('ste_widget_itw_icon_image','icon');
        }
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
            <label><?php _e('Use Icon/Image:','surplus-essentials'); ?></label>
            <input class="ste-itw-icon-option" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'icon-option' ) );?>" id="<?php echo esc_attr( $this->get_field_id( 'icon-option' . '-icon' ) );?>" value="icon" <?php if( $widget_icon_opt == 'icon' ) echo 'checked'; ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'icon-option' ) . '-icon' );?>" class="radio-btn-wrap"><?php _e('Icon','surplus-essentials');?></label>
            <input class="ste-itw-icon-option" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'icon-option' ) );?>" id="<?php echo esc_attr( $this->get_field_id( 'icon-option' . '-photo' ) );?>" value="photo" <?php if( $widget_icon_opt == 'photo' ) echo 'checked'; ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'icon-option' ) . '-photo' );?>" class="radio-btn-wrap"><?php _e('Image','surplus-essentials');?></label>
        </p>
        
        <div class="itw-image-option" <?php if( $widget_icon_opt == 'icon' ){ echo 'style="display:none;"';}?>>
            <?php $obj->ste_get_image_field( $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), $image, __( 'Upload Image', 'surplus-essentials' ) ); ?>
        </div>

        <?php
        global $_wp_additional_image_sizes; 
        $image_sizes = array();
        $default_image_sizes = array(
            'thumbnail' => array(
                'width' => intval( get_option( "thumbnail_size_w" ) ),
                'height'=> intval( get_option( "thumbnail_size_h" ) ),
            ),
            'medium' => array(
                'width' => intval( get_option( "medium_size_w" ) ),
                'height'=> intval( get_option( "medium_size_h" ) ),
            ),
            'large' => array(
                'width' => intval( get_option( "large_size_w" ) ),
                'height'=> intval( get_option( "large_size_h" ) ),
            ),
            'full' => array(
                'width' => 'original width',
                'height'=> 'original height',
            ),

        );

        if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
            $image_sizes = array_merge( $default_image_sizes, $_wp_additional_image_sizes );
        }

        foreach ($image_sizes as $key => $value) {
            $image_sizes_names[] = array( 'name'=>$key, 'width'=>$value['width'], 'height'=>$value['height'] );   
        }
        ?>
        <p class="itw-image-option" <?php if( $widget_icon_opt == 'icon' ){ echo 'style="display:none;"';}?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image Size', 'surplus-essentials' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>">
              <?php foreach ($image_sizes_names as $size_name): 
                ?>
                <option value="<?php echo $size_name['name'] ?>" <?php selected($img_size,$size_name['name']);?> ><?php echo $size_name['name'].' ( '.$size_name['width'].' x '.$size_name['height'].' )'; ?></option>
              <?php 
            endforeach; ?>
            </select>
        </p>
        

        <div class="itw-icon-option" <?php if( $widget_icon_opt == 'photo' ){ echo 'style="display:none;"';}?>>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Icons', 'surplus-essentials' ); ?></label><br />
                <?php
                $class = "";
                if( isset($icon) && $icon!='' )
                {
                    $class = "yes";
                }
                ?>
                <span class="icon-receiver <?php echo $class;?>"><i class="<?php echo esc_attr( $icon ); ?>"></i>
                    <?php
                    if( isset($icon) && $icon!='' )
                    {   ?>
                    <a class="ste-remove-icon"></a>
                    <?php } ?>
                </span>
                <input class="hidden-icon-input" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" value="<?php echo esc_attr( $icon ); ?>" />            
            </p>
            <input class="search-itw-icons" placeholder="<?php _e('search icons here...','surplus-essentials'); ?>" />        
            <?php $obj->ste_get_icon_list(); ?>
        </div>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in New Tab', 'surplus-essentials' ); ?> </label>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>"><?php esc_html_e( 'Read More Label', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $more_text ); ?>" />            
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Read More Link', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_url( $link ); ?>" />            
        </p>
                        
        <?php

        echo 
        '<script>
        jQuery(document).ready(function($){
            $(".ste-itw-icon-option").change(function() {
                if( $(this).val()== "photo" )
                {
                    $(".itw-image-option").fadeIn();
                    $(".itw-icon-option").hide();
                }
                else{
                    $(".itw-icon-option").fadeIn();
                    $(".itw-image-option").hide();
                }
            });
        });
        </script>';
        // if( isset( $instance['icon-option'] ) && $instance['icon-option']!='' )
        // {
        //     $widget_icon_opt = esc_attr( $instance['icon-option'] );
        // }
        // else{
        //     $widget_icon_opt = apply_filters('ste_widget_itw_icon_image','icon');
        // }
        // echo '<>'.$widget_icon_opt;
        // if( $widget_icon_opt == 'photo' )
        // {
        //     echo 
        //     '<style>
        //         .itw-icon-option{
        //             display: none !important;
        //         }
        //         .itw-image-option{
        //             display: block !important;
        //         }
        //     </style>';
        // }
        // else{
        //     echo 
        //     '<style>
        //         .itw-image-option{
        //             display: none;
        //         }
        //         .itw-icon-option{
        //             display: block;
        //         }
        //     </style>';
        // }
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
        
        $instance['title']     = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '' ;
        $instance['content']   = ! empty( $new_instance['content'] ) ? wp_kses_post( $new_instance['content'] ) : '';
        $instance['image']     = ! empty( $new_instance['image'] ) ? esc_attr( $new_instance['image'] ) : '';
        $instance['icon']      = ! empty( $new_instance['icon'] ) ? esc_attr( $new_instance['icon'] ) : '';
        $instance['link']      = ! empty( $new_instance['link'] ) ? esc_url( $new_instance['link'] ) : '';
        $instance['more_text'] = ! empty( $new_instance['more_text'] ) ? esc_attr( $new_instance['more_text'] ) : '';
        $instance['target']    = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';
        $instance['size']    = ! empty( $new_instance['size'] ) ? esc_attr( $new_instance['size'] ) : '';
        $instance['icon-option'] = !empty($new_instance['icon-option']) ? esc_attr($new_instance['icon-option']): '';
        return $instance;
    }
    
}  // class Surplus_Essentials_Icon_Text_Widget / class Surplus_Essentials_Icon_Text_Widget 