@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <section class="mt-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6>@lang('Payment Method Settings') </h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body px-5">
                                    <form id="payment_method_settings_form" action="{{ route('settings.payment.method.settings.update') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <p class="m-0 p-0"><b> @lang('Business Location') :</b>
                                                @if (auth()->user()->branch_id)

                                                    {{ auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code }}
                                                @else

                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                @endif
                                            </p>
                                            <div class="form_element">
                                                <div class="element-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <table class="table table-sm">
                                                                        <thead>
                                                                            <th class="text-start">@lang('S/L')</th>
                                                                            <th class="text-start">@lang('Payment Method')</th>
                                                                            <th class="text-start">@lang('Default Account')</th>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($methods as $method)
                                                                                <tr>
                                                                                    <td class="text-start">
                                                                                        <b>{{ $loop->index + 1 }}.</b>
                                                                                    </td>
                                                                                    <td class="text-start">
                                                                                        {{ $method->name }}
                                                                                        <input type="hidden" name="method_ids[]" value="{{ $method->id }}">
                                                                                    </td>
                                                                                    <td class="text-start">
                                                                                        <select name="account_ids[]" class="form-control">
                                                                                            @foreach ($accounts as $ac)
                                                                                                @php
                                                                                                    $presettedAc = DB::table('payment_method_settings')
                                                                                                    ->where('payment_method_id', $method->id)
                                                                                                    ->where('branch_id', auth()->user()->branch_id)
                                                                                                    ->where('account_id', $ac->id)
                                                                                                    ->first();
                                                                                                @endphp
                                                                                                <option {{ $presettedAc ? 'SELECTED' : ''}} value="{{ $ac->id }}">
                                                                                                    @php
                                                                                                        $accountType = $ac->account_type == 1 ? ' (Type : Cash-In-Hand)' : ' (Type : Bank A/C)';
                                                                                                        $acNo = $ac->account_number ? ', (A/c No : '.$ac->account_number.')' : ', (A/c No : N/A';
                                                                                                        $bank = $ac->b_name ? ', (Bank : '.$ac->b_name.')' : ', (Bank : N/A)';
                                                                                                    @endphp
                                                                                                    {{ $ac->name . $accountType . $acNo . $bank}}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td colspan="2"></td>
                                                                                <td class="text-end"><button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i> <strong>@lang('Loading')...</strong> </button>
                                                                                    <button type="submit" class="btn btn-sm btn-success submit_button">@lang('Save')</button></td>
                                                                            </tr>
                                                                        </tfoot>

                                                                    </table>
                                                                    {{-- @foreach ($methods as $method)
                                                                        <div class="input-group mt-1">
                                                                            <label class="col-4"><b>{{ $loop->index + 1 }}. {{ $method->name }} :</b> </label>
                                                                            <input type="hidden" name="method_ids[]" value="{{ $method->id }}">
                                                                            <div class="col-8">
                                                                                <select name="account_ids[]" class="form-control">
                                                                                    @foreach ($accounts as $ac)
                                                                                        @php
                                                                                            $presettedAc = DB::table('payment_method_settings')
                                                                                            ->where('payment_method_id', $method->id)
                                                                                            ->where('branch_id', auth()->user()->branch_id)
                                                                                            ->where('account_id', $ac->id)
                                                                                            ->first();
                                                                                        @endphp
                                                                                        <option {{ $presettedAc ? 'SELECTED' : ''}} value="{{ $ac->id }}">
                                                                                            @php
                                                                                                $accountType = $ac->account_type == 1 ? ' (Type : Cash-In-Hand)' : ' (Type : Bank A/C)';
                                                                                                $acNo = $ac->account_number ? ', (A/c No : '.$ac->account_number.')' : ', (A/c No : N/A';
                                                                                                $bank = $ac->b_name ? ', (Bank : '.$ac->b_name.')' : ', (Bank : N/A)';
                                                                                            @endphp
                                                                                            {{ $ac->name . $accountType . $acNo . $bank}}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- <div class="row mt-2">
                                                        <div class="col-12 text-end">
                                                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i> <strong>@lang('Loading')...</strong> </button>
                                                            <button type="submit" class="btn btn-sm btn-success submit_button">@lang('Save')</button>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Add user by ajax
        $('#payment_method_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush
