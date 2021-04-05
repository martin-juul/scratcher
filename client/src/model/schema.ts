import { appSchema, tableSchema } from '@nozbe/watermelondb'

export enum TABLE_NAME {
  ARTWORK = 'artworks',
  ALBUM = 'albums',
  GENRE = 'genres',
  PERSON = 'person',
  PERSON_TRACKS = 'person_tracks',
  GENRE_TRACKS = 'genre_tracks',
  TRACK = 'tracks',
}

export const schema = appSchema({
  version: 1,
  tables: [
    tableSchema({
      name: TABLE_NAME.ARTWORK,
      columns: [
        {name: 'basename', type: 'string'},
        {name: 'mime', type: 'string'},
        {name: 'size', type: 'number'},
        {name: 'height', type: 'number'},
        {name: 'width', type: 'number'},
        {name: 'url', type: 'number'},
        {name: 'created', type: 'number'},
        {name: 'updated', type: 'number'},
      ],
    }),
    tableSchema({
      name: TABLE_NAME.ALBUM,
      columns: [
        {name: 'title', type: 'string', isIndexed: true},
        {name: 'slug', type: 'string', isIndexed: true},
        {name: 'year', type: 'number', isIndexed: true},
        {name: 'created', type: 'string'},
        {name: 'updated', type: 'string'},
        {name: 'artwork_id', type: 'string', isIndexed: true},
      ],
    }),
    tableSchema({
      name: TABLE_NAME.GENRE,
      columns: [
        {name: 'name', type: 'string'},
        {name: 'slug', type: 'string', isIndexed: true},
      ],
    }),
    tableSchema({
      name: TABLE_NAME.PERSON,
      columns: [
        {name: 'name', type: 'string', isIndexed: true},
        {name: 'description', type: 'string', isOptional: true},
      ],
    }),
    tableSchema({
      name: TABLE_NAME.PERSON_TRACKS,
      columns: [
        {name: 'person_id', type: 'string', isIndexed: true},
        {name: 'track_id', type: 'string', isIndexed: true},
      ],
    }),
    tableSchema({
      name: TABLE_NAME.GENRE_TRACKS,
      columns: [
        {name: 'genre_id', type: 'string', isIndexed: true},
        {name: 'track_id', type: 'string', isIndexed: true},
      ],
    }),
    tableSchema({
      name: TABLE_NAME.TRACK,
      columns: [
        {name: 'title', type: 'string', isIndexed: true},
        {name: 'sha256', type: 'string'},
        {name: 'path', type: 'string'},
        {name: 'file_format', type: 'string'},
        {name: 'file_size', type: 'number'},
        {name: 'mime_type', type: 'string'},
        {name: 'bitrate', type: 'number'},
        {name: 'length', type: 'number'},
        {name: 'created', type: 'number'},
        {name: 'updated', type: 'number'},
        {name: 'album_id', type: 'string', isIndexed: true},
      ],
    }),
  ],
})
