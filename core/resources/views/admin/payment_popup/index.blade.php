@extends('admin.layouts.app')
@section('panel')
    <div class="card mb-4"><div class="card-header"><h5 class="mb-0">@lang('Global Verification Settings')</h5></div><form action="{{ route('admin.payment.popup.settings') }}" method="POST"><div class="card-body row g-3">@csrf
        <div class="col-md-3"><label>@lang('Unlock Fee Amount')</label><input type="number" step="any" min="0" name="unlock_fee_amount" class="form-control" value="{{ $settings->unlock_fee_amount }}" required></div>
        <div class="col-md-3"><label>@lang('Fee Currency Code/Symbol')</label><input type="text" name="fee_currency" class="form-control" value="{{ $settings->fee_currency }}" required></div>
        <div class="col-md-3"><label>@lang('Require Transaction ID')</label><input type="checkbox" name="is_trx_required" value="1" {{ $settings->is_trx_required ? 'checked' : '' }} data-bs-toggle="toggle" data-on="Yes" data-off="No"></div>
        <div class="col-md-3"><label>@lang('Require Payment Proof')</label><input type="checkbox" name="is_proof_required" value="1" {{ $settings->is_proof_required ? 'checked' : '' }} data-bs-toggle="toggle" data-on="Yes" data-off="No"></div>
        <div class="col-md-3"><label>@lang('Enable VAT')</label><input type="checkbox" name="vat_enabled" value="1" {{ $settings->vat_enabled ? 'checked' : '' }} data-bs-toggle="toggle" data-on="Yes" data-off="No"></div>
        <div class="col-md-3"><label>@lang('VAT Rate (%)')</label><input type="number" step="0.001" min="0" name="vat_rate" class="form-control" value="{{ $settings->vat_rate }}"></div>
        <div class="col-md-3"><label>@lang('Enable Fixed Fee')</label><input type="checkbox" name="fixed_fee_enabled" value="1" {{ $settings->fixed_fee_enabled ? 'checked' : '' }} data-bs-toggle="toggle" data-on="Yes" data-off="No"></div>
        <div class="col-md-3"><label>@lang('Fixed Fee Amount')</label><input type="number" step="any" min="0" name="fixed_fee_amount" class="form-control" value="{{ $settings->fixed_fee_amount }}"></div>
        <div class="col-12 text-end"><button class="btn btn--primary">@lang('Save Global Settings')</button></div>
    </div></form></div>
    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card b-radius--10">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">@lang('Payment Gateways')</h5>
                    <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#gatewayModal">
                        <i class="las la-plus"></i> @lang('Add Gateway')
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Symbol')</th>
                                <th>@lang('Wallet')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($gateways as $gateway)
                                <tr>
                                    <td>{{ __($gateway->name) }}</td>
                                    <td>{{ __($gateway->symbol) }}</td>
                                    <td>{{ __($gateway->wallet_address ?: '-') }}</td>
                                    <td>{!! $gateway->status ? '<span class="badge badge--success">Active</span>' : '<span class="badge badge--danger">Inactive</span>' !!}</td>
                                    <td>
                                        <div class="button--group">
                                            <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#gatewayEditModal{{ $gateway->id }}">
                                                <i class="las la-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure you want to remove this gateway?')" data-action="{{ route('admin.payment.popup.delete', $gateway->id) }}">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center text-muted">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h5 class="mb-0">@lang('Pending Verification Requests')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Method')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ __(@$request->user->fullname) }}</span><br>
                                        <small>@ {{ __(@$request->user->username) }}</small>
                                    </td>
                                    <td>{{ __($request->method) }}<br><small>{{ __(@$request->gateway->name) }}</small></td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge badge--warning">Pending</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge badge--success">Approved</span>
                                        @else
                                            <span class="badge badge--danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Approve this verification request?')" data-action="{{ route('admin.payment.popup.approve', $request->id) }}">
                                                    <i class="las la-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Reject this verification request?')" data-action="{{ route('admin.payment.popup.reject', $request->id) }}">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center text-muted">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gatewayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Payment Gateway')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
                </div>
                <form action="{{ route('admin.payment.popup.store') }}" method="POST" enctype="multipart/form-data" class="disableSubmission">
                    @csrf
                    <div class="modal-body row g-3">
                        <div class="col-md-6 form-group">
                            <label>@lang('Gateway Name')</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('Symbol')</label>
                            <input type="text" name="symbol" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('Network / Type')</label>
                            <input type="text" name="network" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('Wallet Address')</label>
                            <input type="text" name="wallet_address" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>@lang('QR Code Image')</label>
                            <input type="file" name="qr_code_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 form-group"><label>@lang('Require Transaction ID')</label><input type="checkbox" name="is_trx_required" value="1" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"></div>
                        <div class="col-md-6 form-group"><label>@lang('Require Payment Proof')</label><input type="checkbox" name="is_proof_required" value="1" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"></div>
                        <div class="col-md-6 form-group"><label>@lang('Gateway Fee Override (Optional)')</label><input type="number" step="any" min="0" name="unlock_fee_amount" class="form-control" placeholder="Leave blank to use global fee"></div>
                        <div class="col-md-6 form-group"><label>@lang('Gateway Currency Override (Optional)')</label><input type="text" name="fee_currency" class="form-control" placeholder="Leave blank to use global currency"></div>
                        <div class="col-md-12 form-group">
                            <label>@lang('Enabled')</label>
                            <input type="checkbox" name="status" value="1" checked data-bs-toggle="toggle" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-on="@lang('Yes')" data-off="@lang('No')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($gateways as $gateway)
        <div class="modal fade" id="gatewayEditModal{{ $gateway->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Edit Payment Gateway')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
                    </div>
                    <form action="{{ route('admin.payment.popup.update', $gateway->id) }}" method="POST" enctype="multipart/form-data" class="disableSubmission">
                        @csrf
                        <div class="modal-body row g-3">
                            <div class="col-md-6 form-group">
                                <label>@lang('Gateway Name')</label>
                                <input type="text" name="name" class="form-control" value="{{ $gateway->name }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Symbol')</label>
                                <input type="text" name="symbol" class="form-control" value="{{ $gateway->symbol }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Network / Type')</label>
                                <input type="text" name="network" class="form-control" value="{{ $gateway->network }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Wallet Address')</label>
                                <input type="text" name="wallet_address" class="form-control" value="{{ $gateway->wallet_address }}">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>@lang('QR Code Image')</label>
                                <input type="file" name="qr_code_image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6 form-group"><label>@lang('Require Transaction ID')</label><input type="checkbox" name="is_trx_required" value="1" {{ $gateway->is_trx_required ? 'checked' : '' }} data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"></div>
                            <div class="col-md-6 form-group"><label>@lang('Require Payment Proof')</label><input type="checkbox" name="is_proof_required" value="1" {{ $gateway->is_proof_required ? 'checked' : '' }} data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"></div>
                            <div class="col-md-6 form-group"><label>@lang('Gateway Fee Override (Optional)')</label><input type="number" step="any" min="0" name="unlock_fee_amount" class="form-control" value="{{ $gateway->unlock_fee_amount }}" placeholder="Leave blank to use global fee"></div>
                            <div class="col-md-6 form-group"><label>@lang('Gateway Currency Override (Optional)')</label><input type="text" name="fee_currency" class="form-control" value="{{ $gateway->fee_currency }}" placeholder="Leave blank to use global currency"></div>
                            <div class="col-md-12 form-group">
                                <label>@lang('Enabled')</label>
                                <input type="checkbox" name="status" value="1" {{ $gateway->status ? 'checked' : '' }} data-bs-toggle="toggle" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-on="@lang('Yes')" data-off="@lang('No')">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--primary">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#gatewayModal">
        <i class="las la-plus"></i>@lang('Add Gateway')
    </button>
@endpush
