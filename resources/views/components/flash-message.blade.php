{{-- @props ...コンポーネントをした際に、値が空だった場合に表示する文字を設定 --}}
@props(['status' => 'info'])

{{-- 背景について --}}
@php
// 正しい情報の場合
if(session('status') === 'info'){$bgColor = 'bg-blue-300';}
// 正しくない情報の場合
if(session('status') === 'alert'){$bgColor = 'bg-red-500';}
@endphp

{{-- メッセージを取得した場合 --}}
@if(session('message'))
    <div class="{{ $bgColor }} w-1/2 mx-auto p-2 my-4 text-white">
        {{ session('message')}}
    </div>
@endif