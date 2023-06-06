siteObjJs.admin.appquickLinksJs = function () {

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
            $('#' + formId).find('#lob').change();
            $('#' + formId).find('#prepaid_persona').change();
            $('#' + formId).find('#postpaid_persona').change();
        });
        
        var lob = $("#lob").val();
        $('body').on("change", "#lob", function () {
            lob = $("#lob").val();
            if(lob == 'Prepaid'){
                $("#prepaid_persona").attr("disabled",false);
                $("#postpaid_persona").attr("disabled",true);
                $("#red_hierarchy").attr("required",false);
                $("#postpaid_persona").select2("val", "");
                $("#socid").attr("required",false);
                $("#prepaid_persona").select2("val", "All");
                $("#plan").attr("disabled",false);
                $("#plan").select2("val", "Both");
            }else if(lob == 'Postpaid'){
                $("#postpaid_persona").attr("disabled",false);
                $("#prepaid_persona").attr("disabled",true);
                $("#red_hierarchy").attr("required",true);
                $("#socid").attr("required",true);
                $("#postpaid_persona").select2("val", "All");
                $("#prepaid_persona").select2("val", "");
               // $("#plan").select2("val","");
                $("#plan").attr("disabled",true);
            }else if(lob == 'Both'){
                $("#postpaid_persona").attr("disabled",false);
                $("#prepaid_persona").attr("disabled",false);
                $("#red_hierarchy").attr("required",true);
                $("#socid").attr("required",true);
                $("#postpaid_persona").select2("val", "All");
                $("#prepaid_persona").select2("val", "All");
                $("#plan").attr("disabled",false);
                $("#plan").select2("val", "Both");
            }
            else{
                $("#postpaid_persona").attr("disabled",true);
                $("#prepaid_persona").attr("disabled",true);
                $("#red_hierarchy").attr("required",false);
                $("#socid").attr("required",false);
                $("#postpaid_persona").select2("val", "");
                $("#prepaid_persona").select2("val", "");
                $("#plan").attr("disabled",true);
            }
         });
         $('#AppQuickLinksList').on('click', '.filter-cancel', function (e) {
            $(".select2me").select2("val", "");
            $("#title").val('');
        });
    };

    // Method to fetch and place edit form with data using ajax call

    var fetchDataForEdit = function () {
        $('.portlet-body').on('click', '.edit-form-link', function () {
            var id = $(this).attr("id");
            var actionUrl = 'appquick-links/' + id + '/edit';
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
                   
                    var lob = $("#edit_form").find("#lob").val();
                    $("#edit_form").find('#lob').change(function() {  
                        var currLob = $("#edit_form").find("#lob").val();
                        if(currLob == 'Prepaid'){
                            $("#edit_form").find("#prepaid_persona").attr("disabled",false);
                            $("#edit_form").find("#postpaid_persona").attr("disabled",true);
                            $("#edit_form").find("#red_hierarchy").attr("required",false);
                            $("#edit_form").find("#postpaid_persona").select2("val", "");
                            $("#edit_form").find("#prepaid_persona").select2("val", "All");
                            $("#edit_form").find("#socid").attr("required",false);
                            $("#edit_form").find("#plan").attr("disabled",false);
                            $("#edit_form").find("#plan").select2("val", "Both");
                        }else if(currLob == 'Postpaid'){
                            $("#edit_form").find("#postpaid_persona").attr("disabled",false);
                            $("#edit_form").find("#prepaid_persona").attr("disabled",true);
                            $("#edit_form").find("#red_hierarchy").attr("required",true);
                            $("#edit_form").find("#socid").attr("required",true);
                            $("#edit_form").find("#postpaid_persona").select2("val", "All");
                            $("#edit_form").find("#prepaid_persona").select2("val", "");
                            $("#edit_form").find("#plan").attr("disabled",true);
                        }else if(currLob == 'Both'){
                            $("#edit_form").find("#postpaid_persona").attr("disabled",false);
                            $("#edit_form").find("#prepaid_persona").attr("disabled",false);
                            $("#edit_form").find("#red_hierarchy").attr("required",true);
                            $("#edit_form").find("#postpaid_persona").select2("val", "All");
                            $("#edit_form").find("#socid").attr("required",true);
                            $("#edit_form").find("#prepaid_persona").select2("val", "All");
                            $("#edit_form").find("#plan").attr("disabled",false);
                            $("#edit_form").find("#plan").select2("val", "Both");
                        }else{
                            $("#edit_form").find("#postpaid_persona").attr("disabled",true);
                            $("#edit_form").find("#prepaid_persona").attr("disabled",true);
                            $("#edit_form").find("#red_hierarchy").attr("required",false);
                            $("#edit_form").find("#postpaid_persona").select2("val", "");
                            $("#edit_form").find("#prepaid_persona").select2("val", "");
                            $("#edit_form").find("#plan").attr("disabled",true);
                            $("#edit_form").find("#socid").attr("required",false);
                        }
                    });
                    
                    siteObjJs.validation.formValidateInit('#edit-appquick-links', handleAjaxRequest);
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
                        formElement.find("select").select2("val","");                          
                        // $('#lob').select2('val', '');  
                        // $('#prepaid_persona').select2('val', '');
                        // $('#postpaid_persona').select2('val', '');
                        
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
            src: $('#AppQuickLinksList'),
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
                    {data: null, name: 'rownum', searchable: true},
                    {data: 'name', name: 'name', searchable: true,orderable:true},
                    {data: 'lob', name: 'lob', orderable: true},
                    {data: 'prepaid_persona', name: 'prepaid_persona', orderable: true},      
                    {data: 'postpaid_persona', name: 'postpaid_persona', orderable: true},                    
                    {data: 'login', name: 'login', orderable: true},
                    {data: 'plan', name: 'plan', orderable: true},
                    {data: 'red_hierarchy', name: 'red_hierarchy', orderable: true},
                    // {data: 'socid', name: 'socid', orderable: true},
                    {data: 'app_version', name: 'app_version', orderable: true},
                    {data: 'circle', name: 'circle', orderable: true, searchable: true},
                    {data: 'rank', name: 'rank', orderable: true},
                    {data: 'updated_at', name: 'updated_at', orderable: true},
                    {data: 'status', name: 'status', orderable: true},
                    {data: 'action', name: 'action', orderable: true, searchable: true}
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
                    "url": "appquick-links/data",
                    "type": "GET"
                },
                "order": [
                    // [2, "asc"]
                ]// set first column as a default sort by asc
            }
        });
        // $('#data-search').keyup(function () {
        //     grid.getDataTable().search($(this).val()).draw();
        // });
        // $('#search_banner_category').on('change', function () {
        //     grid.getDataTable().column($(this).attr('column-index')).search($(this).val()).draw();
        // });

        $(".quicklink_id").on("select", function (evt) {
            alert(evt);
          var element = evt.params.data.element;
          var $element = $(element);
          
          $element.detach();
          $(this).append($element);
          $(this).trigger("change");
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
            var bannerId = $(this).attr('data-id');
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
                        actionValue: bannerId,
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
            var actionUrl = 'appquick-links/group-action';

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

            //bind the validation method to 'add' form on load
            siteObjJs.validation.formValidateInit('#create-appquick-links', handleAjaxRequest);
        }

    };

}();