import { Track } from './track';
import { Artwork } from './artwork';

export interface PlaylistItem extends Track {
  order: number;
}

export interface Playlist {
  name: string;
  slug: string;
  artwork: Artwork;
  items: PlaylistItem;
  created: Date;
  updated: Date;
}
