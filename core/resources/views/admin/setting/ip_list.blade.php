@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('IP Address')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ips as $ip)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td><span class="fw-bold">{{ $ip->ip_address }}</span></td>
                                        <td>{{ diffForHumans($ip->created_at) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--danger ipAddress" data-bs-toggle="modal"
                                                data-id="{{ $ip->id }}" data-bs-target="#ipDeleteModal">
                                                <i class="las la-lock-open"></i> @lang('Unblock')
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-muted text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ipDeleteModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ipModalLabel">@lang('Unblock IP')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.blocked.ip.delete') }}" method="post" class="disableSubmission">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="">
                        <p>@lang('Are you sure to unblock this IP address from block list')?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--danger w-100 h-45">@lang('Unblock')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModal">@lang('Blocked IP Address')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.blocked.ip.submit') }}" method="post" class="disableSubmission">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <p class="p-3 bg--warning text-dark">
                                @lang("Please exercise caution and ensure the accuracy of the IP address before proceeding. Once added to the block list, users or anyone associated with this IP address will be denied access to your platform. Be careful don't block your IP address any more.")
                            </p>
                        </div>
                        <div class="form-group">
                            <label>@lang('IP Address')</label>
                            <input type="text" name="ip_address"  required class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="IP Address" dateSearch='yes' />

    <button type="button" class="btn btn-sm btn-outline--primary float-end openForm h-45">
        <i class="la la-fw la-plus"></i>
        @lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        "use strict"
        $(document).ready(function() {
            $('.ipAddress').on('click', function() {
                let id = $(this).data('id');
                $("input[name='id']").val(id);
            });

            $('.openForm').on('click', function(){
                let modal = $('#createModal');
                modal.modal('show');
            });
        });
    </script>
@endpush
