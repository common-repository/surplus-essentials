<?php
/**
 * Event Widget
 *
 * @package Surplus_Essentials
 */

// register Surplus_Essentials_Events_Widget widget
function ste_register_events_widget(){
    register_widget( 'Surplus_Essentials_Events_Widget' );
}
add_action('widgets_init', 'ste_register_events_widget');
 
 /**
 * Adds Surplus_Essentials_Events_Widget widget.
 */
class Surplus_Essentials_Events_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        // add_action( 'admin_print_footer_scripts', array( $this, 'wp29r01_ste_print_scripts' ), 9999 );
        add_action( 'admin_footer-widgets.php', array( $this, 'ste_print_scripts' ), 9999 );
        add_action( 'load-widgets.php', array( $this, 'ste_load_datepicker') );
        parent::__construct(
            'ste_event_widget', // event ID
            __( 'SE: Event', 'surplus-essentials' ),
            array( 'description' => __( 'A Event Widget.', 'surplus-essentials' ), ) // Args
        );
    }

    //load wp date picker
    function ste_load_datepicker() {    
        wp_enqueue_script( 'jquery-datepicker' );    
    }

    public function ste_print_scripts() {
        ?>
        <script>
            ( function( $ ){

                function initDatePicker( widget ) {
                    widget.find( '.ste-datepicker-field' ).datepicker({
                        dateFormat: 'yy-mm-dd',
                        change: _.throttle( function() { // For Customizer
                            $(this).trigger( 'change' );
                        }, 3000 )
                    });
                }

                function onFormUpdate( event, widget ) {
                    initDatePicker( widget );
                }

                $( document ).on( 'widget-added widget-updated', onFormUpdate );

                $( document ).ready( function() {
                    $( '#widgets-right .widget:has(.ste-datepicker-field)' ).each( function () {
                        initDatePicker( $( this ) );
                    } );
                } );


            }( jQuery ) );

        </script>
        <?php
    }

    
    
    function ste_itw_get_image_id($image_url) {
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return $attachment[0]; 
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
        $obj = new Surplus_Essentials_Functions();
        $title        = ! empty( $instance['title'] ) ? $instance['title'] : '' ;  
        $image        = ! empty( $instance['image'] ) ? esc_attr( $instance['image'] ) : '';
        $title_link   = ! empty( $instance['title_link'] ) ? esc_url( $instance['title_link'] ) : '';
        $location     = ! empty( $instance['location'] ) ? esc_attr( $instance['location'] ) : '';
        $event_date   = ! empty( $instance['event_date'] ) ? esc_attr( $instance['event_date'] ) : '';
        $img_size     = ! empty( $instance['size'] ) ? $instance['size'] : apply_filters( 'ste_widget_event_img_size','surplus-education-event' );
        $link         = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $custom_link         = ! empty( $instance['custom_link'] ) ? $instance['custom_link'] : '';
        $text         = ! empty( $instance['text'] ) ? $instance['text'] : '';

        $target = 'target="_self"';
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_blank"';
        }
        echo $args['before_widget'];
        ob_start();
        
        ?>
        <div class="flexbox-block">
            <div class="block-img">
            <?php 
                if( $image ){   
                    $image_attributes = wp_get_attachment_image_src( $image, $img_size );
                    ?>
                    <img src="<?php echo $image_attributes[0]; ?>" alt="<?php echo $title;?>" />
                <?php
                }else{
                    $obj->ste_get_fallback_svg( $img_size );
                }
            ?>  
            </div>
            <div class="block-content-wrap">
                <h5 class="title"><?php if( isset( $custom_link ) && $custom_link!='' && isset( $title_link ) && $title_link!='' ) { ?><a <?php echo $target;?> href="<?php echo esc_url($link); ?>"><?php } ?><?php echo esc_html( $title ); ?><?php if(isset($title_link) && $title_link!='') { ?></a><?php } ?></h5>
                <div class="block-meta">
                    <?php if( isset( $event_date ) && $event_date ) : ?>
                    <span class="posted-on">
                        <i class="fas fa-calendar-alt"></i>
                        <a href="<?php the_permalink(); ?>" rel="bookmark">
                            <time class="entry-date published updated">
                                <?php echo esc_html( $event_date ); ?>
                            </time>
                        </a>
                    </span>
                    <?php endif; ?>
                    <?php if( isset( $location ) && $location ) : ?>
                    <span class="event-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo esc_html( $location ); ?>
                    </span>
                    <?php 
                    if( isset( $text ) && $text!='' && isset( $custom_link ) && $custom_link!='' )
                    {   ?>
                        <a href="<?php echo esc_url($link); ?>" target="_blank"><?php echo esc_attr($text);?></a>
                    <?php } ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        echo apply_filters( 'ste_event_widget_filter', $html, $args, $instance );   
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
        $title      = ! empty( $instance['title'] ) ? $instance['title'] : '' ;
        $main_title      = ! empty( $instance['main_title'] ) ? $instance['main_title'] : '' ;
        $img_size   = ! empty( $instance['size'] ) ? $instance['size'] : apply_filters(  'ste_widget_event_img_size','surplus-education-event' );
        $target             = ! empty( $instance['target'] ) ? $instance['target'] : '';
        $custom_link        = ! empty( $instance['custom_link'] ) ? $instance['custom_link'] : '';
        $link               = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $title_link               = ! empty( $instance['title_link'] ) ? $instance['title_link'] : '';
        $text               = ! empty( $instance['text'] ) ? $instance['text'] : '';

        $obj = new Surplus_Essentials_Functions();
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr($title);?>" type="text" />            
        </p>
        <div class="ste-event-outer" id="<?php echo esc_attr( $this->get_field_id( 'ste-event-outer' ) ); ?>">
            <div class="event-widget-wrap">
                <p>
                    <?php 
                    $obj->ste_get_image_field( $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), !empty($instance['image']) ? esc_attr($instance['image']) : '', __( 'Upload Image', 'surplus-essentials' ) ); ?>
                </p>
                <?php
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
                global $_wp_additional_image_sizes;
                if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
                    $image_sizes = array_merge( $default_image_sizes, $_wp_additional_image_sizes );
                }

                foreach ($image_sizes as $key => $value) {
                    $image_sizes_names[] = array( 'name'=>$key, 'width'=>$value['width'], 'height'=>$value['height'] );   
                }
                ?>
                <p class="emw-image-option">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image Size', 'surplus-essentials' ); ?></label>
                    <select name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>">
                      <?php foreach ($image_sizes_names as $size_name): 
                        ?>
                        <option value="<?php echo $size_name['name'] ?>" <?php selected($img_size,$size_name['name']);?> ><?php echo $size_name['name'].' ( '.$size_name['width'].' x '.$size_name['height'].' )'; ?></option>
                      <?php 
                    endforeach; ?>
                    </select>
                </p>
                <p class="date">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'event_date' ) ); ?>"><?php esc_html_e( 'Date', 'surplus-essentials' ); ?></label> 
                    <input class="widefat ste-datepicker-field" id="event-date-pick <?php echo esc_attr( $this->get_field_id( 'event_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'event_date' ) ); ?>" value="<?php echo !empty($instance['event_date']) ? esc_attr($instance['event_date']) : '';?>" type="text" />            
                </p>
                <p class="location">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'location' ) ); ?>"><?php esc_html_e( 'Location', 'surplus-essentials' ); ?></label> 
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'location' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'location' ) ); ?>" value="<?php echo !empty($instance['location']) ? esc_attr($instance['location']) : '';?>" type="text" />
                </p>
                

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'custom_link' ) ); ?>">
                    <input class="widefat custom-link" id="<?php echo esc_attr( $this->get_field_id( 'custom_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'custom_link' ) ); ?>" type="checkbox" value="1" <?php echo checked($custom_link,1);?> /><?php esc_html_e( 'Use Custom Link', 'surplus-essentials' ); ?> </label>
                </p>
                
                <div class="custom-link-wrap">
                    
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Custom Link Text', 'surplus-essentials' ); ?></label> 
                        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_html( $text ); ?>" />            
                    </p>

                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Custom Link', 'surplus-essentials' ); ?></label> 
                        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_url( $link ); ?>" />            
                    </p>

                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
                        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in New Tab', 'surplus-essentials' ); ?> </label>
                    </p>

                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'title_link' ) ); ?>">
                        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_link' ) ); ?>" type="checkbox" value="1" <?php echo checked($title_link,1);?> /><?php esc_html_e( 'Enable Link in Title', 'surplus-essentials' ); ?> </label>
                    </p>

                </div>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('.custom-link').on('click', function( event ){
                    if ($(this).is(':checked')) {
                        $('.custom-link-wrap').fadeIn();
                    }
                    else{
                        $('.custom-link-wrap').fadeOut();
                    }
                });

            });
        </script>
        <style type="text/css">
            .wp-customizer div.ui-datepicker {
                z-index: 500001 !important;
            }
            <?php if(isset($custom_link) && $custom_link!=''){ echo ".custom-link-wrap{display: block;}"; } else{echo ".custom-link-wrap{display: none;}";}?>
        </style>
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
        $instance['title']        = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '' ;
        $instance['image']        = ! empty( $new_instance['image'] ) ? esc_attr( $new_instance['image'] ) : '';
        $instance['size']         = ! empty( $new_instance['size'] ) ? esc_attr( $new_instance['size'] ) : '';
        $instance['event_link']   = ! empty( $new_instance['event_link'] ) ? esc_url( $new_instance['event_link'] ) : '';
        $instance['location']     = ! empty( $new_instance['location'] ) ? esc_attr( $new_instance['location'] ) : '';
        $instance['event_date']   = ! empty( $new_instance['event_date'] ) ? esc_attr( $new_instance['event_date'] ) : '';
        $instance['target']       = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';
        $instance['link']         = ! empty( $new_instance['link'] ) ? esc_attr( $new_instance['link'] ) : '';
        $instance['title_link']         = ! empty( $new_instance['title_link'] ) ? esc_attr( $new_instance['title_link'] ) : '';
        $instance['custom_link']         = ! empty( $new_instance['custom_link'] ) ? esc_attr( $new_instance['custom_link'] ) : '';
        $instance['text']         = ! empty( $new_instance['text'] ) ? esc_attr( $new_instance['text'] ) : '';
        return $instance;
    }
}