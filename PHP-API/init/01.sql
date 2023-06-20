create database if not exists music;
use music;
create table if not exists user
(
    id int unsigned auto_increment primary key,
    name varchar(255) not null,
    surname varchar(255) not null,
    lastname varchar(255) not null,
    birth_date date not null,
    email varchar(255) not null,
    user_name varchar(255) not null,
    unique(user_name)
    );

create table if not exists song
(
    id int unsigned auto_increment primary key,
    artist_id int unsigned not null,
    title varchar(255) not null,
    genre varchar(255)
    );

create table if not exists album
(
    id int unsigned auto_increment primary key,
    artist_id int unsigned not null,
    title varchar(255) not null
    );

create table if not exists album_songs
(
    id int unsigned auto_increment primary key,
    album_id int unsigned not null,
    song_id int unsigned not null,
    foreign key(album_id) references album(id) on delete cascade,
    foreign key(song_id) references song(id) on delete cascade
    );

create table if not exists artist
(
    id int unsigned auto_increment primary key,
    name varchar(255) not null,
    surname varchar(255) not null,
    lastname varchar(255) not null,
    birth_date date not null,
    email varchar(255) not null,
    user_name varchar(255) not null,
    unique(user_name)
    );

alter table song add foreign key (artist_id) references artist (id) on delete cascade;
alter table album add foreign key (artist_id) references artist (id) on delete cascade;

create table if not exists playlist
(
    id int unsigned auto_increment primary key,
    user_id int unsigned not null,
    title varchar(255) not null,
    foreign key(user_id) references user(id) on delete cascade,
    constraint U_user_playlist unique(title, user_id)
);

create table if not exists playlist_songs
(
    id int unsigned auto_increment primary key,
    playlist_id int unsigned not null,
    song_id int unsigned not null,
    foreign key (playlist_id) references playlist(id) on delete cascade,
    foreign key (song_id) references song(id) on delete cascade
);

