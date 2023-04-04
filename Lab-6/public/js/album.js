async function renderUpdating(id) {
    let content = document.getElementById('main_content');
    let defaultTitle = '';
    let artistId = 0;
    await fetch(`/api/albums/${id}`).then(response => response.json()).then((album) => {
        defaultTitle = album.title;
        artistId = album.artist_id;
    });
    content.innerHTML = '';
    let section = document.createElement('section');
    section.id = 'section_content'
    section.innerHTML = `<form class="update_album_form" id="update_form">
<label for="title">Title</label>
<input id="title" type="text" name="title" value="${defaultTitle}">
</form>`;

    let button = document.createElement('button');
    button.innerText = 'Update';
    button.onclick = () => {
        let form = document.getElementById('update_form');
        const formData = new FormData(form);
        fetch(`/api/albums/${id}/update`, {
            method: 'POST',
            headers: {},
            body: formData,
        }).then(() => location.reload());
    };
    section.appendChild(button);
    content.appendChild(section);

    let songAdd = document.createElement('button');
    songAdd.id = 'song_button';
    songAdd.innerText = 'Add Song';
    songAdd.onclick = () => renderUploadAlbumSong(id, artistId);
    section.appendChild(songAdd);


}

function renderUploadAlbumSong(id, artistId) {
    let content = document.getElementById('section_content');
    document.getElementById('song_button').remove();
    let songForm = document.createElement('form');
    songForm.className = 'song_form';
    songForm.id = 'upload_song';
    songForm.innerHTML = `
<input type="hidden" name="artist_id" value="${artistId}">
<label for="title">Title</label>
<input id="title" type="text" name="title">
<label for="genre">Genre</label>
<input id="genre" type="text" name="genre">
<label for="file">File</label>
<input id="file" name="file" type="file" accept="audio/*">`;
    let button = document.createElement('button');
    button.innerText = 'Upload';
    button.onclick = () => {
        let form = document.getElementById('upload_song');
        let formData = new FormData(form);
        let xhr = new XMLHttpRequest();
        xhr.open('POST', `/api/albums/${id}/songs`);
        xhr.send(formData);
        xhr.onload = () => location.reload();
    }
    songForm.appendChild(button);
    content.appendChild(songForm);
}

function deleteSong(album_id, song_id) {
    fetch(`/api/albums/${album_id}/songs/${song_id}`, {
        method: 'DELETE'
    }).then(() => {
        let songElement = document.getElementById(`song-${song_id}`);
        songElement.remove();
    })
}

function renderDeleting(id) {
    fetch(`/api/albums/${id}`, {method: 'DELETE'})
        .then(() => location.replace('/'));
}


