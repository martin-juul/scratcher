import * as React from 'react'
import { StackNavigationProp } from '@react-navigation/stack'
import { RouteProp } from '@react-navigation/native'
import { RootParamList } from '../../navigation'
import { Layout, Text } from '@ui-kitten/components'
import { Pressable, SafeAreaView, StyleSheet, View } from 'react-native'
import { Artwork } from '../../components/Artwork'
import { humanize } from '../../formatting/duration'
import { Pause, Play, SkipBack, SkipForward } from './icons'
import { appTheme } from '../../theme'
import { Progress } from '../../components/Player'
import { useTypedSelector } from '../../store/rootReducer'
import { usePlayer } from '../../components/hooks'
import { useAppDispatch } from '../../store'
import { nextInQueue } from '../../store/queue'

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

  const dispatch = useAppDispatch()
  const queue = useTypedSelector(state => state.queue)

  const {
    isPlaying,
    progress,
    progressPercentage,
    setSliderInitiated,
    sliderPosition,
    status,
    togglePlayPause,
    track,
  } = usePlayer()

  const skip = (next: boolean) => {
    if(next) {
      dispatch(nextInQueue())
    }
  }

  const PlayPauseIcon = () => isPlaying ? <Pause/> : <Play/>

  return (
    <Layout style={{flex: 1}}>
      <SafeAreaView>
        {(track) && (
          <>
            <View style={styles.artwork}>
              {
                (track && track.artwork)
                  ? (<Artwork
                    artwork={track.artwork}
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
                  disabled={true}
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
                  disabled={Boolean(queue.tracks.length < 2)}
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
