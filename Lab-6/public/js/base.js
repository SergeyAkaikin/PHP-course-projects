let headerList = [
    {
        header: 'Home',
        action: () => location.replace('/'),
    },
    {
        header: 'Artists',
        action: () => renderArtists(),
    }
];
function renderHeader() {
    let header = document.getElementById('header_list');
    let nav = document.createElement('ul');
    nav.className = 'list';
    headerList.forEach(headerElem => {
        let element = document.createElement('li.list')
        element.innerText = headerElem.header;
        element.onclick = () => headerElem.action();
        nav.appendChild(element);
    })
    header.appendChild(nav);
}

async function renderHome() {
    let albumList = document.getElementById('list');
    albumList.innerHTML = '';
    await fetch('/api/albums')
        .then(response => response.json())
        .then(albums => {
            albums.forEach(album => {
                let albumElement = document.createElement('li');
                const date = new Date(album.date);
                albumElement.innerText = `${album.title} (${date.getFullYear()}) - ${album.rating} rating`;
                albumElement.onclick = () => redirectToAlbum(album.id);
                albumList.appendChild(albumElement);
            });
        });
}

function redirectToAlbum(id) {
    location.replace(`/album/${id}`);
}

async function renderArtists() {
    let content = document.getElementById('main_content');
    content.innerHTML = '<h1>Artists page</h1>';
    let artistsElem = document.createElement('ol');
    artistsElem.className = 'list';
    let artists = await fetch('/api/artists').then(response => response.json());
    artists.forEach(artist => {
       let artistElem = document.createElement('li');
       artistElem.innerText = artist.user_name;
       artistElem.onclick = () => renderArtist(artist.id);
       artistsElem.appendChild(artistElem);
    });
    content.appendChild(artistsElem);
}

async function renderArtist(artist_id) {
    let content = document.getElementById('main_content');
    content.innerHTML = '';
    let section = document.createElement('section');
    let headerSection = document.createElement('h1');
    let artist = await fetch(`/api/artists/${artist_id}`).then(response => response.json());
    headerSection.innerText = artist.user_name;
    section.appendChild(headerSection);
    let yearElem = document.createElement('p');
    yearElem.innerText = artist.years + ' years';
    section.appendChild(yearElem);
    let emailElem = document.createElement('p');
    emailElem.innerText = artist.email;
    section.appendChild(emailElem);
    let songsList = await fetch(`/api/artists/${artist_id}/songs`).then(response => response.json());
    let songsElem = document.createElement('ol');
    songsElem.className = 'list';
    songsElem.innerText = 'Songs:';
    songsList.forEach(function (song) {
        let songElem = document.createElement('li');
        songElem.innerText = `${song.title} - ${song.genre}`;
        songElem.appendChild(document.createElement('br'));
        let audio = document.createElement('audio');
        audio.src = `http://minio.music.local:9005/audio/${song.path}`;
        audio.controls = true;
        songElem.appendChild(audio);
        songsElem.appendChild(songElem);
    })

    section.appendChild(songsElem);
    content.appendChild(section);
}


