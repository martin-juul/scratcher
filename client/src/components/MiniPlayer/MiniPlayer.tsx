import * as React from 'react'
import { Pressable, StyleSheet, View } from 'react-native'
import { Layout } from '@ui-kitten/components'
import { Text } from '../Text'
import { usePlayer } from '../hooks'
import { Progress } from '../Player'
import { humanize } from '../../formatting/duration'
import { Pause, Play } from '../../screens/PlayerScreen/icons'
import { useNavigation } from '@react-navigation/native'

export function MiniPlayer() {
  const navigation = useNavigation()
  const {progress, progressPercentage, track, isPlaying, togglePlayPause} = usePlayer()
  const noop = () => {
  }

  if (!track?.self) {
    return <></>
  }

  const PlayPauseIcon = () => isPlaying ? <Pause size={20}/> : <Play size={20}/>

  return (
    <Layout style={styles.container} level="2">
      <View style={styles.progress}>
        <Progress position={progress} progressPercentage={progressPercentage} onSlide={noop} onSlideEnd={noop}/>
      </View>

      <Pressable onPress={() => navigation.navigate('Player')}>
        <View style={styles.controls}>
          <View>
            <Text style={styles.title}>{track.title}</Text>
            <Text numberOfLines={1}>{track.artists.slice().map(x => x.name).join(', ')}</Text>
          </View>

          <View style={styles.rightCell}>
            <View style={{marginRight: 10}}>
              <Text>{humanize(progress)} / {humanize(track.length)}</Text>
            </View>

            <View>
              <Pressable onPress={togglePlayPause} hitSlop={{ left: 80, right: 50, top: 10, bottom: 10 }}>
                <PlayPauseIcon/>
              </Pressable>
            </View>
          </View>
        </View>
      </Pressable>
    </Layout>
  )
}

const styles = StyleSheet.create({
  container: {
    height: 60,
    width: '100%',
    padding: 10,
    justifyContent: 'center',
    position: 'relative',
  },
  progress: {position: 'absolute', top: 5, left: 0},
  controls: {flexDirection: 'row', justifyContent: 'space-between', zIndex: 1},
  rightCell: {flexDirection: 'row', alignItems: 'center', zIndex: 2},
  title: {fontWeight: '600'},
})
