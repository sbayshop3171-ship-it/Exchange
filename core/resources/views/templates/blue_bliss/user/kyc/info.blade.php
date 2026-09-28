@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-10">
                <div class="card custom--card">
                    <div class="card-body p-2">
                        @if ($user->kyc_data)
                            <ul class="list-group list-group-flush">
                                @foreach ($user->kyc_data as $val)
                                    @continue(!$val->value)
                                    <li class="list-group-item border-dotted d-flex flex-column flex-sm-row gap-2 flex-wrap justify-content-between p-3">
                                        <small class="text-muted">
                                            {{ __($val->name) }}
                                        </small>
                                        <span class="fw-bold text-muted">
                                            @if ($val->type == 'checkbox')
                                                {{ implode(',', $val->value) }}
                                            @elseif($val->type == 'file')
                                                <a href="{{ route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}"
                                                    class="text--base">
                                                    <i class="fa fa-file"></i> @lang('Attachment')
                                                </a>
                                            @else
                                                <p class="m-0">{{ __($val->value) }}</p>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <h5 class="text-center">@lang('KYC data not found')</h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
