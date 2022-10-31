<?php

namespace App\Services;

// Storageの定義
use Illuminate\Support\Facades\Storage;
// InterventionImageの定義
use InterventionImage;

class ImageService
{
    public static function upload($imageFile, $folderName){
        // dd($imageFile['image']);
        // is_array() ...配列かどうかを確認する
        if(is_array($imageFile))
        {
            // 配列の場合は'image'で値を取得する
            $file = $imageFile['image']; // 配列なので[ʻkeyʼ] で取得
        }else{
            // 配列でない場合は$imageFileに入れる
            $file = $imageFile;
        }

        // uniqid() ...ユニークなIDを取得する
        // // rand() ...重複しないファイル名
        $fileName = uniqid(rand().'_');
        // extension() ...ファイル拡張する
        $extension = $file->extension();
        // ファイル名と拡張子を接続する
        $fileNameToStore = $fileName. '.' . $extension; 
        // アップロードされた画像を$imageFileに入れてresizeして拡張子を取得する
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();

        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage );

        return $fileNameToStore;
    }
}