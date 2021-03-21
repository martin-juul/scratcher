import * as React from 'react';
import { createStackNavigator } from '@react-navigation/stack';
import { PlaylistsScreen } from '../screens/PlaylistsScreen/PlaylistsScreen';
import { PlaylistScreen } from '../screens/PlaylistScreen/PlaylistScreen';

export type PlaylistsParamList = {
  Playlists: undefined;
  Playlist: { slug: string };
}

const PlaylistsStack = createStackNavigator<PlaylistsParamList>();

export const PlaylistsStackScreen = () => (
  <PlaylistsStack.Navigator>
    <PlaylistsStack.Screen name="Playlists" component={PlaylistsScreen} options={{headerShown: false}}/>
    <PlaylistsStack.Screen name="Playlist" component={PlaylistScreen}/>
  </PlaylistsStack.Navigator>
);
