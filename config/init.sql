drop table if exists thread;
create table if not exists thread 
( no int unsigned not null auto_increment primary key,
	auther varchar(255),
	title varchar(255),
	body text,
	cat_id int not null,
	fileName varchar(255),
	thumbnail_flag tinyint(1) default 0,
	good int unsigned default 0,
	bad int unsigned default 0,
	created_at datetime,
	updated_at datetime,
	delete_flag tinyint(1) default 0 
);

insert into thread (
	auther, 
	title, 
	body, 
	cat_id, 
	fileName, 
	thumbnail_flag,
	created_at, 
	updated_at)
	values
	('運営', '削除依頼はこちら', 
		'利用規約の禁止事項に該当する書き込みや不適切だと判断される書き込みに関してこちらで削除依頼を行うことができます。

		削除依頼の対象となるスレッドのカテゴリ名・スレッド名・スレッドのURL・コメント番号・削除理由をご明記の上、このスレッドにコメントしてください。
		内容を確認した後、不適切であると判断されたスレッド(コメント)の削除対応をさせていただきます。

		
		テンプレート
		【カテゴリ名】
		【スレッド名】
		【コメント番号】
		【スレッドURL】
		【削除理由】', 
		1, 
		'personal_info.png', 
		0, 
		now(), 
		now()
	),
	('運営', 'エンジニちゃんねるへのご意見・ご要望はこちら', 
		'平素よりエンジニちゃんねるをご利用いただきまして誠にありがとうございます。
		ご意見・ご要望等ございましたらお気軽にこのスレッドに記載ください。', 
		1, 
		'sns_happy_woman.png', 
		0, 
		now(), 
		now()
	),
	('運営', '新規カテゴリの提案はこちら', 
		'もっとカテゴリを増やしてほしい、もっとカテゴリ分けを詳細にしてほしいなど、今あるカテゴリ一覧にない新規カテゴリの希望があればこちらにコメントしてください。
		検討させていただきます。', 
		1, 
		'text_anke-to.png', 
		0, 
		now(), 
		now()
	);

drop table if exists category;
create table if not exists category 
( id int unsigned not null auto_increment primary key,
	cat_name varchar(255) unique,
	created_at datetime,
	updated_at datetime);

insert into category (cat_name, created_at, updated_at) values
	('ご要望・削除依頼', now(), now() ),
	('ニュース', now(), now() ),
	('雑談', now(), now() ),
	('RaspberryPi', now(), now() ),
	('就職・転職', now(), now() ),
	('資格', now(), now() ),
	('言語', now(), now() ),
	('個人開発', now(), now() ),
	('ゲーム開発', now(), now() ),
	('組み込み・IoT', now(), now() ),
	('未経験・駆け出しエンジニア', now(), now() ),
	('インフラエンジニア', now(), now() ),
	('システムエンジニア', now(), now() ),
	('WEBエンジニア', now(), now() ),
	('エンジニア総合', now(), now() )
;


drop table if exists reply;
create table if not exists reply 
( no int unsigned not null auto_increment primary key,
	thread_no int unsigned not null,
	user_id int unsigned,
	auther varchar(255),
	body text,
	fileName varchar(255),
	good int unsigned default 0,
	bad int unsigned default 0,
	created_at datetime,
	updated_at datetime,
	delete_flag tinyint(1) default 0
);

drop view if exists count_comment;
create view count_comment as select thread_no, count(*) as comments from reply group by thread_no;

