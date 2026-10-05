@extends('admin.layouts.app')

@section('panel')
    <div class="card b-radius--10">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">@lang('Subscription Plans')</h5>
                <small class="text-muted">@lang('Plans appear in Payment Verification after the user selects a payment gateway.')</small>
            </div>
            <button class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#addPlanModal">
                <i class="las la-plus"></i> @lang('Add Plan')
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--light style--two mb-0">
                    <thead><tr><th>@lang('Plan')</th><th>@lang('Duration')</th><th>@lang('Please Pay')</th><th>@lang('Status')</th><th>@lang('Actions')</th></tr></thead>
                    <tbody>
                    @forelse($plans as $plan)
                        <tr>
                            <td class="fw-bold">{{ $plan->name }}</td>
                            <td>{{ $plan->duration_days }} @lang('day(s)')</td>
                            <td>{{ rtrim(rtrim(number_format((float) $plan->amount, 8, '.', ''), '0'), '.') }} {{ $plan->currency }}</td>
                            <td>{!! $plan->status ? '<span class="badge badge--success">Active</span>' : '<span class="badge badge--danger">Inactive</span>' !!}</td>
                            <td>
                                <div class="button--group">
                                    <button class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#editPlan{{ $plan->id }}"><i class="las la-edit"></i></button>
                                    <form action="{{ route('admin.payment.popup.plans.status', $plan->id) }}" method="POST">@csrf<button class="btn btn-sm btn-outline--warning">{{ $plan->status ? __('Disable') : __('Enable') }}</button></form>
                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Delete this subscription plan?')" data-action="{{ route('admin.payment.popup.plans.delete', $plan->id) }}"><i class="las la-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="editPlan{{ $plan->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">@lang('Edit Subscription Plan')</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <form action="{{ route('admin.payment.popup.plans.update', $plan->id) }}" method="POST">@csrf
                                    <div class="modal-body row g-3">
                                        <div class="col-12"><label class="form-label">@lang('Plan Name')</label><input name="name" class="form-control" value="{{ $plan->name }}" required maxlength="100"></div>
                                        <div class="col-md-4"><label class="form-label">@lang('Duration (days)')</label><input type="number" name="duration_days" class="form-control" min="1" value="{{ $plan->duration_days }}" required></div>
                                        <div class="col-md-4"><label class="form-label">@lang('Amount')</label><input type="number" name="amount" class="form-control" min="0" step="any" value="{{ $plan->amount }}" required></div>
                                        <div class="col-md-4"><label class="form-label">@lang('Currency')</label><input name="currency" class="form-control" value="{{ $plan->currency }}" maxlength="20" required></div>
                                        <div class="col-12"><label><input type="checkbox" name="status" value="1" @checked($plan->status)> @lang('Active / visible to users')</label></div>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn--secondary" data-bs-dismiss="modal">@lang('Cancel')</button><button class="btn btn--primary">@lang('Save Changes')</button></div>
                                </form>
                            </div></div>
                        </div>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">@lang('No subscription plans have been added yet.')</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">@lang('Add Subscription Plan')</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('admin.payment.popup.plans.store') }}" method="POST">@csrf
                <div class="modal-body row g-3">
                    <div class="col-12"><label class="form-label">@lang('Plan Name')</label><input name="name" class="form-control" placeholder="e.g. 1 Day Subscription" required maxlength="100"></div>
                    <div class="col-md-4"><label class="form-label">@lang('Duration (days)')</label><input type="number" name="duration_days" class="form-control" min="1" required></div>
                    <div class="col-md-4"><label class="form-label">@lang('Amount')</label><input type="number" name="amount" class="form-control" min="0" step="any" required></div>
                    <div class="col-md-4"><label class="form-label">@lang('Currency')</label><input name="currency" class="form-control" value="USD" maxlength="20" required></div>
                    <div class="col-12"><label><input type="checkbox" name="status" value="1" checked> @lang('Active / visible to users')</label></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn--secondary" data-bs-dismiss="modal">@lang('Cancel')</button><button class="btn btn--primary">@lang('Create Plan')</button></div>
            </form>
        </div></div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#addPlanModal"><i class="las la-plus"></i> @lang('Add Plan')</button>
@endpush
