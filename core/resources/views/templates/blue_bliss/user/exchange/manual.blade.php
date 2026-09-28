@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card custom--card">
                    <div class="card-body">
                        <div class="mb-3"> @php echo @$exchange->sendCurrency->instruction; @endphp </div>
                        <form class="disableSubmission" method="POST" enctype="multipart/form-data">
                            @csrf
                            <x-viser-form identifier="id"
                                identifierValue="{{ @$exchange->sendCurrency->trx_proof_form_id }}" />
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
