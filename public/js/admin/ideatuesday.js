siteObjJs.admin.ideaTuesdayJs = function () {

    // Initialize all the page-specific event listeners here.

    var initializeListener = function () {
        
    };

    
    var handleAjaxRequest = function () {
        var formElement = $(this.currentForm); // Retrive form from DOM and convert it to jquery object
        var actionUrl = formElement.attr("action");
        var actionType = formElement.attr("method");
        var formData = formElement.serializeArray();
        var icon = "check";
        var messageType = "success";
        var form = new FormData(formElement[0]);
        formData.reduce(function (obj, item) {
            form.append(item.name, item.value);
        });
        
        var image = formElement.find('#avatar')[0].files[0];
        if (image) {
            if (image.size > 800000) {
                var error = siteObjJs.admin.galleryJs.maxFileSize;
                console.log(error);
                formElement.find('#file-error').text(error);
                $('html, body').animate({scrollTop: 450}, 500);
                return false;
            }
            var ext = formElement.find('#avatar').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg','svg','webp']) == -1) {
                var error = siteObjJs.admin.galleryJs.mimes;
                formElement.find('#file-error').text(error);
                $('html, body').animate({scrollTop: 450}, 500);
                return false;
            }
            form.append('image', image);
        }
        
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $('.ajax-loader').css("visibility", "visible");
        $.ajax(
                {
                    url: actionUrl,
                    cache: false,
                    type: actionType,
                    processData: false,
                    contentType: false,
                    data: form,
                    success: function (data)
                    {
                        $('.ajax-loader').css("visibility", "hidden");
                        
                        //data: return data from server
                        if (data.status === "error"){
                            icon = "times";
                            messageType = "danger";
                        }

                        $("#ajax-response-text").html("");
                        
                        var validator = formElement.validate();
                        validator.resetForm();
                        formElement[0].reset();
                        //remove any success or error classes on any form, to reset the label and helper colors
                        $('.form-group').removeClass('has-error');
                        $('.form-group').removeClass('has-success');
                        $('.help-block-error').remove();
                        
                        $('.add-form-main').show();
                        $('.collapse.box-expand-form').trigger('click');
                        
                        Metronic.alert({
                            type: messageType,
                            icon: icon,
                            message: data.message,
                            container: $('#ajax-response-text'),
                            place: 'prepend',
                            closeInSeconds: siteObjJs.admin.commonJs.constants.alertCloseSec
                        });
                    },
                    error: function (jqXhr, json, errorThrown)
                    {
                        var errors = jqXhr.responseJSON;
                        var errorsHtml = '';
                        $.each(errors, function (key, value) {
                            errorsHtml += value[0] + '<br />';
                        });
                        // alert(errorsHtml, "Error " + jqXhr.status + ': ' + errorThrown);
                        Metronic.alert({
                            type: 'danger',
                            message: errorsHtml,
                            container: $('#ajax-response-text'),
                            place: 'prepend',
                            closeInSeconds: siteObjJs.admin.commonJs.constants.alertCloseSec
                        });
                    }
                }
        );
    }

    
    return {
        //main function to initiate the module
        init: function () {
            initializeListener();
            //bind the validation method to 'add' form on load
            siteObjJs.validation.formValidateInit('#create-ideatuesday', handleAjaxRequest);
        }

    };

}();