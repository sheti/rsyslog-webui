<!DOCTYPE html>

<?php include 'config.php'; ?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $site_name; ?></title>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
              rel="stylesheet"
              integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
              crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
        <link rel="manifest" href="site.webmanifest">
        <style>
            .progress-stacked, .progress {
                --bs-progress-height: 2rem;
            }
        </style>
    </head>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>

    <script type="text/javascript">
	
    $(function () {
        
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        getEvents();

        var selectedNodeText = "";

        /*context.init({
            fadeSpeed: 100,
            filter: function ($obj){},
            above: 'auto',
            preventDoubleContext: true,
            compress: false
        });*/

        var menu = "", menudate = "";

        $('#table-style').css( 'cursor', 'pointer' );

        $("#table-style").delegate("tr td", "mousedown", function(event) {
                if(event.which == 3){

                        context.destroy();
                        //context.destroy($("table-style tr"));

                        var selectedRow = $(this);
                        selectedNodeText = selectedRow.html();
                        selectedColumn = "";

                        if(selectedRow.find('span').length > 0) selectedNodeText = selectedRow.find('span').html();

                        if( selectedRow.index() == 6 ) return;
                        if( selectedRow.index() == 0 && selectedRow.hasClass('expandedMessage') == false ) selectedColumn = "Severity";
                        if( selectedRow.index() == 1 ) 
                        {
                                selectedNodeText = selectedNodeText.replace( " ", "T" );
                                selectedColumn = "Date";
                        }
                        if( selectedRow.index() == 2 ) selectedColumn = "Facility";
                        if( selectedRow.index() == 3 ) selectedColumn = "Host";
                        if( selectedRow.index() == 4 ) selectedColumn = "Syslogtag";
                        if( selectedRow.index() == 5 ) return;
                        if( selectedRow.hasClass('expandedMessage') == true ) return;

                        menudate = [{
                        text: 'Add logs newer than \'' + selectedNodeText + '\' to filterset',
                        action: function () {
                                        $("#txtSearch").val($("#txtSearch").val() + "\"" + selectedColumn + "\">\"" + selectedNodeText + "\" ");
                                        $('#cmdSearch').click();
                                        context.destroy();
                                }
                        }, {
                                text: 'Add logs older than \'' + selectedNodeText + '\' in filterset',
                                action: function (t) {
                                        $("#txtSearch").val($("#txtSearch").val() + "\"" + selectedColumn + "\"<\"" + selectedNodeText + "\" ");
                                        $('#cmdSearch').click();
                                        context.destroy();
                                }
                        }];

                        menu = [{
                        text: 'Add \'' + selectedNodeText + '\' to filterset',
                        action: function () {
                                        $("#txtSearch").val($("#txtSearch").val() + "\"" + selectedColumn + "\"=\"" + selectedNodeText + "\" ");
                                        $('#cmdSearch').click();
                                        context.destroy();
                                }
                        }, {
                                text: 'Exclude \'' + selectedNodeText + '\' in filterset',
                                action: function (t) {
                                        $("#txtSearch").val($("#txtSearch").val() + "\"" + selectedColumn + "\"<>\"" + selectedNodeText + "\" ");
                                        $('#cmdSearch').click();
                                        context.destroy();
                                }
                        }];

                        if( selectedRow.index() == 1 ) 
                                context.attach($("table-style tr"), menudate);
                        else
                                context.attach($("table-style tr"), menu);
                }
        }); 

        $('#table-style').on('click-row.bs.table', function (e, row, $element) {
                //console.log( JSON.stringify( row ) );

                if( $element.hasClass('expandedMessage') == false) {
                    // Add new tr with full message + add class
                    $element.after('<tr><td colspan="7" class="expandedMessage"><div class="increase-font-size">' + escapeHtml(row.Message) + '</div></td></tr>');
                    $element.addClass('expandedMessage');
                } else {
                    // Remove previous created tr + remove class
                    $element.closest('tr').next().remove();
                    $element.removeClass('expandedMessage');
                }
        });

        $('#cmdSearch').click(function(e) {
            //var classes = 'table table-hover small-table table-striped';
            e.preventDefault();
            //var search = $('#txtSearch').val();
            //$('#txtSearch').val(search);

            getEvents();

            /*$('#table-style').bootstrapTable('destroy')
                    .bootstrapTable({
                            classes: classes,
                            url: 'json/events.php?&search=' + encodeURIComponent(search)
            });*/

            //console.log(encodeURIComponent(search));
        });

        $('#cmdReset').click(function (e) {
                e.preventDefault();
                $("#txtSearch").val("");
                $('#cmdSearch').click();
        });

        $("#pgDebug").on("click", function() {
                $("#txtSearch").val($("#txtSearch").val() + "\"Severity\"=\"DEBUG\" ");
                $('#cmdSearch').click();
        });

        $("#pgNotice").on("click", function() {
                $("#txtSearch").val($("#txtSearch").val() + "\"Severity\"=\"NOTICE\" ");
                $('#cmdSearch').click();
        });

        $("#pgInfo").on("click", function() {
                $("#txtSearch").val($("#txtSearch").val() + "\"Severity\"=\"INFO\" ");
                $('#cmdSearch').click();
        });

        $("#pgWarning").on("click", function() {
                $("#txtSearch").val($("#txtSearch").val() + "\"Severity\"=\"WARNING\" ");
                $('#cmdSearch').click();
        });

        $("#pgError").on("click", function() {
                $("#txtSearch").val($("#txtSearch").val() + "\"Severity\"=\"ERROR\" ");
                $('#cmdSearch').click();
        });
		
    });
    
    function getEvents() {
        var search_string = $("#txtSearch").val();
        if(search_string.length > 0) {
            search_string = '?search=' + encodeURIComponent(search_string);
        }
        $.getJSON( "json/events.php" + search_string, function( data ) {
            $('#table-style').bootstrapTable('load', data);
            
            var sum_events = 0;
            var data_events = [0, 0, 0, 0, 0, 0, 0, 0];
            var id_events = ['', '', '', '#pgError', '#pgWarning', '#pgNotice', '#pgInfo', ''];
            
            for(var i = 0; i < data.length; i++) {
                switch(data[i].Priority) {
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7: 
                        sum_events += 1; ;
                        data_events[data[i].Priority] += 1;
                        break;
                }
            }

            for(var i = 0; i < data_events.length; i++) {
                switch(i) {
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                        if(data_events[i] > 0) {
                            $(id_events[i]).css('width', Math.round((data_events[i]/sum_events) * 100) + "%");
                        } else {
                            $(id_events[i]).css('width', '0%');
                        }
                        break; 
                }
            }
        })
        .fail(function() {
            console.log( "error" );
        });
    }
    
    function toInt( val ) {
            return val & 1;
    }
	
    function rowStyle(row, index) {
        return {
            classes: 'ID_' + row.ID
        };
    }
	
    function SeverityFormat(value) {
        switch(value) {
            case 0: return "<span class=\"badge bg-danger\">EMERGENCY</span>"; 
            case 1: return "<span class=\"badge bg-danger\">ALERT</span>"; 
            case 2: return "<span class=\"badge bg-danger\">CRITICAL</span>"; 
            case 3: return "<span class=\"badge bg-danger\">ERROR</span>"; 
            case 4: return "<span class=\"badge bg-warning\">WARNING</span>"; 
            case 5: return "<span class=\"badge bg-success\">NOTICE</span>"; 
            case 6: return "<span class=\"badge bg-info\">INFO</span>"; 
            case 7: return "<span class=\"badge bg-primary\">DEBUG</span>"; 
        }
        return value;
    }

    function MessageFormat(value)
    {
            return escapeHtml(value);
    }

    function idFormat(value, row)
    {
            //console.log( row + ": " + value );
            return value;
    }

    function FacilityFormat(value) {
        switch(value) {
            case 0: return "KERNEL-MESSAGE";
            case 1: return "USER-MESSAGE";
            case 2: return "MAIL-SYSTEM";
            case 3: return "SECURITY-DAEMON";
            case 4: return "AUTH-MESSAGE";
            case 5: return "SYSLOGD";
            case 6: return "PRINTER";
            case 7: return "NETWORK";
            case 8: return "UUCP";
            case 9: return "CRON";
            case 10: return "AUTH-MESSAGE-10";
            case 11: return "FTP";
            case 12: return "NTP";
            case 13: return "LOG-AUDIT";
            case 14: return "LOG-ALERT";
            case 15: return "CLOCK-DAEMON";
            case 16: return "LOCAL0";
            case 17: return "LOCAL1";
            case 18: return "LOCAL2";
            case 19: return "LOCAL3";
            case 20: return "LOCAL4";
            case 21: return "LOCAL5";
            case 22: return "LOCAL6";
            case 23: return "LOCAL7";
        }
        return value;
    }
	
    function escapeHtml(text) {
        if (typeof text === 'string' || text instanceof String) {
            return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
        }
        return text;
    }

    </script>
  
