<?php
function ste_custom_categories_load_widget() {
    register_widget( 'Surplus_Essentials_Custom_Categories' );
}
add_action( 'widgets_init', 'ste_custom_categories_load_widget' );
 
// Creating the widget 
class Surplus_Essentials_Custom_Categories extends WP_Widget {
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'ste_custom_categories', 
		 
		// Widget name will appear in UI
		__('SE: Custom Categories', 'surplus-essentials'), 
		 
		// Widget description
		array( 'description' => __( 'Widget to display categories with Image and Posts Count', 'surplus-essentials' ), ) 
		);
	}
		 
	// Creating widget front-end
		 
	public function widget( $args, $instance ) {
        $title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $ccw_img_size  = ! empty( $instance['size'] ) ? $instance['size'] : 'full' ;        
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		ob_start();
		$target = 'target="_self"';
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_blank"';
        }
		if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];
        
		echo '<div class="custom-category-wrapper">';
		$cats[] = '1';
		if( isset( $instance['categories'] ) &&  $instance['categories']!='' )
		{
			$cats[] = '';
			$cats = $instance['categories'];
		}
		foreach ($cats as $key => $value) 
		{
			$url[] = '';
			$img = get_term_meta( $value, 'category-image-id', false );
			$category = get_term($value);
			if($category)
			{
				$count = $category->count;

				if( isset($img) && is_array($img) && isset($img[0]) )
				{
					$url1 = wp_get_attachment_image_url( $img[0], $ccw_img_size );
	                if(!isset($url))
	                {
	                    $url1 = wp_get_attachment_image_url( $img[0], 'thumbnail' );
	                }
				}
				else{
					$url1 = apply_filters( 'ste_no_thumb', esc_url(STEP_FILE_URL).'/public/css/image/no-featured-img.png' );
				}
				echo '<div class="category-block" style="background: url('.$url1.') no-repeat">';
				echo '<div class="category-title"><a '.$target.' href="'. esc_url(get_category_link(  $category->term_id )) .'">'.esc_html($category->name);
				if( $count > 0 ) {
					$count_text = (1 == $count) ? __('Post','surplus-essentials') : __('Posts','surplus-essentials');
					echo '<div class="category-item-count">'.absint($count).' '.esc_html($count_text).'</div>';
				}
				echo '</a></div></div>';
			}
		}
		echo '</div>';
		// This is where you run the code and display the output
		$html = ob_get_clean();
        echo apply_filters( 'ste_custom_categories_widget_filter', $html, $args, $instance );
		echo $args['after_widget'];
	}
		         
		// Widget Backend 
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$categories[] = '';
		if ( isset( $instance[ 'categories' ] ) && $instance[ 'categories' ]!='' ) {
			$categories = $instance[ 'categories' ];
		}
        $target     = ! empty( $instance['target'] ) ? $instance['target'] : '';
        $img_size    = ! empty( $instance['size'] ) ? $instance['size'] : 'full';

		// Widget admin form
		$ran = rand(1,1000); $ran++;
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'surplus-essentials' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in new Tab', 'surplus-essentials' ); ?> </label>
        </p>
		<?php
		echo
			'<script>
			jQuery(document).ready(function($){
				$(".ste-categories-select-'.$ran.'").chosen({
                change: _.throttle( function() { // For Customizer
                $(this).trigger( "chosen:updated" );
                }, 3000 ),
                clear: _.throttle( function() { // For Customizer
                $(this).trigger( "chosen:updated" );
                }, 4000 )
                });
				$(".ste-categories-select-'.$ran.'").val('.json_encode($categories).').trigger("chosen:updated");
				if( $( ".ste-categories-select-'.$ran.'" ).siblings( ".chosen-container" ).length > 1 )
				{
				 	$(".ste-categories-select-'.$ran.'").siblings(".chosen-container").eq( 2 ).css( "display", "none" );
				}
			});
			</script>';
		?>
		<style>
		.ste-custom-cats .chosen-container{
			width: 100% !important;
			margin-bottom: 10px;
		}
		.ste-custom-cats .chosen-container:nth-of-type(2) {
    		display: none;
		}
		</style>
		<div class="ste-custom-cats">
			<select name="<?php echo $this->get_field_name( 'categories[]' );?>" class="ste-categories-select-<?php echo $ran;?>" id="ste-categories-select-<?php echo $ran;?>" multiple style="width:350px;" tabindex="4" data-placeholder="<?php _e('Select Categories. Post type are in brackets.',''); ?>">
			  	<?php
			  	$args = array( 'public'   => true, '_builtin' => false );
			  	$custom_taxonomies = get_taxonomies($args);
			  	$args = array( 'public'   => true, '_builtin' => true );
			  	$core_taxonomies = get_taxonomies($args);
				$taxonomies = array_merge($custom_taxonomies,$core_taxonomies);
			  	foreach ($taxonomies as $key => $value) {
			  		$args = array();
 					$terms = get_terms( $key, $args);
					foreach ( $terms as $term ) {
						$tterm = get_term_by('id', $term->term_id , $key);
						$post_type = get_taxonomy( $tterm->taxonomy )->object_type[0];
						if( strpos($post_type, '_') !== false ) {
						    $post_type = substr($post_type, strrpos($post_type, '_' )+1);
						}
					    printf( '<option value="%1$s">%2$s</option>',
					        esc_html( $term->term_id ),
					        esc_html( $term->name.' ['.$post_type.']' )
					    );
					}
				}
			  	?>
			</select>
		</div>
		<span class="ste-option-side-note" class="example-text"><?php $bold = '<b>'; $boldclose = '</b>'; echo sprintf( __('To set thumbnail for categories, go to %1$sPosts > Categories%2$s and %3$sEdit%4$s the categories.','surplus-essentials'), $bold, $boldclose, $bold, $boldclose);?></span>
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
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image Size', 'surplus-essentials' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>">
              <?php foreach ($image_sizes_names as $size_name): 
                ?>
                <option value="<?php echo $size_name['name'] ?>" <?php selected($img_size,$size_name['name']);?> ><?php echo $size_name['name'].' ( '.$size_name['width'].' x '.$size_name['height'].' )'; ?></option>
              <?php 
            endforeach; ?>
            </select>
        </p>
		<?php 
	}
		     
		// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['categories'] = '';
		if( isset( $new_instance['categories'] ) && $new_instance['categories']!='' )
		{
			$instance['categories'] = $new_instance['categories'];
		}
        $instance['target']                  = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';
        $instance['size']    = ! empty( $new_instance['size'] ) ? esc_attr( $new_instance['size'] ) : '';
        
		return $instance;
	}
}