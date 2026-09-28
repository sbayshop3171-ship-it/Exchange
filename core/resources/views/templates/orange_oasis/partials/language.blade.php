@if (gs('multi_language'))
    @php
        $language = App\Models\Language::all();
        $activeLanguage = App\Models\Language::where('code', session('lang'))->first();
    @endphp
    <div class="custom--dropdown ms-2">
        <div class="custom--dropdown__selected dropdown-list__item">
            <div>
                <div class="thumb">
                    <img src="{{ getImage(getFilePath('language') . '/' . @$activeLanguage->image, getFileSize('language')) }}"
                        alt="language image">
                </div>
            </div>
            <span class="text">{{ __($activeLanguage->name) }}</span>
        </div>
        <ul class="dropdown-list">
            @foreach ($language as $item)
                @if ($item->id != $activeLanguage->id)
                    <li class="dropdown-list__item langSel" data-value="{{ $item->code }}">
                        <div>
                            <div class="thumb">
                                <img src="{{ getImage(getFilePath('language') . '/' . @$item->image, getFileSize('language')) }}"
                                    alt="language image">
                            </div>
                        </div>
                        <span class="text">{{ $item->name }}</span>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
@endif

@push('script')
    <script>
        "use stric";
        $(document).ready(function() {
            $('.custom--dropdown__selected').on('click', function() {
                $(this).parent().toggleClass('open');
            });

            $('.custom--dropdown > .dropdown-list > .dropdown-list__item').on('click', function() {
                $('.custom--dropdown > .dropdown-list > .dropdown-list__item').removeClass('selected');
                $(this).addClass('selected').parent().parent().removeClass('open').children(
                    '.custom--dropdown__selected').html($(this).html());
            });

            $(document).on('keyup', function(evt) {
                if ((evt.keyCode || evt.which) === 27) {
                    $('.custom--dropdown').removeClass('open');
                }
            });

            $(document).on('click', function(evt) {
                if ($(evt.target).closest(".custom--dropdown > .custom--dropdown__selected").length === 0) {
                    $('.custom--dropdown').removeClass('open');
                }
            });
        });
    </script>
@endpush
