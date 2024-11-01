<?php
/**
 * Team Member Widget
 *
 * @package Surplus_Essentials
 */

// register Surplus_Essentials_Team_Member_Widget widget
function ste_register_team_member_widget(){
    register_widget( 'Surplus_Essentials_Team_Member_Widget' );
}
add_action('widgets_init', 'ste_register_team_member_widget');
 
 /**
 * Adds Surplus_Essentials_Team_Member_Widget widget.
 */
class Surplus_Essentials_Team_Member_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'ste_team_widget', // Base ID
            __( 'SE: Team Member', 'surplus-essentials' ), // Name
            array( 'description' => __( 'A Team Member Widget.', 'surplus-essentials' ), ) // Args
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
        
        $obj         = new Surplus_Essentials_Functions();
        $name        = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $designation = ! empty( $instance['designation'] ) ? $instance['designation'] : '' ;    
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';
        $linkedin    = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $twitter     = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $facebook    = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $instagram   = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $youtube     = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $dribbble    = ! empty( $instance['dribbble'] ) ? $instance['dribbble'] : '';
        $behance     = ! empty( $instance['behance'] ) ? $instance['behance'] : '';
        $image       = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $img_size  = ! empty( $instance['size'] ) ? $instance['size'] : apply_filters( 'ste_widget_team_member_img_size','full' );
        $link       = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $text       = ! empty( $instance['text'] ) ? $instance['text'] : '';

        $target = 'target="_self"';
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_target"';
        }

        echo $args['before_widget']; 
        ob_start();
        ?>
            <div class="flexbox-wrapper">
                <?php
                if( $image ){
                    /** Added to work for demo content compatible */
                    $attachment_id = $image;
                    if ( !filter_var( $image, FILTER_VALIDATE_URL ) === false ) {
                        $attachment_id = $obj->ste_get_attachment_id( $image );
                    }
                    $team_member_img_size = $img_size;
                }
                ?>
                <?php if( $image ){ ?>
                    <div class="image-wrapper">
                        <?php echo wp_get_attachment_image( $attachment_id, $team_member_img_size, false, 
                                    array( 'alt' => esc_attr( $name )));?>
                    </div>
                <?php } ?>

                <div class="content-wrapper">
                <?php 
                    if( $name ) { echo '<h4 class="team-name">' . apply_filters( 'widget_title', $name, $instance, $this->id_base ) . '</h4>'; }
                    if( isset( $designation ) && $designation!='' ){
                        echo '<span class="team-designation">' . esc_html( $designation ) .  '</span>';
                    }
                    if( $description ) echo '<div class="description">' . wpautop( wp_kses_post( $description ) ) . '</div>';
                ?>                              
                    <div class="team-social-list">
                        <ul class="social-profile">
                        <?php if( isset( $facebook ) && $facebook!='' ) { echo '<li><a '.$target.' href="'.esc_url($facebook).'"><i class="fa fa-facebook"></i></a></li>'; }?>
                        <?php if( isset( $instagram ) && $instagram!='' ) { echo '<li><a '.$target.' href="'.esc_url($instagram).'"><i class="fa fa-instagram"></i></a></li>'; }?>
                        <?php if( isset( $twitter ) && $twitter!='' ) { echo '<li><a '.$target.' href="'.esc_url($twitter).'"><i class="fa fa-twitter"></i></a></li>'; }?>
                        <?php if( isset( $linkedin ) && $linkedin!='' ) { echo '<li><a '.$target.' href="'.esc_url($linkedin).'"><i class="fa fa-linkedin"></i></a></li>'; }?>
                        <?php if( isset( $youtube ) && $youtube!='' ) { echo '<li><a '.$target.' href="'.esc_url($youtube).'"><i class="fa fa-youtube"></i></a></li>'; }?>
                        <?php if( isset( $dribbble ) && $dribbble!='' ) { echo '<li><a '.$target.' href="'.esc_url($dribbble).'"><i class="fa fa-dribbble"></i></a></li>'; }?>
                        <?php if( isset( $behance ) && $behance!='' ) { echo '<li><a '.$target.' href="'.esc_url($behance).'"><i class="fa fa-behance"></i></a></li>'; }?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flexbox-wrapper-team">
                <div class="flexbox-team-modal">
                    <?php
                    if( $image ){
                        /** Added to work for demo content compatible */
                        $attachment_id = $image;
                        if ( !filter_var( $image, FILTER_VALIDATE_URL ) === false ) {
                            $attachment_id = $obj->ste_get_attachment_id( $image );
                        }
                        $team_member_img_size = apply_filters('ste_team_member_size','full');
                    }
                    ?>
                    <?php if( $image ){ ?>
                        <div class="image-wrapper">
                            <?php echo wp_get_attachment_image( $attachment_id, $team_member_img_size, false, 
                                        array( 'alt' => esc_attr( $name )));?>
                        </div>
                    <?php } ?>

                    <div class="content-wrapper">
                    <?php 
                        if( $name ) { echo '<h4 class="team-name">' . esc_html( $name ) . '</h4>'; }
                        if( isset( $designation ) && $designation!='' ){
                            echo '<span class="team-designation">' . esc_html( $designation ) .  '</span>';
                        }
                        if( $description ) echo '<div class="description">' . wpautop( wp_kses_post( $description ) ) . '</div>';
                    ?>                              
                        <div class="team-social-list">
                            <ul class="social-profile">
                            <?php if( isset( $facebook ) && $facebook!='' ) { echo '<li><a '.$target.' href="'.esc_url($facebook).'"><i class="fa fa-facebook"></i></a></li>'; }?>
                            <?php if( isset( $instagram ) && $instagram!='' ) { echo '<li><a '.$target.' href="'.esc_url($instagram).'"><i class="fa fa-instagram"></i></a></li>'; }?>
                            <?php if( isset( $twitter ) && $twitter!='' ) { echo '<li><a '.$target.' href="'.esc_url($twitter).'"><i class="fa fa-twitter"></i></a></li>'; }?>
                            <?php if( isset( $linkedin ) && $linkedin!='' ) { echo '<li><a '.$target.' href="'.esc_url($linkedin).'"><i class="fa fa-linkedin"></i></a></li>'; }?>
                            <?php if( isset( $youtube ) && $youtube!='' ) { echo '<li><a '.$target.' href="'.esc_url($youtube).'"><i class="fa fa-youtube"></i></a></li>'; }?>
                            <?php if( isset( $dribbble ) && $dribbble!='' ) { echo '<li><a '.$target.' href="'.esc_url($dribbble).'"><i class="fa fa-dribbble"></i></a></li>'; }?>
                            <?php if( isset( $behance ) && $behance!='' ) { echo '<li><a '.$target.' href="'.esc_url($behance).'"><i class="fa fa-behance"></i></a></li>'; }?>
                            </ul>
                        </div>
                        <?php
                        if(isset($link) && $link!='' && isset($text) && $text!='')
                        {   ?>
                        <div class="detail-link">
                            <a href="<?php echo esc_url($link);?>"><?php echo esc_html($text); ?></a>
                        </div>
                        <?php } ?>
                    </div>
                    <a href="javascript:void(0);" class="close_popup"></a>
                </div>
            </div>
        <?php
        echo 
        "<style>
            .flexbox-wrapper-team{
                display: none;
            }
        </style>
        <script>
            jQuery(document).ready(function($) {
              $('.flexbox-wrapper').click(function(){
                $(this).siblings('.flexbox-wrapper-team').addClass('show');
                $(this).siblings('.flexbox-wrapper-team').css('display', 'block');
              });

              $('.close_popup').click(function(){
                $(this).parent('.flexbox-wrapper-team').removeClass('show');
                $(this).parent().css('display', 'none');
              }); 
            });
        </script>";
        $html = ob_get_clean();
        echo apply_filters( 'ste_team_member_widget_filter', $html, $args, $instance );    
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
        
        $obj = new Surplus_Essentials_Functions();
        $name               = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $description        = ! empty( $instance['description'] ) ? $instance['description'] : '';
        $linkedin           = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $twitter            = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $facebook           = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $instagram          = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $youtube            = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $dribbble           = ! empty( $instance['dribbble'] ) ? $instance['dribbble'] : '';
        $behance            = ! empty( $instance['behance'] ) ? $instance['behance'] : '';
        $designation        = ! empty( $instance['designation'] ) ? $instance['designation'] : '';
        $image              = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $img_size           = ! empty( $instance['size'] ) ? $instance['size'] : apply_filters(  'ste_widget_team_member_img_size','full' );
        $target             = ! empty( $instance['target'] ) ? $instance['target'] : '';
        $link               = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $text               = ! empty( $instance['text'] ) ? $instance['text'] : '';
        ?>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Name', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />            
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'designation' ) ); ?>"><?php esc_html_e( 'Designation', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'designation' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'designation' ) ); ?>" type="text" value="<?php echo esc_attr( $designation ); ?>" />            
        </p>     
        
        <?php 
        $obj->ste_get_image_field( $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), $image, __( 'Upload Photo', 'surplus-essentials' ) ); 
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
        <p class="tmw-image-option">
            <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image Size', 'surplus-essentials' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>">
              <?php foreach ($image_sizes_names as $size_name): 
                ?>
                <option value="<?php echo $size_name['name'] ?>" <?php selected($img_size,$size_name['name']);?> ><?php echo $size_name['name'].' ( '.$size_name['width'].' x '.$size_name['height'].' )'; ?></option>
              <?php 
            endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'surplus-essentials' ); ?></label>
            <textarea name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php print $description; ?></textarea>
        </p>

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
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn Profile', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="text" value="<?php echo esc_url( $linkedin ); ?>" />            
        </p>
        
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter Profile', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="text" value="<?php echo esc_url( $twitter ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook Profile', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="text" value="<?php echo esc_url( $facebook ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram Profile', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="text" value="<?php echo esc_url( $instagram ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'YouTube Profile', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="text" value="<?php echo esc_url( $youtube ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>"><?php esc_html_e( 'Dribbble Profile', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dribbble' ) ); ?>" type="text" value="<?php echo esc_url( $dribbble ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'behance' ) ); ?>"><?php esc_html_e( 'Behance Profile', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'behance' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'behance' ) ); ?>" type="text" value="<?php echo esc_url( $behance ); ?>" />            
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
        
        $instance['title']               = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '' ;
        $instance['description']        = ! empty( $new_instance['description'] ) ? wp_kses_post( $new_instance['description'] ) : '';
        $instance['designation']        = ! empty( $new_instance['designation'] ) ? esc_attr( $new_instance['designation'] ) : '';
        $instance['linkedin']           = ! empty( $new_instance['linkedin'] ) ? esc_url_raw( $new_instance['linkedin'] ) : '';
        $instance['twitter']            = ! empty( $new_instance['twitter'] ) ? esc_url_raw( $new_instance['twitter'] ) : '';
        $instance['facebook']           = ! empty( $new_instance['facebook'] ) ? esc_url_raw( $new_instance['facebook'] ) : '';
        $instance['instagram']          = ! empty( $new_instance['instagram'] ) ? esc_url_raw( $new_instance['instagram'] ) : '';
        $instance['youtube']            = ! empty( $new_instance['youtube'] ) ? esc_url_raw( $new_instance['youtube'] ) : '';
        $instance['dribbble']           = ! empty( $new_instance['dribbble'] ) ? esc_url_raw( $new_instance['dribbble'] ) : '';
        $instance['behance']            = ! empty( $new_instance['behance'] ) ? esc_url_raw( $new_instance['behance'] ) : '';
        $instance['image']              = ! empty( $new_instance['image'] ) ? esc_attr( $new_instance['image'] ) : '';
        $instance['size']              = ! empty( $new_instance['size'] ) ? esc_attr( $new_instance['size'] ) : '';
        $instance['target']         = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';
        $instance['link']              = ! empty( $new_instance['link'] ) ? esc_attr( $new_instance['link'] ) : '';
        $instance['text']              = ! empty( $new_instance['text'] ) ? esc_attr( $new_instance['text'] ) : '';
        return $instance;
    }
    
}  // class Surplus_Essentials_Team_Member_Widget / class Surplus_Essentials_Team_Member_Widget 