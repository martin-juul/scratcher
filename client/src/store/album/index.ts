import { Album } from '../../services/api';
import { createSlice, PayloadAction } from '@reduxjs/toolkit';

type AlbumLoadedState = {
  loaded: true;
  data: Album;
}

type AlbumState = { loaded: false, data: null } | AlbumLoadedState;

const album = createSlice({
  name: 'album',
  initialState: {loaded: false} as AlbumState,
  reducers: {
    setAlbum(state, action: PayloadAction<Album>) {
      (state as AlbumLoadedState).data = action.payload;
      state.loaded = true;
    },
  },
});

export const {setAlbum} = album.actions;
export default album.reducer;
