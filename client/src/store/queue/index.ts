import { createSlice, PayloadAction } from '@reduxjs/toolkit'
import { Track } from '../../services/api'

export interface QueuedTrack extends Track {
  order?: number
}

type QueueState = {
  nowPlaying: {
    position: number | null
    track: Track | null
  },
  tracks: Track[]
}

const queue = createSlice({
  name: 'queue',
  initialState: {
    nowPlaying: {
      position: null,
      track: null
    },
    tracks: [],
  } as QueueState,
  reducers: {
    addToQueue(state, action: PayloadAction<QueuedTrack>) {
      const payload = action.payload
      if (!payload.order || payload.order > state.tracks.length) {
        payload.order = state.tracks.length
      }

      if (!state.nowPlaying.track) {
        state.nowPlaying.track = payload
      } else {
        state.tracks.splice(payload.order, 0, payload)
      }
    },

    nextInQueue(state) {
      state.nowPlaying.track = state.tracks.splice(0, 1) as unknown as Track
      state.nowPlaying.position = null
    },
    removeFromQueueByIndex(state, action: PayloadAction<number>) {
      state.tracks.splice(action.payload, 1)
    },
    clearQueue(state) {
      state.tracks = []
    },
  },
})

export const {addToQueue, nextInQueue, removeFromQueueByIndex, clearQueue} = queue.actions
export default queue.reducer
