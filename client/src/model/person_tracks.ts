import { Model } from '@nozbe/watermelondb'
import { field } from '@nozbe/watermelondb/decorators'
import { Associations } from '@nozbe/watermelondb/Model';
import { TABLE_NAME } from './schema'

export class PersonTracks extends Model {
  static table = TABLE_NAME.PERSON_TRACKS
  static associations: Associations = {
    [TABLE_NAME.PERSON]: {type: 'belongs_to', key: 'person_id'},
    [TABLE_NAME.TRACK]: {type: 'belongs_to', key: 'track_id'},
  }

  @field('person_id') personId!: string
  @field('track_id') trackId!: string
}
