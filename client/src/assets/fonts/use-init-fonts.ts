import { useFonts } from 'expo-font';

export function useInitFonts() {
  return useFonts({
    OpenSans: require('./OpenSans-Regular.ttf'),
    'OpenSans-Regular': require('./OpenSans-Regular.ttf'),
    'OpenSans-Bold': require('./OpenSans-Bold.ttf'),
    'OpenSans-BoldItalic': require('./OpenSans-BoldItalic.ttf'),
    'OpenSans-ExtraBold': require('./OpenSans-ExtraBold.ttf'),
    'OpenSans-ExtraBoldItalic': require('./OpenSans-ExtraBoldItalic.ttf'),
    'OpenSans-Italic': require('./OpenSans-Italic.ttf'),
    'OpenSans-Light': require('./OpenSans-Light.ttf'),
    'OpenSans-LightItalic': require('./OpenSans-LightItalic.ttf'),
    'OpenSans-SemiBold': require('./OpenSans-SemiBold.ttf'),
    'OpenSans-SemiBoldItalic': require('./OpenSans-SemiBoldItalic.ttf'),
  });
}
