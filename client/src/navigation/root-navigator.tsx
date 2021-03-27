import * as React from 'react';
import { createStackNavigator } from '@react-navigation/stack';
import { DarkTheme, NavigationContainer, NavigationContainerRef, Theme } from '@react-navigation/native';
import { MainNavigator } from './main-navigator';
import { AlbumScreen } from '../screens/AlbumScreen/AlbumScreen';
import { PlayerScreen } from '../screens/PlayerScreen/PlayerScreen';
import { useContext } from 'react';
import { AuthContext } from '../contexts/AuthContext';
import { LoginScreen } from '../screens/LoginScreen/LoginScreen';

export type MainStackParamList = {
  MainStack: undefined
  Album: { slug: string }
}

const MainStack = createStackNavigator<MainStackParamList>();

const MainStackScreen = () => (
  <MainStack.Navigator>
    <MainStack.Screen name="MainStack" component={MainNavigator} options={{headerShown: false}}/>
    <MainStack.Screen name="Album" component={AlbumScreen}/>
  </MainStack.Navigator>
);

export type RootParamList = {
  Main: undefined
  Player: { albumSlug: string, sha: string, nextSha: string | undefined, prevSha: string | undefined }
  Login: undefined
}

const RootStack = createStackNavigator<RootParamList>();

const RootStackScreen = () => {
  const auth = useContext(AuthContext);

  return (
    <RootStack.Navigator mode="modal" headerMode="float">
      {auth.isSignedIn ? (
        <>
          <RootStack.Screen name="Main" component={MainStackScreen} options={{headerShown: false}}/>
          <RootStack.Screen name="Player" component={PlayerScreen}/>
        </>
      ) : (
        <>
          <RootStack.Screen name="Login" component={LoginScreen}/>
        </>
      )}
    </RootStack.Navigator>
  );
};

const AppTheme: Theme = {
  ...DarkTheme,
  colors: {
    ...DarkTheme.colors,
    primary: 'rgb(210,65,65)',
    background: 'rgb(0, 0, 0)',
    text: 'rgb(255, 255, 255)',
  },
};

export const RootNavigator = React.forwardRef<NavigationContainerRef,
  Partial<React.ComponentProps<typeof NavigationContainer>>>((props, ref) => {
  return (
    <NavigationContainer {...props} theme={AppTheme} ref={ref}>
      <RootStackScreen/>
    </NavigationContainer>
  );
});

RootNavigator.displayName = 'RootNavigator';
