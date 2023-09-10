/*======================
DATA TABLE
========================*/
$(function () {
    $('.datatable').dataTable({
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "oLanguage": {
            "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",
        },
        "sDom": '<"table_top"fl<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>'

    });
    $("div.table_top select").addClass('tbl_length');
    // $(".tbl_length").chosen({
    // 	disable_search_threshold: 4
    // });

});


$(function () {
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
    // $(".tbl_length").chosen({
    // 	disable_search_threshold: 4
    // });
});



$(function () {
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


});

$(function () {
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

    /* Apply the jEditable handlers to the table */
    $('.data_editable td').editable('../examples_support/editable_ajax.php', {
        "callback": function (sValue, y) {
            var aPos = oTable.fnGetPosition(this);
            oTable.fnUpdate(sValue, aPos[0], aPos[1]);
        },
        "submitdata": function (value, settings) {
            return {
                "row_id": this.parentNode.getAttribute('id'),
                "column": oTable.fnGetPosition(this)[2]
            };
        }
    });

});


$(function () {
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

$(function () {
    /*
     * Insert a 'details' column to the table
     */
    var nCloneTh = document.createElement('th');
    var nCloneTd = document.createElement('td');
    // nCloneTd.innerHTML = '<img src="../images/details_open.png">';
    nCloneTd.className = "center";

    $('.tbl_details thead tr').each(function () {
        this.insertBefore(nCloneTh, this.childNodes[0]);
    });

    $('.tbl_details tbody tr').each(function () {
        this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
    });

    /*
     * Initialse DataTables, with no sorting on the 'details' column
    */
    var oTable = $('.tbl_details').dataTable({

        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [0] }
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
    // $(".tbl_length").chosen({
    // 	disable_search_threshold: 4
    // });

    /* Add event listener for opening and closing details
     * Note that the indicator for showing which row is open is not controlled by DataTables,
     * rather it is done here
     */
    // $('.tbl_details tbody td img').live('click', function () {
    // 	var nTr = $(this).parents('tr')[0];
    // 	if (oTable.fnIsOpen(nTr)) {
    // 		/* This row is already open - close it */
    // 		this.src = "img/details_open.png";
    // 		oTable.fnClose(nTr);
    // 	} else {
    // 		/* Open this row */
    // 		this.src = "images/details_close.png";
    // 		oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), 'details');
    // 	}
    // });
});

// Header menubar msg option
$('#get_mail').on('click', () => {

    $('#get_mail ul.lists').toggleClass('d-block');
})
