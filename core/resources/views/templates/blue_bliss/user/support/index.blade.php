@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card custom--card">
                    @if (!$supports->isEmpty())
                        <div class="card-body p-0">
                            <table class="table custom--table table-responsive--md">
                                <thead>
                                    <tr>
                                        <th>@lang('S.N.')</th>
                                        <th>@lang('Subject')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Priority')</th>
                                        <th>@lang('Last Reply')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supports as $support)
                                        <tr>
                                            <td>{{ $loop->index + $supports->firstItem() }}</td>
                                            <td>
                                                <a href="{{ route('ticket.view', $support->ticket) }}" class="fw-bold">
                                                    [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }}
                                                </a>
                                            </td>
                                            <td> @php echo $support->statusBadge; @endphp </td>
                                            <td> @php echo $support->priorityBadge; @endphp </td>
                                            <td>{{ diffForHumans($support->last_reply) }} </td>
                                            <td>
                                                <a href="{{ route('ticket.view', $support->ticket) }}"
                                                    class="btn btn--base-outline btn-sm">
                                                    <i class="fa fa-desktop"></i> @lang('Details')
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @include($activeTemplate . 'partials.empty', [
                            'message' => 'No Support Ticket Found!',
                        ])
                    @endif
                </div>
                @if ($supports->hasPages())
                    <div class="py-3 custom__paginate">
                        {{ paginateLinks($supports) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
