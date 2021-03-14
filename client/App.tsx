import React, { useRef } from 'react';
import { ApplicationProvider, IconRegistry } from '@ui-kitten/components';
import { EvaIconsPack } from '@ui-kitten/eva-icons';
import * as eva from '@eva-design/eva';
import { NavigationContainerRef } from '@react-navigation/native';
import { initialWindowMetrics, SafeAreaProvider } from 'react-native-safe-area-context';
import { Provider } from 'react-redux';
import store from './src/store';
import * as storage from './src/services/storage';
import {
  canExit,
  RootNavigator,
  setRootNavigation,
  useBackButtonHandler,
  useNavigationPersistence,
} from './src/navigation';
import { appTheme } from './src/theme';
import './app.json';

export const NAVIGATION_PERSISTENCE_KEY = 'NAVIGATION_STATE';

export default (): React.ReactFragment => {
  const navigationRef = useRef<NavigationContainerRef>();
  // @ts-ignore
  setRootNavigation(navigationRef);
  // @ts-ignore
  useBackButtonHandler(navigationRef, canExit);
  const {initialNavigationState, onNavigationStateChange} = useNavigationPersistence(
    storage,
    NAVIGATION_PERSISTENCE_KEY,
  );

  return (
    <>
      <IconRegistry icons={EvaIconsPack}/>
      <Provider store={store}>
        <ApplicationProvider {...eva} theme={{...eva.dark, ...appTheme}}>
          <SafeAreaProvider initialMetrics={initialWindowMetrics}>
            <RootNavigator
              // @ts-ignore
              ref={navigationRef}
              initialState={initialNavigationState}
              onStateChange={onNavigationStateChange}
            />
          </SafeAreaProvider>
        </ApplicationProvider>
      </Provider>
    </>
  );
}
