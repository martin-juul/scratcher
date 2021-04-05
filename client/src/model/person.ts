import { Model, Q } from '@nozbe/watermelondb'
import { field, lazy } from '@nozbe/watermelondb/decorators'
import { Associations } from '@nozbe/watermelondb/Model';
import { TABLE_NAME } from './schema'

export class Person extends Model {
  static table = TABLE_NAME.PERSON
  static associations: Associations = {
    [TABLE_NAME.PERSON_TRACKS]: {type: 'has_many', foreignKey: 'person_id'},
  }

  @field('name') name!: string
  @field('description') description!: string | null

  @lazy
  tracks = this.collections
    .get('tracks')
    .query(Q.on('person_tracks', 'person_id', this.id))
}
