<div>
    @if(empty($shop->filename))
        {{-- asset() ...画像のリソースデータの読み込み --}}
        <img src="{{ asset('images/no_image.jpg')}}">
    @else
        <img src="{{ asset('storage/shops/' . $shop->filename) }}">
    @endif
</div>