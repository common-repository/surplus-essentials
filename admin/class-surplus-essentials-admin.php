<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wordpress.org/plugins/surplus-essentials/
 * @since      1.0.0
 *
 * @package    surplus_essentials
 * @subpackage surplus_essentials/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    surplus_essentials
 * @subpackage surplus_essentials/admin
 * @author     surplusthemes <info@surplusthemes.com>
 */
class Surplus_Essentials_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = STEP_PLUGIN_VERSION;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Surplus_Essentials_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Surplus_Essentials_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'jquery-ui-fresh', plugin_dir_url( __FILE__ ) . 'css/jquery-ui-fresh.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/surplus-essentials-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'chosen', plugin_dir_url( __FILE__ ) . 'css/chosen.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker' ); 
    	wp_enqueue_style('thickbox');
    	wp_enqueue_style( 'timepicker', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Surplus_Essentials_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Surplus_Essentials_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media();
		
		wp_enqueue_script( 'timepicker', plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.min.js', array( 'jquery'), '5.6.3', true );

		if( !isset( $_GET['page'] ) || isset( $_GET['page'] ) && $_GET['page'] !='invite-users' )
		{
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/surplus-essentials-admin.js', array( 'jquery','wp-color-picker','jquery-ui-datepicker' ), $this->version, true );
			$confirming = array( 
				'msg'       => __( 'Are you sure you want to proceed?', 'surplus-essentials' ),
				'category'	=> __( 'Select Categories', 'surplus-essentials' )
			);
			wp_localize_script( $this->plugin_name, 'sociconsmsg', $confirming );

			wp_localize_script( $this->plugin_name, 'sociconsmsg', array(
					'msg' => __( 'Are you sure you want to delete this Social Media?', 'surplus-essentials' ) ) );
		}
		
		wp_enqueue_script( 'chosen', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'all', plugin_dir_url( __FILE__ ) . 'js/all.min.js', array( 'jquery'), '5.6.3', true );
		wp_enqueue_script( 'v4-shims', plugin_dir_url( __FILE__ ) . 'js/v4-shims.min.js', array( 'jquery'), '5.6.3', true );
	}

    public function ste_icon_list_enqueue(){
		$obj = new Surplus_Essentials_Functions;
		$socicons = $obj->ste_icon_list();
		echo '<div class="ste-icons-wrap-template"><div class="ste-icons-wrap"><ul class="ste-icons-list">';
		foreach ($socicons as $socicon) {
			if($socicon == 'rss'){
				echo '<li><i class="fas fa-' . esc_attr( $socicon ) . '"></i></li>';
			}
			else{
				echo '<li><i class="fab fa-' . esc_attr( $socicon ) . '"></i></li>';
			}
			
		}
		echo'</ul></div></div>';
		echo '<style>
		.ste-icons-wrap{
			display:none;
		}
		</style>';
	}

	public function ste_date_picker_enqueue()
	{
		wp_enqueue_script( 'jquery-ui-datepicker' );
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
	    		$(".event-date-pick").datepicker({dateFormat: 'yy-mm-dd'});
    		});
    	</script>
	<?php
	}
    
	/*
	  * Add a form field in the new category page
	  * @since 1.0.0
	*/
	public function ste_add_category_image ( $taxonomy ) { ?>
	   	<div class="form-field term-group">
	     	<label for="category-image-id"><?php esc_html_e( 'Image', 'surplus-essentials' ); ?></label>
	     	<input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
	     	<div id="category-image-wrapper"></div>
	     	<p>
	       		<input type="button" class="button button-secondary ste_tax_media_button" id="ste_tax_media_button" name="ste_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'surplus-essentials' ); ?>" />
	       		<input type="button" class="button button-secondary ste_tax_media_remove" id="ste_tax_media_remove" name="ste_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'surplus-essentials' ); ?>" />
	    	</p>
	   	</div>
	 	<?php
	}
	 
	/*
	 * Save the form field
	 * @since 1.0.0
	*/
	public function ste_save_category_image ( $term_id ) {
	    if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
	    	$image = esc_attr($_POST['category-image-id']);
	    	add_term_meta( $term_id, 'category-image-id', $image, true );
	    }
	}
	 
	/*
	 * Edit the form field
	 * @since 1.0.0
	*/
	public function ste_update_category_image ( $term, $taxonomy='' ) { ?>
	   	<tr class="form-field term-group-wrap">
	     	<th scope="row">
	       		<label for="category-image-id"><?php esc_html_e( 'Image', 'surplus-essentials' ); ?></label>
	     	</th>
	     	<td>
		       	<?php $image_id = get_term_meta ( $term -> term_id, 'category-image-id', true ); ?>
		       	<input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo absint( $image_id ); ?>">
		       	<div id="category-image-wrapper">
		        	<?php if ( isset( $image_id ) && $image_id!='' ) { ?>
		           	<?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
		         	<?php } ?>
		       	</div>
	       		<p>
	         		<input type="button" class="button button-secondary ste_tax_media_button" id="ste_tax_media_button" name="ste_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'surplus-essentials' ); ?>" />
	         		<input type="button" class="button button-secondary ste_tax_media_remove" id="ste_tax_media_remove" name="ste_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'surplus-essentials' ); ?>" />
	       		</p>
	     	</td>
	   	</tr>
	 	<?php
	}

	/*
	 * Update the form field value
	 * @since 1.0.0
	*/
	public function ste_updated_category_image ( $term_id ) {

	    if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
	    	$image = esc_attr($_POST['category-image-id']);
	     	$var = update_term_meta ( $term_id, 'category-image-id', $image );
	   	} else {
	    	$var = update_term_meta ( $term_id, 'category-image-id', '' );
	   	}
	}

	/*
	 * Add script
	 * @since 1.0.0
	 */
	public function ste_add_script() { ?>
	   <script>
	     jQuery(document).ready( function($) {
	       	function ct_media_upload(button_class) {
	        	jQuery(button_class).click(function() {
					wp.media.editor.send.attachment = function(props, attachment) {
						$('#category-image-id').val(attachment.id);
						$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
						$('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
					}
					wp.media.editor.open(this);
					return false;
				});
	    	}
	     ct_media_upload('.ste_tax_media_button.button'); 
	     $('body').on('click','.ste_tax_media_remove',function(){
	       $('#category-image-id').attr('value','');
	       $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
	     });
	     // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
	     $(document).ajaxComplete(function(event, xhr, settings) {
	       var queryStringArr = settings.data.split('&');
	       if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
	         var xml = xhr.responseXML;
	         $response = $(xml).find('term_id').text();
	         if($response!=""){
	           // Clear the thumb image
	           $('#category-image-wrapper').html('');
	           $('#category-image-id').val('');
	         }
	       }
	     });
	   });
	 </script>
	 <?php 
	}

	function ste_custom_column_header( $columns ){
		$columns['header_name'] = 'Thumbnail'; 
		return $columns;
	}


	// To show the column value
	function ste_custom_column_content( $value, $column_name, $tax_id ){
	   	$img = get_term_meta( $tax_id, 'category-image-id', false );
	   	$ret = '';
	   	if(isset($img[0]) && $img[0]!='')
		{
			$url = wp_get_attachment_image_url($img[0],'thumbnail');
			$ret = '<img src="'.esc_url($url).'" class="tax-img">';
		}
	   	return $ret;
	}

	function ste_client_logo_template()
	{ ?>
		<div class="ste-client-logo-template">
			<div class="link-image-repeat"><span class="cross"><a href="#"><i class="fa fa-times"></i></a></span>
				<div class="widget-client-logo-repeater">
		            <div class="widget-upload">
		            	<label for="widget-stetheme_client_logo_widget-2-image"><?php esc_html_e('Upload Image','surplus-essentials');?></label><br>
		            	<input id="widget-stetheme_client_logo_widget-2-image" class="ste-upload link" type="hidden" name="widget-stetheme_client_logo_widget[2][image][]" value="" placeholder="No file chosen">
						<input id="upload-widget-stetheme_client_logo_widget-2-image" class="ste-upload-button button" type="button" value="Upload">
						<img class="ste-screenshot" id="widget-stetheme_client_logo_widget-2-image-image">
					</div>
					<div class="widget-feat-link">
		                <label for="widget-stetheme_client_logo_widget-2-link"><?php esc_html_e('Featured Link','surplus-essentials');?></label> 
		                <input class="featured-link" id="widget-stetheme_client_logo_widget-2-link" name="widget-stetheme_client_logo_widget[2][link][]" type="text" value="">            
		            </div>
	        	</div>
        	</div>
	    </div>
	<?php
	echo '<style>.ste-client-logo-template{display:none;}</style>';
	}

	function ste_faq_template()
	{?> 
		<div class="ste-faq-template">
			<div class="faqs-repeat" data-id=""><span class="fa fa-times cross"></span>
	            <label for="widget-Surplus_Essentials_faqs_widget-2-question-1"><?php esc_html_e('Question','surplus-essentials');?></label> 
	            <input class="widefat question" id="widget-Surplus_Essentials_faqs_widget-2-question-1" name="widget-Surplus_Essentials_faqs_widget[2][question][1]" type="text" value="">   
	            <label for="widget-Surplus_Essentials_faqs_widget-2-answer-1"><?php esc_html_e('Answer','surplus-essentials');?></label> 
	            <textarea class="answer" id="widget-Surplus_Essentials_faqs_widget-2-answer-1" name="widget-Surplus_Essentials_faqs_widget[2][answer][1]"></textarea>         
	        </div>
	    </div>
	  
        <?php
		echo '<style>.ste-faq-template{display:none;}</style>';
    }


    

   /**
	* Get post types for templates
	*
	* @return array of default settings
	*/
	public function ste_get_posttype_array() {
		$posts = array(
			'st_course' => array( 
					'posttype_label'		=> 'Course',
					'posttype_name'			=> 'course',
					'posttype_slug'			=> 'course',
					'taxonomy_slug'			=> 'courses',
					'posttype_icon'			=> 'book-alt',
					),
			'st_event' => array(  
					'posttype_label'		=> 'Event',
					'posttype_name'			=> 'event',
					'posttype_slug'			=> 'event',
					'taxonomy_slug'			=> 'events',
					'posttype_icon'			=> 'tickets',
					),
			'st_instructor' => array( 
					'posttype_label'		=> 'Instructor',
					'posttype_name'			=> 'instructor',
					'posttype_slug'			=> 'instructor',
					'taxonomy_slug'			=> 'instructors',
					'posttype_icon'			=> 'businesswoman',
					),
		);
		$post_types = apply_filters( 'ste_get_posttype_array', array() );
		$posts = array_merge($posts,$post_types);
		return $posts;
	}

	/**
	 * Register post types.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function ste_register_post_types() {

		$options = get_option( 'surplus_essentials_settings', true );
        if( isset($options) && $options!='' && is_array($options))
        {
          $size = sizeof($options['posttype_name']);
          	for ($i=0 ; $i < $size ; $i++) {
          		
          		$posttype_name = ucwords(str_replace('_', ' ', $options['posttype_name'][$i]));
          		if( isset($options['posttype_label'][$i]) && $options['posttype_label'][$i]!='' )
          		{
          			$posttype_name = ucwords($options['posttype_label'][$i]);
          		}
          		$slug = str_replace('st_', '', $options['posttype_slug'][$i]);
          		///////////////////
	          	$labels = array(
					'name'                  => _x( $posttype_name, 'Post Type General Name', 'surplus-essentials' ),
					'singular_name'         => _x( $posttype_name, 'Post Type Singular Name', 'surplus-essentials' ),
					'menu_name'             => __( $posttype_name, 'surplus-essentials' ),
					'name_admin_bar'        => __( $posttype_name, 'surplus-essentials' ),
					'archives'              => __( $posttype_name.' Archives', 'surplus-essentials' ),
					'attributes'            => __( $posttype_name.' Attributes', 'surplus-essentials' ),
					'parent_item_colon'     => __( 'Parent '. $posttype_name.':', 'surplus-essentials' ),
					'all_items'             => __( 'All '. $posttype_name, 'surplus-essentials' ),
					'add_new_item'          => __( 'Add New '. $posttype_name, 'surplus-essentials' ),
					'add_new'               => __( 'Add New', 'surplus-essentials' ),
					'new_item'              => __( 'New '. $posttype_name, 'surplus-essentials' ),
					'edit_item'             => __( 'Edit '. $posttype_name, 'surplus-essentials' ),
					'update_item'           => __( 'Update '. $posttype_name, 'surplus-essentials' ),
					'view_item'             => __( 'View '. $posttype_name, 'surplus-essentials' ),
					'view_items'            => __( 'View '. $posttype_name, 'surplus-essentials' ),
					'search_items'          => __( 'Search '. $posttype_name, 'surplus-essentials' ),
					'not_found'             => __( 'Not found', 'surplus-essentials' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'surplus-essentials' ),
					'featured_image'        => __( 'Featured Image', 'surplus-essentials' ),
					'set_featured_image'    => __( 'Set featured image', 'surplus-essentials' ),
					'remove_featured_image' => __( 'Remove featured image', 'surplus-essentials' ),
					'use_featured_image'    => __( 'Use as featured image', 'surplus-essentials' ),
					'insert_into_item'      => __( 'Insert into '.$posttype_name, 'surplus-essentials' ),
					'uploaded_to_this_item' => __( 'Uploaded to this '.$posttype_name, 'surplus-essentials' ),
					'items_list'            => __( $posttype_name .' list', 'surplus-essentials' ),
					'items_list_navigation' => __( $posttype_name .' list navigation', 'surplus-essentials' ),
					'filter_items_list'     => __( 'Filter '. $posttype_name .'list', 'surplus-essentials' ),
				);
				$args = array(
					'label'                 => __( $posttype_name.'', 'surplus-essentials' ),
					'description'           => __( $posttype_name.' Post Type', 'surplus-essentials' ),
					'labels'                => $labels,
					'supports'            	=> array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions' ),
					'hierarchical'          => false,
					'public'                => true,
					'menu_icon' 			=> (isset( $options['posttype_icon'][$i] ) && $options['posttype_icon'][$i]!='' )? 'dashicons-'.$options['posttype_icon'][$i]:'dashicons-welcome-write-blog',
					'show_ui'               => true,
					'show_in_menu'          => true,
					'show_in_admin_bar'     => true,
					'can_export'            => true,
					'publicly_queryable'    => true,
					'capability_type'       => 'post',
					'has_archive'           => $options['taxonomy_slug'][$i],
			        'rewrite'               => array( 'slug' => $options['posttype_slug'][$i], "with_front" => false ),
				);
				register_post_type( $options['posttype_name'][$i], $args );
          	}
      	}
      	else{
			$myarray = $this->ste_get_posttype_array();
			foreach ($myarray as $key => $value) {
          		$posttype_name = ucwords(str_replace('_', ' ', $value['posttype_name']));
          		$slug = str_replace('st_', '', $value['posttype_name']);

				$labels = array(
					'name'                  => _x( $posttype_name, 'Post Type General Name', 'surplus-essentials' ),
					'singular_name'         => _x( $posttype_name, 'Post Type Singular Name', 'surplus-essentials' ),
					'menu_name'             => __( $posttype_name, 'surplus-essentials' ),
					'name_admin_bar'        => __( $posttype_name, 'surplus-essentials' ),
					'archives'              => __( $posttype_name.' Archives', 'surplus-essentials' ),
					'attributes'            => __( $posttype_name.' Attributes', 'surplus-essentials' ),
					'parent_item_colon'     => __( 'Parent '. $posttype_name.':', 'surplus-essentials' ),
					'all_items'             => __( 'All '. $posttype_name, 'surplus-essentials' ),
					'add_new_item'          => __( 'Add New '. $posttype_name, 'surplus-essentials' ),
					'add_new'               => __( 'Add New', 'surplus-essentials' ),
					'new_item'              => __( 'New '. $posttype_name, 'surplus-essentials' ),
					'edit_item'             => __( 'Edit '. $posttype_name, 'surplus-essentials' ),
					'update_item'           => __( 'Update '. $posttype_name, 'surplus-essentials' ),
					'view_item'             => __( 'View '. $posttype_name, 'surplus-essentials' ),
					'view_items'            => __( 'View '. $posttype_name, 'surplus-essentials' ),
					'search_items'          => __( 'Search '. $posttype_name, 'surplus-essentials' ),
					'not_found'             => __( 'Not found', 'surplus-essentials' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'surplus-essentials' ),
					'featured_image'        => __( 'Featured Image', 'surplus-essentials' ),
					'set_featured_image'    => __( 'Set featured image', 'surplus-essentials' ),
					'remove_featured_image' => __( 'Remove featured image', 'surplus-essentials' ),
					'use_featured_image'    => __( 'Use as featured image', 'surplus-essentials' ),
					'insert_into_item'      => __( 'Insert into '.$posttype_name, 'surplus-essentials' ),
					'uploaded_to_this_item' => __( 'Uploaded to this '.$posttype_name, 'surplus-essentials' ),
					'items_list'            => __( $posttype_name .' list', 'surplus-essentials' ),
					'items_list_navigation' => __( $posttype_name .' list navigation', 'surplus-essentials' ),
					'filter_items_list'     => __( 'Filter '. $posttype_name .'list', 'surplus-essentials' ),
				);
				$args = array(
					'label'                 => __( $posttype_name.'', 'surplus-essentials' ),
					'description'           => __( $posttype_name.' Post Type', 'surplus-essentials' ),
					'labels'                => $labels,
					'supports'            	=> array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions' ),
					'hierarchical'          => false,
					'public'                => true,
					'menu_icon' 			=> isset( $value['posttype_icon'] ) ? 'dashicons-'.$value['posttype_icon']:'dashicons-welcome-write-blog',
					'show_ui'               => true,
					'show_in_menu'          => true,
					'show_in_admin_bar'     => true,
					'can_export'            => true,
					'publicly_queryable'    => true,
					'capability_type'       => 'post',
					'has_archive'           => $value['taxonomy_slug'],
			        'rewrite'               => array( 'slug' => $value['posttype_slug'], "with_front" => false ),
				);
				register_post_type( $key, $args );
			}
		}
	}

	/**
	 * Register a taxonomy, post_types_categories for the post types.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	function ste_create_post_type_taxonomies() {
		// Add new taxonomy, make it hierarchical
		$options = get_option( 'surplus_essentials_settings', true );
        if( isset($options) && $options!='' && is_array($options))
        {
          	$size = sizeof($options['posttype_name']);
          	for ($i=0 ; $i < $size ; $i++) {
          		if(isset($options['taxonomy_slug'][$i]) && $options['taxonomy_slug'][$i]!='')
          		{
          			$posttype_name = ucwords(str_replace('_', ' ', $options['posttype_name'][$i]));
	          		if( isset($options['posttype_label'][$i]) && $options['posttype_label'][$i]!='' )
	          		{
	          			$posttype_name = ucwords($options['posttype_label'][$i]);
	          		}

	          		
	          		$labels = array(
						'name'              => _x( $posttype_name.' Categories', 'taxonomy general name', 'surplus-essentials' ),
						'singular_name'     => _x( $posttype_name.' Category', 'taxonomy singular name', 'surplus-essentials' ),
						'search_items'      => __( 'Search Categories', 'surplus-essentials' ),
						'all_items'         => __( 'All Categories', 'surplus-essentials' ),
						'parent_item'       => __( 'Parent Categories', 'surplus-essentials' ),
						'parent_item_colon' => __( 'Parent Categories:', 'surplus-essentials' ),
						'edit_item'         => __( 'Edit Categories', 'surplus-essentials' ),
						'update_item'       => __( 'Update Categories', 'surplus-essentials' ),
						'add_new_item'      => __( 'Add New Categories', 'surplus-essentials' ),
						'new_item_name'     => __( 'New Categories Name', 'surplus-essentials' ),
						'menu_name'         => __( $posttype_name.' Categories', 'surplus-essentials' ),
					);

					$args = array(
						'hierarchical'      => true,
						'labels'            => $labels,
						'show_ui'           => true,
						'show_admin_column' => true,
						'show_in_nav_menus' => true,
						'rewrite'           => array( 'slug' => $options['taxonomy_slug'][$i], 'hierarchical' => true ),
					);
					register_taxonomy( $options['taxonomy_slug'][$i], array( $options['posttype_name'][$i] ), $args );
				}
          	}
        }
		else{
			$myarray = $this->ste_get_posttype_array();
			foreach ($myarray as $key => $value) {
				if(isset($value['taxonomy_slug']))
				{
          			if(strpos($value['posttype_name'], '_')!==false)
          			{
          				$posttype_name = ucwords(str_replace('_', ' ', $value['posttype_name']));
          			}
          			else{
          				$posttype_name = ucwords($value['posttype_name']);
          			}
					$labels = array(
						'name'              => _x( $posttype_name.' Categories', 'taxonomy general name', 'surplus-essentials' ),
						'singular_name'     => _x( $posttype_name.' Category', 'taxonomy singular name', 'surplus-essentials' ),
						'search_items'      => __( 'Search Categories', 'surplus-essentials' ),
						'all_items'         => __( 'All Categories', 'surplus-essentials' ),
						'parent_item'       => __( 'Parent Categories', 'surplus-essentials' ),
						'parent_item_colon' => __( 'Parent Categories:', 'surplus-essentials' ),
						'edit_item'         => __( 'Edit Categories', 'surplus-essentials' ),
						'update_item'       => __( 'Update Categories', 'surplus-essentials' ),
						'add_new_item'      => __( 'Add New Categories', 'surplus-essentials' ),
						'new_item_name'     => __( 'New Categories Name', 'surplus-essentials' ),
						'menu_name'         => __( $posttype_name.' Categories', 'surplus-essentials' ),
					);

					$args = array(
						'hierarchical'      => true,
						'labels'            => $labels,
						'show_ui'           => true,
						'show_admin_column' => true,
						'show_in_nav_menus' => true,
						'rewrite'           => array( 'slug' => $value['taxonomy_slug'], 'hierarchical' => true ),
					);
					register_taxonomy( $value['taxonomy_slug'], array( $key ), $args );
				}
			}
		}
	}
}