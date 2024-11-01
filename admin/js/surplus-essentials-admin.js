jQuery(document).ready(function($){
    $('body').on('click', '.add-post-type', function(e) {
        $('<div class="post-type-wrap"><label><?php _e("Post Type Name",""); ?><input type="text" placeholder="" value="" name=""></label><label><?php _e("Post Type Slug",""); ?><input type="text" placeholder="" value="" name=""></label>')
    });
    
    $(".event-date-pick").datepicker({dateFormat: 'yy-mm-dd'});
    
    $('body').on('click', '.ste-testimonial-add', function(e) {
        e.preventDefault();
        da = $(this).siblings('.ste-testimonial-outer').attr('id');
        suffix = da.match(/\d+/);
        var maximum=0;
        $( '.testimonial-widget-wrap:visible' ).each(function() {
            var value =  $(this).attr( 'data-id' );
            if(!isNaN(value))
            {
                value = parseInt(value);
                maximum = (value > maximum) ? value : maximum;
            }
        });
        var newinput = $('.ste-testimonial-template').clone();
        newinput.html(function(i, oldHTML) {
            maximum++;
            newinput.find( '.testimonial-widget-wrap' ).attr('data-id',maximum);
            newinput.find( '.testimonial textarea' ).attr('name','widget-ste_testimonial_widget['+suffix+'][testimonial][]');
            newinput.find( '.name input' ).attr('name','widget-ste_testimonial_widget['+suffix+'][name][]');
            newinput.find( '.widget-upload input' ).attr('name','widget-ste_testimonial_widget['+suffix+'][image][]');
            newinput.find( '.designation input' ).attr('name','widget-ste_testimonial_widget['+suffix+'][designation][]');
        });
        $(this).siblings('.ste-testimonial-outer').find('.ste-testimonial-holder').before(newinput.html());
    });

    $('body').on('click', '.ste-testimonial-cancel', function(e) {
        e.preventDefault();
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
        });
    });

    $('body').on('click', '.dashicons-dismiss', function(e) {
        e.preventDefault();
        if($('body').hasClass('appearance_page_surplus_essentials_settings')) return;

        var confirmation = confirm('Are you sure you want to delete?');
        if( ! confirmation ){
            return false;
        }
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
        });
    });

    $('body').on('click', '.posttype.dashicons-dismiss', function(e) {
        e.preventDefault();
        var confirmation = confirm('Are you sure you want to delete?');
        if( ! confirmation ){
            return false;
        }
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
        });
    });

    $(".date-pick").datepicker({dateFormat: 'yy-mm-dd'});
    $(".time-pick").timepicker();
    $('body').on('click', '#add-faq:visible', function(e) {
        e.preventDefault();
        da = $(this).siblings('.widget-client-faq-repeater').attr('id');
        suffix = da.match(/\d+/);
        len=0;
        $( '.faqs-repeat:visible' ).each(function() {
            var value =  $(this).attr( 'data-id' );
            if(!isNaN(value))
            {
                value = parseInt(value);
                len = (value > len) ? value : len;
            }
        });
        var newinput = $('.ste-faq-template').clone();
        len++;
        // newinput.html(function(i, oldHTML) {
        // });
        newinput.find( '.faqs-repeat' ).attr('data-id',len);
        newinput.find( '.question' ).attr('name','widget-ste_faqs_widget['+suffix+'][question]['+len+']');
        newinput.find( '.answer' ).attr('name','widget-ste_faqs_widget['+suffix+'][answer]['+len+']');
        // newinput.html(function(i, oldHTML) {
        //     return oldHTML.replace(/{{indexes}}/g, len);
        // });
        $('.cl-faq-holder').before(newinput.html()).focus().trigger('change');
    });

    
    var file_frame;
    $('.user-signature-image').on('click', function( event ){
        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $( this ).data( 'uploader_title' ),
            button: {
            text: $( this ).data( 'uploader_button_text' ),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();
            $('#user_signature_image').val(attachment.url);
            // Do something with attachment.id and/or attachment.url here
        });

        // Finally, open the modal
        file_frame.open();
    });


    $('body').on('click', '.del-user-social-links', function (e){  
        var confirmation = confirm(sociconsmsg.msg);
        if( ! confirmation ){
            return false;
        }
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
            $('#add-user-socicon').focus().trigger('change');
        });
        return;
    });

    $(document).on('focus','.user-contact-social-profile',function() {
        // if($(this).val()=='')
        // {
            // if( $(this).siblings('.ste-icons-list').length < 1 )
            // {
                var $iconlist = $('.ste-icons-wrap').clone();
                $(this).after($iconlist.html());
                $(this).siblings('.ste-icons-list').fadeIn('slow');
            // }
            
            // if ( $(this).siblings('.ste-icons-list').find('#remove-icon-list').length < 1 )
            // {
                var input = '<span id="remove-icon-list" class="fas fa-times"></span>';
                $(this).siblings('.ste-icons-list:visible').prepend(input);
            // }
        // }
    });

    $(document).on('blur','.user-contact-social-profile',function(e) {
        e.preventDefault();
        $(this).siblings('.ste-icons-list').fadeOut('slow',function(){
            $(this).remove();
        });
    });

    $(document).on('click','.ste-icons-list li',function(event) {
        var prefix = $(this).children('svg').attr('data-prefix');
        var icon = $(this).children('svg').attr('data-icon');
        var val = prefix + ' fa-' + icon;

        $(this).parent().siblings('.user-social-profile').attr('value', icon);
        $(this).parent().siblings('.user-contact-social-profile').attr('value', icon);
        $(this).parent().siblings('.instructor-social-profile').attr('value', icon);
        $(this).parent().parent().siblings('.ste-contact-social-length').attr('value','https://'+icon+'.com');
        $(this).parent().siblings('.instructor-social-length').attr('value','https://'+icon+'.com');
        $(this).parent().siblings('.user-social-links').attr('value','https://'+icon+'.com');

        $(this).siblings('.ste-icons-wrap-search').remove('slow');
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
        });

        $(this).parent().siblings('.user-social-profile').trigger('change');
        $(this).parent().siblings('.user-social-links').trigger('change');
        $(this).parent().siblings('.user-contact-social-profile').trigger('change');
        $(this).parent().siblings('.ste-contact-social-length').trigger('change');
        $(this).parent().siblings('.instructor-social-length').trigger('change');


        event.preventDefault();
    });

    $(document).on('keyup','.user-contact-social-profile',function() {
        var value = $(this).val();
        var matcher = new RegExp(value, 'gi');
        $(this).siblings('.ste-icons-list').children('li').show().not(function(){
            return matcher.test($(this).find('svg').attr('data-icon'));
        }).hide();
    });
    
    $(document).on('keyup','.search-itw-icons',function() {
        var value = $(this).val();
        var matcher = new RegExp(value, 'gi');
        $(this).siblings('.ste-font-awesome-list').find('li').show().not(function(){
            return matcher.test($(this).find('svg').attr('data-icon'));
        }).hide();
    });
    
    $(document).on('keyup','.ste-sc-icons',function() {
        var value = $(this).val();
        var matcher = new RegExp(value, 'gi');
        $(this).siblings('.ste-font-awesome-list').find('li').show().not(function(){
            return matcher.test($(this).find('svg').attr('data-icon'));
        }).hide();
    });

    $(document).on('keyup','.ste-icons-wrap-search',function() {
        var value = $(this).val();
        var matcher = new RegExp(value, 'gi');
        $(this).parent('.ste-icons-list').children('li').show().not(function(){
            return matcher.test($(this).find('svg').attr('data-icon'));
        }).hide();
    });

    $(document).on('keyup','.user-social-profile',function() {
        var value = $(this).val();
        var matcher = new RegExp(value, 'gi');
        $(this).siblings('.ste-icons-list').children('li').show().not(function(){
            return matcher.test($(this).find('svg').attr('data-icon'));
        }).hide();
    });

    $(document).on('focus','.user-social-profile',function() {

        if( $(this).siblings('.ste-icons-list').length < 1 )
        {
            var $iconlist = $('.ste-icons-wrap').clone();
            $(this).after($iconlist.html());
            $(this).siblings('.ste-icons-list').fadeIn('slow');
        }
        
        if ( $(this).siblings('.ste-icons-list').find('#remove-icon-list').length < 1 )
        {
            var input = '<span id="remove-icon-list" class="fas fa-times"></span>';
            $(this).siblings('.ste-icons-list:visible').prepend(input);
        }

    });

    $(document).on('blur','.user-social-profile',function(e) {
        e.preventDefault();
        $(this).siblings('.ste-icons-list').fadeOut('slow',function(){
            $(this).remove();
        });
    });

    // $(document).on('click', function (e) {
    //     if( $(event.target).attr('class') == 'user-social-links' || $(event.target).attr('class') == 'ste-social-length' || $(event.target).attr('class') == 'ste-icons-wrap-search')
    //     {
    //       return;
    //     }
    //     $('.ste-icons-list:visible').fadeOut('slow',function(){
    //         $(this).remove();
    //     });
    //     $('.ste-icons-wrap-search:visible').fadeOut('slow',function(){
    //         $(this).remove();
    //     });
    // });


       var frame;

    // ADD IMAGE LINK
        $('body').on('click','.ste-upload-button',function(e) {
            e.preventDefault();
            var btn = $(this);
            var custom_uploader = wp.media({
                title: 'Surplus Uploader',
                multiple: false  // Set this to true to allow multiple files to be selected
                })
            .on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                var str = attachment.url.split('.').pop(); 
                btn.siblings('.ste-screenshot').attr('src', attachment.url).after('<a class="ste-remove-image"><i class="dashicons dashicons-no"></i></a>');
                btn.siblings('.ste-screenshot').slideDown();
                btn.siblings('.ste-upload').attr('value',attachment.id);
                btn.val('Change').trigger('change');;
            }) 
            .open();
        });

        $('body').on('click','.ste-remove-image',function(e) {
            var selector = $(this);
            selector.siblings('.ste-upload').val('').trigger('change');
            selector.siblings('.ste-remove-image').hide();
            selector.siblings('.ste-screenshot').slideUp();
            $(this).remove();
            selector.siblings('.ste-upload-button').val('Upload');
            
            return false;
        });

            // Upload / Change Image
    function ste_image_upload( button_class ) {
        
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;

        jQuery( 'body' ).on( 'click', button_class, function(e) {

            var button_id = '#' + jQuery( this ).attr( 'id' ),
                self = jQuery( button_id),
                send_attachment_bkp = wp.media.editor.send.attachment,
                button = jQuery( button_id ),
                id = button.attr( 'id' ).replace( '-button', '' );

            _custom_media = true;

            wp.media.editor.send.attachment = function( props, attachment ){

                if ( _custom_media ) {

                    jQuery( '#' + id + '-preview' ).attr( 'src', attachment.url ).css( 'display', 'block' ).append('<a class="ste-media-remove" id="ste-media-remove">x</a>').slideDown('fast');
                    jQuery( '#' + id + '-remove' ).css( 'display', 'inline-block' );
                    jQuery( '#' + id + '-noimg' ).css( 'display', 'none' );
                    jQuery( '#' + id ).siblings('.ste-media-url').val( attachment.id ).trigger( 'change' ); 
                    button_id.val('Change Image'); 

                } else {

                    return _orig_send_attachment.apply( button_id, [props, attachment] );

                }
            };

            wp.media.editor.open( button );

            return false;
        });
    }
    ste_image_upload( '.ste-media-upload' );

        // set var
    var in_customizer = false;

    // check for wp.customize return boolean
    if (typeof wp !== 'undefined') {
        in_customizer = typeof wp.customize !== 'undefined' ? true : false;
    }

    // Remove Image
    function ste_image_remove( button_class ) {

        jQuery( 'body' ).on( 'click', button_class, function(e) {

            var button = jQuery( this ),
            id = button.attr( 'id' ).replace( '-remove', '' );
            jQuery( '#' + id + '-preview' ).css( 'display', 'none' );
            jQuery( '#' + id + '-noimg' ).css( 'display', 'block' );
            button.css( 'display', 'none' );
            jQuery( '#' + id ).parent().siblings('.ste-media-url').val('');
            jQuery( '#' ).val( '' ).trigger( 'change' );

        });
    }
    ste_image_remove( '.ste-media-remove' );

    $('body').on('click', '#add-user-socicon', function (e){ 
        e.preventDefault();
        da = $(this).siblings('.ste-sortable-icons').attr('id');
        suffix = da.match(/\d+/);
        var maximum=0;
        $( '.social-share-list' ).each(function() {
        var value =  $(this).attr( 'data-id' );
        if(!isNaN(value))
        {
        	value = parseInt(value);
        	maximum = (value > maximum) ? value : maximum;
        }
        });
        var newField = $( '.ste-socicon-template' ).clone();
    	maximum++;
    	var name = 'widget-ste_author_bio['+suffix+'][socicon]['+maximum+']';
    	newField.find( '.user-social-links' ).attr('name',name);

        var profile = 'widget-ste_author_bio['+suffix+'][socicon_profile]['+maximum+']';
        newField.find( '.user-social-profile' ).attr('name',profile);

        newField.html(function(i, oldHTML) {
            return oldHTML.replace(/{{socicon_index}}/g, maximum);
        });
        $( '.ste-socicon-holder' ).before(  newField.html() );
    });

    $('body').on('click', '.ste-social-add', function(e) {
        e.preventDefault();
        da = $(this).siblings('.ste-sortable-links').attr('id');
        suffix = da.match(/\d+/);
        var maximum=0;
        $( '.ste-social-icon-wrap:visible' ).each(function() {
            var value =  $(this).attr( 'data-id' );
            if(!isNaN(value))
            {
                value = parseInt(value);
                maximum = (value > maximum) ? value : maximum;
            }
        });
        var newinput = $('.ste-social-template').clone();
        maximum++;
        newinput.find( '.ste-social-length' ).attr('name','widget-ste_social_links['+suffix+'][social]['+maximum+']');
        newinput.find( '.user-social-profile' ).attr('name','widget-ste_social_links['+suffix+'][social_profile]['+maximum+']');
        newinput.html(function(i, oldHTML) {
            return oldHTML.replace(/{{indexes}}/g, maximum);
        });

        $(this).siblings('.ste-sortable-links').find('.ste-social-icon-holder').before(newinput.html());
    });

    $('body').on('click', '.del-ste-icon', function() {
        var con = confirm(sociconsmsg.msg);
        if (!con) {
            return false;
        }
        $(this).parent().fadeOut('slow', function() {
            $(this).remove();
            $('.ste-social-title-test').focus().trigger('change');
        });
        return;
    });

    $('body').on('click', '.del-contact-ste-icon', function() {
        var con = confirm(sociconsmsg.msg);
        if (!con) {
            return false;
        }
        $(this).parent().fadeOut('slow', function() {
            $(this).remove();
            $('.ste-contact-social-title-test').focus().trigger('change');
        });
        return;
    });


    $('body').on('click', '.ste-contact-social-add:visible', function(e) {
        e.preventDefault();
        da = $(this).siblings('.ste-contact-sortable-links').attr('id');
        suffix = da.match(/\d+/);
        var maximum=0;
        $( '.ste-contact-social-icon-wrap:visible' ).each(function() {
            var value =  $(this).attr( 'data-id' );
            if(!isNaN(value))
            {
                value = parseInt(value);
                maximum = (value > maximum) ? value : maximum;
            }
        });
        var newinput = $('.ste-contact-social-template').clone();
        maximum++;
        newinput.find( '.ste-contact-social-length' ).attr('name','widget-ste_contact_social_links['+suffix+'][social]['+maximum+']');
        newinput.find( '.user-contact-social-profile' ).attr('name','widget-ste_contact_social_links['+suffix+'][social_profile]['+maximum+']');
        newinput.html(function(i, oldHTML) {
            return oldHTML.replace(/{{ind}}/g, maximum);
        });
        $(this).siblings('.ste-contact-sortable-links').find('.ste-contact-social-icon-holder').before(newinput.html()).trigger('change');
    });

    $('body').on('click', '.ste-itw-add', function(e) {
        e.preventDefault();
        da = $(this).siblings('.ste-img-text-outer').attr('id');
        suffix = da.match(/\d+/);
        var maximum=0;
        $( '.image-text-widget-wrap:visible' ).each(function() {
            var value =  $(this).attr( 'data-id' );
            if(!isNaN(value))
            {
                value = parseInt(value);
                maximum = (value > maximum) ? value : maximum;
            }
        });
        var newinput = $('.ste-itw-template').clone();
        
        newinput.html(function(i, oldHTML) {
                maximum++;
            
            newinput.find( '.image-text-widget-wrap' ).attr('data-id',maximum);
            newinput.find( '.text input' ).attr('name','widget-ste_image_text_widget['+suffix+'][link_text][]');
            newinput.find( '.link input' ).attr('name','widget-ste_image_text_widget['+suffix+'][link][]');
            newinput.find( '.widget-upload input' ).attr('name','widget-ste_image_text_widget['+suffix+'][image][]');

            newinput.find( '.text input' ).attr('id','widget-ste_image_text_widget['+suffix+'][link_text][]');
            newinput.find( '.link input' ).attr('id','widget-ste_image_text_widget['+suffix+'][link][]');
            newinput.find( '.widget-upload input' ).attr('id','widget-ste_image_text_widget['+suffix+'][image][]');

            newinput.find( '.text label' ).attr('for','widget-ste_image_text_widget['+suffix+'][link_text][]');
            newinput.find( '.link label' ).attr('for','widget-ste_image_text_widget['+suffix+'][link][]');
            newinput.find( '.widget-upload label' ).attr('for','widget-ste_image_text_widget['+suffix+'][image][]');

            // newinput.find( '.ste-screenshot' ).attr('id','widget-ste_image_text_widget-'+suffix+'-image');

            // oldHTML.replace(/{{indexes}}/g, maximum);
        });

        $(this).siblings('.ste-img-text-outer').find('.itw-holder').before(newinput.html());
        // $(this).siblings('.ste-sortable-links').find('.ste-social-icon-holder').before(newinput.html());
    });
    $('body').on('click', '.image-text-cancel', function(e) {
        e.preventDefault();
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
        });
    });
    $('body').on('click', '#remove-icon-list', function(e) {
        e.preventDefault();
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
        });
    });

    $('body').on('click', '.add-logo:visible', function(e) {
        e.preventDefault();
        da = $(this).siblings('.widget-client-logo-repeater').attr('id');
        suffix = da.match(/\d+/);
        var len = $('.link-image-repeat:visible').length;
        len++;
        var newinput = $('.ste-client-logo-template').clone();
        newinput.html(function(i, oldHTML) {
            newinput.find( '.featured-link' ).attr('name','widget-ste_partner_logo_widget['+suffix+'][link]['+len+']');
            newinput.find( '.widget-upload .link' ).attr('name','widget-ste_partner_logo_widget['+suffix+'][image]['+len+']');
            $('.widget-client-logo-repeater').trigger('change');
        });
        $(this).siblings('.widget-client-logo-repeater').siblings('.cl-repeater-holder').before(newinput.html());

    });
    $('body').on('click', '.cross', function(e) {
        e.preventDefault();
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
            $('.widget-client-logo-repeater').trigger('change');
        });
    });

    $(document).on('click', '.ste-font-group li', function() {
        var id = $(this).parents('.widget').attr('id');
        $('#' + id).find('.ste-font-group li').removeClass();
        $('#' + id).find('.icon-receiver').children('a').remove('.ste-remove-icon');
        $(this).addClass('selected');
        var prefix =  $(this).parents('.ste-font-awesome-list').find('.ste-font-group li.selected').children('svg').attr('data-prefix');
        var icon =  $(this).parents('.ste-font-awesome-list').find('.ste-font-group li.selected').children('svg').attr('data-icon');
        var aa = prefix + ' fa-' + icon;
        $(this).parents('.ste-font-awesome-list').siblings('p').find('.hidden-icon-input').val(aa);
        $(this).parents('.ste-font-awesome-list').siblings('p').find('.icon-receiver').html('<i class="' + aa + '"></i>');
        $('#' + id).find('.icon-receiver').children('i').after('<a class="ste-remove-icon"></a>');

        if (in_customizer) {
            $('.hidden-icon-input').trigger('change');
        }
        return $(this).focus().trigger('change');
    });
    $(document).on('click', '.ste-remove-icon', function() {
        var id = $(this).parents('.widget').attr('id');
        $('#' + id).find('.ste-font-group li').removeClass();
        $('#' + id).find('.hidden-icon-input').val('');
        $('#' + id).find('.icon-receiver').html('<i class=""></i>').children('a').remove('.ste-remove-icon');
        if (in_customizer) {
            $('.hidden-icon-input').trigger('change');
        }
        return $('#' + id).find('.icon-receiver').trigger('change');
    });

    /** To add remove button if icon is selected in widget update event */
    $(document).on('widget-updated', function(e, widget) {
        // "widget" represents jQuery object of the affected widget's DOM element
        var $this = $('#' + widget[0].id).find('.yes');
            $this.append('<a class="ste-remove-icon"></a>');
    });

    stetheme_pro_check_icon();

    /** function to check if icon is selected and saved when loading in widget.php */
    function stetheme_pro_check_icon() {
        $('.icon-receiver').each(function() {
            // var id = $(this).parents('.widget').attr('id');
            if($(this).hasClass('yes'))
            {
                $(this).append('<a class="ste-remove-icon"></a>');
            }
        });
    }
    function initColorPicker(widget) {
        widget.find('.my-widget-color-field').wpColorPicker({
         change: _.throttle(function () { // For Customizer
         jQuery(this).trigger('change');
            }, 3000)
        });
    }
    function onFormUpdate(event, widget) {
       initColorPicker(widget);
    }

    jQuery(document).on('widget-added widget-updated', onFormUpdate);

    $('#course-timings tbody').sortable();
    $('.ste-instructor-sortable-icons').sortable();
});
