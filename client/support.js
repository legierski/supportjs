(function (window, $, undefined) {

    "use strict";

    var

    options = {

        // All the options within supportjs

        api_key : '',

        urls : {
            stylesheet : 'https://s3-eu-west-1.amazonaws.com/supportjs/supportjs.css',
            api : 'https://app.supportjs.com/api/0.1/receive_message',
            additional_stylesheet : ''
        },

        user : {
            full_name : '',
            email : '',
            send_user_agent : true,
            send_current_url : true,
            additional_info : {}
        },

        tab : {
            label_closed : 'Support',
            label_open : 'Close window'
        },

        message_window : {
            visible : false,
            sent : false,
            header_copy : 'Contact support',
            send_button_copy : 'Send message',
            send_button_sending_copy : 'Sending...',
            full_name_placeholder : 'Your name',
            email_placeholder : 'Your email',
            subject_placeholder : 'Message subject',
            message_placeholder : 'Type your message here',
            message_sent_copy : 'Thanks, your message has been sent. You can ',
            message_sent_close_link_copy : 'close this window'
        },

        errors_copy : {
            sending_message_failed : 'Oops! We couldn\'t send your message. Please check your internet connection.'
        }

    },

    html = {

        stylesheet : function () {
            return String() +

            '<link rel="stylesheet" href="'+options.urls.stylesheet+'">';
        },

        additional_stylesheet : function () {
            return String() +

            '<link rel="stylesheet" href="'+options.urls.additional_stylesheet+'">';
        },

        tab : function () {
            return String() +

            '<a class="supportjs-tab">' +
                '<span class="supportjs-tab-label-closed">' + options.tab.label_closed + '</span>' +
                '<span class="supportjs-tab-label-open">' + options.tab.label_open + '</span>' +
            '</a>';
        },

        backdrop : function() {
            return String() +

            '<div class="supportjs-backdrop" style="display: none;"></div>';
        },

        message_window : function () {
            return String() +

            '<div class="supportjs-window" style="display: none;">' +
                '<h2 class="supportjs-header">' + options.message_window.header_copy + '</h2>' +
                '<div class="supportjs-form-body">' +
                    '<input type="text" value="' + options.user.full_name + '" class="supportjs-user-full-name" placeholder="' + options.message_window.full_name_placeholder + '">' +
                    '<input type="text" value="' + options.user.email + '" class="supportjs-user-email" placeholder="' + options.message_window.email_placeholder + '">' +
                    '<input type="text" value="" class="supportjs-subject" placeholder="' + options.message_window.subject_placeholder + '">' +
                    '<textarea class="supportjs-message" placeholder="' + options.message_window.message_placeholder + '"></textarea>' +
                    '<div class="supportjs-cf">' +
                        '<div class="supportjs-left">' +
                            '<a href="#" class="supportjs-powered-by">Powered by supportjs.com</a>' +
                        '</div>' +
                        '<div class="supportjs-right">' +
                            '<a href="#" class="supportjs-send">' + options.message_window.send_button_copy + '</a>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="supportjs-form-body-sent" style="display: none;">' +
                    '<div class="supportjs-message-sent">' +
                        '<span class="supportjs-tick">&#x2714;</span>' +
                        '<span class="supportjs-message-sent-copy">' + options.message_window.message_sent_copy + '<a href="#" class="supportjs-message-sent-close-link">' + options.message_window.message_sent_close_link_copy + '</a></span>' +
                    '</div>' +
                '</div>' +
            '</div>';
        }

    },

    supportjs = {

        // All functions that should be available e.g. as supportjs.load() go here

        set_user : function (name, email) {
            options.user.full_name = name;
            options.user.email = email;
        },

        set_additional_info : function(additional_info) {
            options.user.additional_info = additional_info;
        },

        load : function (api_key) {

            if(typeof api_key === 'string') {
                options.api_key = api_key;
            }

            $('head').append(html.stylesheet);

            if(options.urls.additional_stylesheet !== '') {
                $('head').append(html.additional_stylesheet);
            }

            $('body').append(html.backdrop);
            $('body').append(html.message_window);
            $('body').append(html.tab);

            $('.supportjs-backdrop').click(supportjs.toggle_window);
            $('.supportjs-tab').click(supportjs.toggle_window);
            $('.supportjs-send').click(supportjs.send);
            $('.supportjs-message-sent-close-link').click(supportjs.toggle_window);

            if(options.user.full_name !== '' && options.user.email !== '') {
                $('.supportjs-user-full-name').hide();
                $('.supportjs-user-email').hide();
            }
        },

        toggle_window : function (e) {

            e.preventDefault();

            $('.supportjs-backdrop').toggle();
            $('.supportjs-window').toggle();
            $('.supportjs-tab-label-closed').toggle();
            $('.supportjs-tab-label-open').toggle();

            options.message_window.visible = !options.message_window.visible;

            if(options.message_window.visible) {
                $('.supportjs-message').focus();
            }

            if(options.message_window.sent) {
                reset_window();
            }

        },

        send : function (e) {
            e.preventDefault();

            $('.supportjs-user-full-name').attr('disabled', 'disabled');
            $('.supportjs-user-email').attr('disabled', 'disabled');
            $('.supportjs-subject').attr('disabled', 'disabled');
            $('.supportjs-message').attr('disabled', 'disabled');
            $('.supportjs-send').attr('disabled', 'disabled');


            $('.supportjs-send').addClass('supportjs-sending');
            $('.supportjs-send').html(options.message_window.send_button_sending_copy);

            if(options.user.send_user_agent) {
                options.user.additional_info.user_agent = navigator.userAgent;
            }

            if(options.user.send_current_url) {
                options.user.additional_info.current_url = document.URL;
            }

            var data_to_send = {
                api_key : options.api_key,
                full_name : $('.supportjs-user-full-name').val(),
                email : $('.supportjs-user-email').val(),
                subject : $('.supportjs-subject').val(),
                message : $('.supportjs-message').val(),
                additional_info : options.user.additional_info
            };

            $.ajax({
                url: options.urls.api,
                type: 'POST',
                cache: false,
                data: data_to_send,
                dataType: 'json',
                success: function(response) {

                    if(response.success) {
                        options.message_window.sent = true;
                        show_sent_screen();
                    }
                    else {
                        if(typeof response.msg != 'undefined') {
                            alert(response.msg);
                        }
                        else {
                            alert('Sending message failed. Please contact support@supportjs.com');
                        }
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Oops! ' + textStatus + ' ' + errorThrown);
                    alert(options.errors_copy.sending_message_failed);
                }
            });

        },

        options : function (new_options, path) {
            if(path === undefined) {
                path = options;
            }

            if(typeof new_options === 'object') {
                for(var property in new_options) {

                    if(typeof new_options[property] === 'object') {
                        supportjs.options(new_options[property], path[property]);
                    }
                    else {
                        path[property] = new_options[property];
                    }
                }
            }
        }
    },

    // All other functions not accessible by supportjs.function_name go here

    show_sent_screen = function () {
        $('.supportjs-form-body').hide();
        $('.supportjs-form-body-sent').show();
    },

    reset_window = function () {

        options.message_window.visible = false;
        options.message_window.sent = false;

        // get rid of "used" message window
        $('.supportjs-backdrop').remove();
        $('.supportjs-tab').remove();
        $('.supportjs-window').remove();

        //recreate elements
        supportjs.load();
    }





    ;










    // Expose supportjs to the global object
    window.supportjs = supportjs;

}(this, jQuery));
