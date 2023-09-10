$(document).on('click', '#submit_btn', function (e) {
    e.preventDefault();
    var action = $(this).data('action_id');
    var button_type = $(this).data('button_type');
    if (action == 1) {
        actionMessage = 'Sale created Successfully.';
    } else if (action == 2) {
        actionMessage = ' Draft created successfully.';
    } else if (action == 4) {
        actionMessage = 'Quotation created Successfully.';
    }
    $('#action').val(action);
    $('#button_type').val(button_type);
    $('#b').val(action);
    $('#pos_submit_form').submit();
});

$(document).on('click', '#full_due_button', function (e) {
    e.preventDefault();
    fullDue();
});

function fullDue() {
    var total_payable_amount = $('#total_payable_amount').val();
    $('#paying_amount').val(parseFloat(0).toFixed(2));
    $('#change_amount').val(- parseFloat(total_payable_amount).toFixed(2));
    $('#total_due').val(parseFloat(total_payable_amount).toFixed(2));
    $('#action').val(1);
    $('#button_type').val(0);
    $('#pos_submit_form').submit();
}

function cancel() {
    $.confirm({
        'title': 'Cancel Confirmation',
        'content': 'Are you sure to cancel ?',
        'buttons': {
            'Yes': {
                'class': 'yes btn-modal-primary',
                'action': function () {
                    $('#product_list').empty();
                    $('.payment_method').hide();
                    $('#pos_submit_form')[0].reset();
                    calculateTotalAmount();
                    toastr.error('Sale has been cancelled.');
                    document.getElementById('search_product').focus();
                    var store_url = $('#store_url').val();
                    $('#pos_submit_form').attr('action', store_url);
                    activeSelectedItems();
                }
            },
            'No': { 'class': 'no btn-danger', 'action': function () { console.log('Deleted canceled.'); } }
        }
    });
}

//Key shortcut for cancel
shortcuts.add('ctrl+m', function () {
    cancel();
});

//Key shortcut for pic hold invoice
shortcuts.add('f2', function () {
    $('#action').val(2);
    $('#button_type').val(0);
    $('#pos_submit_form').submit();
});

//Key shortcut for pic hold invoice
shortcuts.add('f4', function () {
    $('#action').val(4);
    $('#button_type').val(0);
    $('#pos_submit_form').submit();
});

//Key shortcut for pic hold invoice
shortcuts.add('f8', function () {
    $('#action').val(5);
    $('#button_type').val(0);
    $('#pos_submit_form').submit();
});

//Key shortcut for pic hold invoice
shortcuts.add('f10', function () {
    $('#action').val(1);
    $('#button_type').val(1);
    $('#pos_submit_form').submit();
});

$('.other_payment_method').on('click', function (e) {
    e.preventDefault();
    $('#otherPaymentMethod').modal('show');
});

$(document).on('click', '#cancel_pay_mathod', function (e) {
    e.preventDefault();
    console.log('cancel_pay_mathod');
    $('#payment_method').val('Cash');
    $('.payment_method').hide();
    $('#otherPaymentMethod').modal('hide');
});

//Key shortcut for all payment method
shortcuts.add('ctrl+b', function () {
    $('#otherPaymentMethod').modal('show');
});

//Key shortcut for credit sale
shortcuts.add('alt+g', function () {
    fullDue();
});

//Key shortcut for quick payment
shortcuts.add('alt+s', function () {
    var total_payable = $('#total_payable_amount').val();
    var paying_amount = $('#paying_amount').val();
    var change = $('#change_amount').val();
    var due = $('#total_due').val();
    $('#modal_total_payable').val(parseFloat(total_payable).toFixed(2));
    $('#modal_paying_amount').val(parseFloat(paying_amount).toFixed(2));
    $('#modal_change_amount').val(parseFloat(change).toFixed(2));
    $('#modal_total_due').val(parseFloat(due).toFixed(2));
    $('#cashReceiveMethod').modal('show');
    setTimeout(function () {
        $('#modal_paying_amount').focus();
        $('#modal_paying_amount').select();
    }, 500);
});

