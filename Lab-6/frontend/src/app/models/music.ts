export interface Album {
  id: number,
  artistName: string,
  artistId: number,
  title: string,
  date: Date,
  rating: number,
}

export interface Song {
  id: number,
  artistId: number,
  title: string,
  genre: string,
  path: string,

  added: boolean,
}


export interface Playlist {
  id: number,
  title: string,
  userId: number,
}
