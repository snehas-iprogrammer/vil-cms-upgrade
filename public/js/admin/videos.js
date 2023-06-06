siteObjJs.admin.videosJs = function () {

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
            $('#' + formId).find('#circle').change();
            $('#' + formId).find('#lob').change();
            $('#' + formId).find('#login_type').change();
            $('#' + formId).find('#brand').change();
            $('#' + formId).find('#link_type').change();
            $('#' + formId).find('#device_os').change();
            $('#' + formId).find('#app_version').change();
            $('#' + formId).find('#prepaid_persona').change();
            $('#' + formId).find('#plan').change();
            $('#' + formId).find('#postpaid_persona').change();
            $('#' + formId).find('#socid').change();
            location.reload();
        });
        
        var linkType = $("#link_type").val();
        $('body').on("change", "#link_type", function () {
            linkType = $("#link_type").val();
            //alert(linkType);
            if(linkType == 1){
                $("#external_link").attr("disabled",true);
                $("#internal_link").attr("disabled",false);
            }else{
                $("#external_link").attr("disabled",false);
                $("#internal_link").attr("disabled",true);
            }
         });

        var lob = $("#lob").val();
        $('body').on("change", "#lob", function () {
            lob = $("#lob").val();
            //alert(linkType);
            if(lob == 'Prepaid'){
                $("#prepaid_persona").attr("disabled",false);
                $("#plan").attr("disabled",false);
                $("#postpaid_persona").attr("disabled",true);
                $("#socid").attr("disabled",true);
            }else if(lob == 'Postpaid'){
                $("#postpaid_persona").attr("disabled",false);
                $("#socid").attr("disabled",false);
                $("#prepaid_persona").attr("disabled",true);
                $("#plan").attr("disabled",true);
            }else{
                $("#plan").attr("disabled",false);
                $("#postpaid_persona").attr("disabled",false);
                $("#socid").attr("disabled",false);
                $("#prepaid_persona").attr("disabled",false);
                $("#postpaid_persona").select2("val", "All");
                $("#prepaid_persona").select2("val", "All");
            }
         });
         
         $('#VideosList').on('click', '.filter-cancel', function (e) {
            $("#status_search").select2("val", "");
            $("#video_title").val('');
        });
    };
    
    var handleDatetimePicker = function () {
        if (!$().datetimepicker) {
            return;
        }
        $(".form_datetime").datetimepicker({
            autoclose: true,
            isRTL: Metronic.isRTL(),
            format:'yyyy-mm-dd hh:ii',
            pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
        });
    };

    // Method to fetch and place edit form with data using ajax call

    var fetchDataForEdit = function () {
        $('.portlet-body').on('click', '.edit-form-link', function () {
            var id = $(this).attr("id");
            var actionUrl = 'videos/' + id + '/edit';
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
                    
                    var linkType = $("#edit_form").find("#link_type").val();
                    if(linkType == 1){
                        $("#edit_form").find("#external_link").attr('disabled','disabled');
                        $("#edit_form").find("#internal_link").removeAttr('disabled');
                    }else if(linkType == 2){
                        $("#edit_form").find("#external_link").removeAttr('disabled');
                        $("#edit_form").find("#internal_link").attr('disabled','disabled');
                    }
                    
                    $("#edit_form").find('#link_type').change(function() {  
                        var currLinkType = $("#edit_form").find("#link_type").val();
                        if(currLinkType == 1){
                            //alert('DD' + currLinkType);
                            $("#edit_form").find("#external_link").attr('disabled','disabled');
                            $("#edit_form").find("#internal_link").removeAttr('disabled');
                        }else if(currLinkType == 2){
                            //alert('FF' + currLinkType);
                            $("#edit_form").find("#external_link").removeAttr('disabled');
                            $("#edit_form").find("#internal_link").attr('disabled','disabled');
                        }
                    });

                    var lob = $("#edit_form").find("#lob").val();
                    
                    if(lob == 'Prepaid'){
                        $("#edit_form").find("#postpaid_persona").attr('disabled','disabled');
                        $("#edit_form").find("#socid").attr('disabled','disabled');
                        $("#edit_form").find("#prepaid_persona").removeAttr('disabled');
                        $("#edit_form").find("#plan").removeAttr('disabled');
                    }else if(lob == 'Postpaid'){
                        $("#edit_form").find("#postpaid_persona").removeAttr('disabled');
                        $("#edit_form").find("#socid").removeAttr('disabled');
                        $("#edit_form").find("#prepaid_persona").attr('disabled','disabled');
                        $("#edit_form").find("#plan").attr('disabled','disabled');
                    }else{
                        $("#edit_form").find("#postpaid_persona").removeAttr('disabled');
                        $("#edit_form").find("#socid").removeAttr('disabled');
                        $("#edit_form").find("#prepaid_persona").removeAttr('disabled');
                        $("#edit_form").find("#plan").removeAttr('disabled');
                        $("#edit_form").find("#postpaid_persona").select2("val", "All");
                        $("#edit_form").find("#prepaid_persona").select2("val", "All");
                    }
                    
                    $("#edit_form").find('#lob').change(function() {  
                        var currLob = $("#edit_form").find("#lob").val();
                        $("#edit_form").find("#postpaid_persona").select2("val", "");
                        $("#edit_form").find("#prepaid_persona").select2("val", "");
                        if(currLob == 'Prepaid'){
                            $("#edit_form").find("#postpaid_persona").attr('disabled','disabled');
                            $("#edit_form").find("#socid").attr('disabled','disabled');
                            $("#edit_form").find("#prepaid_persona").removeAttr('disabled');
                            $("#edit_form").find("#plan").removeAttr('disabled');
                        }else if(currLob == 'Postpaid'){
                            $("#edit_form").find("#postpaid_persona").removeAttr('disabled');
                            $("#edit_form").find("#socid").removeAttr('disabled');
                            $("#edit_form").find("#prepaid_persona").attr('disabled','disabled');
                            $("#edit_form").find("#plan").attr('disabled','disabled');
                        }else{
                            $("#edit_form").find("#postpaid_persona").removeAttr('disabled');
                            $("#edit_form").find("#socid").removeAttr('disabled','disabled');
                            $("#edit_form").find("#prepaid_persona").removeAttr('disabled');
                            $("#edit_form").find("#plan").removeAttr('disabled');
                            $("#edit_form").find("#postpaid_persona").select2("val", "All");
                            $("#edit_form").find("#prepaid_persona").select2("val", "All");
                        }
                    });
                    handleDatetimePicker();
                    siteObjJs.validation.formValidateInit('#edit-videos', handleAjaxRequest);
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
                        $('#circle').select2('val', '');
                        $('#lob').select2('val', '');
                        $('#login_type').select2('val', '');
                        $('#brand').select2('val', '');
                        $('#link_type').select2('val', '');
                        $('#device_os').select2('val', '');
                        $('#app_version').select2('val', '');
                        $('#prepaid_persona').select2('val', '');
                        $('#plan').select2('val', '');
                        $('#postpaid_persona').select2('val', '');
                        $('#socid').select2('val', '');
                        
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
            src: $('#VideosList'),
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
                    {data: 'video_title', name: 'video_title', orderable: true},                    
                    {data: 'lob', name: 'lob', orderable: true},
                    {data: 'login_type', name: 'login_type', orderable: true},
                    {data: 'brand', name: 'brand', orderable: false},
                    {data: 'circle', name: 'circle', orderable: false},
                    {data: 'app_version', name: 'app_version', orderable: false},
                    {data: 'device_os', name: 'device_os', orderable: false},
                    {data: 'link', name: 'link', orderable: false},
                    {data: 'updated_at', name: 'updated_at', orderable: true},
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
                    "url": "videos/data",
                    "type": "GET"
                },
            }
        });
        $('#data-search').keyup(function () {
            grid.getDataTable().search($(this).val()).draw();
        });
        $('#search_video_category').on('change', function () {
            grid.getDataTable().column($(this).attr('column-index')).search($(this).val()).draw();
        });

        // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());
            var actionType = action.attr('data-actionType');
            var actionField = action.attr('data-actionField');
            var actionValue = action.val();
            var actionName = action.attr('data-action');

            if (action.val() !== "" && grid.getSelectedRowsCount() > 0) {

                var formdata = {
                    action: actionName,
                    actionField: actionField,
                    actionType: actionType,
                    actionValue: actionValue,
                    ids: grid.getSelectedRows()
                };

                handleGroupAction(grid, formdata);

            } else if (action.val() === "") {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Please select an action',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'No record (s) selected',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });

        var table = grid.getTable();
        var oTable = table.dataTable();

        table.on('click', '.delete', function (e) {
            e.preventDefault();
            var videoId = $(this).attr('data-id');
            bootbox.confirm({
                buttons: {confirm: {label: 'CONFIRM'}},
                message: 'Are you sure you want to delete this record?',
                callback: function (result) {
                    if (result === false) {
                        return;
                    }
                    var formdata = {
                        action: 'delete',
                        actionField: 'id',
                        actionType: 'group',
                        actionValue: videoId,
                        ids : bannerId
                    };
                    handleGroupAction(grid, formdata);
                }
            });
        });

        function handleGroupAction(grid, data) {

            var token = $('meta[name="csrf-token"]').attr('content');
            var form = new FormData();
            form.append("action", data.action);
            form.append("actionType", data.actionType);
            form.append("field", data.actionField);
            form.append("value", data.actionValue);
            form.append("ids", data.ids);
            form.append("_token", token);
            var actionUrl = 'videos/group-action';

            jQuery.ajax({
                url: actionUrl,
                cache: false,
                data: form,
                dataType: "json",
                type: "POST",
                processData: false,
                contentType: false,
                success: function (data)
                {
                    grid.getDataTable().ajax.reload();
                    if (data.status === 'success') {
                        Metronic.alert({
                            type: 'success',
                            icon: 'success',
                            message: data.message,
                            container: $('#ajax-response-text'),
                            place: 'prepend'
                        });

                    }
                    else if (data.status === 'fail') {
                        Metronic.alert({
                            type: 'danger',
                            icon: 'warning',
                            message: data.message,
                            container: grid.getTableWrapper(),
                            place: 'prepend'
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {

                }
            });
        }

    };


    return {
        //main function to initiate the module
        init: function () {
            initializeListener();
            handleTable();
            fetchDataForEdit();
            handleDatetimePicker();
            //bind the validation method to 'add' form on load
            siteObjJs.validation.formValidateInit('#create-videos', handleAjaxRequest);
        }

    };

}();