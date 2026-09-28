@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10 col-11">
                @if ($user->kyc_data)
                    <div class="card custom--card">
                        <div class="card-body">
                            <ul class="list-group list-group-flush p-0">
                                @foreach ($user->kyc_data as $val)
                                    @continue(!$val->value)
                                    <li class="list-group-item d-flex flex-column border-0 px-0">
                                        <h6>
                                            @if ($val->type == 'checkbox')
                                                {{ implode(',', $val->value) }}
                                            @elseif($val->type == 'file')
                                                <a class="text--base fw-bold"
                                                    href="{{ route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}">
                                                    <i class="fa fa-file"></i> @lang('Attachment')
                                                </a>
                                            @else
                                                <p>{{ __($val->value) }}</p>
                                            @endif
                                        </h6>
                                        <small>{{ __($val->name) }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    <h5 class="text-center bg-white px-2 py-5">@lang('No data submitted yet')</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
