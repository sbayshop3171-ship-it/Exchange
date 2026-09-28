@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pt-80 pb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    @php  echo @$policy->data_values->description; @endphp
                </div>
            </div>
        </div>
    </div>
@endsection
