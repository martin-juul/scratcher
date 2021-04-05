import { Model, Query, Relation } from '@nozbe/watermelondb'
import { children, date, field, relation } from '@nozbe/watermelondb/decorators'
import { Associations } from '@nozbe/watermelondb/Model';
import { Artwork } from './artwork'
import { TABLE_NAME } from './schema'
import { Track } from './track'

export class Album extends Model {
  static table = TABLE_NAME.ALBUM
  static associations: Associations = {
    [TABLE_NAME.TRACK]: {type: 'has_many', foreignKey: 'album_id'},
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
  @field('track_number') track_number!: string

  @date('created') created!: Date
  @date('updated') updated!: Date

  @children(TABLE_NAME.TRACK) tracks!: Query<Track>
  @relation('artworks', 'artwork_id') artwork!: Relation<Artwork>
}
