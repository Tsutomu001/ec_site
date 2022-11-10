<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品一覧
        </h2>
        <form>
        @csrf
            <div class="lg:flex lg:justify-around">
                <div class = "lg:flex items-center">
                    <div class="flex items-center">
                        <select name="category" class="mr-3">
                            <option value="0" @if(\Request::get('category') === '0') selected @endif>全て</option>
                            {{-- categoryの名前を取得する --}}
                            @foreach($categories as $category) 
                            <optgroup label="{{ $category->name }}"> 
                            {{-- 紐づいたsecondaryの名前を取得する --}}
                            @foreach($category->secondary as $secondary) 
                            <option value="{{ $secondary->id }}" @if(\Request::get('category') == $secondary->id ) selected @endif> 
                            {{ $secondary->name }} 
                            </option> 
                            @endforeach 
                            @endforeach 
                        </select>
                        <div><input name="keyword" class="border border-gray-500 py-2 mr-3" placeholder="キーワードを入力"></div>
                        <div><button class="ml-auto mr-3 text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">検索する</button></div>
                    </div>
                </div>
            </div>
        </form>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-wrap">
                        @foreach ($products as $product)
                            <div class='w-1/4 p-2 md:p-4'>
                                <a href="{{ route('user.items.show' , [ 'item' => $product->id ]) }}">
                                <div class="border rounded-md p-2 md:p-4">
                                    {{-- componentsのshop-thumbnail.blade.phpを使用する --}}
                                    {{-- ?○○ ...nullだったら空のデータ --}}
                                    <x-thumbnail filename="{{$product->filename ?? ''}}" type="products" />
                                    {{-- <div class="text-gray-700">{{ $product->name }}</div> --}}
                                    <div class="mt-4">
                                        <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">{{ $product->category }}</h3>
                                        <h2 class="text-gray-900 title-font text-lg font-medium">{{ $product->name }}</h2>
                                        {{-- number_format() ...金額にカンマ(,)を付ける --}}
                                        <p class="mt-1">{{ number_format($product->price) }}<span class="text-sm text-gray-700">円(税込)</span></p>
                                    </div>
                                </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>