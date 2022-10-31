@php
// $typeが'shops'だったら'storage/shops/'に保存する
if($type === 'shops'){
    $path = 'storage/shops/';
}
if($type === 'products'){
    $path = 'storage/products/';
}

@endphp

<div>
    @if(empty($filename))
        {{-- asset() ...画像のリソースデータの読み込み --}}
        <img src="{{ asset('images/no_image.jpg')}}">
    @else
        <img src="{{ asset($path . $filename) }}">
    @endif
</div>