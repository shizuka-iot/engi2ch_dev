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

drop table if exists category;
create table if not exists category 
( id int unsigned not null auto_increment primary key,
	cat_name varchar(255) unique,
	created_at datetime,
	updated_at datetime);

insert into category (cat_name, created_at, updated_at) values
('ニュース', now(), now() ),
('エンジニア総合', now(), now() ),
('未経験・駆け出しエンジニア', now(), now() ),
('WEBエンジニア', now(), now() ),
('システムエンジニア', now(), now() ),
('組み込み・IoT', now(), now() ),
('ゲーム開発', now(), now() ),
('就職・転職', now(), now() ),
('資格', now(), now() ),
('言語', now(), now() ),
('RaspberryPi', now(), now() ),
('個人開発', now(), now() ),
('雑談', now(), now() ),
('ご要望・削除依頼', now(), now() );


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
