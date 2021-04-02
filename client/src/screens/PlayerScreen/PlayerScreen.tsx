import * as React from 'react'
import { useCallback, useContext, useEffect, useState } from 'react'
import { StackNavigationProp } from '@react-navigation/stack'
import { RouteProp } from '@react-navigation/native'
import { RootParamList } from '../../navigation'
import { Layout, Text } from '@ui-kitten/components'
import { Pressable, SafeAreaView, StyleSheet, View } from 'react-native'
import { Track } from '../../services/api'
import { Sound } from 'expo-av/build/Audio/Sound'
import { Audio, AVPlaybackStatus } from 'expo-av'
import { Artwork } from '../../components/Artwork'
import { humanize } from '../../formatting/duration'
import { Pause, Play, SkipBack, SkipForward } from './icons'
import { appTheme } from '../../theme'
import { APP_URL } from '@env'
import { Progress } from '../../components/Player'
import { useTypedSelector } from '../../store/rootReducer'
import { AuthContext } from '../../contexts'

type AlbumsScreenNavigationProp = StackNavigationProp<RootParamList, 'Player'>;
type Props = {
  navigation: AlbumsScreenNavigationProp;
  route: RouteProp<RootParamList, 'Player'>
}

export function PlayerScreen({navigation, route}: Props) {
  const ARTWORK = {
    height: 300,
    width: 300,
  }

  const { api } = useContext(AuthContext)
  const album = useTypedSelector(state => state.album)

  const [track, setTrack] = useState<Track>()

  const [error, setError] = useState<string>()
  const [isLoaded, setIsLoaded] = useState(false)
  const [isBuffering, setIsBuffering] = useState(false)
  const [isPlaying, setIsPlaying] = useState(false)
  const [status, setStatus] = useState<AVPlaybackStatus>()
  const [sound, setSound] = useState<Sound>()

  const [progress, setProgress] = useState(0)
  const [progressPercentage, setProgressPercentage] = useState(0)
  const [sliderPosition, setSliderPosition] = useState(0)
  const [sliderInitiated, setSliderInitiated] = useState(false)

  const [prevSha, setPrevSha] = useState<string>()
  const [nextSha, setNextSha] = useState<string>()

  useEffect(() => {
    if (album && album.loaded && track) {
      let trackNumber = track.track_number
      if (trackNumber) {
        trackNumber = Number(trackNumber)

        const prev = album.data.tracks.filter(x => (Number(x.track_number) + 1) === trackNumber)[0]
        if (prev) setPrevSha(prev.sha256)

        const next = album.data.tracks.filter(x => (Number(x.track_number) - 1) === trackNumber)[0]
        if (next) setNextSha(next.sha256)
      }
    }
  }, [album, track])

  useEffect(() => {
    if (route.params.albumSlug && route.params.sha) {
      api.track(route.params.albumSlug, route.params.sha).then(r => setTrack(r))
    }
  }, [route.params.albumSlug, route.params.sha, api])

  React.useEffect(() => {
    Audio.setAudioModeAsync({
      playsInSilentModeIOS: true,
      staysActiveInBackground: true,
      shouldDuckAndroid: false,
    })

    return sound
      ? () => {
        console.log('Unloading Sound')
        sound.unloadAsync()
      }
      : undefined
  }, [sound])

  useEffect(() => {
    const onPlaybackStatusUpdate = (status: AVPlaybackStatus) => {
      setStatus(status)

      if (!status.isLoaded) {
        if (status.error) {
          setError(status.error)
        }
      } else {
        setIsLoaded(true)
        setIsBuffering(status.isBuffering)
        setIsPlaying(status.isPlaying)
        setProgress(status.positionMillis / 1000)
      }
    }

    if (track) {
      Audio.Sound.createAsync({
        uri: APP_URL + '/api/stream/' + route.params.sha,
        overrideFileExtensionAndroid: track.file_format,
      }, {
        shouldPlay: true,
      }).then(({sound}) => {
        sound._subscribeToNativeEvents()
        sound.setOnPlaybackStatusUpdate(onPlaybackStatusUpdate)

        setSound(sound)
      }).catch(console.error)
    }
  }, [track])

  useEffect(() => {
    if (!sliderInitiated) {
      setSliderPosition(progressPercentage)
    }
  }, [sliderInitiated, progressPercentage])

  useEffect(() => {
    if (isPlaying && !sliderInitiated) {
      if (track && track.length) {
        const percentage = progress / track?.length * 100
        setProgressPercentage(percentage)
      }
    }
  }, [isPlaying, sliderInitiated, progress, track])

  const togglePlayPause = useCallback(() => {
    isPlaying
      ? sound?.pauseAsync()
      : sound?.playAsync()
  }, [isPlaying])

  const skip = (next: boolean) => {
    if (next && !nextSha) return
    if (!next && !prevSha) return

    navigation.navigate('Player', {
      albumSlug: album.data?.slug as string,
      sha: (next ? nextSha : prevSha) as string,
      prevSha: prevSha,
      nextSha: nextSha,
    })
  }

  const PlayPauseIcon = () => isPlaying ? <Pause/> : <Play/>

  return (
    <Layout style={{flex: 1}}>
      <SafeAreaView>
        {(album && album.loaded && track) && (
          <>
            <View style={styles.artwork}>
              {
                (album && album.data.artwork)
                  ? (<Artwork
                    artwork={album.data.artwork}
                    height={ARTWORK.height}
                    width={ARTWORK.width}
                    props={{borderRadius: 5}}
                  />)
                  : (<View style={{height: ARTWORK.height, width: ARTWORK.width}}/>)
              }
            </View>

            <View style={styles.controls}>
              <Text style={styles.title} numberOfLines={1}>{track.title}</Text>
              <Text style={styles.artists} numberOfLines={1}>{track.artists.map(x => x.name).join(', ')}</Text>

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
                  disabled={!prevSha}
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
                  disabled={!nextSha}
                >
                  <SkipForward/>
                </Pressable>
              </View>
            </View>
          </>)
        }
      </SafeAreaView>
    </Layout>
  )
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
    marginTop: '20%',
    paddingHorizontal: 36,
  },
})
