@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form action="{{ route('sales.cash.register.store') }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form_element">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="text-primary">@lang('Open Cash Register')</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Opeing Balance') :</b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input required type="number" step="any" name="cash_in_hand" class="form-control" placeholder="@lang('Enter Amount')" value="0.00">
                                                        <span class="error">{{ $errors->first('cash_in_hand') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('Cash Counter') :</b> </label>
                                                    <div class="col-8">
                                                        <select required name="counter_id" class="form-control">
                                                            <option value="">@lang('Select Cash Counter')</option>
                                                            @foreach ($cashCounters as $cc)
                                                                <option {{ old('counter_id') == $cc->id ? 'SELECTED' : '' }}
                                                                    value="{{ $cc->id }}">{{ $cc->counter_name.' ('.$cc->short_name.')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error">{{ $errors->first('counter_id') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('Business Location') :</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('Sale Account') :</b> </label>
                                                    <div class="col-8">
                                                        <select required name="sale_account_id" class="form-control add_input"
                                                        id="sale_account_id" data-name="Sale A/C">
                                                            @foreach ($saleAccounts as $saleAccount)
                                                                <option value="{{ $saleAccount->id }}">
                                                                    {{ $saleAccount->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error">{{ $errors->first('sale_account_id') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="submitBtn">
                                            <div class="row justify-content-center">
                                                <div class="col-12 text-end">
                                                    <button type="submit" class="btn btn-sm btn-success ">
                                                        <b>@lang('Submit')</b>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')

@endpush
