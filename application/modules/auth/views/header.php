<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow"/>
        <title></title>
               <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/bootstrap-button.js"></script>
        <script src="/assets/js/bootstrap-fileupload.js"></script>
        <script src="/assets/js/bootstrap-notify.js"></script>
        <script src="/assets/js/jquery.uploadify.min.js"></script>
        <script src="/assets/js/bootbox.min.js"></script>
        <script src="/assets/js/jquery.dataTables.js"></script>
        <script src="/assets/js/bootstrap-progressbar.js"></script>
        <script src="/assets/js/bootstrap-tagsinput.js"></script>
        <script type="text/javascript" src="/assets/js/notifIt.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.total-storage.min.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.scrollpanel.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="/assets/js/date.format.js"></script>
        <script type="text/javascript" src="/assets/js/select2.js"></script>
        <script type="text/javascript" src="/assets/js/select2_locale_ru.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.form-validator.min.js"></script>

        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="/assets/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-button.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-fileupload.css" rel="stylesheet">
        <link href="/assets/css/jquery.dataTables.css" rel="stylesheet">
        <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/assets/css/uploadify.css" />
        <link rel="stylesheet" href="/assets/css/bootstrap-tagsinput.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/notifIt.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/jquery.datetimepicker.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/select2.css">
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <style>
            body,html{
                font-family: 'Ubuntu', sans-serif;
                padding-top: 30px;
            }
            div.dataTables_length label {
                float: left;
                text-align: left;
                margin-left: 30px;
            }

            div.dataTables_length select {
                width: 75px;
            }

            div.dataTables_filter label {
                float: right;
            }

            div.dataTables_info {
                padding-top: 8px;
                margin-left: 30px;
            }

            div.dataTables_paginate {
                float: right;
                margin: 0;
            }

            table.table {
                clear: both;
                margin-bottom: 6px !important;
                max-width: none !important;
            }

            table.table thead .sorting,
            table.table thead .sorting_asc,
            table.table thead .sorting_desc,
            table.table thead .sorting_asc_disabled,
            table.table thead .sorting_desc_disabled {
                cursor: pointer;
                *cursor: hand;
            }

            table.table thead .sorting { background: url('/assets/img/sort_both.png') no-repeat center right; }
            table.table thead .sorting_asc { background: url('/assets/img/sort_asc.png') no-repeat center right; }
            table.table thead .sorting_desc { background: url('/assets/img/sort_desc.png') no-repeat center right; }

            table.table thead .sorting_asc_disabled { background: url('/assets/img/sort_asc_disabled.png') no-repeat center right; }
            table.table thead .sorting_desc_disabled { background: url('/assets/img/sort_desc_disabled.png') no-repeat center right; }

            table.dataTable th:active {
                outline: none;
            }

            /* Scrolling */
            div.dataTables_scrollHead table {
                margin-bottom: 0 !important;
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }

            div.dataTables_scrollHead table thead tr:last-child th:first-child,
            div.dataTables_scrollHead table thead tr:last-child td:first-child {
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }

            div.dataTables_scrollBody table {
                border-top: none;
                margin-bottom: 0 !important;
            }

            div.dataTables_scrollBody tbody tr:first-child th,
            div.dataTables_scrollBody tbody tr:first-child td {
                border-top: none;
            }

            div.dataTables_scrollFoot table {
                border-top: none;
            }
            #user_list{
              font-size: 12px;  
            }
#user_list_info, #user_list_paginate, #user_list_info, #user_list_paginate{
                margin-top: 10px;
                font-size: 12px;
            }
            .dataTables_length select {
                width: auto !important;
                font-size: 12px;
                margin-top:10px;
            }
            .dataTables_length label{
                font-size: 12px;
            }
            .dataTables_filter input{
                width: 120px;
                font-size: 12px;
            }
            .dataTables_filter label{
                font-size: 12px;
            }
            .dataTables_paginate{
                margin-top: 10px;
                font-size: 12px;
            }
            .dataTables_info{
                margin-top: 10px;
                font-size: 12px;
            }

            /*
             * TableTools styles
             */
            .table tbody tr.active td,
            .table tbody tr.active th {
                background-color: #08C;
                color: white;
            }

            .table tbody tr.active:hover td,
            .table tbody tr.active:hover th {
                background-color: #0075b0 !important;
            }

            .table-striped tbody tr.active:nth-child(odd) td,
            .table-striped tbody tr.active:nth-child(odd) th {
                background-color: #017ebc;
            }

            table.DTTT_selectable tbody tr {
                cursor: pointer;
                *cursor: hand;
            }

            div.DTTT .btn {
                color: #333 !important;
                font-size: 12px;
            }

            div.DTTT .btn:hover {
                text-decoration: none !important;
            }


            ul.DTTT_dropdown.dropdown-menu a {
                color: #333 !important; /* needed only when demo_page.css is included */
            }

            ul.DTTT_dropdown.dropdown-menu li:hover a {
                background-color: #0088cc;
                color: white !important;
            }

            /* TableTools information display */
            div.DTTT_print_info.modal {
                height: 150px;
                margin-top: -75px;
                text-align: center;
            }

            div.DTTT_print_info h6 {
                font-weight: normal;
                font-size: 28px;
                line-height: 28px;
                margin: 1em;
            }

            div.DTTT_print_info p {
                font-size: 14px;
                line-height: 20px;
            }



            /*
             * FixedColumns styles
             */
            div.DTFC_LeftHeadWrapper table,
            div.DTFC_LeftFootWrapper table,
            table.DTFC_Cloned tr.even {
                background-color: white;
            }

            div.DTFC_LeftHeadWrapper table {
                margin-bottom: 0 !important;
                border-top-right-radius: 0 !important;
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }

            div.DTFC_LeftHeadWrapper table thead tr:last-child th:first-child,
            div.DTFC_LeftHeadWrapper table thead tr:last-child td:first-child {
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }

            div.DTFC_LeftBodyWrapper table {
                border-top: none;
                margin-bottom: 0 !important;
            }

            div.DTFC_LeftBodyWrapper tbody tr:first-child th,
            div.DTFC_LeftBodyWrapper tbody tr:first-child td {
                border-top: none;
            }

            div.DTFC_LeftFootWrapper table {
                border-top: none;
            }
            .modal-body {
                max-height: 800px;
            }
        </style>
        <script type="text/javascript">
            /* Set the defaults for DataTables initialisation */
            $.extend(true, $.fn.dataTable.defaults, {
                "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sUrl": "http://www.sprymedia.co.uk/dataTables/lang.txt"
                }
            });


            /* Default class modification */
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sWrapper": "dataTables_wrapper form-inline"
            });


            /* API method to get paging information */
            $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
            {
                return {
                    "iStart": oSettings._iDisplayStart,
                    "iEnd": oSettings.fnDisplayEnd(),
                    "iLength": oSettings._iDisplayLength,
                    "iTotal": oSettings.fnRecordsTotal(),
                    "iFilteredTotal": oSettings.fnRecordsDisplay(),
                    "iPage": oSettings._iDisplayLength === -1 ?
                            0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                    "iTotalPages": oSettings._iDisplayLength === -1 ?
                            0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                };
            };


            /* Bootstrap style pagination control */
            $.extend($.fn.dataTableExt.oPagination, {
                "bootstrap": {
                    "fnInit": function(oSettings, nPaging, fnDraw) {
                        var oLang = oSettings.oLanguage.oPaginate;
                        var fnClickHandler = function(e) {
                            e.preventDefault();
                            if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
                                fnDraw(oSettings);
                            }
                        };

                        $(nPaging).addClass('pagination').append(
                                '<ul>' +
                                '<li class="prev disabled"><a href="#">&larr; ' + oLang.sPrevious + '</a></li>' +
                                '<li class="next disabled"><a href="#">' + oLang.sNext + ' &rarr; </a></li>' +
                                '</ul>'
                                );
                        var els = $('a', nPaging);
                        $(els[0]).bind('click.DT', {action: "previous"}, fnClickHandler);
                        $(els[1]).bind('click.DT', {action: "next"}, fnClickHandler);
                    },
                    "fnUpdate": function(oSettings, fnDraw) {
                        var iListLength = 5;
                        var oPaging = oSettings.oInstance.fnPagingInfo();
                        var an = oSettings.aanFeatures.p;
                        var i, ien, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);

                        if (oPaging.iTotalPages < iListLength) {
                            iStart = 1;
                            iEnd = oPaging.iTotalPages;
                        }
                        else if (oPaging.iPage <= iHalf) {
                            iStart = 1;
                            iEnd = iListLength;
                        } else if (oPaging.iPage >= (oPaging.iTotalPages - iHalf)) {
                            iStart = oPaging.iTotalPages - iListLength + 1;
                            iEnd = oPaging.iTotalPages;
                        } else {
                            iStart = oPaging.iPage - iHalf + 1;
                            iEnd = iStart + iListLength - 1;
                        }

                        for (i = 0, ien = an.length; i < ien; i++) {
                            // Remove the middle elements
                            $('li:gt(0)', an[i]).filter(':not(:last)').remove();

                            // Add the new list items and their event handlers
                            for (j = iStart; j <= iEnd; j++) {
                                sClass = (j == oPaging.iPage + 1) ? 'class="active"' : '';
                                $('<li ' + sClass + '><a href="#">' + j + '</a></li>')
                                        .insertBefore($('li:last', an[i])[0])
                                        .bind('click', function(e) {
                                            e.preventDefault();
                                            oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
                                            fnDraw(oSettings);
                                        });
                            }

                            // Add / remove disabled classes from the static elements
                            if (oPaging.iPage === 0) {
                                $('li:first', an[i]).addClass('disabled');
                            } else {
                                $('li:first', an[i]).removeClass('disabled');
                            }

                            if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
                                $('li:last', an[i]).addClass('disabled');
                            } else {
                                $('li:last', an[i]).removeClass('disabled');
                            }
                        }
                    }
                }
            });


            /*
             * TableTools Bootstrap compatibility
             * Required TableTools 2.1+
             */
            if ($.fn.DataTable.TableTools) {
                // Set the classes that TableTools uses to something suitable for Bootstrap
                $.extend(true, $.fn.DataTable.TableTools.classes, {
                    "container": "DTTT btn-group",
                    "buttons": {
                        "normal": "btn",
                        "disabled": "disabled"
                    },
                    "collection": {
                        "container": "DTTT_dropdown dropdown-menu",
                        "buttons": {
                            "normal": "",
                            "disabled": "disabled"
                        }
                    },
                    "print": {
                        "info": "DTTT_print_info modal"
                    },
                    "select": {
                        "row": "active"
                    }
                });

                // Have the collection use a bootstrap compatible dropdown
                $.extend(true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
                    "collection": {
                        "container": "ul",
                        "button": "li",
                        "liner": "a"
                    }
                });
            }


            /* Table initialisation */
            $(document).ready(function() {
                $('#user_list').dataTable({
                    "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sUrl": "/assets/js/dataTables.russian.txt"
                    }
                });
            });

            function create_user() {
                form = $("#create_user_form").serialize();

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('auth/create_user'); ?>",
                    data: form,
                    success: function(data) {
                        $(".modal.fade.bs-example-modal-sm").modal('hide');
                    }

                });
                event.preventDefault();
                return false;  //stop the actual form post !important!
            }
        </script>

    </head>
    
<div class="container-fluid">
            <?php
            echo $menu;
            ?>
        </div>