<?php
/**
 * Widget Social Links
 *
 * @package Surplus Essentials
 */

// register Surplus_Essentials_Contact_Social_Links widget 
function ste_register_contact_social_links_widget() {
    register_widget( 'Surplus_Essentials_Contact_Social_Links' );
}
add_action( 'widgets_init', 'ste_register_contact_social_links_widget' );


//load wp sortable
function ste_contact_load_sortable() {    
    wp_enqueue_script( 'jquery-ui-core' );    
    wp_enqueue_script( 'jquery-ui-sortable' );    
}
add_action( 'load-widgets.php', 'ste_contact_load_sortable' );

//allow skype
function ste_allowed_contact_social_protocols( $protocols ) {
    $social_protocols = array(
        'skype'
    );
    return array_merge( $protocols, $social_protocols );    
}
add_filter( 'kses_allowed_protocols' ,'ste_allowed_contact_social_protocols' );

 /**
 * Adds Surplus_Essentials_Contact_Social_Links widget.
 */
class Surplus_Essentials_Contact_Social_Links extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        add_action( 'admin_print_footer_scripts', array( $this,'ste_social_template' ) );
        
        parent::__construct(
            'ste_contact_social_links', // Base ID
            esc_html__( 'SE: Contact Widget', 'surplus-essentials' ), // Name
            array( 'description' => esc_html__( 'A Contact Widget', 'surplus-essentials' ), ) // Args
        );
    }

    /**
    * 
    * Social icon template.
    *
    * @since 1.0.0
    */
    function ste_social_template() { ?>
        <div class="ste-contact-social-template">
            <li class="ste-contact-social-icon-wrap" data-id="{{ind}}">
                <span class="btab-contact-social-links-sortable-handle"></span>
                <span class="ste-contact-social-links-field-handle"><i class="fas fa-plus"></i></span>
                <label for="<?php echo esc_attr( $this->get_field_id( 'social_profile' ) ); ?>"><?php esc_html_e( 'Social Icon', 'surplus-essentials' ); ?></label>
                <span class="example-text">Example: facebook</span>
                <div class="social-search-wrap"><input class="user-contact-social-profile" placeholder="<?php _e('Search Social Icons','surplus-essentials');?>" id="" name="" type="text" value="" /></div>
                <label class="link-label" for="<?php echo esc_attr( $this->get_field_id( 'social' ) ); ?>"><?php esc_html_e( 'Link', 'surplus-essentials' ); ?></label>
                <span class="example-text">Example: http://facebook.com</span>
                <input class="ste-contact-social-length" name="" type="text" value="" />
                <span class="del-contact-ste-icon"><i class="fas fa-times"></i></span>
            </li>
        </div>
    <?php
    echo '<style>
        .ste-contact-social-template{
            display: none;
        }
        </style>';
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

        $title           = ! empty( $instance['title'] ) ? $instance['title'] : '';        
        $size            = isset($instance['size'])?esc_attr($instance['size']):'20';
        $description     = ! empty( $instance['description'] ) ? $instance['description'] : '';        
        $telephone       = ! empty( $instance['telephone'] ) ? $instance['telephone'] : '';        
        $email           = ! empty( $instance['email'] ) ? $instance['email'] : '';        
        $address         = ! empty( $instance['address'] ) ? $instance['address'] : '';
        // $allowed_socicon = $this->ste_allowed_socicons();
        
        echo $args['before_widget'];
        ob_start();
        
        if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title']; ?>            
        <div class="ste-contact-widget-wrap contact-info">
        <?php 
            if($description!='') echo wpautop( wp_kses_post( $description ) ); 
            
            if( $telephone || $email || $address ){ 
                echo '<ul class="contact-list">';
                if( $telephone!='' ) echo '<li><i class="fa fa-phone"></i><a href="' . esc_url( 'tel:' . preg_replace( '/\D/', '', $telephone ) ) . '">' . esc_html( $telephone ) . '</a></li>';
                if($email!='') echo '<li><i class="fa fa-envelope"></i><a href="' . esc_url( 'mailto:' . sanitize_email( $email ) ) . '">' . esc_html( $email ) . '</a></li>';
                if($address!='') echo '<li><i class="fa fa-map-marker"></i>' . esc_html( $address ) . '</li>';                
                echo '</ul>';
            }
            
            if( isset( $instance['social'] ) && !empty($instance['social']) )
            { 
                $icons = $instance['social']; ?>                
                <ul class="social-networks">
                    <?php
                        $arr_keys  = array_keys( $icons );
                        foreach ($arr_keys as $key => $value)
                        { 
                            if ( array_key_exists( $value, $instance['social'] ) )
                            { 
                                if(isset($instance['social'][$value]) && !empty($instance['social'][$value]))
                                {
                                    if(!isset($instance['social_profile'][$value]) || (isset($instance['social_profile'][$value]) && $instance['social_profile'][$value] == ''))
                                    {
                                        $icon = $this->ste_get_social_icon_name( $instance['social'][$value] );
                                        $class = ($icon == 'rss') ? 'fas fa-'.$icon : 'fab fa-'.$icon;
                                    }
                                    elseif(isset($instance['social_profile'][$value]) && !empty($instance['social_profile'][$value]))
                                    {
                                        $icon = $instance['social_profile'][$value] ;
                                        $class = ($icon == 'rss') ? 'fas fa-'.$icon : 'fab fa-'.$icon;
                                    }
                                    ?>
                                    <li class="ste-contact-social-icon-wrap">
                                        <a <?php if(isset($instance['target']) && $instance['target']=='1'){ echo "target=_blank"; } ?> href="<?php echo esc_url($instance['social'][$value]);?>">
                                            <span class="ste-contact-social-links-field-handle"><i class="<?php echo esc_attr($class);?>"></i></span>
                                        </a>
                                    </li>
                                <?php
                                }
                            }
                        }
                    ?>
                </ul>
                <?php 
            } 
            ?>
        </div>
        <?php
        $html = ob_get_clean();
        echo apply_filters( 'ste_contact_widget_filter', $html, $args, $instance );
        echo $args['after_widget'];        
    }
        

    /**
     * Get the icon from supported URL lists.
     * @return array
     */
    function ste_get_supported_url_icon() {
        return apply_filters( 'ste_social_icons_get_supported_url_icon', array(
            'feed'                  => 'rss',
            'ok.ru'                 => 'odnoklassniki',
            'vk.com'                => 'vk',
            'last.fm'               => 'lastfm',
            'youtu.be'              => 'youtube',
            'battle.net'            => 'battlenet',
            'blogspot.com'          => 'blogger',
            'play.google.com'       => 'play',
            'plus.google.com'       => 'google-plus',
            'photos.google.com'     => 'googlephotos',
            'chrome.google.com'     => 'chrome',
            'scholar.google.com'    => 'google-scholar',
            'feedburner.google.com' => 'mail',
        ) );
    }

    /**
     * Get the social icon name for given website url.
     *
     * @param  string $url Social site link.
     * @return string
     */
    function ste_get_social_icon_name( $url ) {
        $icon = '';
        $obj = new Surplus_Essentials_Functions;
        if ( $url = strtolower( $url ) ) {
            foreach ( $this->ste_get_supported_url_icon() as $link => $icon_name ) {
                if ( strstr( $url, $link ) ) {
                    $icon = $icon_name;
                }
            }

            if ( ! $icon ) {
                foreach ( $obj->ste_icon_list() as $icon_name ) {
                    if ( strstr( $url, $icon_name ) ) {
                        $icon = $icon_name;
                    }
                }
            }
        }

        return apply_filters( 'ste_social_icons_get_icon_name', $icon, $url );
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = '';
        if( isset( $instance['title'] ) )
        {
            $title  = $instance['title'];       
        } 
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';        
        $telephone   = ! empty( $instance['telephone'] ) ? $instance['telephone'] : '';        
        $email       = ! empty( $instance['email'] ) ? $instance['email'] : '';        
        $address     = ! empty( $instance['address'] ) ? $instance['address'] : '';        
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                $('.ste-contact-sortable-links').sortable({
                    cursor: 'move',
                    update: function (event, ui) {
                        $('ul.ste-contact-sortable-links input').trigger('change');
                    }
                });
            });
        </script>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'surplus-essentials' ); ?></label> 
                <input class="widefat ste-contact-social-title-test" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'surplus-essentials' ); ?></label> 
                <textarea class="widefat ste-contact-social-description-test" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_attr( $description ); ?></textarea>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'telephone' ) ); ?>"><?php esc_html_e( 'Telephone', 'surplus-essentials' ); ?></label> 
                <input class="widefat ste-contact-social-telephone-test" id="<?php echo esc_attr( $this->get_field_id( 'telephone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'telephone' ) ); ?>" type="tel" value="<?php echo esc_attr( $telephone ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email', 'surplus-essentials' ); ?></label> 
                <input class="widefat ste-contact-social-email-test" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="email" value="<?php echo esc_attr( $email ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address', 'surplus-essentials' ); ?></label> 
                <input class="widefat ste-contact-social-address-test" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" type="text" value="<?php echo esc_attr( $address ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" class="check-btn-wrap"> 
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" <?php $j='0'; if( isset( $instance['target'] ) ){ $j='1'; } ?> value="1" <?php checked( $j, true ); ?> name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" />
                    <?php esc_html_e( 'Open Social Links in New Tab', 'surplus-essentials' ); ?>
                </label>
            </p>

        <ul class="ste-contact-sortable-links" id="<?php echo esc_attr( $this->get_field_id( 'ste-contact-social-links' ) ); ?>">
        <?php
        if(isset($instance['social']) && !empty($instance['social']))
        {
            $icons  = $instance['social'];
            $arr_keys  = array_keys( $icons );
            if(isset($arr_keys))
            {
                foreach ($arr_keys as $key => $value)
                { 
                    if ( array_key_exists( $value,$instance['social'] ) )
                    { 
                        if(isset($instance['social'][$value]) && !empty($instance['social'][$value]))
                        {
                            if(!isset($instance['social_profile'][$value]) || (isset($instance['social_profile'][$value]) && $instance['social_profile'][$value] == ''))
                            {
                                $icon = $this->ste_get_social_icon_name( $instance['social'][$value] );
                                $class = ($icon == 'rss') ? 'fas fa-'.$icon : 'fab fa-'.$icon;
                            }
                            elseif(isset($instance['social_profile'][$value]) && !empty($instance['social_profile'][$value]))
                            {
                                $icon = $instance['social_profile'][$value] ;
                                $class = ($icon == 'rss') ? 'fas fa-'.$icon : 'fab fa-'.$icon;
                            }
                            ?>
                                <li class="ste-contact-social-icon-wrap" data-id="<?php echo $value;?>">
                                        <span class="btab-contact-social-links-sortable-handle"></span>
                                        <span class="ste-contact-social-links-field-handle"><i class="<?php echo esc_attr($class);?>"></i></span>
                                        <label for="<?php echo esc_attr( $this->get_field_id( 'social_profile['.$value.']' ) ); ?>"><?php esc_html_e( 'Social Icon', 'surplus-essentials' ); ?></label>
                                        <span class="example-text">Example: facebook</span>
                                        <div class="social-search-wrap"><input class="user-social-profile" id="<?php echo esc_attr( $this->get_field_id( 'social_profile['.$value.']' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'social_profile['.$value.']' ) ); ?>" type="text" value="<?php echo esc_attr($icon);?>" /></div>
                                        <label class="link-label" for="<?php echo esc_attr( $this->get_field_name( 'social['.$value.']' ) ) ?>"><?php esc_html_e( 'Link', 'surplus-essentials' ); ?></label>
                                        <span class="example-text">Example: http://facebook.com</span>
                                        <input class="ste-contact-social-length" id="<?php echo esc_attr( $this->get_field_name( 'social['.$value.']' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( 'social['.$value.']' ) ) ?>" type="text" value="<?php echo esc_url($instance['social'][$value]);?>" />
                                        <span class="del-contact-ste-icon"><i class="fas fa-times"></i></span>
                                    </p>
                                </li>
                        <?php
                        }
                    }
                }
            }
        }
        ?>
        <div class="ste-contact-social-icon-holder"></div>
        </ul>
        <input class="ste-contact-social-add button button-primary" type="button" value="<?php _e('Add Social Icon','surplus-essentials');?>"><br>
        <span class="ste-option-side-note" class="example-text"><?php _e('Click on the above button to add social media icons. You can also change the order of the social icons.','surplus-essentials');?></span>
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
        $instance                = array();
        $instance['title']       = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['target']      = $new_instance['target'];
        $instance['size']        = $new_instance['size'];
        $instance['description'] = ! empty( $new_instance['description'] ) ? $new_instance['description'] : '';        
        $instance['telephone']   = ! empty( $new_instance['telephone'] ) ? $new_instance['telephone'] : '';        
        $instance['email']       = ! empty( $new_instance['email'] ) ? $new_instance['email'] : '';        
        $instance['address']     = ! empty( $new_instance['address'] ) ? $new_instance['address'] : '';
        
        if(isset($new_instance['social_profile']) && !empty($new_instance['social_profile']))
        {
            $arr_keys  = array_keys( $new_instance['social_profile'] );
                    
            foreach ($arr_keys as $key => $value)
            { 
                if ( array_key_exists( $value,$new_instance['social_profile'] ) )
                { 
                    
                    $instance['social_profile'][$value] =  esc_attr($new_instance['social_profile'][$value]);
                    
                }
            }
        }

        if(isset($new_instance['social']) && !empty($new_instance['social']))
        {
            $arr_keys  = array_keys( $new_instance['social'] );
                    
            foreach ($arr_keys as $key => $value)
            { 
                if ( array_key_exists( $value,$new_instance['social'] ) )
                { 
                    
                    $instance['social'][$value] =  esc_url($new_instance['social'][$value]);
                    
                }
            }
        }
        return $instance;            
    }
} 