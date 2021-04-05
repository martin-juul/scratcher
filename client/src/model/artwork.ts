import { Model } from '@nozbe/watermelondb'
import { date, field } from '@nozbe/watermelondb/decorators'
import { TABLE_NAME } from './schema'

export class Artwork extends Model {
  static table = TABLE_NAME.ARTWORK

  @field('basename') basename!: string
  @field('mime') mime!: string
  @field('size') size!: number
  @field('height') height!: number
  @field('width') width!: number
  @field('url') url!: string

  @date('created') created!: Date
  @date('updated') updated!: Date
}
