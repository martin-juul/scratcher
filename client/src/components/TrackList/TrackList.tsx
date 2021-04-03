import * as React from 'react'
import { useCallback } from 'react'
import { Track } from '../../services/api'
import { ListRenderItemInfo, StyleSheet, View } from 'react-native'
import { List, ListItem, Text } from '@ui-kitten/components'
import { humanize } from '../../formatting/duration'
import { appTheme } from '../../theme'
import { useAppDispatch } from '../../store'
import { addToQueue } from '../../store/queue'

export interface TrackListProps {
  tracks: Track[]
  addAllOnPlay?: boolean
}

export function TrackList({tracks, addAllOnPlay}: TrackListProps) {
  const dispatch = useAppDispatch()

  const playItem = useCallback((track: Track) => {
    if (addAllOnPlay) {
      const trackPos = tracks.findIndex(x => x === track)
      if (trackPos === -1) {
        dispatch(addToQueue(track))
      } else {
        const items = tracks.slice(trackPos)
        for (const item of items) {
          dispatch(addToQueue(track))
        }
      }
    } else {
      dispatch(addToQueue(track))
    }
  }, [tracks])

  const renderItem = ({item}: ListRenderItemInfo<Track>) => (
    <ListItem onPress={() => playItem(item)}>
      <View style={styles.item}>
        <View style={styles.header}>
          <View style={styles.track}>
            <Text style={styles.title} numberOfLines={1}>{item.title}</Text>
          </View>
          <Text style={styles.artist}>{item.artists.map(x => x.name).join(', ')}</Text>
        </View>

        <View>
          <Text style={styles.duration}>{humanize(item.length)}</Text>
        </View>
      </View>
    </ListItem>
  )

  return (
    <>
      {tracks && (
        <List
          data={tracks}
          renderItem={renderItem}
        />
      )}
    </>
  )
}

const styles = StyleSheet.create({
  item: {
    alignItems: 'center',
    justifyContent: 'space-between',
    flexDirection: 'row',
    width: '100%',
  },
  header: {
    maxWidth: '80%',
  },
  track: {
    flexDirection: 'row',
  },
  number: {
    marginRight: 5,
    fontWeight: '700',
    color: appTheme['color-basic-400'],
  },
  title: {
    fontSize: 14,
    fontWeight: '700',
  },
  artist: {
    color: appTheme['color-basic-400'],
    fontSize: 13,
    fontWeight: '600',
    marginTop: 3,
  },
  duration: {
    fontWeight: '800',
    color: appTheme['color-basic-400'],
  },
})
