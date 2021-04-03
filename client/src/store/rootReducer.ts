import { combineReducers } from '@reduxjs/toolkit'
import { TypedUseSelectorHook, useSelector } from 'react-redux'

import albumReducer from './album'
import queueReducer from './queue'

export const rootReducer = combineReducers({
  album: albumReducer,
  queue: queueReducer,
})

export type RootState = ReturnType<typeof rootReducer>;
export default rootReducer
export const useTypedSelector: TypedUseSelectorHook<RootState> = useSelector
