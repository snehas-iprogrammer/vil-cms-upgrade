siteObjJs.admin.tabJs = function () {

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

        $('body').on('click', '.delete', function (e) {
            e.preventDefault();
            var silentOtasId = $(this).attr('id');
            bootbox.confirm({
                buttons: {confirm: {label: 'CONFIRM'}},
                message: 'Are you sure you want to delete this record?',
                callback: function (result) {
                    if (result === false) {
                        return;
                    }

                    handleGroupAction(grid);
                }
            });
            function handleGroupAction(grid) {
                var token = $('meta[name="csrf-token"]').attr('content');
                var actionUrl = 'tab/' + silentOtasId;
                jQuery.ajax({
                    url: actionUrl,
                    cache: false,
                    data: {
                        _token: token,
                        _method: "delete",
                        ids: silentOtasId
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
         
        $('#tab-table').on('click', '.filter-cancel', function (e) {
            $("#status_search").select2("val", "");
        });
    };

    var fetchDataForEdit = function () {
        $('.portlet-body').on('click', '.edit-form-link', function () {
            var id = $(this).attr("id");
            var actionUrl = 'tab/' + id + '/edit';
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
                   
                    siteObjJs.validation.formValidateInit('#edit-tab', handleAjaxRequest);
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
                        formElement.find("input[type=text]").val("");
                      
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
            src: $('#tab-table'),
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
                    {data: 'name', name: 'name', orderable: false},
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
                    "url": "tab/data",
                    "type": "GET"
                }
            }
        });
        $('#data-search').keyup(function () {
            grid.getDataTable().search($(this).val()).draw();
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

        function handleGroupAction(grid, data) {

            var token = $('meta[name="csrf-token"]').attr('content');
            var form = new FormData();
            form.append("action", data.action);
            form.append("actionType", data.actionType);
            form.append("field", data.actionField);
            form.append("value", data.actionValue);
            form.append("ids", data.ids);
            form.append("_token", token);
            var actionUrl = 'banner/group-action';

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
            siteObjJs.validation.formValidateInit('#create-tab', handleAjaxRequest);
        }

    };

}();