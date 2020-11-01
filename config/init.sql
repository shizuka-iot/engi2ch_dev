drop table if exists user;
create table if not exists user 
( id int unsigned not null auto_increment primary key,
	username varchar(255),
	email varchar(255) unique,
	password varchar(255),
	created_at datetime,
	updated_at datetime,
	delete_flag tinyint(1) default 0 );


drop table if exists thread;
create table if not exists thread 
( no int unsigned not null auto_increment primary key,
	user_id int unsigned,
	auther varchar(255),
	title varchar(255),
	body text,
	cat_id int not null,
	fileName varchar(255),
	thumbnail_flag tinyint(1) default 0,
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
('プログラミング総合', now(), now() ),
('初心者', now(), now() ),
('就職・転職', now(), now() ),
('未経験', now(), now() ),
('PHP', now(), now() ),
('JavaScript', now(), now() ),
('Java', now(), now() ),
('C言語', now(), now() ),
('C++', now(), now() ),
('C#', now(), now() ),
('Ruby', now(), now() ),
('フレームワーク', now(), now() ),
('自作アプリ', now(), now() ),
('フリーランス', now(), now() ),
('SNS', now(), now() ),
('雑談', now(), now() ),
('その他', now(), now() );


drop table if exists reply;
create table if not exists reply 
( no int unsigned not null auto_increment primary key,
	thread_no int unsigned not null,
	user_id int unsigned,
	auther varchar(255),
	body text,
	fileName varchar(255),
	good int default 0,
	bad int default 0,
	created_at datetime,
	updated_at datetime,
	delete_flag tinyint(1) default 0
);
