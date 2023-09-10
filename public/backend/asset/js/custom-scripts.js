/*======================
DATA TABLE
========================*/
$(function() {
    $('.data_tbl').dataTable({
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "oLanguage": {
            "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",
        },
        "sDom": '<"table_top"fl<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>'

    });
    $("div.table_top select").addClass('tbl_length');
    $(".tbl_length").chosen({
        disable_search_threshold: 4
    });

});


$(function() {
    $('#data_tbl_tools').dataTable({
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "oLanguage": {
            "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",
        },
        "sDom": '<"table_top"fl<"clear">>,<"tbl_tools"CT<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>',

        "oTableTools": {
            "sSwfPath": "swf/copy_cvs_xls_pdf.swf"
        }
    });
    $("div.table_top select").addClass('tbl_length');
    $(".tbl_length").chosen({
        disable_search_threshold: 4
    });
});



$(function() {
    $('#action_tbl').dataTable({
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [0, 7] }
        ],
        "aaSorting": [
            [1, 'asc']
        ],
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "oLanguage": {
            "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",
        },
        "sDom": '<"table_top"fl<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>'

    });
    $("div.table_top select").addClass('tbl_length');
    $(".tbl_length").chosen({
        disable_search_threshold: 4
    });

});

$(function() {

    $('.data_editable').dataTable({
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "oLanguage": {
            "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",
        },
        "sDom": '<"table_top"fl<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>'
            /*
        "fnDrawCallback": function () {
            $('.data_editable tbody td').editable();
        },*/

    });
    $("div.table_top select").addClass('tbl_length');
    $(".tbl_length").chosen({
        disable_search_threshold: 4
    });
    /* Apply the jEditable handlers to the table */
    $('.data_editable td').editable('../examples_support/editable_ajax.php', {
        "callback": function(sValue, y) {
            var aPos = oTable.fnGetPosition(this);
            oTable.fnUpdate(sValue, aPos[0], aPos[1]);
        },
        "submitdata": function(value, settings) {
            return {
                "row_id": this.parentNode.getAttribute('id'),
                "column": oTable.fnGetPosition(this)[2]
            };
        }
    });

});


$(function() {

    $('.data_tbl_search').dataTable({
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": false,
        "bInfo": false,
        "bAutoWidth": false,
        "oLanguage": {
            "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",
        },
        "sDom": '<"table_top"fl<"clear">>,<"table_content"t>'

    });
});


/* Formating function for row details */
function fnFormatDetails(oTable, nTr) {
    var aData = oTable.fnGetData(nTr);
    var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
    sOut += '<tr><td>Rendering engine:</td><td>' + aData[1] + ' ' + aData[4] + '</td></tr>';
    sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
    sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
    sOut += '</table>';

    return sOut;
}

// $(function() {
//     /*
//      * Insert a 'details' column to the table
//      */
//     var nCloneTh = document.createElement('th');
//     var nCloneTd = document.createElement('td');
//     nCloneTd.innerHTML = '<img src="../img/details_open.png">';
//     nCloneTd.className = "center";

//     $('.tbl_details thead tr').each(function() {
//         this.insertBefore(nCloneTh, this.childNodes[0]);
//     });

//     $('.tbl_details tbody tr').each(function() {
//         this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
//     });

//     /*
//      * Initialse DataTables, with no sorting on the 'details' column
//      */
//     var oTable = $('.tbl_details').dataTable({

//         "aoColumnDefs": [
//             { "bSortable": false, "aTargets": [0] }
//         ],
//         "aaSorting": [
//             [1, 'asc']
//         ],
//         "sPaginationType": "full_numbers",
//         "iDisplayLength": 10,
//         "oLanguage": {
//             "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",
//         },
//         "sDom": '<"table_top"fl<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>'
//     });
    
//     $("div.table_top select").addClass('tbl_length');
//     $(".tbl_length").chosen({
//         disable_search_threshold: 4
//     });

