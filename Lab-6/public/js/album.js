
function renderHome() {
    let albumList = document.getElementById('list');
    let albums = fetch('/api/albums')
        .then(response => response.json())
        .then(albums => {
            let num = 1;
            albums.forEach(album => {
                let albumElement = document.createElement('li');
                const date = new Date(album.date);
                albumElement.innerText = `${num}. ${album.title} (${date.getFullYear()}) - ${album.songCount} songs, ${album.rate} rating`;
                albumElement.onclick = () => renderAlbum(album.id);
                albumList.appendChild(albumElement);
                num++;
            });
        });
}

function renderAlbum(id) {
    console.log(id);
}

renderHome();
