siteObjJs.admin.anonScreenCarouselDetailsJs = function () {

    // Initialize all the page-specific event listeners here.

    var initializeListener = function () {
        $('body').on("click", ".btn-collapse", function () {
            $("#ajax-response-text").html("");

            //retrieve id of form element and create new instance of validator to clear the error messages if any
            var formElement = $(this).closest("form");
            var formId = formElement.attr("id");
            var validator = $('#' + formId).validate();
            validator.resetForm();

            //remove any success or error classes on any form, to reset the label and helper colors
            $('.form-group').removeClass('has-error');
            $('.form-group').removeClass('has-success');
            location.reload();
        });  
        
        var mediaType = $("#media_type").val();
        $("#video_url").attr("disabled",true);
        $('body').on("change", "#media_type", function () {
            mediaType = $("#media_type").val();
            //alert(linkType);
            if(mediaType == 'Video'){
                $("#video_url").attr("disabled",false);
                $("#avatar").attr("disabled",true);
            }else{
                $("#video_url").attr("disabled",true);
                $("#avatar").attr("disabled",false);
            }
        });
        
        $('body').on('click', '.delete', function (e) {
            e.preventDefault();
            var faqId = $(this).attr('data-id');
            bootbox.confirm({
                buttons: {confirm: {label: 'CONFIRM'}},
                message: 'Are you sure you want to delete this record?',
                callback: function (result) {
                    if (result === false) {
                        return;
                    }

                    var token = $('meta[name="csrf-token"]').attr('content');
                    var actionUrl = 'anon-screen-carousel-details/' + faqId;
                    jQuery.ajax({
                        url: actionUrl,
                        cache: false,
                        data: {
                            _token: token,
                            _method: "delete",
                            ids: faqId
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
        });
    };
    
            
    // Method to fetch and place edit form with data using ajax call
    
    var fetchDataForEdit = function () {
        $('.portlet-body').on('click', '.edit-form-link', function () {
            var id = $(this).attr("id");
            var actionUrl = 'anon-screen-carousel-details/' + id + '/edit';
            $.ajax({
                url: actionUrl,
                cache: false,
                dataType: "json",
                type: "GET",
                success: function (data)
                {
                    $("#edit_form").html(data.form);
                    $('#edit_form .select2me').select2({
                        placeholder: "Select",
                        allowClear: true
                    });
                    
                    var mediaType = $("#edit_form").find("#media_type").val();
                    if(mediaType == 'Video'){
                        $("#edit_form").find("#video_url").removeAttr('disabled');
                        $("#edit_form").find("#avatar").attr('disabled','disabled');
                    }else{ 
                        $("#edit_form").find("#video_url").attr("disabled",'disabled');
                        $("#edit_form").find("#avatar").removeAttr('disabled');
                    }
                    
                    $('body').on("change", "#media_type", function () {
                        mediaType = $("#edit_form").find("#media_type").val();
                        if(mediaType == 'Video'){
                            $("#edit_form").find("#video_url").removeAttr('disabled');
                            $("#edit_form").find("#avatar").attr('disabled','disabled');
                        }else{
                            $("#edit_form").find("#video_url").attr("disabled",'disabled');
                            $("#edit_form").find("#avatar").removeAttr('disabled');
                        }
                    });
                    
                    siteObjJs.validation.formValidateInit('#edit-anon-screen-carousel-details', handleAjaxRequest);
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
                        container: grid.getTableWrapper(),
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
        var mediaType = $('#media_type').val();
        if(typeof(image) != "undefined" && image !== null && mediaType != 'Video') {
            if (image) {
                if (image.size > 1000000) {
                    var error = siteObjJs.admin.galleryJs.maxFileSize;
                    console.log(error);
                    formElement.find('#file-error').text(error);
                    $('html, body').animate({scrollTop: 450}, 500);
                    return false;
                }
                var ext = formElement.find('#avatar').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'json', 'pdf','svg','webp']) == -1) {
                    var error = siteObjJs.admin.galleryJs.mimes;
                    formElement.find('#file-error').text(error);
                    $('html, body').animate({scrollTop: 450}, 500);
                    return false;
                }
                form.append('media', image);
            }
        }else if(typeof(image) != "undefined" && image !== null && mediaType == 'Video'){
            form.append('media', null);
        }
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
                        //data: return data from server
                        if (data.status === "error")
                        {
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
                        
                        //Empty the form fields
                        formElement.find("input[type=text], textarea").val("");
                                                
                        $('.edit-form-main').hide();
                        $('.add-form-main').show();
                        $('.collapse.box-expand-form').trigger('click');
                        
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

    var handleTable = function () {
        grid = new Datatable();
        grid.init({
            src: $('#AnonScreenCarouselDetailsList'),
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
                    //{data: 'id', name: 'id', visible: false},
                    {data: 'anon_screen_id', name: 'anon_screen_id', orderable: false},
                    {data: 'title', name: 'title', orderable: false},
                    {data: 'description', name: 'description', orderable: false},
                    {data: 'media_type', name: 'media_type', orderable: false},
                    {data: 'media', name: 'media', orderable: false},
                    {data: 'shape', name: 'shape', orderable: false},
                    {data: 'rank', name: 'rank', orderable: true},
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
                    "url": "anon-screen-carousel-details/data",
                    "type": "GET"
                },
                "order": [
                    [2, "asc"]
                ]// set first column as a default sort by asc
            }
        });
        $('#data-search').keyup(function () {
            grid.getDataTable().search($(this).val()).draw();
        });

    };


    return {
        //main function to initiate the module
        init: function () {
            initializeListener();
            handleTable();
            fetchDataForEdit();

            //bind the validation method to 'add' form on load
            siteObjJs.validation.formValidateInit('#create-anon-screen-carousel-details', handleAjaxRequest);
        }

    };

}();