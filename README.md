# Project B Front End 
## 基本資料

項目 | 內容
--- | ---
專案代號 | Project B
專案名稱 | PHOTOx1
類別 | 網站服務
版本 | 0.75
伺服器 | GoDadddy
負責人 | JimZheng
更新時間 | 2014-01-15

## 資料更新步驟

1. 修改[資料庫][資料庫]內的資料
1. 將資料庫 excel 轉成 json 格式
1. 將轉好的 json 貼至 script/VooProjectB.js 的 VooProjectB.rawdata 
1. 修改 index.php 的 $uid 初始值
1. 修改 index.php 的 banner 各項參數
1. 新增 index.php 中左上角展覽分類的項目：展覽名稱 - 展覽人名稱
1. 以 index.php 覆蓋 GoDaddy/httpdocs/index.php
1. 以 index.php 覆蓋 GoDaddy/httpdocs/user/eric.cc.hsu/index.php
1. 以 index.php 覆蓋 GoDaddy/httpdocs/user/kinghand.wang/index.php
1. 以 index.php 覆蓋 GoDaddy/httpdocs/user/nelson0719/index.php
1. 以 index.php 覆蓋 GoDaddy/httpdocs/user/(.*)/index.php
1. 以 index.php 覆蓋 GoDaddy/httpdocs/object/(.*)/index.php
1. 以 script/VooProjectB.js 覆蓋 GoDaddy/httpdocs/script/VooProjectB.js
1. 上傳 400 寬圖片至 GoDaddy/httpdocs/user/(.*)/photo/400/
1. 上傳 800 寬圖片至 GoDaddy/httpdocs/user/(.*)/photo/800/

## 注意事項

* 無 backend ，所有資料更新皆為人工手動，位於 script/VooProjectB.js 內的 VooProjectB.rawdata 屬性下。
* javascript 字串無法斷行，請用 \n 代替斷行。
* 目前資料庫： Eric , Nelson , kinghand.wang

## 提出的問題

* 已解決排版錯亂的問題

## 更新大記事

* 主頁面變更為 php ，以利 facebook graph api 進行
* 置入 github
* 隱藏 buynow 按鈕

[資料庫]: https://docs.google.com/a/voo.com.tw/spreadsheet/ccc?key=0Ana45DEu1C7FdHk2c2w4QmY4WkVWM2pRZVdBMDFCLVE&usp=drive_web#gid=0 "資料庫"