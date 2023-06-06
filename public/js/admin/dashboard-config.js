siteObjJs.admin.dashboardConfigJs = function () {

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
            $('#' + formId).find('#app_version').change();
//            location.reload();
        });

        var lob = $("#lob").val();
        $('body').on("change", "#lob", function () {
            lob = $("#lob").val();
            $("#red_hierarchy").select2("val", "");
            if(lob == 'Prepaid'){
               
                $("#postpaid_persona").select2("val", "");
                $("#prepaid_persona").select2("val", "All");
                $("#prepaid_persona").attr("disabled",false);
                $("#postpaid_persona").attr("disabled",true);  
                $("#red_hierarchy").removeAttr("required");
                $("#red_hierarchy").attr("disabled",true);
            }else if(lob == 'Postpaid'){
                $("#postpaid_persona").select2("val", "All");
                $("#prepaid_persona").select2("val", "");
                $("#postpaid_persona").attr("disabled",false);
                $("#prepaid_persona").attr("disabled",true);
                $("#red_hierarchy").attr("required", "required");
                $("#red_hierarchy").attr("disabled",false);
             
            }else if(lob == 'Both'){
                $("#postpaid_persona").select2("val", "All");
                $("#prepaid_persona").select2("val", "All");
                $("#postpaid_persona").attr("disabled",false);
                $("#prepaid_persona").attr("disabled",false);
                $("#red_hierarchy").attr("required", "required");
                $("#red_hierarchy").attr("disabled",false);
              
            }else{
                $("#postpaid_persona").select2("val", "");
                $("#prepaid_persona").select2("val", "");
                $("#postpaid_persona").attr("disabled",false);
                $("#prepaid_persona").attr("disabled",false);
                $("#red_hierarchy").removeAttr("required");
                $("#red_hierarchy").attr("disabled",false);
            }
         });

        $('body').on('click', '.delete', function (e) {
            e.preventDefault();
            var dashboardConfigId = $(this).attr('id');
            bootbox.confirm({
                buttons: {confirm: {label: 'CONFIRM'}},
                message: 'Are you sure you want to delete this record?',
                callback: function (result) {
                    if (result === false) {
                        return;
                    }

                    handleGroupAction(grid,dashboardConfigId);
                }
            });
            function handleGroupAction(grid,config_id) {
                var token = $('meta[name="csrf-token"]').attr('content');
                var actionUrl = 'dashboard-config/' + config_id;
                jQuery.ajax({
                    url: actionUrl,
                    cache: false,
                    data: {
                        _token: token,
                        _method: "delete",
                        ids: config_id
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
            var dashboardConfigId = $(this).attr("id");
            var actionUrl = 'dashboard-config/' + dashboardConfigId + '/edit';
            $.ajax({
                url: actionUrl,
                cache: false,
                dataType: "json",
                type: "GET",
                success: function (data)
                {
                    console.log(data.form);
                    $("#edit_form").html(data.form);
                    $('#edit_form .select2me').select2({
                        placeholder: "Select",
                        allowClear: true
                    }); 
                    var lob = $("#edit_form").find("#lob").val();


                    if(lob == 'Prepaid'){
                        $("#edit_form").find("#prepaid_persona").removeAttr('disabled');
                        $("#edit_form").find("#postpaid_persona").attr('disabled','disabled');
                        $("#edit_form").find("#red_hierarchy").removeAttr("required");
                        $("#edit_form").find("#red_hierarchy").attr('disabled','disabled');
                        
                    }else if(lob == 'Postpaid'){
                        $("#edit_form").find("#postpaid_persona").removeAttr('disabled');
                        $("#edit_form").find("#prepaid_persona").attr('disabled','disabled');
                        $("#edit_form").find("#red_hierarchy").attr("required", "required");
                        $("#edit_form").find("#red_hierarchy").removeAttr('disabled');
                    }else{
                        $("#edit_form").find("#postpaid_persona").removeAttr('disabled');
                        $("#edit_form").find("#prepaid_persona").removeAttr('disabled');
                        $("#edit_form").find("#red_hierarchy").removeAttr("required");
                        $("#edit_form").find("#red_hierarchy").removeAttr('disabled');
                        
                    }

                    $("#edit_form").find('#lob').change(function() {  
                        var lob = $("#edit_form").find("#lob").val();

                        if(lob == 'Prepaid'){
                            $("#edit_form").find("#red_hierarchy").attr('disabled','disabled');
                            $("#edit_form").find("#prepaid_persona").removeAttr('disabled');
                            $("#edit_form").find("#postpaid_persona").attr('disabled','disabled');
                            $("#edit_form").find("#postpaid_persona").select2("val", "");
                            $("#edit_form").find("#prepaid_persona").select2("val", "All");
                            $("#edit_form").find("#red_hierarchy").removeAttr("required");
                            $("#edit_form").find("#red_hierarchy").select2("val", "");
                            $("#edit_form").find("#red_hierarchy").attr('disabled','disabled');
                        }else if(lob == 'Postpaid'){
                            $("#edit_form").find("#postpaid_persona").removeAttr('disabled');
                            $("#edit_form").find("#prepaid_persona").attr('disabled','disabled');
                            $("#edit_form").find("#postpaid_persona").select2("val", "All");
                            $("#edit_form").find("#prepaid_persona").select2("val", "");
                            $("#edit_form").find("#red_hierarchy").attr("required", "required");
                            $("#edit_form").find("#red_hierarchy").select2("val", "");
                            $("#edit_form").find("#red_hierarchy").removeAttr('disabled');
                        }else{
                            $("#edit_form").find("#postpaid_persona").removeAttr('disabled');
                            $("#edit_form").find("#prepaid_persona").removeAttr('disabled');
                            $("#edit_form").find("#postpaid_persona").select2("val", "All");
                            $("#edit_form").find("#prepaid_persona").select2("val", "All");
                            $("#edit_form").find("#red_hierarchy").removeAttr("required");
                            $("#edit_form").find("#red_hierarchy").select2("val", "");
                            $("#edit_form").find("#red_hierarchy").removeAttr('disabled');
                        }
                    });

                    siteObjJs.validation.formValidateInit('#edit-dashboard-config', handleAjaxRequest);
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
            });
        });
    };
    // Common method to handle add and edit ajax request and reponse

    var handleAjaxRequest = function () {
        var formElement = $(this.currentForm); // Retrive form from DOM and convert it to jquery object
        var actionUrl = formElement.attr("action");
        var actionType = formElement.attr("method");
        var formData = formElement.serialize();
        var icon = "check";
        var messageType = "success";
        $.ajax(
                {
                    url: actionUrl,
                    cache: false,
                    type: actionType,
                    data: formData,
                    success: function (data)
                    {
                        //console.log(data);
                        //data: return data from server
                        if (data.status === "error")
                        {
                            icon = "times";
                            messageType = "danger";
                        }

                        //Empty the form fields
                        formElement.find("input[type=text], textarea").val("");
                        $('#circle').select2('val', '');
                        $('#lob').select2('val', '');
                        $('#login_type').select2('val', '');
                        $('#brand').select2('val', '');
                        $('#app_version').select2('val', '');
                        
                        //trigger cancel button click event to collapse form and show title of add page
                        $('.btn-collapse').trigger('click');
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
                        //alert(errorsHtml, "Error " + jqXhr.status + ': ' + errorThrown);
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
            src: $('#dashboard-config-table'),
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
                    {data: 'lob', name: 'lob'},
                    {data: 'prepaid_persona', name: 'prepaid_persona'},
                    {data: 'postpaid_persona', name: 'postpaid_persona'},
                    {data: 'red_hierarchy', name: 'red_hierarchy'},
                    {data: 'brand', name: 'brand'},
                    {data: 'login_type', name: 'login_type'},
                    {data: 'circle', name: 'circle'},
                    {data: 'app_version', name: 'app_version'},
                    // {data:  'active_tab_for_lottie',name:'active tab for lottie'},
                 //   {data: 'header_menu', name: 'header_menu'},
                    {data: 'rail_sequence', name: 'rail_sequence'},
                    {data: 'status', name: 'status'},
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
                    "url": "dashboard-config/data",
                    "type": "GET"
                },
                "order": [
                    [1, "asc"]
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
            siteObjJs.validation.formValidateInit('#create-dashboard-config', handleAjaxRequest);
        }

    };
}();