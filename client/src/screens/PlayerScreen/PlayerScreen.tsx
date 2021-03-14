import * as React from 'react';
import { useCallback, useEffect, useState } from 'react';
import { StackNavigationProp } from '@react-navigation/stack';
import { RouteProp } from '@react-navigation/native';
import { RootParamList } from '../../navigation';
import { Layout, Text } from '@ui-kitten/components';
import { Pressable, SafeAreaView, StyleSheet, View } from 'react-native';
import { Album, ApiService, Track } from '../../services/api';
import { Sound } from 'expo-av/build/Audio/Sound';
import { Audio, AVPlaybackStatus } from 'expo-av';
import { Artwork } from '../../components/Artwork';
import { humanize } from '../../formatting/duration';
import { Pause, Play, SkipBack, SkipForward } from './icons';
import { appTheme } from '../../theme';
import { APP_URL } from '@env';
import { Progress } from '../../components/Player';

type AlbumsScreenNavigationProp = StackNavigationProp<RootParamList, 'Player'>;
type Props = {
  navigation: AlbumsScreenNavigationProp;
  route: RouteProp<RootParamList, 'Player'>
}

export function PlayerScreen({navigation, route}: Props) {
  const ARTWORK = {
    height: 300,
    width: 300,
  };

  const [album, setAlbum] = useState<Album>();
  const [track, setTrack] = useState<Track>();

  const [error, setError] = useState<string>();
  const [isLoaded, setIsLoaded] = useState(false);
  const [isBuffering, setIsBuffering] = useState(false);
  const [isPlaying, setIsPlaying] = useState(false);
  const [status, setStatus] = useState<AVPlaybackStatus>();
  const [sound, setSound] = useState<Sound>();

  const [progress, setProgress] = useState(0);
  const [progressPercentage, setProgressPercentage] = useState(0);
  const [sliderPosition, setSliderPosition] = useState(0);
  const [sliderInitiated, setSliderInitiated] = useState(false);

  const [hasPrev] = useState(route.params.prevSha);
  const [hasNext] = useState(route.params.nextSha);

  useEffect(() => {
    if (route.params.albumSlug && route.params.sha) {
      const api = new ApiService();

      api.album(route.params.albumSlug).then(r => setAlbum(r));
      api.track(route.params.albumSlug, route.params.sha).then(r => setTrack(r));
    }
  }, [route.params.albumSlug, route.params.sha]);

  React.useEffect(() => {
    Audio.setAudioModeAsync({
      playsInSilentModeIOS: true,
      staysActiveInBackground: true,
      shouldDuckAndroid: false,
    });

    return sound
      ? () => {
        console.log('Unloading Sound');
        sound.unloadAsync();
      }
      : undefined;
  }, [sound]);

  useEffect(() => {
    const onPlaybackStatusUpdate = (status: AVPlaybackStatus) => {
      setStatus(status);

      if (!status.isLoaded) {
        if (status.error) {
          setError(status.error);
        }
      } else {
        setIsLoaded(true);
        setIsBuffering(status.isBuffering);
        setIsPlaying(status.isPlaying);
        setProgress(status.positionMillis / 1000);
      }
    };

    if (track) {
      Audio.Sound.createAsync({
        uri: APP_URL + '/api/stream/' + route.params.sha,
        overrideFileExtensionAndroid: track.file_format,
      }, {
        shouldPlay: true,
      }).then(({sound}) => {
        sound._subscribeToNativeEvents();
        sound.setOnPlaybackStatusUpdate(onPlaybackStatusUpdate);

        setSound(sound);
      }).catch(console.error);
    }
  }, [track]);

  useEffect(() => {
    if (!sliderInitiated) {
      setSliderPosition(progressPercentage);
    }
  }, [sliderInitiated, progressPercentage]);

  useEffect(() => {
    if (isPlaying && !sliderInitiated) {
      if (track && track.length) {
        const percentage = progress / track?.length * 100;
        setProgressPercentage(percentage);
      }
    }
  }, [isPlaying, sliderInitiated, progress, track]);

  const togglePlayPause = useCallback(() => {
    isPlaying
      ? sound?.pauseAsync()
      : sound?.playAsync();
  }, [isPlaying]);

  const skip = (next: boolean) => {
    if (next && !hasNext) {
      return;
    }

    if (!next && !hasPrev) {
      return;
    }

    navigation.navigate('Player', {
      albumSlug: route.params.albumSlug,
      sha: next ? route.params.nextSha as string : route.params.prevSha as string,
      prevSha: next ? route.params.sha : undefined,
      nextSha: next ? undefined : route.params.sha,
    });
  };

  const PlayPauseIcon = () => isPlaying ? <Pause/> : <Play/>;

  return (
    <Layout style={{flex: 1}}>
      <SafeAreaView>
        {track && (
          <>
            <View style={styles.artwork}>
              {
                (album && album.artwork)
                  ? (<Artwork
                    artwork={album.artwork}
                    height={ARTWORK.height}
                    width={ARTWORK.width}
                    props={{borderRadius: 5}}
                  />)
                  : (<View style={{height: ARTWORK.height, width: ARTWORK.width}}/>)
              }
            </View>

            <View style={styles.controls}>
              <Text style={styles.title}>{track.title}</Text>
              <Text style={styles.artists}>{track.artists.map(x => x.name).join(', ')}</Text>

              <View style={styles.progressContainer}>
                <View style={styles.progressTrack}/>
                {status?.isLoaded && (
                  <Progress
                    position={sliderPosition}
                    progressPercentage={progressPercentage - 2}
                    onSlide={() => setSliderInitiated(true)}
                    onSlideEnd={() => setSliderInitiated(false)}/>
                )}
              </View>

              <View style={styles.rowBetween}>
                <Text style={styles.progressTimer}>{humanize(progress)}</Text>
                <Text style={styles.progressTimer}>{humanize(track.length)}</Text>
              </View>

              <View style={[styles.buttonGroup, styles.rowBetween]}>
                <Pressable
                  hitSlop={20}
                  onPress={() => skip(false)}
                  disabled={!hasPrev}
                >
                  <SkipBack/>
                </Pressable>

                <Pressable
                  hitSlop={20}
                  onPress={() => togglePlayPause()}
                >
                  <PlayPauseIcon/>
                </Pressable>

                <Pressable
                  hitSlop={20}
                  onPress={() => skip(true)}
                  disabled={!hasNext}
                >
                  <SkipForward/>
                </Pressable>
              </View>
            </View>
          </>)
        }
      </SafeAreaView>
    </Layout>
  );
}

const styles = StyleSheet.create({
  artwork: {
    alignItems: 'center',
    justifyContent: 'center',
    marginVertical: 40,
  },
  controls: {
    marginHorizontal: 16,
  },
  title: {
    fontSize: 24,
    fontWeight: '700',
  },
  artists: {
    color: appTheme['color-basic-400'],
    fontWeight: '500',
    marginTop: 8,
  },
  progressContainer: {
    position: 'relative',
    marginVertical: 20,
  },
  progressTrack: {
    backgroundColor: '#999999',
    borderRadius: 20,
    height: 5,
    width: '100%',
    zIndex: 0,
  },
  rowBetween: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  progressTimer: {
    fontWeight: '600',
  },
  buttonGroup: {
    marginTop: 36,
    paddingHorizontal: 36,
  },
});
