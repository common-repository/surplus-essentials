<?php
/**
 * Icon Text Widget
 *
 * @package Surplus_Essentials
 */

// register Surplus_Essentials_Client_Logo_Widget widget
function ste_register_client_logo_widget(){
    register_widget( 'Surplus_Essentials_Client_Logo_Widget' );
}
add_action('widgets_init', 'ste_register_client_logo_widget');
 
 /**
 * Adds Surplus_Essentials_Client_Logo_Widget widget.
 */
class Surplus_Essentials_Client_Logo_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'ste_partner_logo_widget', // Base ID
            __( 'SE: Client Logo Widget', 'surplus-essentials' ), // Name
            array( 'description' => __( 'A Client Logo Widget.', 'surplus-essentials' ), ) // Args
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
        
        if ( is_active_widget( false, false, $this->id_base, true ) ) {

            wp_enqueue_style( 'owl-carousel', STEP_FILE_URL . '/public/css/owl.carousel.min.css', array(), '2.2.1', 'all' );
            wp_enqueue_style( 'owl-theme-default', STEP_FILE_URL . '/public/css/owl.theme.default.min.css', array(), '2.2.1', 'all' );
            wp_enqueue_script( 'owl-carousel', STEP_FILE_URL . '/public/js/owl.carousel.min.js', array( 'jquery' ), '2.2.1', false );
        }

        $obj     = new Surplus_Essentials_Functions();
        $title   = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $items   = ! empty( $instance['items'] ) ? absint($instance['items']) : 1;
        $image   = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $link    = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $display_bw   = ! empty( $instance['display_bw'] ) ? $instance['display_bw'] : '' ;
        $target     = ! empty( $instance['target'] ) ? $instance['target'] : '';
        $class = '';
        if( isset($display_bw) && $display_bw!='' )
        {
            $class = "black-white";
        }
        echo $args['before_widget']; 
        ob_start();
        ?>
            <?php
            if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title']; ?> 

            <div class="client-logo-wrapper">
                <div class="logo-wrapper owl-carousel">
                    <?php $target = 'target="_self"';
                    if( isset($instance['target']) && $instance['target']!='' )
                    {
                        $target = 'target="_blank"';
                    }
                    if( isset($image) && $image !=''){
                        $size = sizeof( $instance['image']);
                        $max = max(array_keys($instance['image']));
                    
                        foreach ($instance['image'] as $key => $value) {
                            
                            if( isset( $instance['image'][$key] ) && $instance['image'][$key]!='' ){
                                
                                $image_id = $instance['image'][$key];

                                if ( !filter_var( $instance['image'][$key], FILTER_VALIDATE_URL ) === false ) {
                                    $image_id = $obj->ste_get_attachment_id( $instance['image'][$key] );
                                }
                                // retrieve the thumbnail size of our image
                                $cl_img_size = apply_filters('ste_cl_img_size','post-slider-thumb-size');
                                ?>
                                <div class="logo-block <?php echo esc_attr( $class ); ?>">
                                    <?php
                                    if( isset($instance['link'][$key]) && $instance['link'][$key]!='' )
                                    { ?>
                                        <a href="<?php echo esc_url($instance['link'][$key]);?>" <?php echo $target;?>>
                                    <?php
                                    }
                                    echo wp_get_attachment_image( $image_id, $cl_img_size, false, 
                                        array( 'alt' => esc_attr( $title )));
                                    if( isset($instance['link'][$key]) && $instance['link'][$key]!='' )
                                    {
                                    echo '</a>';                                
                                    }
                                    ?> 
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>  
                </div>
            </div>
            <script>
                jQuery(document).ready(function($){
                    $('.client-logo-wrapper .logo-wrapper').owlCarousel({
                        loop:true,
                        margin:10,
                        nav:true,
                        autoplay:true,
                        items:<?php echo absint($items);?>,
                        responsiveClass:true,
                    });
                }); 
                </script>
        <?php
        $html = ob_get_clean();
        echo apply_filters( 'ste_companion_iw', $html, $args, $title,  $image, $link );   
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
        $obj     = new Surplus_Essentials_Functions();
        $title   = ! empty( $instance['title'] ) ? $instance['title'] : '' ;
        $display_bw   = ! empty( $instance['display_bw'] ) ? $instance['display_bw'] : '' ;
        $image   = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $target     = ! empty( $instance['target'] ) ? $instance['target'] : '';
        $link    = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $items    = ! empty( $instance['items'] ) ? absint($instance['items']) : 1;
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                $('.widget-client-logo-repeater').sortable({
                    cursor: 'move',
                    update: function (event, ui) {
                        $('.widget-client-logo-repeater .link-image-repeat input').trigger('change');
                    }
                });
                $('.check-btn-wrap').on('click', function( event ){
                    $(this).trigger('change');
                });
            });
        </script>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>"><?php esc_html_e( 'Number of Logos (per slide)', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>" type="number" value="<?php echo esc_attr( $items ); ?>" />            
        </p>
        <div class="widget-client-logo-repeater" id="<?php echo esc_attr( $this->get_field_id( 'surplus-themes-essentials-logo-repeater' ) ); ?>">
            <?php 
            if(isset($instance['image'])){
                foreach ( $instance['image'] as $key => $value) { ?>
                    <div class="link-image-repeat"><span class="cross"><a href="javascript:void(0);"><i class="fa fa-times"></i></a></span>
                        <?php $obj->ste_get_image_field( $this->get_field_id( 'image['.$key.']' ), $this->get_field_name( 'image['.$key.']' ),  $instance['image'][$key], __( 'Upload Image', 'surplus-essentials' ) ); ?>
                        <div class="widget-feat-link">
                            <label for="<?php echo esc_attr( $this->get_field_id( 'link['.$key.']' ) ); ?>"><?php esc_html_e( 'Featured Link', 'surplus-essentials' ); ?></label> 
                            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link['.$key.']' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link['.$key.']' ) ); ?>" type="text" value="<?php echo isset( $instance['link'][$key] ) ? esc_url( $instance['link'][$key] ):''; ?>" /> 
                        </div>
                    </div>
                <?php 
                }
            }
            ?>
        </div>
        <span class="cl-repeater-holder"></span>

        <button class="add-logo button"><?php _e('Add Another Logo','surplus-essentials');?></button>
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
        $instance['title']   = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '' ;
        $instance['items']   = ! empty( $new_instance['items'] ) ? sanitize_text_field( $new_instance['items'] ) : '' ;
        foreach ( $new_instance['name'] as $key => $value ) {
            $instance['name'][$key]    = $value;
        }
        foreach ( $new_instance['image'] as $key => $value ) {
            $instance['image'][$key]   = $value;
        }
        foreach ( $new_instance['link'] as $key => $value ) {
            $instance['link'][$key]    = $value;
        }
        return $instance;
    }  
}  // class Surplus_Essentials_Client_Logo_Widget