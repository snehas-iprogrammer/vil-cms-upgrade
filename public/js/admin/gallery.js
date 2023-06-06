siteObjJs.admin.galleryJs = function () {

    $.validator.addMethod("checkFileSize", function (value, element) {
        var fileElement = element.files[0];
        if (!fileElement) {
            return true;
        }
        var size = fileElement.size / 1024;
        if (size > 800000) {
            return false;
        }
        return true;
    }, 'Maximum file size allowed is 800kb only.');

    //bind to onchange event of avatar input field
    $('#avatar').bind('change', function (e) {
        var formElement = $(this.closest('form'));
        //this.files[0].size gets the size of your file.
        if (this.files[0]) {
            if (this.files[0].size > 800000) {
                var error = siteObjJs.admin.galleryJs.maxFileSize;
                formElement.find('#file-error').text(error);
                return false;
            }

            var ext = formElement.find('#avatar').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                var error = siteObjJs.admin.galleryJs.mimes;
                formElement.find('#file-error').text(error);
                return false;
            }
            else
            {
                $('#file-error').text('');
            }

        }
    });
    
    $('#avatar1').bind('change', function (e) {
        var formElement = $(this.closest('form'));
        //this.files[0].size gets the size of your file.
        if (this.files[0]) {
            if (this.files[0].size > 800000) {
                var error = siteObjJs.admin.galleryJs.maxFileSize;
                formElement.find('#file-error1').text(error);
                return false;
            }

            var ext = formElement.find('#avatar1').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                var error = siteObjJs.admin.galleryJs.mimes;
                formElement.find('#file-error1').text(error);
                return false;
            }
            else
            {
                $('#file-error1').text('');
            }

        }
    });

    $.validator.addMethod("checkFileFormats", function (value, element) {
        if (!$(element).val()) {
            return true;
        }
        var ext = $(element).val().split('.').pop().toLowerCase();
        if ($.inArray(ext, [ 'png', 'jpg', 'jpeg']) == -1) {
            return false;
        }
        return true;
    }, 'Supported formats are jpg, jpeg, png.');

