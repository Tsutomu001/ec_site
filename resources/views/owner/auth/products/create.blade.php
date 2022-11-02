<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- バリデーションのエラーメッセージ --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    
                    {{-- enctype="multipart/form-data" ...添付ファイルの情報を送信する --}}
                    <form  method='post' action="{{ route('owner.products.store') }}">
                        @csrf
                        <div class="-m-2">
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <select name="category"> 
                                        {{-- categoryの名前を取得する --}}
                                        @foreach($categories as $category) 
                                        <optgroup label="{{ $category->name }}"> 
                                        {{-- 紐づいたsecondaryの名前を取得する --}}
                                        @foreach($category->secondary as $secondary) 
                                        <option value="{{ $secondary->id}}" > 
                                        {{ $secondary->name }} 
                                        </option> 
                                        @endforeach 
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <x-select-image :images="$images" name="image1" />
                            <x-select-image :images="$images" name="image2" />
                            <x-select-image :images="$images" name="image3" />
                            <x-select-image :images="$images" name="image4" />
                            <x-select-image :images="$images" name="image5" />
                            {{-- flex justify-around ...横並びにしてちょうどよい間隔にする --}}
                            <div class="p-2 w-full flex justify-around mt-4">
                                {{-- onclick ...戻るボタンを押したら○○に移動する --}}
                                <button type="button" onclick="location.href='{{ route('owner.products.index')}}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                                <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        'use strict' 
        const images = document.querySelectorAll('.image') //全てのimageタグ(.image)を取得 

        images.forEach(image => { // 1つずつ繰り返す
            // クリックしたら以下の動作
            image.addEventListener('click', function(e){
            const imageName = e.target.dataset.id.substr(0, 6) //data-idの6文字  substr()...引数に指定した〇～〇を取得する
            const imageId = e.target.dataset.id.replace(imageName + '_', '') // 6文字カット  replace()...文字列中の指定値を置き換える(imageName + '_'から''に)
            const imageFile = e.target.dataset.file 
            const imagePath = e.target.dataset.path 
            const modal = e.target.dataset.modal 

            // サムネイルと input type=hiddenのvalueに設定 
            // '_thumbnail'と'_hidden'に張り付ける
            document.getElementById(imageName + '_thumbnail').src = imagePath + '/' + imageFile 
            document.getElementById(imageName + '_hidden').value = imageId 
            MicroModal.close(modal); //モーダルを閉じる 
        },) 
        })
    </script>
</x-app-layout>