//Key shortcut for pic hold invoice
shortcuts.add('alt+a', function () {
    $('#action').val(6);
    $('#button_type').val(0);
    $('#pos_submit_form').submit();
});

//Key shortcut for pic hold invoice
shortcuts.add('alt+z', function () {
    allSuspends();
});

//Key shortcut for focus search product input
shortcuts.add('alt+v', function () {
    document.getElementById('search_product').focus();
});

//Key shortcut for show recent transactions
shortcuts.add('alt+x', function () {
    showRecentTransectionModal();
});

$(document).on('click', '#show_stock', function (e) {
    e.preventDefault();
    showStock();
});

//Key shortcut for show current stock
shortcuts.add('alt+c', function () {
    showStock();
});

// After submitting form successfully this function will be executed.
function afterSubmitForm() {
    $('.modal').modal('hide');
    $('#pos_submit_form')[0].reset();
    $('.payment_method').hide();
    $('#product_list').empty();
    calculateTotalAmount();
    $('.submit_preloader').hide();
    var store_url = $('#store_url').val();
    $('#pos_submit_form').attr('action', store_url);
    activeSelectedItems();
}

$(document).keypress(".scanable", function (event) {
    if (event.which == '10' || event.which == '13') {
        event.preventDefault();
    }
});

$('#payment_method').on('change', function () {
    var value = $(this).val();
    $('.payment_method').hide();
    $('#' + value).show();
});

var tableRowIndex = 0;
$(document).on('click', '#delete', function (e) {
    e.preventDefault();
    var parentTableRow = $(this).closest('tr');
    tableRowIndex = parentTableRow.index();
    var url = $(this).attr('href');
    $('#deleted_form').attr('action', url);
    $.confirm({
        'title': 'Delete Confirmation',
        'content': 'Are you sure?',
        'buttons': {
            'Yes': {
                'class': 'yes btn-modal-primary',
                'action': function () { $('#deleted_form').submit(); $('#recent_trans_preloader').show(); }
            },
            'No': { 'class': 'no btn-danger', 'action': function () { console.log('Deleted canceled.') } }
        }
    });
});

//data delete by ajax
$(document).on('submit', '#deleted_form', function (e) {
    e.preventDefault();
    var url = $(this).attr('action');
    var request = $(this).serialize();
    $.ajax({
        url: url,
        type: 'post',
        data: request,
        success: function (data) {
            toastr.error(data);
            $('#transection_list tr:nth-child(' + (tableRowIndex + 1) + ')').remove();
            $('#recent_trans_preloader').hide();
            $('#suspendedSalesModal').modal('hide');
            $('#holdInvoiceModal').modal('hide');
        }
    });
});

$('.calculator-bg__main button').prop('type', 'button');

function activeSelectedItems() {
    $('.product-name').removeClass('ac_item');
    $('#product_list').find('tr').each(function () {
        var p_id = $(this).find('#product_id').val();
        var v_id = $(this).find('#variant_id').val();
        var id = p_id + v_id;
        $('#' + id).addClass('ac_item');
    });
}

// Add Pos Shortcut Menu Script
$(document).on('click', '#addPosShortcutBtn', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, function (data) {
        $('#modal-body_shortcuts').html(data);
        $('#shortcutMenuModal').modal('show');
    });
});

$(document).on('change', '#check_menu', function () {
    $('#add_pos_shortcut_menu').submit();
});

$(document).on('submit', '#add_pos_shortcut_menu', function (e) {
    e.preventDefault();
    var url = $(this).attr('action');
    var request = $(this).serialize();
    $.ajax({
        url: url,
        type: 'post',
        data: request,
        success: function (data) {
            allPosShortcutMenus();
        }
    });
});

// POS read manual button
$('#readDocument').click(function () {
    if ($('#readDocument div.doc').css('display', 'none')) {
        $('#readDocument div.doc').toggleClass('d-block')
    }
})

$(document).on('click', '#show_cost_button', function () {
    $('#show_cost_section').toggle(500);
});

