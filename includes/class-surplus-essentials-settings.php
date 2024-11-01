<?php
/**
 * The file that adds plugin settings.
 *
 * A class definition that includes shortcodes.
 * @link       https://surplusthemes.com
 * @since      1.0.0
 *
 * @package    Surplus_Essentials
 * @subpackage Surplus_Essentials/includes
 */
class Surplus_Essentials_Settings {

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'surplus_essentials_register_options_page' ) );
        add_action( 'admin_footer', array( $this, 'surplus_essentials_add_dashicons_list' ) );
        add_action( 'admin_init', array( $this, 'surplus_essentials_register_settings' ) );

    }

    //register settings
    function surplus_essentials_register_settings() {
      register_setting( 'surplus_essentials_settings', 'surplus_essentials_settings' );
    }

    //register options page
    function surplus_essentials_register_options_page() {
        add_submenu_page( "themes.php", 'Surplus Essen. Settings', 'Surplus Essentials', 'manage_options', 'surplus_essentials_settings', array( $this, 'surplus_theme_options_page' ) );
    }

    function array_diff_assoc_recursive($array1, $array2)
    {
      foreach($array1 as $key => $value)
      {
        if(is_array($value))
        {
          if(!isset($array2[$key]))
          {
            $difference[$key] = $value;
          }
          elseif(!is_array($array2[$key]))
          {
            $difference[$key] = $value;
          }
          else
          {
            $new_diff = $this->array_diff_assoc_recursive($value, $array2[$key]);
            if($new_diff != FALSE)
            {
              $difference[$key] = $new_diff;
            }
          }
        }
        elseif(!isset($array2[$key]) || $array2[$key] != $value)
        {
          $difference[$key] = $value;
        }
      }
      return !isset($difference) ? 0 : $difference;
    }

    //settings form
    function surplus_theme_options_page()
    {
    ?>
        <div class="wrap">
            <h2> <?php esc_html_e( 'Settings', 'surplus-essentials' ); ?></h2>
            <div class="ste-header">
              <h3><?php esc_html_e( 'Surplus Themes Essentials', 'surplus-essentials' ); ?></h3>
              <span class="ste-version">V: <?php echo STEP_PLUGIN_VERSION; ?></span>
              <div class="ste-clear"></div>
            </div>
            <h3><?php esc_html_e( 'Surplus Themes Essentials Settings', 'surplus-essentials' ); ?></h3>
            <?php 
              $options = get_option( 'surplus_essentials_settings', true );
              $history_options = get_option( 'surplus_essentials_settings_history', true );         
              $new_arr = array();       
              if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) { 
                if( isset( $history_options ) && $history_options !='' && is_array( $history_options ) )
                {
                  $size =  sizeof($options['posttype_slug']);
                  for ($i=0; $i < $size; $i++)
                  { 
                    if( !in_array( $options['posttype_slug'][$i], $history_options['posttype_slug'] ) )
                    {
                      $new_arr['posttype_label'][] = $options['posttype_label'][$i];
                      $new_arr['posttype_name'][] = $options['posttype_name'][$i];
                      $new_arr['posttype_slug'][] = $options['posttype_slug'][$i];
                      $new_arr['posttype_icon'][] = $options['posttype_icon'][$i];
                      $new_arr['taxonomy_slug'][] = $options['taxonomy_slug'][$i];
                    }
                  }
                  $arr = array_merge($new_arr, $history_options);
                  update_option( 'surplus_essentials_settings_history', $arr );
                }
              ?>
              <div id="-settings_updated" class="updated settings-error notice is-dismissible"> 
                <p>
                    <strong><?php echo ($_GET['settings-updated'] == 'true') ? __( 'Settings saved.', 'surplus-essentials' ) : __( 'Default settings restored successfully.', 'surplus-essentials' ); ?></strong>
                </p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'surplus-essentials' ); ?></span>
                </button>
              </div>
        <?php } ?>
          <div class="ste-settings-main-wrap">
            <form method="post" action="options.php">
              <?php 
              settings_fields( 'surplus_essentials_settings' );
              do_settings_sections('surplus_essentials_settings');

                $custom = array(
                   'public'   => true,
                   '_builtin' => false,
                );
                $output = 'objects'; // names or objects, note names is the default
                
                $operator = 'and'; // 'and' or 'or'

                $custom_posts = get_post_types( $custom, $output, $operator ); 

                $plugin_admin = new Surplus_Essentials_Admin('abc','1.0.0');
                $arr = $plugin_admin->ste_get_posttype_array();                
                ?>
                <div class="custom-posts-wrap">
                <?php
                if( isset($options) && $options!='' && is_array($options))
                {
                  $size = sizeof($options['posttype_name']);
                  for ($i=0 ; $i < $size ; $i++) { ?>
                      <div class="post-type-outer-wrap">
                        <div class="post-type-wrap">
                        <label><span class="ste-cpt-required">*&nbsp;</span><?php esc_html_e('Post Type Label', 'surplus-essentials'); ?><input type="text" placeholder="" value="<?php echo isset( $options['posttype_label'][$i] ) ? esc_attr($options['posttype_label'][$i]):''; ?>" name="surplus_essentials_settings[posttype_label][]"></label></div>
                        <div class="post-type-wrap">
                        <label><span class="ste-cpt-required">*&nbsp;</span><?php esc_html_e('Post Type Name', 'surplus-essentials'); ?><span class="tooltip" title="<?php esc_attr_e('Please add unique post type name while you create a new post type.','surplus-essentials');?>">&nbsp;<i class="fas fa-question-circle"></i></span><input type="text" placeholder="" value="<?php echo isset( $options['posttype_name'][$i] ) ? esc_attr($options['posttype_name'][$i]):''; ?>" name="surplus_essentials_settings[posttype_name][]"></label><div class="note" style="font-style: italic;"><?php esc_html_e('For Surplus Themes\'s theme, please add st_ as a prefix with your post type name. Example: st_portfolio.','surplus-essentials');?></div></div>
                        <div class="post-type-wrap">
                        <label><?php esc_html_e('Post Type Slug','surplus-essentials'); ?><span class="tooltip" title="<?php esc_attr_e('Please add unique post type slug while you create a new post type.','surplus-essentials');?>">&nbsp;<i class="fas fa-question-circle"></i></span><input type="text" placeholder="" value="<?php echo isset( $options['posttype_slug'][$i] ) ? esc_attr($options['posttype_slug'][$i]):''; ?>" name="surplus_essentials_settings[posttype_slug][]"></label></div>
                        <div class="post-type-wrap">
                        <label><?php esc_html_e('Icon','surplus-essentials'); ?><input type="text" placeholder="" value="<?php echo isset( $options['posttype_icon'][$i] ) ? esc_attr($options['posttype_icon'][$i]):''; ?>" class="dashicon-select-text" name="surplus_essentials_settings[posttype_icon][]" autocomplete="off" class="dashicon-select-text"></label></div>
                        <div class="post-type-wrap">
                        <label><?php esc_html_e('Taxonomy Slug','surplus-essentials'); ?><input type="text" placeholder="" value="<?php echo isset( $options['taxonomy_slug'][$i] ) ? esc_attr($options['taxonomy_slug'][$i]):''; ?>" name="surplus_essentials_settings[taxonomy_slug][]">
                        </label></div><span class="posttype dashicons dashicons-dismiss"></span>
                      </div>
                    <?php
                    }
                }
                else
                {
                  if(isset($arr) && $arr!='')
                  {
                    foreach ($arr as $cp=>$val) { ?>
                      <div class="post-type-outer-wrap">
                        <div class="post-type-wrap">
                        <label><span class="ste-cpt-required">*&nbsp;</span><?php esc_html_e('Post Type Label','surplus-essentials'); ?><input type="text" placeholder="" value="<?php echo isset( $val['posttype_label'] ) ? esc_attr($val['posttype_label']):''; ?>" name="surplus_essentials_settings[posttype_label][]"></label></div>
                        <div class="post-type-wrap">
                        <label><span class="ste-cpt-required">*&nbsp;</span><?php esc_html_e('Post Type Name','surplus-essentials'); ?><span class="tooltip" title="<?php esc_attr_e('Please add unique post type name while you create a new post type.','surplus-essentials');?>">&nbsp;<i class="fas fa-question-circle"></i></span><input type="text" placeholder="" value="<?php echo $cp; ?>" name="surplus_essentials_settings[posttype_name][]"></label><div class="note" style="font-style: italic;"><?php esc_html_e('For Surplus Themes\'s theme, please add st_ as a prefix with your post type name. Example: st_portfolio.','surplus-essentials');?></div></div>
                        <div class="post-type-wrap">
                        <label><?php esc_html_e('Post Type Slug','surplus-essentials'); ?><span class="tooltip" title="<?php esc_attr_e('Please add unique post type slug while you create a new post type.','surplus-essentials');?>">&nbsp;<i class="fas fa-question-circle"></i></span><input type="text" placeholder="" value="<?php echo isset( $val['posttype_slug'] ) ? esc_attr($val['posttype_slug']):''; ?>" name="surplus_essentials_settings[posttype_slug][]"></label></div>
                        <div class="post-type-wrap">
                        <label><?php esc_html_e('Icon','surplus-essentials'); ?><input type="text" placeholder="" value="<?php echo isset( $val['posttype_icon'] ) ? esc_attr($val['posttype_icon']):''; ?>" name="surplus_essentials_settings[posttype_icon][]" class="dashicon-select-text" autocomplete="off"></label></div>
                        <div class="post-type-wrap">
                        <label><?php esc_html_e('Taxonomy Slug','surplus-essentials'); ?><input type="text" placeholder="" value="<?php echo isset( $val['taxonomy_slug'] ) ? esc_attr($val['taxonomy_slug']):''; ?>" name="surplus_essentials_settings[taxonomy_slug][]"></label></div>
                        <span class="posttype dashicons dashicons-dismiss"></span>
                      </div>
                    <?php
                    }
                  }
                } ?>
              </div>

              <span class="add-post-type-holder"></span>
              <input type="button" class="add-post-type" value="<?php esc_attr_e('Add Post Type','surplus-essentials');?>" name="add-post-type">
              <?php submit_button(); ?>
            </form>
            <script type="text/javascript">
            jQuery(document).ready(function($){
              $('body').on('click', '.add-post-type', function(e) {
                e.preventDefault();
                $template = '<div class="post-type-outer-wrap"><div class="post-type-wrap"><span class="ste-cpt-required">*&nbsp;</span><label><?php _e("Post Type Label","surplus-essentials"); ?><input type="text" placeholder="" value="" name="surplus_essentials_settings[posttype_label][]"></label></div><div class="post-type-wrap"><label><span class="ste-cpt-required">*&nbsp;</span><?php _e("Post Type Name","surplus-essentials"); ?><span class="tooltip" title="<?php _e('Please add unique post type name while you create a new post type.','surplus-essentials');?>">&nbsp;<i class="fas fa-question-circle"></i></span><input type="text" placeholder="" value="" name="surplus_essentials_settings[posttype_name][]"></label><div class="note" style="font-style: italic;"><?php _e('For Surplus Themes theme, please add st_ as a prefix with your post type name. Example: st_portfolio.','surplus-essentials');?></div></div><div class="post-type-wrap"><label><?php _e("Post Type Slug","surplus-essentials"); ?><span class="tooltip" title="<?php _e('Please add unique post type slug while you create a new post type.','surplus-essentials');?>">&nbsp;<i class="fas fa-question-circle"></i></span><input type="text" placeholder="" value="" name="surplus_essentials_settings[posttype_slug][]"></label></div><div class="post-type-wrap"><label><?php _e("Icon","surplus-essentials"); ?><input type="text" placeholder="" value="" autocomplete="off" class="dashicon-select-text" name="surplus_essentials_settings[posttype_icon][]"></label></div><div class="post-type-wrap"><label><?php _e("Taxonomy Slug","surplus-essentials"); ?><input type="text" placeholder="" value="" name="surplus_essentials_settings[taxonomy_slug][]"></label></div><span class="posttype dashicons dashicons-dismiss"></span></div>';
                  $($template).insertBefore('.add-post-type-holder');
              });
              $(document).on('focus','.dashicon-select-text',function() {
                  $(this).val('');
                  if( $(this).siblings('.ste-dashicons-list').length < 1 )
                  {
                      var $iconlist = $('.ste-dashicons-list-wrap').clone();
                      $(this).after($iconlist.html());
                      $(this).siblings('.ste-dashicons-list').fadeIn('slow');
                  }        
                  if ( $(this).siblings('.ste-dashicons-list').find('#remove-icon-list').length < 1 )
                  {
                      var input = '<span id="remove-icon-list"><i class="fas fa-times"></i></span>';
                      $(this).siblings('.surplus-themes-toolkit-icons-list:visible').prepend(input);
                  }
              });

              $(document).on('keyup','.dashicon-select-text',function() {
                  var value = $(this).val();
                  var matcher = new RegExp(value, 'gi');
                  $(this).siblings('.ste-dashicons-list').children('.icons__item').show().not(function(){
                      return matcher.test($(this).find('i').attr('class'));
                  }).hide();
              });
              $(document).on('blur','.dashicon-select-text',function(e) {
                  e.preventDefault();
                  $(this).siblings('.ste-dashicons-list').fadeOut('slow',function(){
                      $(this).remove();
                  });
              });
              $(document).on('click','.ste-dashicons-list .icons__item',function(e) {
                  e.preventDefault();
                  $val = $(this).attr('data-name');
                  if($val=='dismiss')return;
                  $(this).parent().siblings('.dashicon-select-text').val($val);
                  $(this).parent().fadeOut('slow',function(){
                      $(this).remove();
                  });
              });
            });
            </script>
            <div class="ste-backend-sidebar">
              <h3><span><?php esc_html_e( 'Surplus Themes', 'surplus-essentials' ) ?></span></h3>
              <div class="inside">
                  <h4><?php esc_html_e( 'What we do?', 'surplus-essentials' ) ?></h4>
                  <ol>
                    <li><?php esc_html_e( 'Develop Themes', 'surplus-essentials' ) ?></li>
                    <li><?php esc_html_e( 'Develop Plugins', 'surplus-essentials' ) ?></li>
                    <li><?php esc_html_e( 'E-commerce', 'surplus-essentials' ) ?></li>
                    <li><?php esc_html_e( 'Maintenance', 'surplus-essentials' ) ?></li>
                    <li><?php esc_html_e( 'Free/Pro Support', 'surplus-essentials' ) ?></li>
                    <li><?php esc_html_e( 'Customisation', 'surplus-essentials' ) ?></li>
                  </ol>
                  <p><a href="https://surplusthemes.com" target="_blank"><?php esc_html_e( 'Visit Us', '' ) ?></a></p>
              </div> <!-- .inside -->
            </div>
            <div class="ste-backend-sidebar">
                <h3><span><?php esc_html_e( 'Helpful Links', 'surplus-essentials' ) ?></span></h3>
                <div class="inside">
                    <h4><?php esc_html_e( 'Questions, bugs or great ideas?', 'surplus-essentials' ) ?></h4>
                    <p><a href="http://wordpress.org/support/plugin/surplus-themes-essentials/" target="_blank"><?php esc_html_e( 'Visit this plugin support page', 'surplus-essentials' ) ?></a></p>
                    <h4><?php esc_html_e( 'Wanna help make this plugin better?', 'surplus-essentials' ) ?></h4>
                    <ul>
                        <li><a href="https://wordpress.org/support/plugin/surplus-themes-essentials/reviews/?filter=5" target="_blank"><?php esc_html_e( 'Review and rate this plugin on WordPress.org', 'surplus-essentials' ) ?></a></li>
                    </ul>
                </div> <!-- .inside -->
            </div>
          </div>
        </div>
    <?php
    }

    //function to add dashicons list
    function surplus_essentials_add_dashicons_list()
    { ?>
      <div class="ste-dashicons-list-wrap">
        <ul class="ste-dashicons-list">  
            <li class="icons__item" data-name="admin-appearance"><i class="dashicons dashicons-admin-appearance"></i> appearance</li>
          
            <li class="icons__item" data-name="admin-collapse"><i class="dashicons dashicons-admin-collapse"></i> collapse</li>
          
            <li class="icons__item" data-name="admin-comments"><i class="dashicons dashicons-admin-comments"></i> comments</li>
          
            <li class="icons__item" data-name="admin-customizer"><i class="dashicons dashicons-admin-customizer"></i> customizer</li>
          
            <li class="icons__item" data-name="admin-generic"><i class="dashicons dashicons-admin-generic"></i> generic</li>
          
            <li class="icons__item" data-name="admin-home"><i class="dashicons dashicons-admin-home"></i> home</li>
          
            <li class="icons__item" data-name="admin-links"><i class="dashicons dashicons-admin-links"></i> links</li>
          
            <li class="icons__item" data-name="admin-media"><i class="dashicons dashicons-admin-media"></i> media</li>
          
            <li class="icons__item" data-name="admin-multisite"><i class="dashicons dashicons-admin-multisite"></i> multisite</li>
          
            <li class="icons__item" data-name="admin-network"><i class="dashicons dashicons-admin-network"></i> network</li>
          
            <li class="icons__item" data-name="admin-page"><i class="dashicons dashicons-admin-page"></i> page</li>
          
            <li class="icons__item" data-name="admin-plugins"><i class="dashicons dashicons-admin-plugins"></i> plugins</li>
          
            <li class="icons__item" data-name="admin-post"><i class="dashicons dashicons-admin-post"></i> post</li>
          
            <li class="icons__item" data-name="admin-settings"><i class="dashicons dashicons-admin-settings"></i> settings</li>
          
            <li class="icons__item" data-name="admin-site-alt"><i class="dashicons dashicons-admin-site-alt"></i> site-alt</li>
          
            <li class="icons__item" data-name="admin-site-alt2"><i class="dashicons dashicons-admin-site-alt2"></i> site-alt2</li>
          
            <li class="icons__item" data-name="admin-site-alt3"><i class="dashicons dashicons-admin-site-alt3"></i> site-alt3</li>
          
            <li class="icons__item" data-name="admin-site"><i class="dashicons dashicons-admin-site"></i> site</li>
          
            <li class="icons__item" data-name="admin-tools"><i class="dashicons dashicons-admin-tools"></i> tools</li>
          
            <li class="icons__item" data-name="admin-users"><i class="dashicons dashicons-admin-users"></i> users</li>
          
            <li class="icons__item" data-name="album"><i class="dashicons dashicons-album"></i> album</li>
          
            <li class="icons__item" data-name="align-center"><i class="dashicons dashicons-align-center"></i> align-center</li>
          
            <li class="icons__item" data-name="align-left"><i class="dashicons dashicons-align-left"></i> align-left</li>
          
            <li class="icons__item" data-name="align-none"><i class="dashicons dashicons-align-none"></i> align-none</li>
          
            <li class="icons__item" data-name="align-right"><i class="dashicons dashicons-align-right"></i> align-right</li>
          
            <li class="icons__item" data-name="analytics"><i class="dashicons dashicons-analytics"></i> analytics</li>
          
            <li class="icons__item" data-name="archive"><i class="dashicons dashicons-archive"></i> archive</li>
          
            <li class="icons__item" data-name="arrow-down-alt"><i class="dashicons dashicons-arrow-down-alt"></i> arrow-down-alt</li>
          
            <li class="icons__item" data-name="arrow-down-alt2"><i class="dashicons dashicons-arrow-down-alt2"></i> arrow-down-alt2</li>
          
            <li class="icons__item" data-name="arrow-down"><i class="dashicons dashicons-arrow-down"></i> arrow-down</li>
          
            <li class="icons__item" data-name="arrow-left-alt"><i class="dashicons dashicons-arrow-left-alt"></i> arrow-left-alt</li>
          
            <li class="icons__item" data-name="arrow-left-alt2"><i class="dashicons dashicons-arrow-left-alt2"></i> arrow-left-alt2</li>
          
            <li class="icons__item" data-name="arrow-left"><i class="dashicons dashicons-arrow-left"></i> arrow-left</li>
          
            <li class="icons__item" data-name="arrow-right-alt"><i class="dashicons dashicons-arrow-right-alt"></i> arrow-right-alt</li>
          
            <li class="icons__item" data-name="arrow-right-alt2"><i class="dashicons dashicons-arrow-right-alt2"></i> arrow-right-alt2</li>
          
            <li class="icons__item" data-name="arrow-right"><i class="dashicons dashicons-arrow-right"></i> arrow-right</li>
          
            <li class="icons__item" data-name="arrow-up-alt"><i class="dashicons dashicons-arrow-up-alt"></i> arrow-up-alt</li>
          
            <li class="icons__item" data-name="arrow-up-alt2"><i class="dashicons dashicons-arrow-up-alt2"></i> arrow-up-alt2</li>
          
            <li class="icons__item" data-name="arrow-up-duplicate"><i class="dashicons dashicons-arrow-up-duplicate"></i> arrow-up-duplicate</li>
          
            <li class="icons__item" data-name="arrow-up"><i class="dashicons dashicons-arrow-up"></i> arrow-up</li>
          
            <li class="icons__item" data-name="art"><i class="dashicons dashicons-art"></i> art</li>
          
            <li class="icons__item" data-name="awards"><i class="dashicons dashicons-awards"></i> awards</li>
          
            <li class="icons__item" data-name="backup"><i class="dashicons dashicons-backup"></i> backup</li>
          
            <li class="icons__item" data-name="book-alt"><i class="dashicons dashicons-book-alt"></i> book-alt</li>
          
            <li class="icons__item" data-name="book"><i class="dashicons dashicons-book"></i> book</li>
          
            <li class="icons__item" data-name="buddicons-activity"><i class="dashicons dashicons-buddicons-activity"></i> buddicons-activity</li>
          
            <li class="icons__item" data-name="buddicons-bbpress-logo"><i class="dashicons dashicons-buddicons-bbpress-logo"></i> buddicons-bbpress-logo</li>
          
            <li class="icons__item" data-name="buddicons-buddypress-logo"><i class="dashicons dashicons-buddicons-buddypress-logo"></i> buddicons-buddypress-logo</li>
          
            <li class="icons__item" data-name="buddicons-community"><i class="dashicons dashicons-buddicons-community"></i> buddicons-community</li>
          
            <li class="icons__item" data-name="buddicons-forums"><i class="dashicons dashicons-buddicons-forums"></i> buddicons-forums</li>
          
            <li class="icons__item" data-name="buddicons-friends"><i class="dashicons dashicons-buddicons-friends"></i> buddicons-friends</li>
          
            <li class="icons__item" data-name="buddicons-groups"><i class="dashicons dashicons-buddicons-groups"></i> buddicons-groups</li>
          
            <li class="icons__item" data-name="buddicons-pm"><i class="dashicons dashicons-buddicons-pm"></i> buddicons-pm</li>
          
            <li class="icons__item" data-name="buddicons-replies"><i class="dashicons dashicons-buddicons-replies"></i> buddicons-replies</li>
          
            <li class="icons__item" data-name="buddicons-topics"><i class="dashicons dashicons-buddicons-topics"></i> buddicons-topics</li>
          
            <li class="icons__item" data-name="buddicons-tracking"><i class="dashicons dashicons-buddicons-tracking"></i> buddicons-tracking</li>
          
            <li class="icons__item" data-name="building"><i class="dashicons dashicons-building"></i> building</li>
          
            <li class="icons__item" data-name="businessman"><i class="dashicons dashicons-businessman"></i> businessman</li>
          
            <li class="icons__item" data-name="businessperson"><i class="dashicons dashicons-businessperson"></i> businessperson</li>
          
            <li class="icons__item" data-name="businesswoman"><i class="dashicons dashicons-businesswoman"></i> businesswoman</li>
          
            <li class="icons__item" data-name="calendar-alt"><i class="dashicons dashicons-calendar-alt"></i> calendar-alt</li>
          
            <li class="icons__item" data-name="calendar"><i class="dashicons dashicons-calendar"></i> calendar</li>
          
            <li class="icons__item" data-name="camera-alt"><i class="dashicons dashicons-camera-alt"></i> camera-alt</li>
          
            <li class="icons__item" data-name="camera"><i class="dashicons dashicons-camera"></i> camera</li>
          
            <li class="icons__item" data-name="carrot"><i class="dashicons dashicons-carrot"></i> carrot</li>
          
            <li class="icons__item" data-name="cart"><i class="dashicons dashicons-cart"></i> cart</li>
          
            <li class="icons__item" data-name="category"><i class="dashicons dashicons-category"></i> category</li>
          
            <li class="icons__item" data-name="chart-area"><i class="dashicons dashicons-chart-area"></i> chart-area</li>
          
            <li class="icons__item" data-name="chart-bar"><i class="dashicons dashicons-chart-bar"></i> chart-bar</li>
          
            <li class="icons__item" data-name="chart-line"><i class="dashicons dashicons-chart-line"></i> chart-line</li>
          
            <li class="icons__item" data-name="chart-pie"><i class="dashicons dashicons-chart-pie"></i> chart-pie</li>
          
            <li class="icons__item" data-name="clipboard"><i class="dashicons dashicons-clipboard"></i> clipboard</li>
          
            <li class="icons__item" data-name="clock"><i class="dashicons dashicons-clock"></i> clock</li>
          
            <li class="icons__item" data-name="cloud"><i class="dashicons dashicons-cloud"></i> cloud</li>
          
            <li class="icons__item" data-name="code-standards"><i class="dashicons dashicons-code-standards"></i> code-standards</li>
          
            <li class="icons__item" data-name="color-picker"><i class="dashicons dashicons-color-picker"></i> color-picker</li>
          
            <li class="icons__item" data-name="controls-back"><i class="dashicons dashicons-controls-back"></i> controls-back</li>
          
            <li class="icons__item" data-name="controls-forward"><i class="dashicons dashicons-controls-forward"></i> controls-forward</li>
          
            <li class="icons__item" data-name="controls-pause"><i class="dashicons dashicons-controls-pause"></i> controls-pause</li>
          
            <li class="icons__item" data-name="controls-play"><i class="dashicons dashicons-controls-play"></i> controls-play</li>
          
            <li class="icons__item" data-name="controls-repeat"><i class="dashicons dashicons-controls-repeat"></i> controls-repeat</li>
          
            <li class="icons__item" data-name="controls-skipback"><i class="dashicons dashicons-controls-skipback"></i> controls-skipback</li>
          
            <li class="icons__item" data-name="controls-skipforward"><i class="dashicons dashicons-controls-skipforward"></i> controls-skipforward</li>
          
            <li class="icons__item" data-name="controls-volumeoff"><i class="dashicons dashicons-controls-volumeoff"></i> controls-volumeoff</li>
          
            <li class="icons__item" data-name="controls-volumeon"><i class="dashicons dashicons-controls-volumeon"></i> controls-volumeon</li>
          
            <li class="icons__item" data-name="dashboard"><i class="dashicons dashicons-dashboard"></i> dashboard</li>
          
            <li class="icons__item" data-name="desktop"><i class="dashicons dashicons-desktop"></i> desktop</li>
          
            <li class="icons__item" data-name="dismiss"><i class="dashicons dashicons-dismiss"></i> dismiss</li>
          
            <li class="icons__item" data-name="download"><i class="dashicons dashicons-download"></i> download</li>
          
            <li class="icons__item" data-name="edit-large"><i class="dashicons dashicons-edit-large"></i> edit-large</li>
          
            <li class="icons__item" data-name="edit"><i class="dashicons dashicons-edit"></i> edit</li>
          
            <li class="icons__item" data-name="editor-aligncenter"><i class="dashicons dashicons-editor-aligncenter"></i> editor-aligncenter</li>
          
            <li class="icons__item" data-name="editor-alignleft"><i class="dashicons dashicons-editor-alignleft"></i> editor-alignleft</li>
          
            <li class="icons__item" data-name="editor-alignright"><i class="dashicons dashicons-editor-alignright"></i> editor-alignright</li>
          
            <li class="icons__item" data-name="editor-bold"><i class="dashicons dashicons-editor-bold"></i> editor-bold</li>
          
            <li class="icons__item" data-name="editor-break"><i class="dashicons dashicons-editor-break"></i> editor-break</li>
          
            <li class="icons__item" data-name="editor-code-duplicate"><i class="dashicons dashicons-editor-code-duplicate"></i> editor-code-duplicate</li>
          
            <li class="icons__item" data-name="editor-code"><i class="dashicons dashicons-editor-code"></i> editor-code</li>
          
            <li class="icons__item" data-name="editor-contract"><i class="dashicons dashicons-editor-contract"></i> editor-contract</li>
          
            <li class="icons__item" data-name="editor-customchar"><i class="dashicons dashicons-editor-customchar"></i> editor-customchar</li>
          
            <li class="icons__item" data-name="editor-expand"><i class="dashicons dashicons-editor-expand"></i> editor-expand</li>
          
            <li class="icons__item" data-name="editor-help"><i class="dashicons dashicons-editor-help"></i> editor-help</li>
          
            <li class="icons__item" data-name="editor-indent"><i class="dashicons dashicons-editor-indent"></i> editor-indent</li>
          
            <li class="icons__item" data-name="editor-insertmore"><i class="dashicons dashicons-editor-insertmore"></i> editor-insertmore</li>
          
            <li class="icons__item" data-name="editor-italic"><i class="dashicons dashicons-editor-italic"></i> editor-italic</li>
          
            <li class="icons__item" data-name="editor-justify"><i class="dashicons dashicons-editor-justify"></i> editor-justify</li>
          
            <li class="icons__item" data-name="editor-kitchensink"><i class="dashicons dashicons-editor-kitchensink"></i> editor-kitchensink</li>
          
            <li class="icons__item" data-name="editor-ltr"><i class="dashicons dashicons-editor-ltr"></i> editor-ltr</li>
          
            <li class="icons__item" data-name="editor-ol-rtl"><i class="dashicons dashicons-editor-ol-rtl"></i> editor-ol-rtl</li>
          
            <li class="icons__item" data-name="editor-ol"><i class="dashicons dashicons-editor-ol"></i> editor-ol</li>
          
            <li class="icons__item" data-name="editor-outdent"><i class="dashicons dashicons-editor-outdent"></i> editor-outdent</li>
          
            <li class="icons__item" data-name="editor-paragraph"><i class="dashicons dashicons-editor-paragraph"></i> editor-paragraph</li>
          
            <li class="icons__item" data-name="editor-paste-text"><i class="dashicons dashicons-editor-paste-text"></i> editor-paste-text</li>
          
            <li class="icons__item" data-name="editor-paste-word"><i class="dashicons dashicons-editor-paste-word"></i> editor-paste-word</li>
          
            <li class="icons__item" data-name="editor-quote"><i class="dashicons dashicons-editor-quote"></i> editor-quote</li>
          
            <li class="icons__item" data-name="editor-removeformatting"><i class="dashicons dashicons-editor-removeformatting"></i> editor-removeformatting</li>
          
            <li class="icons__item" data-name="editor-rtl"><i class="dashicons dashicons-editor-rtl"></i> editor-rtl</li>
          
            <li class="icons__item" data-name="editor-spellcheck"><i class="dashicons dashicons-editor-spellcheck"></i> editor-spellcheck</li>
          
            <li class="icons__item" data-name="editor-strikethrough"><i class="dashicons dashicons-editor-strikethrough"></i> editor-strikethrough</li>
          
            <li class="icons__item" data-name="editor-table"><i class="dashicons dashicons-editor-table"></i> editor-table</li>
          
            <li class="icons__item" data-name="editor-textcolor"><i class="dashicons dashicons-editor-textcolor"></i> editor-textcolor</li>
          
            <li class="icons__item" data-name="editor-ul"><i class="dashicons dashicons-editor-ul"></i> editor-ul</li>
          
            <li class="icons__item" data-name="editor-underline"><i class="dashicons dashicons-editor-underline"></i> editor-underline</li>
          
            <li class="icons__item" data-name="editor-unlink"><i class="dashicons dashicons-editor-unlink"></i> editor-unlink</li>
          
            <li class="icons__item" data-name="editor-video"><i class="dashicons dashicons-editor-video"></i> editor-video</li>
          
            <li class="icons__item" data-name="email-alt"><i class="dashicons dashicons-email-alt"></i> email-alt</li>
          
            <li class="icons__item" data-name="email-alt2"><i class="dashicons dashicons-email-alt2"></i> email-alt2</li>
          
            <li class="icons__item" data-name="email"><i class="dashicons dashicons-email"></i> email</li>
          
            <li class="icons__item" data-name="excerpt-view"><i class="dashicons dashicons-excerpt-view"></i> excerpt-view</li>
          
            <li class="icons__item" data-name="external"><i class="dashicons dashicons-external"></i> external</li>
          
            <li class="icons__item" data-name="facebook-alt"><i class="dashicons dashicons-facebook-alt"></i> facebook-alt</li>
          
            <li class="icons__item" data-name="facebook"><i class="dashicons dashicons-facebook"></i> facebook</li>
          
            <li class="icons__item" data-name="feedback"><i class="dashicons dashicons-feedback"></i> feedback</li>
          
            <li class="icons__item" data-name="filter"><i class="dashicons dashicons-filter"></i> filter</li>
          
            <li class="icons__item" data-name="flag"><i class="dashicons dashicons-flag"></i> flag</li>
          
            <li class="icons__item" data-name="format-aside"><i class="dashicons dashicons-format-aside"></i> format-aside</li>
          
            <li class="icons__item" data-name="format-audio"><i class="dashicons dashicons-format-audio"></i> format-audio</li>
          
            <li class="icons__item" data-name="format-chat"><i class="dashicons dashicons-format-chat"></i> format-chat</li>
          
            <li class="icons__item" data-name="format-gallery"><i class="dashicons dashicons-format-gallery"></i> format-gallery</li>
          
            <li class="icons__item" data-name="format-image"><i class="dashicons dashicons-format-image"></i> format-image</li>
          
            <li class="icons__item" data-name="format-quote"><i class="dashicons dashicons-format-quote"></i> format-quote</li>
          
            <li class="icons__item" data-name="format-status"><i class="dashicons dashicons-format-status"></i> format-status</li>
          
            <li class="icons__item" data-name="format-video"><i class="dashicons dashicons-format-video"></i> format-video</li>
          
            <li class="icons__item" data-name="forms"><i class="dashicons dashicons-forms"></i> forms</li>
          
            <li class="icons__item" data-name="googleplus"><i class="dashicons dashicons-googleplus"></i> googleplus</li>
          
            <li class="icons__item" data-name="grid-view"><i class="dashicons dashicons-grid-view"></i> grid-view</li>
          
            <li class="icons__item" data-name="groups"><i class="dashicons dashicons-groups"></i> groups</li>
          
            <li class="icons__item" data-name="hammer"><i class="dashicons dashicons-hammer"></i> hammer</li>
          
            <li class="icons__item" data-name="heart"><i class="dashicons dashicons-heart"></i> heart</li>
          
            <li class="icons__item" data-name="hidden"><i class="dashicons dashicons-hidden"></i> hidden</li>
          
            <li class="icons__item" data-name="id-alt"><i class="dashicons dashicons-id-alt"></i> id-alt</li>
          
            <li class="icons__item" data-name="id"><i class="dashicons dashicons-id"></i> id</li>
          
            <li class="icons__item" data-name="image-crop"><i class="dashicons dashicons-image-crop"></i> image-crop</li>
          
            <li class="icons__item" data-name="image-filter"><i class="dashicons dashicons-image-filter"></i> image-filter</li>
          
            <li class="icons__item" data-name="image-flip-horizontal"><i class="dashicons dashicons-image-flip-horizontal"></i> image-flip-horizontal</li>
          
            <li class="icons__item" data-name="image-flip-vertical"><i class="dashicons dashicons-image-flip-vertical"></i> image-flip-vertical</li>
          
            <li class="icons__item" data-name="image-rotate-left"><i class="dashicons dashicons-image-rotate-left"></i> image-rotate-left</li>
          
            <li class="icons__item" data-name="image-rotate-right"><i class="dashicons dashicons-image-rotate-right"></i> image-rotate-right</li>
          
            <li class="icons__item" data-name="image-rotate"><i class="dashicons dashicons-image-rotate"></i> image-rotate</li>
          
            <li class="icons__item" data-name="images-alt"><i class="dashicons dashicons-images-alt"></i> images-alt</li>
          
            <li class="icons__item" data-name="images-alt2"><i class="dashicons dashicons-images-alt2"></i> images-alt2</li>
          
            <li class="icons__item" data-name="index-card"><i class="dashicons dashicons-index-card"></i> index-card</li>
          
            <li class="icons__item" data-name="info"><i class="dashicons dashicons-info"></i> info</li>
          
            <li class="icons__item" data-name="instagram"><i class="dashicons dashicons-instagram"></i> instagram</li>
          
            <li class="icons__item" data-name="laptop"><i class="dashicons dashicons-laptop"></i> laptop</li>
          
            <li class="icons__item" data-name="layout"><i class="dashicons dashicons-layout"></i> layout</li>
          
            <li class="icons__item" data-name="leftright"><i class="dashicons dashicons-leftright"></i> leftright</li>
          
            <li class="icons__item" data-name="lightbulb"><i class="dashicons dashicons-lightbulb"></i> lightbulb</li>
          
            <li class="icons__item" data-name="list-view"><i class="dashicons dashicons-list-view"></i> list-view</li>
          
            <li class="icons__item" data-name="location-alt"><i class="dashicons dashicons-location-alt"></i> location-alt</li>
          
            <li class="icons__item" data-name="location"><i class="dashicons dashicons-location"></i> location</li>
          
            <li class="icons__item" data-name="lock-duplicate"><i class="dashicons dashicons-lock-duplicate"></i> lock-duplicate</li>
          
            <li class="icons__item" data-name="lock"><i class="dashicons dashicons-lock"></i> lock</li>
          
            <li class="icons__item" data-name="marker"><i class="dashicons dashicons-marker"></i> marker</li>
          
            <li class="icons__item" data-name="media-archive"><i class="dashicons dashicons-media-archive"></i> media-archive</li>
          
            <li class="icons__item" data-name="media-audio"><i class="dashicons dashicons-media-audio"></i> media-audio</li>
          
            <li class="icons__item" data-name="media-code"><i class="dashicons dashicons-media-code"></i> media-code</li>
          
            <li class="icons__item" data-name="media-default"><i class="dashicons dashicons-media-default"></i> media-default</li>
          
            <li class="icons__item" data-name="media-document"><i class="dashicons dashicons-media-document"></i> media-document</li>
          
            <li class="icons__item" data-name="media-interactive"><i class="dashicons dashicons-media-interactive"></i> media-interactive</li>
          
            <li class="icons__item" data-name="media-spreadsheet"><i class="dashicons dashicons-media-spreadsheet"></i> media-spreadsheet</li>
          
            <li class="icons__item" data-name="media-text"><i class="dashicons dashicons-media-text"></i> media-text</li>
          
            <li class="icons__item" data-name="media-video"><i class="dashicons dashicons-media-video"></i> media-video</li>
          
            <li class="icons__item" data-name="megaphone"><i class="dashicons dashicons-megaphone"></i> megaphone</li>
          
            <li class="icons__item" data-name="menu-alt"><i class="dashicons dashicons-menu-alt"></i> menu-alt</li>
          
            <li class="icons__item" data-name="menu-alt2"><i class="dashicons dashicons-menu-alt2"></i> menu-alt2</li>
          
            <li class="icons__item" data-name="menu-alt3"><i class="dashicons dashicons-menu-alt3"></i> menu-alt3</li>
          
            <li class="icons__item" data-name="menu"><i class="dashicons dashicons-menu"></i> menu</li>
          
            <li class="icons__item" data-name="microphone"><i class="dashicons dashicons-microphone"></i> microphone</li>
          
            <li class="icons__item" data-name="migrate"><i class="dashicons dashicons-migrate"></i> migrate</li>
          
            <li class="icons__item" data-name="minus"><i class="dashicons dashicons-minus"></i> minus</li>
          
            <li class="icons__item" data-name="money"><i class="dashicons dashicons-money"></i> money</li>
          
            <li class="icons__item" data-name="move"><i class="dashicons dashicons-move"></i> move</li>
          
            <li class="icons__item" data-name="nametag"><i class="dashicons dashicons-nametag"></i> nametag</li>
          
            <li class="icons__item" data-name="networking"><i class="dashicons dashicons-networking"></i> networking</li>
          
            <li class="icons__item" data-name="no-alt"><i class="dashicons dashicons-no-alt"></i> no-alt</li>
          
            <li class="icons__item" data-name="no"><i class="dashicons dashicons-no"></i> no</li>
          
            <li class="icons__item" data-name="palmtree"><i class="dashicons dashicons-palmtree"></i> palmtree</li>
          
            <li class="icons__item" data-name="paperclip"><i class="dashicons dashicons-paperclip"></i> paperclip</li>
          
            <li class="icons__item" data-name="performance"><i class="dashicons dashicons-performance"></i> performance</li>
          
            <li class="icons__item" data-name="phone"><i class="dashicons dashicons-phone"></i> phone</li>
          
            <li class="icons__item" data-name="playlist-audio"><i class="dashicons dashicons-playlist-audio"></i> playlist-audio</li>
          
            <li class="icons__item" data-name="playlist-video"><i class="dashicons dashicons-playlist-video"></i> playlist-video</li>
          
            <li class="icons__item" data-name="plugins-checked"><i class="dashicons dashicons-plugins-checked"></i> plugins-checked</li>
          
            <li class="icons__item" data-name="plus-alt"><i class="dashicons dashicons-plus-alt"></i> plus-alt</li>
          
            <li class="icons__item" data-name="plus-alt2"><i class="dashicons dashicons-plus-alt2"></i> plus-alt2</li>
          
            <li class="icons__item" data-name="plus"><i class="dashicons dashicons-plus"></i> plus</li>
          
            <li class="icons__item" data-name="portfolio"><i class="dashicons dashicons-portfolio"></i> portfolio</li>
          
            <li class="icons__item" data-name="post-status"><i class="dashicons dashicons-post-status"></i> post-status</li>
          
            <li class="icons__item" data-name="pressthis"><i class="dashicons dashicons-pressthis"></i> pressthis</li>
          
            <li class="icons__item" data-name="products"><i class="dashicons dashicons-products"></i> products</li>
          
            <li class="icons__item" data-name="randomize"><i class="dashicons dashicons-randomize"></i> randomize</li>
          
            <li class="icons__item" data-name="redo"><i class="dashicons dashicons-redo"></i> redo</li>
          
            <li class="icons__item" data-name="rest-api"><i class="dashicons dashicons-rest-api"></i> rest-api</li>
          
            <li class="icons__item" data-name="rss"><i class="dashicons dashicons-rss"></i> rss</li>
          
            <li class="icons__item" data-name="schedule"><i class="dashicons dashicons-schedule"></i> schedule</li>
          
            <li class="icons__item" data-name="screenoptions"><i class="dashicons dashicons-screenoptions"></i> screenoptions</li>
          
            <li class="icons__item" data-name="search"><i class="dashicons dashicons-search"></i> search</li>
          
            <li class="icons__item" data-name="share-alt"><i class="dashicons dashicons-share-alt"></i> share-alt</li>
          
            <li class="icons__item" data-name="share-alt2"><i class="dashicons dashicons-share-alt2"></i> share-alt2</li>
          
            <li class="icons__item" data-name="share"><i class="dashicons dashicons-share"></i> share</li>
          
            <li class="icons__item" data-name="shield-alt"><i class="dashicons dashicons-shield-alt"></i> shield-alt</li>
          
            <li class="icons__item" data-name="shield"><i class="dashicons dashicons-shield"></i> shield</li>
          
            <li class="icons__item" data-name="slides"><i class="dashicons dashicons-slides"></i> slides</li>
          
            <li class="icons__item" data-name="smartphone"><i class="dashicons dashicons-smartphone"></i> smartphone</li>
          
            <li class="icons__item" data-name="smiley"><i class="dashicons dashicons-smiley"></i> smiley</li>
          
            <li class="icons__item" data-name="sort"><i class="dashicons dashicons-sort"></i> sort</li>
          
            <li class="icons__item" data-name="sos"><i class="dashicons dashicons-sos"></i> sos</li>
          
            <li class="icons__item" data-name="star-empty"><i class="dashicons dashicons-star-empty"></i> star-empty</li>
          
            <li class="icons__item" data-name="star-filled"><i class="dashicons dashicons-star-filled"></i> star-filled</li>
          
            <li class="icons__item" data-name="star-half"><i class="dashicons dashicons-star-half"></i> star-half</li>
          
            <li class="icons__item" data-name="sticky"><i class="dashicons dashicons-sticky"></i> sticky</li>
          
            <li class="icons__item" data-name="store"><i class="dashicons dashicons-store"></i> store</li>
          
            <li class="icons__item" data-name="tablet"><i class="dashicons dashicons-tablet"></i> tablet</li>
          
            <li class="icons__item" data-name="tag"><i class="dashicons dashicons-tag"></i> tag</li>
          
            <li class="icons__item" data-name="tagcloud"><i class="dashicons dashicons-tagcloud"></i> tagcloud</li>
          
            <li class="icons__item" data-name="testimonial"><i class="dashicons dashicons-testimonial"></i> testimonial</li>
          
            <li class="icons__item" data-name="text-page"><i class="dashicons dashicons-text-page"></i> text-page</li>
          
            <li class="icons__item" data-name="text"><i class="dashicons dashicons-text"></i> text</li>
          
            <li class="icons__item" data-name="thumbs-down"><i class="dashicons dashicons-thumbs-down"></i> thumbs-down</li>
          
            <li class="icons__item" data-name="thumbs-up"><i class="dashicons dashicons-thumbs-up"></i> thumbs-up</li>
          
            <li class="icons__item" data-name="tickets-alt"><i class="dashicons dashicons-tickets-alt"></i> tickets-alt</li>
          
            <li class="icons__item" data-name="tickets"><i class="dashicons dashicons-tickets"></i> tickets</li>
          
            <li class="icons__item" data-name="tide"><i class="dashicons dashicons-tide"></i> tide</li>
          
            <li class="icons__item" data-name="translation"><i class="dashicons dashicons-translation"></i> translation</li>
          
            <li class="icons__item" data-name="trash"><i class="dashicons dashicons-trash"></i> trash</li>
          
            <li class="icons__item" data-name="twitter-alt"><i class="dashicons dashicons-twitter-alt"></i> twitter-alt</li>
          
            <li class="icons__item" data-name="twitter"><i class="dashicons dashicons-twitter"></i> twitter</li>
          
            <li class="icons__item" data-name="undo"><i class="dashicons dashicons-undo"></i> undo</li>
          
            <li class="icons__item" data-name="universal-access-alt"><i class="dashicons dashicons-universal-access-alt"></i> universal-access-alt</li>
          
            <li class="icons__item" data-name="universal-access"><i class="dashicons dashicons-universal-access"></i> universal-access</li>
          
            <li class="icons__item" data-name="unlock"><i class="dashicons dashicons-unlock"></i> unlock</li>
          
            <li class="icons__item" data-name="update-alt"><i class="dashicons dashicons-update-alt"></i> update-alt</li>
          
            <li class="icons__item" data-name="update"><i class="dashicons dashicons-update"></i> update</li>
          
            <li class="icons__item" data-name="upload"><i class="dashicons dashicons-upload"></i> upload</li>
          
            <li class="icons__item" data-name="vault"><i class="dashicons dashicons-vault"></i> vault</li>
          
            <li class="icons__item" data-name="video-alt"><i class="dashicons dashicons-video-alt"></i> video-alt</li>
          
            <li class="icons__item" data-name="video-alt2"><i class="dashicons dashicons-video-alt2"></i> video-alt2</li>
          
            <li class="icons__item" data-name="video-alt3"><i class="dashicons dashicons-video-alt3"></i> video-alt3</li>
          
            <li class="icons__item" data-name="visibility"><i class="dashicons dashicons-visibility"></i> visibility</li>
          
            <li class="icons__item" data-name="warning"><i class="dashicons dashicons-warning"></i> warning</li>
          
            <li class="icons__item" data-name="welcome-add-page"><i class="dashicons dashicons-welcome-add-page"></i> welcome-add-page</li>
          
            <li class="icons__item" data-name="welcome-comments"><i class="dashicons dashicons-welcome-comments"></i> welcome-comments</li>
          
            <li class="icons__item" data-name="welcome-learn-more"><i class="dashicons dashicons-welcome-learn-more"></i> welcome-learn-more</li>
          
            <li class="icons__item" data-name="welcome-view-site"><i class="dashicons dashicons-welcome-view-site"></i> welcome-view-site</li>
          
            <li class="icons__item" data-name="welcome-widgets-menus"><i class="dashicons dashicons-welcome-widgets-menus"></i> welcome-widgets-menus</li>
          
            <li class="icons__item" data-name="welcome-write-blog"><i class="dashicons dashicons-welcome-write-blog"></i> welcome-write-blog</li>
          
            <li class="icons__item" data-name="wordpress-alt"><i class="dashicons dashicons-wordpress-alt"></i> wordpress-alt</li>
          
            <li class="icons__item" data-name="wordpress"><i class="dashicons dashicons-wordpress"></i> wordpress</li>
          
            <li class="icons__item" data-name="yes-alt"><i class="dashicons dashicons-yes-alt"></i> yes-alt</li>
          
            <li class="icons__item" data-name="yes"><i class="dashicons dashicons-yes"></i> yes</li>
        </ul>
      </div>
      <style type="text/css">
        .ste-dashicons-list-wrap{
          display: none;
        }
      </style>
    <?php
    }
}
new Surplus_Essentials_Settings;