@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card custom--card">
                    <div class="card-body">
                        <div class="mb-3"> @php echo @$exchange->sendCurrency->instruction; @endphp </div>
                        <form method="POST" enctype="multipart/form-data" class="disableSubmission">
                            @csrf
                            <x-viser-form identifier="id" identifierValue="{{ @$exchange->sendCurrency->trx_proof_form_id }}" />
                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
