    <script src="{{asset('/backend/js/jquery-1.7.1.min.js')}}"></script>
    <script src="{{asset('/backend/js/number-bdt-formater.js')}}"></script>
    <!--Jquery Cdn-->
    <script src="{{asset('/backend/asset/cdn/js/jquery-3.6.0.js')}}"></script>
    <!--Jquery Cdn End-->

    <script src="{{ asset('/backend/asset/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/backend/asset/js/jquery.fontstar.js') }}"></script>
    <script src="/assets/plugins/custom/print_this/printThis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!--Toaster.js js link-->
    <script src="/assets/plugins/custom/toastrjs/toastr.min.js"></script>
    <!--Toaster.js js link end-->


    <!-- DataTable Cdn -->
    <script type="text/javascript"  src="{{asset('/backend/asset/cdn/js/jquery.dataTables.min.js')}}"></script>
    <!-- DataTable Cdn End-->

    <script src="{{asset('/backend/js/bootstrap-dropdown.js')}}"></script>
    <script src="{{asset('/backend/js/TableTools.min.js')}}"></script>
    <script src="{{asset('/backend/js/jeditable.jquery.js')}}"></script>
    {{-- <script src="{{asset('/backend/js/custom-script.js')}}"></script> --}}
    <script src="{{asset('/backend/asset/js/main.js')}}"></script>
    {{-- <script src="{{asset('/backend/asset/js/SimpleCalculadorajQuery.js')}}" defer></script> --}}
    <script>
        toastr.options = {"positionClass": "toast-top-center",}
        $(document).on('click', '#logout_option',function(e){
            e.preventDefault();
            $.confirm({
                'title': "@lang('Logout Confirmation')",
                'content': "@lang('Are you sure, you want to logout?')",
                'buttons': {
                    @lang("YES"): {'btnClass': 'yes btn-modal-primary','action': function() {$('#logout_form').submit();}},
                    @lang("NO"): {'btnClass': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        $(document).on('click', '.display tbody tr', function () {
            $('.display tbody tr').removeClass('active_tr');
            $(this).addClass('active_tr');
        });

        $(document).on('click', '#hard_reload', function () {
            window.location.reload(true);
        });
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.js"></script>
