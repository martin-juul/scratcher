import { Track } from './track'

export interface PlaylistItem extends Track {
  order: number;
}

export interface Playlist {
  name: string;
  slug: string;
  isPublic: boolean;
  created: Date;
  updated: Date;
  tracks?: PlaylistItem[];
  trackCount?: number;
}
