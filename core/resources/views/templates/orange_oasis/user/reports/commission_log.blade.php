@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="custom--card card">
                    @if (!$logs->isEmpty())
                        <table class="table table--responsive--lg">
                            <thead>
                                <tr>
                                    <th>@lang('Commission From')</th>
                                    <th>@lang('Commission Level')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Transaction')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ __(@$log->userFrom->username) }}</td>
                                        <td>{{ __($log->level) }}</td>
                                        <td>{{ showAmount($log->amount) }}</td>
                                        <td>{{ __($log->title) }}</td>
                                        <td>{{ $log->trx }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        @include($activeTemplate . 'partials.empty', ['message' => 'Data not found!'])
                    @endif
                </div>
                @if ($logs->hasPages())
                    <div class="mt-3">
                        {{ paginateLinks($logs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
