# エンジニちゃんねる -エンジニアのための匿名掲示板-

## 概要
フレームワークを使わないでCRUD練習のためにPHPで作った掲示板です。  
匿名掲示板なのでユーザー登録機能はあえて省きました。  
新規スレッド作成・スレッドへの返信・いいね機能があります。  
投稿した内容をユーザー自ら修正したり削除することはできません。

## 開発環境
* Raspberry Pi4 model B 4G
* OS Raspberry Pi OS (debian 10.6)
* Apache2
* PHP 7.3.19-1
* jQuery(Ajax)
* MariaDB 15.1
* CanvasAPI

## 本番環境
* AWS Lightsail (LAMP)

## 機能詳細
* スレタイ検索
* カテゴリ検索
* 並び替え（人気順・新着順・コメントの多い順・古い順） 
![search](https://raw.githubusercontent.com/shizuka-iot/imgs/main/engi2ch_sample01.gif)  

* ページネーション
* 存在しないページのurlを入力すると専用の404ページを表示
* スレッドやコメントにいいね・悪いねの評価が出来ます。  
	円グラフでその割合を確認できます。  
	Ajax通信で非同期でDBの値と画面を更新します。 
![good_button](https://raw.githubusercontent.com/shizuka-iot/imgs/main/engi2ch_sample02.gif)
