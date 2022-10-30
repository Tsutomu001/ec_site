<?php

namespace App\Services;

// Storageの定義
use Illuminate\Support\Facades\Storage;
// InterventionImageの定義
use InterventionImage;

class ImageService
{
    public static function upload($imageFile, $folderName){

        // uniqid() ...ユニークなIDを取得する
        // // rand() ...重複しないファイル名
        $fileName = uniqid(rand().'_');
        // extension() ...ファイル拡張する
        $extension = $imageFile->extension();
        // ファイル名と拡張子を接続する
        $fileNameToStore = $fileName. '.' . $extension; 
        // アップロードされた画像を$imageFileに入れてresizeして拡張子を取得する
        $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();

        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage );

        return $fileNameToStore;
    }
}