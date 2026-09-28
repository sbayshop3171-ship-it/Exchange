@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Buy At')</th>
                                    <th>@lang('Sell At')</th>
                                    <th>@lang('Reserve Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($currencies as $currency)
                                    <tr>
                                        <td>
                                            <div class="user">
                                                <div class="thumb">
                                                    <img
                                                        src="{{ getImage(getFilePath('currency') . '/' . $currency->image, getFileSize('currency')) }}">
                                                </div>
                                                <span class="name">
                                                    {{ __($currency->name) }} - {{ __($currency->cur_sym) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>{{ showAmount($currency->buy_at) }}</td>
                                        <td>{{ showAmount($currency->sell_at) }}</td>
                                        <td>
                                            {{ showAmount($currency->reserve, currencyFormat: false) }}
                                            {{ $currency->cur_sym }}
                                        </td>
                                        <td> @php echo $currency->statusBadge; @endphp </td>
                                        <td>
                                            <button aria-expanded="false" class="btn btn-outline--primary btn-sm"
                                                data-bs-toggle="dropdown" type="button">
                                                <i class="las la-ellipsis-v"></i>@lang('More')
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="{{ route('admin.currency.edit', $currency->id) }}"
                                                    class="dropdown-item">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </a>
                                                <a href="{{ route('admin.currency.transaction.proof.form', $currency->id) }}"
                                                    class="dropdown-item">
                                                    <i class="lab la-wpforms"></i> @lang('Transaction Proof Form')
                                                </a>
                                                <a href="{{ route('admin.currency.sending.form', $currency->id) }}"
                                                    class="dropdown-item">
                                                    <i class="las la-paste"></i> @lang('Sending Form')
                                                </a>
                                                @if ($currency->status == Status::DISABLE)
                                                    <button class="dropdown-item confirmationBtn"
                                                        data-action="{{ route('admin.currency.status', $currency->id) }}"
                                                        data-question="@lang('Are you sure to enable this currency?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="dropdown-item  confirmationBtn"
                                                        data-action="{{ route('admin.currency.status', $currency->id) }}"
                                                        data-question="@lang('Are you sure to disable this currency?')">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($currencies->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($currencies) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />

    <div class="modal fade" id="currencyApiModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="currencyApiModalLabel">@lang('Currency API Key')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i></button>
                </div>
                <form action="{{ route('admin.currency.api.update') }}" method="post" class="disableSubmission">
                    @csrf
                    <div class="modal-body">
                        <div class="row form-group">
                            <div class="justify-content-between d-flex flex-wrap">
                                <label>@lang('Currency Rate API Key')</label>
                                <div>
                                    <small>@lang('For the API key') : </small>
                                    <u>
                                        <a target="_blank" class="text--primary" href="https://app.exchangerate-api.com">
                                            @lang('Exchange Rate API')
                                        </a>
                                    </u>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="api_key"
                                    required value="{{ gs('currency_api_key') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45" id="btn-save" value="add">
                            @lang('Submit')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form />
    <a class="btn btn-outline--primary" href="{{ route('admin.currency.create') }}">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
    <button class="btn btn-outline--dark h-45 me-2" data-bs-toggle="modal" data-bs-target="#currencyApiModal">
        <i class="las la-key"></i>@lang('Currency API Key')
    </button>
@endpush
