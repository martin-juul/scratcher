import { Database } from '@nozbe/watermelondb'
import SQLiteAdapter from '@nozbe/watermelondb/adapters/sqlite'
import { schema } from './schema'

const adapter = new SQLiteAdapter({
  schema,
  synchronous: true,
  experimentalUseJSI: true,
})

export const database = new Database({
  adapter,
  modelClasses: [],
  actionsEnabled: true,
})
