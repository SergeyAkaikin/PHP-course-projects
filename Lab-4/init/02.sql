use music;
insert into user(name, surname, lastname, birth_date, email, user_name)
values ('Ivan', 'Ivanov', 'Ivanovich', '2002-12-05', 'ivan12@gmail.com', 'ivan12'),
       ('Anton', 'Antonov', 'Antonovich', '1999-01-11', 'anton123@mail.ru', 'antonio'),
       ('Viktor', 'Kruchin', 'Viktorovich', '2004-03-28', 'vekts@gmail.com', 'vekts666'),
       ('Alena', 'Bachaeva', 'Sergeevna', '2001-10-22', 'alena@mail.ru', 'alenka');

insert into artist(name, surname, lastname, birth_date, email, user_name)
values ('Maksim', 'Tuchin', 'Valerievich', '1998-09-20', 'tuchin12@gmail.com', 'tucha'),
       ('Danil', 'Rodkin', 'Vladimirovich', '2000-12-01', 'spirit@mail.ru', 'spirit'),
       ('Denis', 'Saveliev', 'Anatolievich', '1999-02-09', 'freedom@gmail.com', 'lil_freedom'),
       ('Nyusha', 'Vladimirovna', 'Shurochkina', '1990-08-15', 'nyusha@gmail.ru', 'nyusha');

insert into song(artist_id, title, genre)
select id, 'rain', 'rock'
from artist
where user_name = 'tucha';

insert into song(artist_id, title, genre)
select id, 'fire', 'pop'
from artist
where user_name = 'spirit';

insert into song(artist_id, title, genre)
select id, 'maybe', 'rap'
from artist
where user_name = 'lil_freedom';

insert into song(artist_id, title, genre)
select id, 'Do not interrupt', 'pop'
from artist
where user_name = 'nyusha';

insert into song(artist_id, title, genre)
select id, 'Choose a miracle', 'pop'
from artist
where user_name = 'nyusha';

insert into song(artist_id, title, genre)
select id, 'I melt', 'pop'
from artist
where user_name = 'nyusha';

insert into album(artist_id, title)
select id, 'Choose a miracle'
from artist
where user_name = 'nyusha';

insert into album_songs(album_id, song_id)
select (select album.id as album_id from album where title = 'Choose a miracle') as album_id,
       (select song.id as song_id from song where title = 'Do not interrupt')    as song_id;

insert into album_songs(album_id, song_id)
select (select album.id as album_id from album where title = 'Choose a miracle') as album_id,
       (select song.id as song_id from song where title = 'Choose a miracle')    as song_id;

insert into playlist(title, user_id)
select 'my_playlist', id from user where user_name='ivan12';

insert into playlist_songs(playlist_id, song_id)
select (select id from playlist where user_id = 1 and title='my_playlist'),
       (select id from song where title='rain' and artist_id=(select id from artist where user_name='tucha'));