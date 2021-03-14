import * as React from 'react';
import { createStackNavigator } from '@react-navigation/stack';
import { AlbumsScreen } from '../screens/AlbumsScreen/AlbumsScreen';
import { AlbumScreen } from '../screens/AlbumScreen/AlbumScreen';

export type AlbumsParamList = {
  Albums: undefined;
  Album: { slug: string };
}

const AlbumsStack = createStackNavigator<AlbumsParamList>();

export const AlbumsStackScreen = () => (
  <AlbumsStack.Navigator>
    <AlbumsStack.Screen name="Albums" component={AlbumsScreen} options={{headerShown: false}}/>
    <AlbumsStack.Screen name="Album" component={AlbumScreen}/>
  </AlbumsStack.Navigator>
);
