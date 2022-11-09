## ECサイト

## ダウンロード方法

git clone

git clone https://github.com/Tsutomu001/ec_site

git clone ブランチを指定してダウンロードする場合

git clone -b ブランチ名 https://github.com/Tsutomu001/ec_site

もしくはzipファイルでダウンロードしてください

## インストール方法

-cd ec_site

-composer install

npm install

npm run dev

.env.exampleをコピーして.env ファイルを作成

.envファイルの中の下記をご利用の環境に合わせて変更してください

ex.)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ec_site
DB_USERNAME=root
DB_PASSWORD=

XAMPP/MAMPまたは他の環境でDBを起動した後に

php artisan migrate:fresh --seed
と実行してください。(データベーステーブルとダミーデータが追加されればOK)

最後に
php artisan key:generate
と入力してキーを生成後、

php artisan serve
で簡易サーバーを立ち上げ、表示確認してください。



## インストール後の実施事項

画像のダミーデータは
public/imagesフォルダ内に
sample1.jpg ~ sample6.jpg として
保存しています。

php artisan storage:link で
storageフォルダにリンク後、

storage/app/public/productsフォルダ内に
保存されると表示されます。
(productsフォルダがない場合は作成してください。)

ショップの画像も表示する場合は
storage/app/public/shopsフォルダを作成し
画像を保存してください。