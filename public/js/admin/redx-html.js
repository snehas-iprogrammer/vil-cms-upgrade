siteObjJs.admin.redxHtmlJs = function () {

// Initialize all the page-specific event listeners here.
var token = $('meta[name="csrf-token"]').attr('content');
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
        });
        $('body').on('click', '.delete', function (e) {
            e.preventDefault();
            var redxHtmlId = $(this).attr('id');
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
                var actionUrl = 'redx-html/' + redxHtmlId;
                jQuery.ajax({
                    url: actionUrl,
                    cache: false,
                    data: {
                        _token: token,
                        _method: "delete",
                        ids: redxHtmlId
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
            var redxHtml = $(this).attr("id");
            var actionUrl = 'redx-html/' + redxHtml + '/edit';
            $.ajax({
                url: actionUrl,
                cache: false,
                dataType: "json",
                type: "GET",
                success: function (data)
                {
                    $("#edit_form").html(data.form);
                    siteObjJs.validation.formValidateInit('#edit-redx-html', handleAjaxRequest);
                    /*if (typeof (tinymce) !== 'undefined') {
                        var length = tinymce.editors.length;
                        for (var i = length; i > 0; i--) {
                            tinymce.editors[i - 1].remove();
                        };
                    }
                    tinymce.init(editor_config);*/
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
        var formID = formElement.attr("id");
        var actionUrl = formElement.attr("action");
        var actionType = formElement.attr("method");
        var formData = formElement.serializeArray();
        var icon = "check";
        var messageType = "success";
        var form = new FormData(formElement[0]);
        formData.reduce(function (obj, item) {
            form.append(item.name, item.value);
        });
        
        //console.log(tinymce.editors[1].getContent());
        
        /*if (formID === 'edit-redx-html'){
            form.append('redx_html', tinymce.editors[1].getContent());
        }else{
            form.append('redx_html', tinymce.editors[0].getContent());
        }*/

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
                        console.log(data);
                        //data: return data from server
                        if (data.status === "error")
                        {
                            icon = "times";
                            messageType = "danger";
                        }

                        //Empty the form fields
                        formElement.find("input[type=text], textarea").val("");
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
            src: $('#redx-html-table'),
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
                    {data: 'redx_html', name: 'redx_html'},
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
                    "url": "redx-html/data",
                    "type": "GET"
                },
                "order": [
                    //[1, "desc"]
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
            siteObjJs.validation.formValidateInit('#create-redx-html', handleAjaxRequest);
            //tinymce.init(editor_config);
        }

    };
}();