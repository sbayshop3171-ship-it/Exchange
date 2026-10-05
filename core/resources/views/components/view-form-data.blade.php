@php
    $viewData = $data;
    if (is_string($viewData)) {
        $decoded = json_decode($viewData, true);
        $viewData = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
    }
    $viewData = is_array($viewData) || $viewData instanceof \Traversable ? $viewData : [];
@endphp
@if ($viewData)
    @foreach ((array) $viewData as $k => $item)
        <div class="row mb-3">
            <div class="col-md-12">
                @if (is_object($item) || is_array($item))
                    @php
                        $itemName = is_array($item) ? ($item['name'] ?? $k) : ($item->name ?? $k);
                        $itemType = is_array($item) ? ($item['type'] ?? 'text') : ($item->type ?? 'text');
                        $itemValue = is_array($item) ? ($item['value'] ?? '') : ($item->value ?? '');
                    @endphp
                    <h6>{{ __(keyToTitle($itemName)) }}</h6>
                @else
                    @php $itemType = 'text'; $itemValue = $item; @endphp
                    <h6>{{ __(keyToTitle($k)) }}</h6>
                @endif
                @if ($itemType == 'checkbox')
                    {{ is_array($itemValue) ? implode(',', $itemValue) : $itemValue }}
                @elseif($itemType == 'file')
                    @if ($itemValue)
                        @if (auth()->guard('admin')->user())
                            <a href="{{ route('admin.download.attachment', encrypt(getFilePath('verify') . '/' . $itemValue)) }}"
                                class="me-3"><i class="fa fa-file"></i> @lang('Attachment') </a>
                        @else
                            <a href="{{ route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $itemValue)) }}"
                                class="me-3"><i class="fa fa-file"></i> @lang('Attachment') </a>
                        @endif
                    @else
                        @lang('No file or file path not found....')
                    @endif
                @else
                    <p>{{ is_array($itemValue) ? implode(',', $itemValue) : __($itemValue) }}</p>
                @endif
            </div>
        </div>
    @endforeach
@endif
