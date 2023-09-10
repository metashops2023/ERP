<div class="row">
    <div class="pos-footer">
        <span class="float-start text-white mt-3 badge bg-primary">@lang('Software By') <b>@lang('SpeedDigit Pvt'). Ltd.</b> </span>
        @if (json_decode($generalSettings->pos, true)['is_show_recent_transactions'] == '0')
            <div class="pos-foot-con">
                <a href="#" class="resent-tn"><span class="fas fa-clock" tabindex="-1"></span>@lang('Recent Transection')</a>
            </div>
        @endif
    </div>
</div>

<script>
    $(document).on('click', '.resent-tn',function (e) {
        e.preventDefault();

        showRecentTransectionModal();
    });

    function showRecentTransectionModal() {

        recentSales();
        $('#recentTransModal').modal('show');
        $('.tab_btn').removeClass('tab_active');
        $('#tab_btn').addClass('tab_active');
    }

    function recentSales() {

        $('#recent_trans_preloader').show();
        $.ajax({
            url:"{{url('sales/pos/recent/sales')}}",
            type:'get',
            success:function(data){

                $('#transection_list').html(data);
                $('#recent_trans_preloader').hide();
            }
        });
    }

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();
        $('#recent_trans_preloader').show();
        var url = $(this).attr('href');

        $.ajax({
            url:url,
            type:'get',
            success:function(data){

                $('#transection_list').html(data);
                $('#recent_trans_preloader').hide();
            }
        });

        $('.tab_btn').removeClass('tab_active');
        $(this).addClass('tab_active');
    });

    $(document).on('click', '#only_print', function (e) {
        e.preventDefault();

        var url = $(this).attr('href');
        $.get(url, function(data) {
            $(data).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",
                removeInline: false,
                printDelay: 500,
                header : null,
                footer : null,
            });
        });
    });
</script>
