@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-12 text-end">
                <a href="{{ route('ticket.open') }}" class="btn btn--base btn--sm">
                    <i class="las la-plus"></i>
                    @lang('Create Ticket')
                </a>
            </div>
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-body p-0">
                        @if (!$supports->isEmpty())
                            <table class="table table--responsive--lg">
                                <thead>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Priority')</th>
                                    <th>@lang('Last Reply')</th>
                                    <th>@lang('Action')</th>
                                </thead>
                                <tbody>
                                    @foreach ($supports as $support)
                                        <tr>
                                            <td>{{ $loop->index + $supports->firstItem() }}</td>
                                            <td> <a href="{{ route('ticket.view', $support->ticket) }}" class="fw-bold">
                                                    [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }}
                                                </a>
                                            </td>
                                            <td>@php echo $support->statusBadge; @endphp</td>
                                            <td>@php echo $support->priorityBadge; @endphp</td>
                                            <td>{{ showDateTime($support->last_reply) }} </td>
                                            <td>
                                                <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn--outline-base btn-sm">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            @include($activeTemplate . 'partials.empty', [
                                'message' => 'Tickets not found!',
                            ])
                        @endif
                    </div>
                </div>
                @if ($supports->hasPages())
                    <div class="mt-3">
                        {{ paginateLinks($supports) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