//     /* Add event listener for opening and closing details
//      * Note that the indicator for showing which row is open is not controlled by DataTables,
//      * rather it is done here
//      */
//     $('.tbl_details tbody td img').live('click', function() {
//         var nTr = $(this).parents('tr')[0];
//         if (oTable.fnIsOpen(nTr)) {
//             /* This row is already open - close it */
//             this.src = "../img/details_open.png";
//             oTable.fnClose(nTr);
//         } else {
//             /* Open this row */
//             this.src = "./img/details_close.png";
//             oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), 'details');
//         }
//     });
// });




$(function() {
    /* tells us if we dragged the box */
    var dragged = false;

    /* timeout for moving the mox when scrolling the window */
    var moveBoxTimeout;

    /* make the actionsBox draggable */
    $('#actionsBox').draggable({
        start: function(event, ui) {
            dragged = true;
        },
        stop: function(event, ui) {
            var $actionsBox = $('#actionsBox');
            /*
            calculate the current distance from the window's top until the element
            this value is going to be used further, to move the box after we scroll
             */
            $actionsBox.data('distanceTop', parseFloat($actionsBox.css('top'), 10) - $(document).scrollTop());
        }
    });

    /*
    when clicking on an input (checkbox),
    change the class of the table row,
    and show the actions box (if any checked)
     */
    $('#action_tbl input[type="checkbox"]').bind('click', function(e) {
        var $this = $(this);
        if ($this.is(':checked'))
            $this.parents('tr:first').addClass('selected');
        else
            $this.parents('tr:first').removeClass('selected');
        showActionsBox();
    });

    function showActionsBox() {
        /* number of checked inputs */
        var BoxesChecked = $('#action_tbl input:checked').length;
        /* update the number of checked inputs */
        $('#cntBoxMenu').html(BoxesChecked);
        /*
        if there is at least one selected, show the BoxActions Menu
        otherwise hide it
         */
        var $actionsBox = $('#actionsBox');
        if (BoxesChecked > 0) {
            /*
            if we didn't drag, then the box stays where it is
            we know that that position is the document current top
            plus the previous distance that the box had relative to the window top (distanceTop)
             */
            if (!dragged)
                $actionsBox.stop(true).animate({ 'top': parseInt(15 + $(document).scrollTop()) + 'px', 'opacity': '1' }, 500);
            else
                $actionsBox.stop(true).animate({ 'top': parseInt($(document).scrollTop() + $actionsBox.data('distanceTop')) + 'px', 'opacity': '1' }, 500);
        } else {
            $actionsBox.stop(true).animate({ 'top': parseInt($(document).scrollTop() - 50) + 'px', 'opacity': '0' }, 500, function() {
                $(this).css('left', '50%');
                dragged = false;
                /* if the submenu was open we hide it again */
                var $toggleBoxMenu = $('#toggleBoxMenu');
                if ($toggleBoxMenu.hasClass('closed')) {
                    $toggleBoxMenu.click();
                }
            });
        }
    }

    /*
    when scrolling, move the box to the right place
     */
    $(window).scroll(function() {
        clearTimeout(moveBoxTimeout);
        moveBoxTimeout = setTimeout(showActionsBox, 500);
    });

    /* open sub box menu for other actions */
    $('#toggleBoxMenu').toggle(
        function(e) {
            $(this).addClass('closed').removeClass('open');
            $('#actionsBox .submenu').stop(true, true).slideDown();
        },
        function(e) {
            $(this).addClass('open').removeClass('closed');
            $('#actionsBox .submenu').stop(true, true).slideUp();
        }
    );

    /*
    close the actions box menu:
    hides it, and then removes the element from the DOM,
    meaning that it will no longer appear
     */
    $('#closeBoxMenu').bind('click', function(e) {
        $('#actionsBox').animate({ 'top': '-50px', 'opacity': '0' }, 1000, function() {
            $(this).remove();
        });
    });

    /*
    as an example, for all the actions (className:box_action)
    alert the values of the checked inputs
     */
    $('#actionsBox .box_action').bind('click', function(e) {
        var ids = '';
        $('#action_tbl input:checked').each(function(e, i) {
            var $this = $(this);
            ids += 'id : ' + $this.attr('id') + ' , value : ' + $this.val() + '\n';
        });
        alert('checked inputs:\n' + ids);
    });
});