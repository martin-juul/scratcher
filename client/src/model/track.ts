import { Model, Q, Relation } from '@nozbe/watermelondb'
import { date, field, lazy, relation } from '@nozbe/watermelondb/decorators'
import { Associations } from '@nozbe/watermelondb/Model';
import { Album } from './album'
import { TABLE_NAME } from './schema'

export class Track extends Model {
  static table = TABLE_NAME.TRACK
  static associations: Associations = {
    [TABLE_NAME.ALBUM]: {type: 'belongs_to', key: 'album_id'},
    [TABLE_NAME.PERSON_TRACKS]: {type: 'has_many', foreignKey: 'track_id'},
  }

  @field('title') title!: string
  @field('sha256') sha256!: string
  @field('path') path!: string
  @field('file_format') file_format!: string
  @field('file_size') file_size!: number
  @field('mime_type') mime_type!: string
  @field('isrc') isrc!: string
  @field('bitrate') bitrate!: number
  @field('stream') stream!: string
  @field('self') self!: string
  @field('track_number') track_number!: string

  @date('created') created!: Date
  @date('updated') updated!: Date

  @relation('albums', 'album_id') album!: Relation<Album>

  @lazy
  artists = this.collections
    .get('people')
    .query(Q.on('person_tracks', 'track_id', this.id))
}
