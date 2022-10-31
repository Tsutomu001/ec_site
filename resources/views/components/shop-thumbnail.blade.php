<div>
    @if(empty($filename))
        {{-- asset() ...画像のリソースデータの読み込み --}}
        <img src="{{ asset('images/no_image.jpg')}}">
    @else
        <img src="{{ asset('storage/shops/' . $filename) }}">
    @endif
</div>