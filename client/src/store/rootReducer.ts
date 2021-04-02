import { combineReducers } from '@reduxjs/toolkit'
import { TypedUseSelectorHook, useSelector } from 'react-redux'

import albumReducer from './album'

export const rootReducer = combineReducers({
  album: albumReducer,
})

export type RootState = ReturnType<typeof rootReducer>;
export default rootReducer
export const useTypedSelector: TypedUseSelectorHook<RootState> = useSelector
