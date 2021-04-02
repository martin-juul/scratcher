import { Collection } from './response'
import { Artwork } from './artwork'
import { Artist } from './person'
import { Track } from './track'

export interface Album {
  title: string;
  year: number;
  slug: string;
  created: Date;
  updated: Date;
  artist: Artist | null;
  artwork: Artwork | null;
  tracks: Track[];
}

export type AlbumCollectionResponse = Collection<Omit<Album, 'tracks'>>;
