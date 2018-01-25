jQuery(document).ready(function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    //form generated ---------------------------------------------------------
    //Stretchy.selectors.filter = ".foo, .foo *";
    //Stretchy.active = false;
    jQuery(".custom_date").datepicker();
    jQuery('#auto_forms').on('submit',function(e) {
        e.preventDefault();
        var templateUrl = object_name.templateUrl;
        setCookie("link", hashes, 30);
        var maximizer_url =  jQuery(this).attr('action');
        // console.log(maximizer_url);
        // console.log(jQuery(this).serializeArray());
        jQuery('#SubmitButton').val('Submitting...');
        jQuery.ajax({
            type: 'POST',
            url: templateUrl + '/form-sample/lib/url_finder.php?type=save&act='+maximizer_url,
            data: jQuery(this).serializeArray(),
            success: function (result) {
                var result_data =  jQuery.trim(result);
                if(result_data =='True'){
                    // console.log("result_data");
                    // jQuery('.message-success').css("height", '100%');
                    window.location.href = "http://excelinbusiness.com/thank-you-for-completing-the-form-we-will-be-in-contact-shortly/";

                }else{
                    // console.log(result);
                    jQuery('#SubmitButton').val('Submit');
                    jQuery('.message-fail').css("height", '100%');
                }
            },
            error: function (errorThrown) {
                // console.log(errorThrown);
            }
        });
    });
    //form generated ---------------------------------------------------------
    function setCookie(cname,cvalue,exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    var cookieValue = getCookie("mmckk");
    if(cookieValue){
        jQuery(".on-change-edit").css('display','none');
        jQuery(".pum-container").css('height','1%');
        jQuery(".on-change-text").html('<a style="color: white" target="_blank" class="download_link" href="#">Please click here to complete your download.</a>');
        // console.log(cookieValue);
    }
    //email validator function
    function validateEmail(sEmail) {
        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if (filter.test(sEmail)) {
            return 1;
        }
        else {
            return 2;
        }
    }
    //get the form id every mouse hover
    var super_id = 'placeholder';
    var universal_validator = false;
    var _carry = '';
    jQuery(".wpcf7").hover(function() {
        super_id = ('#'+jQuery(this).attr('id')).toString();
        // console.log((super_id+" .wpcf7-submit"));
        }
    );
    var link_universal = '';
    //field validator block........
    jQuery('.dl-link').click(function (e) {
        e.preventDefault();
        // console.log(jQuery(this).attr('data-link'));
        link_universal = jQuery(this).attr('data-link');
        jQuery('.download_link').attr('href','https://excelinbusiness.com/download/dl/?wpdmdl='+link_universal)
    });
    jQuery('.wpcf7-submit').click(function (e) {
        e.preventDefault();
        universal_validator = false;
        //initialize form data..
        arr = jQuery(super_id+' .wpcf7-form').serializeArray();
        jQuery('.wpcf7-not-valid-tip').remove();
        jQuery('.maximizer-validator').remove();
        //loop through an array of fields
        for (var i = 0, len = arr.length; i < len; i++) {
            if(arr[i].value != undefined) {
                // console.log(arr[i].value);
                //determine which field has a required value
                if (jQuery(super_id + ' input[name="' + arr[i].name + '"]').attr('aria-required')) {
                    if (arr[i].value.length < 1) {
                        //append if value is null
                        jQuery(super_id + ' input[name="' + arr[i].name + '"]').after('<p style="color: red" role="alert" class="maximizer-validator">The field is required.</p>');
                        // console.log('call');
                        universal_validator = true;
                    } else {
                        //catch email fields values
                        if (arr[i].name === 'your-email') {
                            if (validateEmail(arr[i].value) == 2) {
                                var message = "The e-mail address entered is invalid.";
                                jQuery(super_id + ' input[name="' + arr[i].name + '"]').after('<p style="color: red" role="alert" class="maximizer-validator">' + message + '</p>');
                                universal_validator = true;
                            } else {
                               // console.log('nice email' + validateEmail(arr[i].value));
                            }
                        }
                        if (arr[i].name == 'tel-num' || arr[i].name == 'phone') {
                            if (!jQuery.isNumeric(arr[i].value)) {
                                var message = "The "+arr[i].name+" entered is invalid.";
                                jQuery(super_id + ' input[name=' + arr[i].name + ']').after('<p style="color: red" role="alert" class="maximizer-validator">' + message + '</p>');
                                universal_validator = true;
                            }
                        }
                        if (arr[i].name === 'alternate-date') {
                            var avail_date = Date.parse(jQuery(super_id + ' input[name=avail-date]').val());
                            var alternative =  Date.parse(arr[i].value);
                            if (alternative <= avail_date ) {
                                var message = "The alternate date should not be equal or lower than the availability date.";
                                jQuery(super_id + ' input[name=' + arr[i].name + ']').after('<p style="color: red; padding-left:6.2px" role="alert" class="maximizer-validator">' + message + '</p>');
                                universal_validator = true;
                            }
                        }
                    }
                }else{
                    if (arr[i].name == 'state_t') {
                        if (arr[i].value == '') {
                            var message = "The state value entered is invalid.";
                            jQuery('#state').after('<p style="color: red; padding-left:6.2px" role="alert" class="maximizer-validator">' + message + '</p>');
                            universal_validator = true;
                        }
                    }
                    if (arr[i].name == 'country_t') {
                        if (arr[i].value == '') {
                            var message = "The country value entered is invalid.";
                            jQuery('#country').after('<p style="color: red; padding-left:6.2px" role="alert" class="maximizer-validator">' + message + '</p>');
                            universal_validator = true;
                        }
                    }

                }
            }
            if(arr[i].name === '_before_after_goal_id'){
                break;
            }
        }
        if(jQuery(super_id +' input[name="software-selling[]"]').is(':checkbox')) {
            if (jQuery(super_id + ' input[name="software-selling[]"]:checked').length < 1) {
                var message = "The field is required.";
                jQuery(".software-selling").before('<p style="color: red" role="alert" class="maximizer-validator">' + message + '</p>');
                universal_validator = true;
            }
        }
        if(jQuery(super_id +' input[name="service-selling[]"]').is(':checkbox')) {
            if (jQuery(super_id +' input[name="service-selling[]"]:checked').length < 1) {
                var message = "The field is required.";
                jQuery(".service-selling").before('<p style="color: red" role="alert" class="maximizer-validator">' + message + '</p>');
                universal_validator = true;
            }
        }
        if(!universal_validator) {
            _carry = super_id + 'input[name="email"]';
            saveData(arr);
            // jQuery('.wpcf7-form').submit();
            console.log('call');
        }else{
            return false;
        }
        // console.log('validated');
    });
    //handles submit to email eib and maximizer
    function saveData(data) {
        var templateUrl = object_name.templateUrl;
        if(universal_validator == false) {
                var currenturl = jQuery(super_id+' .wpcf7-form').attr('action');
                jQuery.ajax({
                    type: "POST",
                    url: currenturl,
                    async: false,
                    data: data,
                    success: function (result) {
                        var link = jQuery(jQuery.parseHTML(result)).find(".screen-reader-response").text();
                        // console.log(result);
                        if(jQuery(super_id+' .wpcf7-submit').val() === 'Download') {
                            setCookie("mmckk", link_universal, 30);
                            window.open("https://excelinbusiness.com/download/dl/?wpdmdl=" + link_universal,"_blank");
                            // location.reload();
                            link_universal = '';
                            // console.log('download');
                        }
                    }
                });
            // console.log('evaluator passed');
            // uncomment this line to synchronize with maximizer
            var currenturl_2 = jQuery(super_id+' .wpcf7-form').attr('action');
            jQuery.ajax({
                type: 'POST',
                url: templateUrl + '/form-sample/lib/url_finder.php?type=get_url&act='+currenturl_2,
                async: false,
                data: data,
                success: function (result) {
                    // console.log(result);
                    var result_data =  jQuery.trim(result);
                    if(result_data =='True'){
                        console.log("inserted");
                        window.location.href = "https://excelinbusiness.com/thank-you-for-completing-the-form-we-will-be-in-contact-shortly/";
                    }else{
                        console.log(result);
                        jQuery('.wpcf7-submit').after('<p style="color: red" role="alert" class="maximizer-validator"> ' + result + '</p>'); 
                    }
                },
                error: function (errorThrown) {
                    // console.log(errorThrown);
                }
            });
        }else{
            // console.log('data is invalid'+super_id);
            return false;
        }
    }
});
