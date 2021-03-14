import * as React from 'react';
import Icon from 'react-native-vector-icons/Ionicons';
import { appTheme } from '../../theme';
import { IconProps } from 'react-native-vector-icons/Icon';

const ICON_SIZE = 56;

const COLOR = appTheme['color-basic-300'];

export const Play = (props?: Partial<IconProps>) => <Icon
  color={COLOR}
  size={ICON_SIZE}
  name="ios-play"
  {...props}
/>;

export const Pause = (props?: Partial<IconProps>) => <Icon
  color={COLOR}
  size={ICON_SIZE}
  name="ios-pause"
  {...props}
/>;

export const SkipBack = (props?: Partial<IconProps>) => <Icon
  color={COLOR}
  size={ICON_SIZE}
  name="ios-play-skip-back"
  {...props}
/>;

export const SkipForward = (props?: Partial<IconProps>) => <Icon
  color={COLOR}
  size={ICON_SIZE}
  name="ios-play-skip-forward"
  {...props}
/>;
