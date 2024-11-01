<?php
/**
 * Widget Featured
 *
 * @package Surplus Essentials
 */
 
// register Surplus_Essentials_Featured_Page_Widget widget
function ste_register_featured_page_widget() {
    register_widget( 'Surplus_Essentials_Featured_Page_Widget' );
}
add_action( 'widgets_init', 'ste_register_featured_page_widget' );
 
 /**
 * Adds Surplus_Essentials_Featured_Page_Widget widget.
 */
class Surplus_Essentials_Featured_Page_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'ste_featured_page_widget', // Base ID
            __( 'SE: Featured Page Widget', 'surplus-essentials' ), // Name
            array( 'description' => __( 'A Featured Page Widget', 'surplus-essentials' ), ) // Args
        );
    }

    function ste_featured_page_image_alignment()
    {
        $array = apply_filters('ste_featured_page_widget_img_alignment',array(
            'right'     => __('Right','surplus-essentials'),
            'left'      => __('Left','surplus-essentials'),
            'centered'  => __('Centered','surplus-essentials')
        ));
        return $array;
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
        $title             = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $read_more         = !empty( $instance['readmore'] ) ? $instance['readmore'] : __( 'Read More', 'surplus-essentials' );      
        $show_feat_img     = !empty( $instance['show_feat_img'] ) ? $instance['show_feat_img'] : '' ;  
        $show_page_content = !empty( $instance['show_page_content'] ) ? $instance['show_page_content'] : '' ;        
        $show_readmore     = !empty( $instance['show_readmore'] ) ? $instance['show_readmore'] : '' ;        
        $page_list         = !empty( $instance['page_list'] ) ? $instance['page_list'] : 1 ;
        $image_alignment   = !empty( $instance['image_alignment'] ) ? $instance['image_alignment'] : 'right' ;
        $pagetitle         = !empty( $instance['pagetitle'] ) ? $instance['pagetitle'] : '';

        if( !isset( $page_list ) || $page_list == '' ) return;
        
        $post_no = get_post($page_list); 
        
        $target = apply_filters('ste_featured_page_widget_target','target="_self"');
        
        if( $post_no ){
            setup_postdata( $post_no );
            echo $args['before_widget'];
            ob_start();
                ?>
                <div class="flexbox-wrapper <?php echo esc_attr($image_alignment);?>">
                    <?php if( has_post_thumbnail( $post_no ) && $show_feat_img ){ ?>
                    <figure class="featured-page-img">
                        <a <?php echo $target;?> href="<?php the_permalink( $post_no ); ?>">
                            <?php 
                            $featured_img_size = apply_filters( 'ste_widget_featured_page_img_size', 'medium' );
                            echo get_the_post_thumbnail( $post_no, $featured_img_size ); ?>
                        </a>
                    </figure>
                    <?php } ?>
                    <div class="featured-page-content">
                        <?php
                        if(isset($title) && $title!='')
                        {  ?>
                            <div class="sub-title"><?php echo esc_html($title);?></div>
                        <?php } ?>
                        <?php
                        if(!isset($pagetitle) || $pagetitle=='')
                        {
                            echo esc_html( $post_no->post_title );
                        }
                        ?>
                        <div class="featured-page-desc">
                            <?php 
                            if( isset( $show_page_content ) && $show_page_content!='' ){
                                echo apply_filters( 'the_content', $post_no->post_content );                                
                            }else{
                                echo apply_filters( 'the_excerpt', get_the_excerpt( $post_no ) );                                
                            }
                            ?>
                        </div>
                        <div class="button-wrap">
                            <?php
                            if( !isset( $show_page_content ) || $show_page_content=='' && isset( $show_readmore ) && $show_readmore!='' )
                            { ?>
                                <a href="<?php the_permalink( $post_no ); ?>" <?php echo $target;?> class="btn-readmore"><?php echo esc_html( $read_more );?></a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>        
            <?php   
            $html = ob_get_clean();
            echo apply_filters( 'ste_featured_page_widget_filter', $html, $args, $instance );
            wp_reset_postdata();
            echo $args['after_widget'];   
        }
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $postlist[0] = array(
            'value' => 0,
            'label' => __('--Choose--', 'surplus-essentials'),
        );
        $arg = array( 'posts_per_page' => -1, 'post_type' => array( 'page' ) );
        $posts = get_posts($arg); 
        
        foreach( $posts as $p ){ 
            $postlist[$p->ID] = array(
                'value' => $p->ID,
                'label' => $p->post_title
            );
        }
        $title     = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $read_more         = !empty( $instance['readmore'] ) ? $instance['readmore'] : __( 'Read More', 'surplus-essentials' );
        $show_feat_img     = !empty( $instance['show_feat_img'] ) ? $instance['show_feat_img'] : '' ;  
        $show_page_title   = !empty( $instance['show_page_title'] ) ? $instance['show_page_title'] : '' ;        
        $show_page_content = !empty( $instance['show_page_content'] ) ? $instance['show_page_content'] : '' ;        
        $show_readmore     = !empty( $instance['show_readmore'] ) ? $instance['show_readmore'] : '' ;        
        $page_list         = !empty( $instance['page_list'] ) ? $instance['page_list'] : 1 ;
        $image_alignment   = !empty( $instance['image_alignment'] ) ? $instance['image_alignment'] : 1 ;
        $pagetitle    = ! empty( $instance['pagetitle'] ) ? $instance['pagetitle'] : '';

        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Sub Title', 'surplus-essentials' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />            
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'pagetitle' ) ); ?>">
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pagetitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pagetitle' ) ); ?>" type="checkbox" value="1" <?php echo checked($pagetitle,1);?> /><?php esc_html_e( 'Hide Page title in the front-end.', 'surplus-essentials' ); ?> </label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'page_list' ) ); ?>"><?php esc_html_e( 'Page:', 'surplus-essentials' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'page_list' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'page_list' ) ); ?>" class="widefat">
                <?php
                foreach ( $postlist as $single_post ) { ?>
                    <option value="<?php echo $single_post['value']; ?>" id="<?php echo esc_attr( $this->get_field_id( $single_post['label'] ) ); ?>" <?php selected( $single_post['value'], $page_list ); ?>><?php echo $single_post['label']; ?></option>
                <?php } ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_page_content' ) ); ?>" class="check-btn-wrap">
                <input class="full-content" id="<?php echo esc_attr( $this->get_field_id( 'show_page_content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_page_content' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_page_content ); ?>/>
                <?php esc_html_e( 'Show Page Full Content', 'surplus-essentials' ); ?>
            </label>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_feat_img' ) ); ?>" class="check-btn-wrap">
                <input id="<?php echo esc_attr( $this->get_field_id( 'show_feat_img' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_feat_img' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_feat_img ); ?>/>
                <?php esc_html_e( 'Show Featured Image', 'surplus-essentials' ); ?>
            </label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'image_alignment' ) ); ?>"><?php esc_html_e( 'Image Alignment:', 'surplus-essentials' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'image_alignment' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'image_alignment' ) ); ?>" class="widefat">
                <?php
                $align_options = $this->ste_featured_page_image_alignment();
                foreach ( $align_options as $key=>$val ) { ?>
                    <option value="<?php echo $key; ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" <?php selected( $key, $image_alignment ); ?>><?php echo $val; ?></option>
                <?php } ?>
            </select>
        </p>

        
        <div class="read-more" <?php echo isset($show_page_content) && ($show_page_content =='1') ? "style='display:none;'" : "style='display:block;'" ;?>> 
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_readmore' ) ); ?>" class="check-btn-wrap">
                    <input id="<?php echo esc_attr( $this->get_field_id( 'show_readmore' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_readmore' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_readmore ); ?>/>
                    <?php esc_html_e( 'Show Read More', 'surplus-essentials' ); ?>
                </label>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>"><?php esc_html_e( 'Read More Text', 'surplus-essentials' ); ?></label> 
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readmore' ) ); ?>" type="text" value="<?php echo esc_attr( $read_more ); ?>" />
            </p>
        </div>
        <?php
        echo 
        '<script>
        jQuery(document).ready(function($){
            $(".full-content").on("change", function(e) {
                var checked = $(this).is(":checked");
                if( checked )
                {
                    $(this).parent().parent().siblings(".read-more").hide();
                }
                else{
                    $(this).parent().parent().siblings(".read-more").show();
                }
            });
        });
        </script>'; 
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
        $instance['title']             = !empty( $new_instance['title'] ) ? esc_attr($new_instance['title']) : '';
        $instance['show_page_title']   = !empty( $new_instance['show_page_title'] ) ? esc_attr($new_instance['show_page_title']) : '' ;
        $instance['show_page_content'] = !empty( $new_instance['show_page_content'] ) ? esc_attr($new_instance['show_page_content']) : '' ;
        $instance['show_readmore']     = !empty( $new_instance['show_readmore'] ) ? esc_attr($new_instance['show_readmore']) : '' ;
        $instance['image_alignment']   = !empty( $new_instance['image_alignment'] ) ? esc_attr($new_instance['image_alignment']) : 1 ;
        $instance['readmore']          = ! empty( $new_instance['readmore'] ) ? sanitize_text_field( $new_instance['readmore'] ) : __( 'Read More', 'surplus-essentials' );
        $instance['page_list']         = ! empty( $new_instance['page_list'] ) ? absint( $new_instance['page_list'] ) : 1;
        $instance['show_feat_img']     = ! empty( $new_instance['show_feat_img'] ) ? absint( $new_instance['show_feat_img'] ) : '';
        $instance['pagetitle']         = ! empty( $new_instance['pagetitle'] ) ? esc_attr( $new_instance['pagetitle'] ) : '';

        return $instance;
    }

} // class Surplus_Essentials_Featured_Page_Widget
