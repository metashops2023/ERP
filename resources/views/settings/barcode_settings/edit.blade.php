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
            <form id="edit_barcode_settings_form" action="{{ route('settings.barcode.update', $bs->id) }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-4">
                                    <div class="py-2 px-2 form-header">
                                        <div class="row">
                                            <div class="col-8"><h5>@lang('Edit barcode sticker setting')</h5></div>
                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('Setting Name') :</b> <span class="text-danger">*
                                                    </span></label>
                                                    <div class="col-8">
                                                        <input type="text" name="name" class="form-control" id="name"
                                                            placeholder="@lang('Sticker Sheet setting Name')" autofocus value="{{ $bs->name }}">
                                                            <span class="error error_name"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('Setting Description') :</b> </label>

                                                    <div class="col-8">
                                                        <textarea class="form-control" name="description" id="" cols="10" rows="3" placeholder="@lang('Sticker Sheet setting Description')">{{ $bs->description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $bs->is_continuous == 1 ? 'CHECKED' : '' }} name="is_continuous" id="is_continuous">
                                                    <b>@lang('Continous feed or rolls')</b>
                                                </p>
                                            </div>
                                        </div>


                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Top Margin (Inc)') : <span class="text-danger">*
                                                    </span> </b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-arrow-up input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="top_margin" id="top_margin" placeholder="@lang('Additional Top Margin')" value="{{ $bs->top_margin }}">
                                                        </div>
                                                        <span class="error error_top_margin"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Left Margin (Inc)') : <span class="text-danger">*
                                                    </span> </b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-arrow-left input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="left_margin" id="left_margin" placeholder="@lang('Additional Left Margin')" value="{{ $bs->left_margin }}">
                                                        </div>
                                                        <span class="error error_top_margin"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Sticker Width (Inc)') : <span class="text-danger">*
                                                    </span> </b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-text-width input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="sticker_width" id="sticker_width" placeholder="@lang('Sticker Width')" value="{{ $bs->sticker_width }}">
                                                        </div>
                                                        <span class="error error_sticker_width"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Sticker Height (Inc)') :<span class="text-danger">*
                                                    </span></b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-text-height input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="sticker_height" id="sticker_height" placeholder="@lang('Sticker Height')" value="{{ $bs->sticker_height }}">
                                                        </div>
                                                        <span class="error error_sticker_height"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Paper Width (Inc)') : <span class="text-danger">*
                                                    </span> </b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-text-width input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="paper_width" id="paper_width" placeholder="@lang('Paper Width')" value="{{ $bs->paper_width }}">
                                                        </div>
                                                        <span class="error error_paper_width"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Paper Height (Inc)') : <span class="text-danger">*
                                                    </span></b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-text-height input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="paper_height" id="paper_height" placeholder="@lang('Paper Height')" value="{{ $bs->paper_height }}">
                                                        </div>
                                                        <span class="error error_paper_height"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Row Distance (Inc)') :<span class="text-danger">*
                                                    </span> </b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-arrows-alt-v input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="row_distance" id="row_distance" placeholder="@lang('Row Distance')" value="{{ $bs->row_distance }}">
                                                        </div>
                                                        <span class="error error_row_distance"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Col Distance (Inc)') : <span class="text-danger">*
                                                    </span></b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-arrows-alt-h input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="column_distance" id="column_distance" placeholder="@lang('Colunmns Distance')" value="{{ $bs->column_distance }}">
                                                        </div>
                                                        <span class="error error_column_distance"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('Stickers In a Row') :<span class="text-danger">*
                                                    </span> </b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-th input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="stickers_in_a_row" id="stickers_in_a_row" placeholder="@lang('Stickers In a Row')" value="{{ $bs->stickers_in_a_row }}">
                                                        </div>
                                                        <span class="error error_stickers_in_a_row"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>No. of Stickers per sheet : <span class="text-danger">*
                                                    </span></b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-braille input_i"></i></span>
                                                            </div>
                                                            <input type="number" step="any" class="form-control" name="stickers_in_one_sheet" id="stickers_in_one_sheet" placeholder="@lang('No. of Stickers per sheet')" value="{{ $bs->stickers_in_one_sheet }}">
                                                        </div>
                                                        <span class="error error_stickers_in_one_sheet"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="submit-area py-3 mb-4">
                                    <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                    <button class="btn btn-sm btn-success submit_button float-end">@lang('Update')</button>
                                </div>
                            </div>
                        </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    // Add user by ajax
    $(document).on('submit', '#edit_barcode_settings_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        $('.submit_button').prop('type', 'button');
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.submit_button').prop('type', 'submit');
                toastr.success(data);
                $('.loading_button').hide();
                window.location = "{{ route('settings.barcode.index') }}";
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
@endpush