<body>
 
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg bg-body-tertiary" style="margin-top: 1rem" role="navigation">
            <div class="container-fluid">
                <a class="navbar-brand" href="/"><?php echo $site_name; ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSearch">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/"><i class="bi bi-house"></i></a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input id="txtSearch" type="text" class="form-control input-widesearch" placeholder="Search">
                            <button id="cmdSearch" type="submit" class="btn btn-primary" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                            <button id="cmdReset" type="submit" class="btn btn-dark" title="Reset all">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </nav>

        <div id="debugmessages" style="margin-top: 1rem"></div>

        <div class="progress-stacked" style="margin-top: 1rem; margin-bottom: 1rem;">
            <div id="pgDebug" class="progress" role="progressbar" style="width: 0%" title="Debug" data-bs-toggle="tooltip" data-bs-title="Debug">
                <div class="progress-bar progress-bar-striped bg-primary"></div>
            </div>
            <div id="pgInfo" class="progress" role="progressbar" style="width: 0%" title="Information" data-bs-toggle="tooltip" data-bs-title="Information">
                <div class="progress-bar progress-bar-striped bg-info"></div>
            </div>
            <div id="pgNotice" class="progress" role="progressbar" style="width: 0%" data-toggle="tooltip" title="Notice" data-bs-toggle="tooltip" data-bs-title="Notice">
                <div class="progress-bar progress-bar-striped bg-success"></div>
            </div>
            <div id="pgWarning" class="progress" role="progressbar" style="width: 0%" data-toggle="tooltip" title="Warning" data-bs-toggle="tooltip" data-bs-title="Warning">
                <div class="progress-bar progress-bar-striped bg-warning"></div>
            </div>
            <div id="pgError" class="progress" role="progressbar" style="width: 0%" data-toggle="tooltip" title="Error" data-bs-toggle="tooltip" data-bs-title="Error">
                <div class="progress-bar progress-bar-striped bg-danger"></div>
            </div>
        </div>
<!-- small-table table-striped-->
        <table id="table-style"
               class="table"
               data-toggle="table"
               data-pagination="true"
               data-page-size="100">
            <thead> 
                <tr>
                    <th data-field="ID" data-visible="false" data-formatter="idFormat">Id</th>
                    <th data-field="Priority" data-formatter="SeverityFormat">Severity</th>
                    <th data-field="DeviceReportedTime">Date</th>
                    <th data-field="Facility" data-formatter="FacilityFormat">Facility</th>
                    <th data-field="FromHost">Host</th>
                    <th data-field="SysLogTag">Syslogtag</th>
                    <th data-field="SmallMessage" data-formatter="MessageFormat">Message</th>
                </tr>
            </thead>
        </table>
    </div>
</body>
</html>

