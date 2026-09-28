@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card border mb-3">
                <div class="card-body">
                    <form action="{{ route('admin.trustpilot.widget.submit') }}" method="post" class="disableSubmission">
                        @csrf
                        <div class="form-group mb-3">
                            <label>@lang('Widget Code')</label>
                            <textarea name="code" class="form-control" rows="10">{{ @gs('trustpilot_widget_code') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45 mt-3">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
