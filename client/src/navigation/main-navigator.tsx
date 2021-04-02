import * as React from 'react'
import { StyleSheet } from 'react-native'
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs'
import { BottomNavigation, BottomNavigationTab, Icon } from '@ui-kitten/components'
import { AlbumsStackScreen } from './albums-navigator'
import { PlaylistsStackScreen } from './playlists-navigator'

export type PrimaryParamList = {
  Albums: undefined;
  Playlists: undefined;
}

const {Navigator, Screen} = createBottomTabNavigator<PrimaryParamList>()

const styles = StyleSheet.create({
  bottomNavigation: {
    paddingBottom: 14,
  },
})

const ALBUM_ICON = (props: any) => <Icon {...props} name='grid-outline'/>
const PLAYLIST_ICON = (props: any) => <Icon {...props} name='list-outline'/>

const BottomTabBar = ({navigation, state}: any) => (
  <BottomNavigation
    style={styles.bottomNavigation}
    selectedIndex={state.index}
    onSelect={index => navigation.navigate(state.routeNames[index])}
  >
    <BottomNavigationTab icon={ALBUM_ICON} title="Albums"/>
    <BottomNavigationTab icon={PLAYLIST_ICON} title="Playlists"/>
  </BottomNavigation>
)

export const MainNavigator = () => (
  <Navigator lazy={false} tabBar={props => <BottomTabBar {...props} />}>
    <Screen name='Albums' component={AlbumsStackScreen}/>
    <Screen name='Playlists' component={PlaylistsStackScreen}/>
  </Navigator>
)

const exitRoutes = ['Albums', 'Playlists']
export const canExit = (routeName: string) => exitRoutes.includes(routeName)
