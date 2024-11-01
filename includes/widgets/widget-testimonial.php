<?php
/**
 * Testimonial Widget
 *
 * @package Surplus_Essentials
 */

// register Surplus_Essentials_Testimonials_Widget widget
function ste_register_testimonials_widget(){
    register_widget( 'Surplus_Essentials_Testimonials_Widget' );
}
add_action('widgets_init', 'ste_register_testimonials_widget');
 
 /**
 * Adds Surplus_Essentials_Testimonials_Widget widget.
 */
class Surplus_Essentials_Testimonials_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        add_action( 'admin_print_footer_scripts', array( $this,'surplus_essentials_widget_template' ) );
        parent::__construct(
            'ste_testimonial_widget', // testimonial ID
            __( 'SE: Testimonial', 'surplus-essentials' ),
            array( 'description' => __( 'A Testimonial Widget.', 'surplus-essentials' ), ) // Args
        );
    }

    function surplus_essentials_widget_template(){ 
        $obj = new Surplus_Essentials_Functions();
        $image = '';
        ?>
        <div class="ste-testimonial-template">
            <div class="testimonial-widget-wrap" data-id="1"><a href="#" class="ste-testimonial-cancel"><span class="dashicons dashicons-no"></span></a>
                <?php $obj->ste_get_image_field( $this->get_field_id( 'image[]' ), $this->get_field_name( 'image[]' ), $image, __( 'Upload Image', 'surplus-essentials' ) ); 
                ?>
                <p class="name">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'name[]' ) ); ?>"><?php esc_html_e( 'Name', 'surplus-essentials' ); ?></label> 
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name[]' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name[]' ) ); ?>" type="text" />            
                </p>
                <p class="designation">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'designation[]' ) ); ?>"><?php esc_html_e( 'Designation', 'surplus-essentials' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'designation[]' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'designation[]' ) ); ?>" type="text" />            
                </p>
                <p class="testimonial">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'testimonial[]' ) ); ?>"><?php esc_html_e( 'Testimonial', 'surplus-essentials' ); ?></label>
                    <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'testimonial[]' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'testimonial[]' ) ); ?>"></textarea>            
                </p>
            </div>
        </div>
    <?php
        echo 
        '<style>
            .ste-testimonial-template{
                display: none;
            }
        </style>';
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
        
        if ( is_active_widget( false, false, $this->id_base, true ) ) {

            wp_enqueue_style( 'owl-carousel', STEP_FILE_URL . '/public/css/owl.carousel.min.css', array(), '2.2.1', 'all' );
            wp_enqueue_style( 'owl-theme-default', STEP_FILE_URL . '/public/css/owl.theme.default.min.css', array(), '2.2.1', 'all' );
            wp_enqueue_script( 'owl-carousel', STEP_FILE_URL . '/public/js/owl.carousel.min.js', array( 'jquery' ), '2.2.1', false );
        }

        $obj = new Surplus_Essentials_Functions();
        $title   = ! empty( $instance['title'] ) ? $instance['title'] : '' ;    
        echo $args['before_widget'];
        ob_start();
        if ( ! empty( $title ) )
        echo $args['before_title'] . esc_html( $title ) . $args['after_title']; 
        ?>
        <div class="testimonial-section-wrapper">
            <div class="flexbox-wrapper owl-carousel">
                <?php
                if( isset( $instance['testimonial'] ) )
                {
                    $size = sizeof( $instance['testimonial']);
                    $max = max(array_keys($instance['testimonial']));
                    for ($i=0; $i <= $max; $i++) {
                        echo '<div class="widget st_testimonial_widget">';
                        echo '<div class="testimonial-wrap">
                                <div class="testimonial-content">';
                                    echo isset($instance['testimonial'][$i]) ? esc_attr($instance['testimonial'][$i]):'';
                                echo '</div>';
                       
                        if( isset( $instance['image'][$i] ) && $instance['image'][$i]!='' ){ ?>
                            <?php
                            $image_id = $instance['image'][$i];
                            if ( !filter_var( $instance['image'][$i], FILTER_VALIDATE_URL ) === false ) {
                                $image_id = $obj->ste_get_attachment_id( $instance['image'][$i] );
                            }

                            // retrieve the thumbnail size of our image
                            $it_img_size = apply_filters('ste_testimonial_img_size', 'post-slider-thumb-size' );
                            echo '<div class="testimonial-img">'.wp_get_attachment_image( $image_id, $it_img_size ).'</div>';
                            ?>
                        <?php 
                        }
                        if( !isset( $instance['image'][$i] ) ){
                            echo '<div class="testimonial-img"><a href="'.esc_url(STEP_FILE_URL).'/public/css/image/no-featured-img.png" class="post-thumbnail"><img src="'.esc_url(STEP_FILE_URL).'/public/css/image/no-featured-img.png"></a></div>';
                        }
                         echo '<div class="testimonial-author-wrap">';
                         echo '<div class="testimonial-name">';
                         echo isset($instance['name'][$i]) ? esc_attr($instance['name'][$i]):'';
                         echo '</div>';
                         echo '<div class="testimonial-designation">';
                         echo isset($instance['designation'][$i]) ? esc_attr($instance['designation'][$i]):'';
                         echo '</div>';
                         echo '</div>';
                        echo '</div>
                        </div>'; 
                    }
                } 
                ?>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($){
            $('.testimonial-section-wrapper .owl-carousel').owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                items:1,
            });
        }); 
        </script>
        <?php
        $html = ob_get_clean();
        echo apply_filters( 'ste_testimonial_widget_filter', $html, $args, $instance );   
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
        // print_r($instance['testimonial']);
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr($title);?>" type="text" />            
        </p>
        <div class="ste-testimonial-outer" id="<?php echo esc_attr( $this->get_field_id( 'ste-testimonial-outer' ) ); ?>">
            <?php
            $obj = new Surplus_Essentials_Functions();
            if( isset( $instance['testimonial'] ) && $instance['testimonial']!='' && is_array( $instance['testimonial'] ) )
            {
                $size = sizeof( $instance['testimonial'] );
                $max = max(array_keys($instance['testimonial']));
                for ($i=0; $i <= $max; $i++) { 
                    if ( isset($instance['testimonial'][$i]) ) {
                        ?> 
                        <div class="testimonial-widget-wrap" data-id="<?php echo $i;?>"><a href="#" class="ste-testimonial-cancel"><span class="dashicons dashicons-no"></span></a>
                            <p>
                                <?php 
                                $obj->ste_get_image_field( $this->get_field_id( 'image[]' ), $this->get_field_name( 'image[]' ), !empty($instance['image'][$i]) ? esc_attr($instance['image'][$i]) : '', __( 'Upload Image', 'surplus-essentials' ) ); ?>
                            </p>
                            <p class="name">
                                <label for="<?php echo esc_attr( $this->get_field_id( 'natestimonial]' ) ); ?>"><?php esc_html_e( 'Name', 'surplus-essentials' ); ?></label> 
                                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name[]' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name[]' ) ); ?>" value="<?php echo !empty($instance['name'][$i]) ? esc_attr($instance['name'][$i]) : '';?>" type="text" />            
                            </p>
                            <p class="designation">
                                <label for="<?php echo esc_attr( $this->get_field_id( 'designation[]' ) ); ?>"><?php esc_html_e( 'Designation', 'surplus-essentials' ); ?></label> 
                                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'designation[]' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'designation[]' ) ); ?>" value="<?php echo !empty($instance['designation'][$i]) ? esc_attr($instance['designation'][$i]) : '';?>" type="text" />
                            </p>
                            <p class="testimonial">
                                <label for="<?php echo esc_attr( $this->get_field_id( 'testimonial[]' ) ); ?>"><?php esc_html_e( 'Testimonial', 'surplus-essentials' ); ?></label> 
                                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'testimonial[]' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'testimonial[]' ) ); ?>"><?php echo !empty($instance['testimonial'][$i]) ? esc_attr($instance['testimonial'][$i]) : '';?></textarea>
                            </p>
                        </div>
                <?php 
                    }   
                }
            }
            ?>
            <span class="ste-testimonial-holder"></span>
        </div>
        <input class="ste-testimonial-add button-secondary" type="button" value="<?php _e('Add Testimonial','surplus-essentials');?>"><br>
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
        // print_r($new_instance);die;
        if( isset( $new_instance['testimonial'] ) )
        {
            $size = sizeof($new_instance['testimonial']);
            for ($i=0; $i < $size; $i++) { 
                $instance['image'][$i]        = ! empty( $new_instance['image'][$i] ) ? esc_attr( $new_instance['image'][$i] ) : '';
                $instance['testimonial'][$i]         = ! empty( $new_instance['testimonial'][$i] ) ? esc_attr( $new_instance['testimonial'][$i] ) : '';
                $instance['name'][$i]    = ! empty( $new_instance['name'][$i] ) ? esc_attr( $new_instance['name'][$i] ) : '';
                $instance['designation'][$i]    = ! empty( $new_instance['designation'][$i] ) ? esc_attr( $new_instance['designation'][$i] ) : '';
            }
        }
        // echo '<pre>';
        // print_r($instance);
        // print_r($new_instance);
        // echo '</pre>';
        // die;
        return $instance;
    }
}