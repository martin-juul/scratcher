import { Genre } from './genre';
import { Artist } from './person';

export interface Track {
  title: string;
  sha256: string;
  path: string;
  file_format: string;
  file_size: number;
  mime_type: string;
  isrc: string;
  bitrate: number;
  length: number;
  stream: string;
  track_number: string | number;
  genres: Genre[];
  artists: Artist[];
  created: Date;
  updated: Date;
}
