<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/backend/asset/css/fontawesome/css/all.min.css') }}">
@if($rtl)
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous"> -->

<link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.rtl.min.css') }}">
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-+qdLaIRZfNu4cVPK/PxJJEy0B0f3Ugv8i482AKY7gwXwhaCroABd086ybrVKTa0q" crossorigin="anonymous"> -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic&display=swap" rel="stylesheet">

@else
<link rel="stylesheet" href="{{ asset('/backend/asset/css/bootstrap.min.css') }}">
@endif

{{-- <link rel="stylesheet" href="{{ asset('/backend/asset/css/calculator.css') }}"> --}}
<link href="{{ asset('/backend/css/reset.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/backend/css/typography.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/backend/css/body.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/backend/css/data-table.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/backend/css/form.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/backend/css/wizard.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/backend/css/sprite.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/backend/css/gradient.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('/backend/asset/css/comon.css') }}">
<link rel="stylesheet" href="{{ asset('/backend/asset/css/layout.css') }}">
<link rel="stylesheet" href="{{ asset('/backend/asset/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('/backend/asset/css/theme.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
{{-- DataTable Global CSS --}}
<style>
    .dt-buttons {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
        float: right;
        margin-right: 16px;
    }

    .dt-buttons button {
        padding: 0px 8px !important;
        margin-top: 9px;
        margin-bottom: 3px;
        border: 0px solid transparent;
        background-color: #4e9ff3;
        color: white;
        border-radius: 3px;
        font-size: 10px;
        height: 20px;
        line-height: 20px;
        font-weight: 600;
    }

    .mtr-table {
        margin-top: -13px;
        padding-left: 8px;
        padding-right: 8px;
        margin-bottom: 5px !important;
    }

    .dataTables_paginate {
        padding-right: 8px;
        padding-left: 8px;
        padding-top: 9px;
        font-family: monospace;
    }

    .dataTables_paginate a.paginate_button {
        padding: 2px 8px;
        background: #65667b;
        margin: 3px;
        color: #fff !important;
        cursor: pointer;
    }

    .dataTables_paginate a.paginate_button.current {
        background: #6083b1;
    }

    .disabled {
        color: #fff;
        background: #828d9bb6 !important;
    }

    .sub-menu-width .switch_text {
        font-size: 10px !important;
        /* font-family: monospace; */
        /* text-align: left; */
    }

    body {
        overflow-x: hidden;
    }

    .dataTables_filter input {
        padding-right: 8px;
        padding-left: 26px;
    }

    .dataTables_filter {
        width: 65%;
    }

    .dataTables_length {
        padding-right: 15px;
    }

    /* .dataTables_info {width: 60%;} */
    .text-custom-blue {
        color: #6083b1;
    }

    .monospace {
        font-family: monospace;
    }

    @media screen and (min-width: 960px) {
        #dashboard-8 .main__nav {
            overflow-y: hidden;
        }
    }

    select.month-item-year {
        width: 100px;
        font-size: 14px;
    }

    select.month-item-name {
        font-size: 13px;
    }

    .widget_content .table-responsive {
        min-height: 60vh !important;
    }

    #readDocument>div {
        width: 200px;
        height: 500px;
        top: 30px;
        left: 250px;
        background: #481530;
        border-radius: 4px;
        box-shadow: 0 3px 1px #000, 0 5px 5px 0 rgb(0 0 0)
    }

    #readDocument>div ul li {
        text-align: left;
        display: flex;
        margin-bottom: 3px
    }

    #readDocument>div ul li span.icon {
        font-size: 10px;
        background: #fff;
        text-align: center;
        padding: 1px 6px;
        color: #000;
        border-radius: 3px;
        margin-right: 7px;
        width: 50px;
        font-weight: 600
    }

    /* RTL */
    :root {
        --red-color-gradient: linear-gradient(#8c0437ee, #1e000d);
        --red-color-1: #7e0d3d;
        --red-color-2: #5d1b3f;
        --blue-color-gradient: linear-gradient(#036dad 0%, #29b0fd 100%);
        --blue-color-1: #29b0fd;
        --blue-color-2: #036dad;
        --dark-color-gradient: linear-gradient(#bd5e1e, #ff934b);
        --dark-color-1: #bd5e1e;
        --dark-color-2: #ff934b;
        --light-color-gradient: linear-gradient(#e1e1e1, #989898);
        --light-color-1: #c4c4c4;
        --light-color-2: #989898;
        --white-color: #fff;
        --black-color: #000;
        --black-color-1: #000;
        --bs-primary: var(--dark-color-gradient) !important;
        --bs-blue: var(--dark-color-gradient) !important;
    }

    .dark-theme .top-icon {
        border: #ffffff 2px solid;
        background: #ff934b47;
    }

    .rtl .navigation {
        right: 142px;
        padding-right: 0px;
        padding-left: 142px !important;
        margin-left: 0px !important;
    }

    .rtl .category-bar {
        margin-right: 80px;
        margin-left: 6px;
    }

    .rtl .sub-menu_t {
        right: 0px !important;
        left: 142px;
        padding-right: 142px;
    }

    .rtl .main-woaper {
        padding-left: 0px !important;
        padding-right: 142px !important;
    }

    .rtl .logo__sec {
        display: inline-block;
        float: right;
        margin-right: 5px;
    }

    .rtl .name-head span {
        margin-left: 5px;
    }
    .rtl .dropdown-item span, .rtl .dropdown-item i {
        padding: 0px 5px;
    }

    .rtl #calculatorModal.modal.show .modal-dialog {
        top: 24px;
        left: 6%;
        right: auto;
    }
    .dark-theme .main__nav {
        background: var(--dark-color-gradient);
    }

    .rtl .main__nav ul li {
        /* border-left: 1px solid var(--dark-color-2); */
        border-right: 0px;
    }

    .rtl .sub-menu-width .switch_text {
        font-size: 14px !important;
    }
    .rtl .section-header span {
        padding-left: 9px;
    }

    html {
        color: red;
    }
    .rtl {
        font-family: Arial, 'Noto Naskh Arabic', serif;
    }

</style>