// Initialize all the page-specific event listeners here.
    var defaultImage;
    var initializeListener = function () {

        $('body').on("click", ".btn-collapse", function () {
            $("#ajax-response-text").html("");

            //retrieve id of form element and create new instance of validator to clear the error messages if any
            var formElement = $(this).closest("form");
            var formId = formElement.attr("id");
            var validator = $('#' + formId).validate();
            validator.resetForm();
            
            formElement.find('input[name="status"][value="1"]').prop("checked", true);
            formElement.find('input[name="status"][value="1"]').closest("span").addClass("checked");
            formElement.find('input[name="status"][value="0"]').closest("span").removeClass("checked");

            //remove any success or error classes on any form, to reset the label and helper colors
            $('.form-group').removeClass('has-error');
            $('.form-group').removeClass('has-success');
        });

        //Remove image from preview and clear file input on "remove" click
        $('body').on("click", ".remove-image", function () {
            var formElement = $(this.closest('form'));
           
            bootbox.confirm({
                buttons: {confirm: {label: 'CONFIRM'}},
                message: siteObjJs.admin.galleryJs.confirmRemoveImage,
                callback: function (result) {
                    var imgElet = formElement.find('#avatar');
                    imgElet[0].files[0] = "";
                    console.log(publicImagePath);
                    formElement.find("img.img-thumbnail").attr("src", publicImagePath+'images/default-user-icon-profile.png');
                    formElement.find("img.img-thumbnail").removeAttr("onerror");
                    formElement.find('#remove').val('remove');
                    formElement.find(".remove-image").hide();
                    formElement.find(".btn-file .fileinput-new").html('Select Image');
                    if (result === false) {
                        return;
                    }
                }
            });
        });

        var token = $('meta[name="csrf-token"]').attr('content');
        $('body').on('click', '.delete', function (e) {
            e.preventDefault();
            var testimonialsId = $(this).attr('id');
            bootbox.confirm({
                buttons: {confirm: {label: 'CONFIRM'}},
                message: 'Are you sure you want to delete this record?',
                callback: function (result) {
                    if (result === false) {
                        return;
                    }
                    $("#edit_form").html("");
                    //$(".add-form-main").show();
                    //$('.add-form-main .togglelable').trigger('click');
                    handleGroupAction(grid);
                }
            });
            function handleGroupAction(grid) {
                var token = $('meta[name="csrf-token"]').attr('content');
                var actionUrl = 'gallery/' + testimonialsId;
                jQuery.ajax({
                    url: actionUrl,
                    cache: false,
                    data: {
                        _token: token,
                        _method: "delete",
                        ids: testimonialsId
                    },
                    type: "POST",
                    success: function (data)
                    {
                        grid.getDataTable().ajax.reload();
                        if (data.status === 'success') {
                            Metronic.alert({
                                type: 'success',
                                icon: 'success',
                                message: data.message,
                                container: $('#ajax-response-text'),
                                place: 'prepend',
                                closeInSeconds: siteObjJs.admin.commonJs.constants.alertCloseSec
                            });
                        }
                        else if (data.status === 'fail') {
                            Metronic.alert({
                                type: 'danger',
                                icon: 'warning',
                                message: data.message,
                                container: $('#ajax-response-text'),
                                place: 'prepend',
                                closeInSeconds: siteObjJs.admin.commonJs.constants.alertCloseSec
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {

                    }
                });
            }
        });
    };
    // Method to fetch and place edit form with data using ajax call

    var fetchDataForEdit = function () {
        $('.portlet-body').on('click', '.edit-form-link', function () {
            var testimonials_id = $(this).attr("id");

            var actionUrl = 'gallery/' + testimonials_id + '/edit';
            $.ajax({
                url: actionUrl,
                cache: false,
                dataType: "json",
                type: "GET",
                success: function (data)
                {
                    $("#edit_form").html(data.form);
                    $('#edit_form #avatar').bind('change', function (e) {
                        var formElement = $(this.closest('form'));
                        //this.files[0].size gets the size of your file.
                        if (this.files[0]) {
                            if (this.files[0].size > 800000) {
                                var error = siteObjJs.admin.galleryJs.maxFileSize;
                                formElement.find('#file-error').text(error);
                                return false;
                            } else {
                                $('#file-error').text('');
                                $('#edit_form #file-error').text('');
                            }
                            var ext = formElement.find('#avatar').val().split('.').pop().toLowerCase();
                            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                                var error = siteObjJs.admin.galleryJs.mimes;
                                formElement.find('#file-error').text(error);
                                return false;
                            } else {
                                $('#file-error').text('');
                                $('#edit_form #file-error').text('');
                            }

                        }
                    });
                    
                    $('#edit_form #avatar1').bind('change', function (e) {
                        var formElement = $(this.closest('form'));
                        //this.files[0].size gets the size of your file.
                        if (this.files[0]) {
                            if (this.files[0].size > 800000) {
                                var error = siteObjJs.admin.galleryJs.maxFileSize;
                                formElement.find('#file-error1').text(error);
                                return false;
                            } else {
                                $('#file-error1').text('');
                                $('#edit_form #file-error1').text('');
                            }
                            var ext = formElement.find('#avatar1').val().split('.').pop().toLowerCase();
                            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                                var error = siteObjJs.admin.galleryJs.mimes;
                                formElement.find('#file-error').text(error);
                                return false;
                            } else {
                                $('#file-error1').text('');
                                $('#edit_form #file-error1').text('');
                            }

                        }
                    });
                    
                    siteObjJs.validation.formValidateInit('#edit-gallery', handleAjaxRequest);
                    $('form').find('input:radio').uniform();
                    $('#edit_form:first *:input[type!=hidden]:first').focus();
                    handleBootstrapMaxlength();
                },
                error: function (jqXhr, json, errorThrown)
                {
                    var errors = jqXhr.responseJSON;
                    var errorsHtml = '';
                    $.each(errors, function (key, value) {
                        errorsHtml += value[0] + '<br />';
                    });
                    Metronic.alert({
                        type: 'danger',
                        message: errorsHtml,
                        container: $('#ajax-response-text'),
                        place: 'prepend',
                        closeInSeconds: siteObjJs.admin.commonJs.constants.alertCloseSec
                    });
                }
            });
        });
    };

    // Common method to handle add and edit ajax request and reponse
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
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                var error = siteObjJs.admin.galleryJs.mimes;
                formElement.find('#file-error').text(error);
                $('html, body').animate({scrollTop: 450}, 500);
                return false;
            }
            form.append('image', image);
        }
        
        var thumbnail_image = formElement.find('#avatar1')[0].files[0];
        if (thumbnail_image) {
            if (thumbnail_image.size > 800000) {
                var error = siteObjJs.admin.galleryJs.maxFileSize;
                console.log(error);
                formElement.find('#file-error1').text(error);
                $('html, body').animate({scrollTop: 450}, 500);
                return false;
            }
            var ext = formElement.find('#avatar1').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                var error = siteObjJs.admin.galleryJs.mimes;
                formElement.find('#file-error1').text(error);
                $('html, body').animate({scrollTop: 450}, 500);
                return false;
            }
            form.append('thumbnail_image', thumbnail_image);
        }
        
        if ($('input[name=_method]').val()) {
            form.append('_method', $('input[name=_method]').val());
        }
        
        if($('input[name=_method]').val() == 'PUT' || $('input[name=_method]').val() == 'put'){
            $('.ajax-loader-edit').css("visibility", "visible");
        }else{
            $('.ajax-loader').css("visibility", "visible");
        }
        $.ajax(
                {
                    url: actionUrl,
                    /*beforeSend: function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },*/
                    cache: false,
                    type: actionType,
                    processData: false,
                    contentType: false,
                    data: form,
                    success: function (data)
                    {
                        if($('input[name=_method]').val() == 'PUT' || $('input[name=_method]').val() == 'put'){
                            $('.ajax-loader-edit').css("visibility", "hidden");
                        }else{
                            $('.ajax-loader').css("visibility", "hidden");
                        }
                        //data: return data from server
                        if (data.status === "error")
                        {
                            icon = "times";
                            messageType = "danger";
                        }

                        formElement.find(".btn-collapse").trigger("click");

                        //Empty the form fields
                        formElement.find("input[type=text], textarea").val("");
                        formElement.find('input[name="status"][value="1"]').prop("checked", true);
                        formElement.find('input[name="status"][value="1"]').closest("span").addClass("checked");
                        formElement.find('input[name="status"][value="0"]').closest("span").removeClass("checked");

                        $("#ajax-response-text").html("");
                        //retrieve id of form element and create new instance of validator to clear the error messages if any

                        if (formElement.attr("id") == 'create-gallery') {
                            formElement.find(".fileinput-preview.fileinput-exists.thumbnail").find("img").attr("src", siteObjJs.admin.galleryJs.defaultImage);
                        } else {
                            $('#edit_form').html("");
                            $(this).closest('form').trigger('reset');
                            $('.edit-form-main').hide();
                            $('.add-form-main').show();
                            $('.collapse.box-expand-form').trigger('click');
                            $('html, body').animate({scrollTop: 10}, 500);
                            $('.add-form-main form :input:visible:enabled:first').focus();
                        }
                        //reload the data in the datatable
                        grid.getDataTable().ajax.reload();
                        Metronic.alert({
                            type: messageType,
                            icon: icon,
                            message: data.message,
                            container: $('#ajax-response-text'),
                            place: 'prepend',
                            closeInSeconds: siteObjJs.admin.commonJs.constants.alertCloseSec
                        });
                    },
                    /*complete: function(){
                        $('.ajax-loader').css("visibility", "hidden");
                    },*/
                    error: function (jqXhr, json, errorThrown)
                    {
                        var errors = jqXhr.responseJSON;
                        var errorsHtml = '';
                        $.each(errors, function (key, value) {
                            errorsHtml += value[0] + '<br />';
                        });
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
    };

    var handleTable = function () {

        grid = new Datatable();
        grid.init({
            src: $('#testimonials-table'),
            loadingMessage: 'Loading...',
            dataTable: {
                'language': {
                    'info': '<span class="seperator">|</span><b>Total _TOTAL_ record(s) found</b>',
                    'infoEmpty': '',
                },
                "bStateSave": false,
                "lengthMenu": siteObjJs.admin.commonJs.constants.gridLengthMenu,
                "pageLength": siteObjJs.admin.commonJs.constants.recordsPerPage,
                "columns": [
                    {data: null, name: 'rownum', searchable: false},
                    {data: 'id', name: 'id', visible: false},
                    {data: 'title', name: 'title', orderable: false},                    
                    {data: 'thumbnail_image', name: 'thumbnail_image', orderable: false},
                    {data: 'image', name: 'image', orderable: false},
                    {data: 'order', name: 'order', orderable: false},
                    {data: 'status', name: 'status', orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;
                    var page = api.page();
                    var recNum = null;
                    var displayLength = settings._iDisplayLength;
                    api.column(0, {page: 'current'}).data().each(function (group, i) {
                        recNum = ((page * displayLength) + i + 1);
                        $(rows).eq(i).children('td:first-child').html(recNum);
                    });
                },
                "ajax": {
                    "url": "gallery/data",
                    "type": "GET"
                },
                "order": [
                    //3, "asc"
                ]// set first column as a default sort by asc
            }
        });
        $('#data-search').keyup(function () {
            grid.getDataTable().search($(this).val().trim()).draw();
        });
    };
    var objIconInputId;
    var objFormId;

    var BrowseServer = function (formId, obj) {
        objIconInputId = obj;
        objFormId = formId;

        OpenServerBrowser(
                'filemanager/show',
                screen.width * 0.7,
                screen.height * 0.7);
    };
    var handleBootstrapMaxlength = function () {
        $('#create-gallery').find("textarea").maxlength({
            limitReachedClass: "label label-danger",
            alwaysShow: true,
            placement: 'bottom-left',
            threshold: 10
        });
        $('#edit-gallery').find("textarea").maxlength({
            limitReachedClass: "label label-danger",
            alwaysShow: true,
            placement: 'bottom-left',
            threshold: 10
        });
    };

    var OpenServerBrowser = function (url, width, height)
    {
        var iLeft = (screen.width - width) / 2;
        var iTop = (screen.height - height) / 2;
        var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes";
        sOptions += ",width=" + width;
        sOptions += ",height=" + height;
        sOptions += ",left=" + iLeft;
        sOptions += ",top=" + iTop;
        var oWindow = window.open(url, "BrowseWindow", sOptions);
    }
    var getIconInputId = function () {
        return objIconInputId;
    };

    var getFormId = function () {
        return objFormId;
    };
    return {
        //main function to initiate the module
        init: function () {
            initializeListener();
            handleTable();
            fetchDataForEdit();
            handleBootstrapMaxlength();
            //bind the validation method to 'add' form on load
            siteObjJs.validation.formValidateInit('#create-gallery', handleAjaxRequest);
        },
        BrowseServer: BrowseServer,
        getIconInputId: getIconInputId,
        getFormId: getFormId

    };
}();

//It is used for call window opener from filemanager.js
var SetUrl = function (url, width, height)
{
    var getFormId = siteObjJs.admin.galleryJs.getFormId();
    var getIconInputId = siteObjJs.admin.galleryJs.getIconInputId();
    $('#' + getFormId + ' #' + getIconInputId).val(url);
    $('#' + getFormId).find("#testimonials_image").attr("src", url);
    oWindow = null;
}