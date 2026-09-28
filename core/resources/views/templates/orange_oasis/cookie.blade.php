@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-80 pb-80 bg--light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                @php echo @$cookie->data_values->description ; @endphp
            </div>
        </div>
    </div>
</div>
@endsection
