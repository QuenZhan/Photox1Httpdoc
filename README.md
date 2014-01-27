# Project B Front End 

## 基本資料

項目		| 內容
-----------	| ----
專案代號	| Project B
專案名稱 	| PHOTOx1
類別 		| 網站服務
版本 		| 0.8
伺服器 		| AWS
BE負責人	| Talkin
FE負責人	| JimZheng
更新時間	| 2014-01-14

## 該做的貨

* category page
* blogger page
* upload page
* user login

## 進度

1. php dummy server 介面建置中
1. 完成 0.8 main page
1. 完成 0.8 object page 
1. 完成 0.8 category page 

## 問題

### getSingleObject

1.不管輸入什麼參數 回傳都是 errior="wrong oid" 或 "wrong special oid";
2. 不應只回傳 error ，就算找不到指定的 oid object 也應回傳一個預設的 targetObject
 
### 點選分類的 link url

若點擊分類的「花束」，會到 「domainname/tw/all/花束」
若點擊「賞喵悅目 (小賢豆豆媽)」，那 url 會是什麼？推測有以下選擇

1. domainname/tw/all/賞喵悅目 (小賢豆豆媽)
1. domainname/user/賞喵悅目 (小賢豆豆媽)
1. domainname/user/cid
4. domainname/user/uid

getCategories 沒有 uid 資料，無法使用第四點可能。

### Login 的問題

目前沒有取得目前已登入 User 的方法，建議以 getUser 取得：
若 getUser uid 找不到指定 uid 的 User ，則回傳目前登入的 User。

	if(empty(findUser($uid))){
		return 目前登入的User
	}
	else{
		return 指定 uid 的 User
	}
	
第二個方法： _SESSION 名稱溝通：所有 Frontend 用的 _SESSION 都有一個 prefix ： fe

例如： feUid,feUser

### getObjects

* 無法換頁
* 無法指定 category，會回傳 Error 而不是 json

## 建議

setObject 應新增一個回傳值：oid，回傳此被修正/被新增的 Object id。