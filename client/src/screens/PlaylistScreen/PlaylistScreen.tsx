import * as React from 'react'
import { useContext, useEffect, useState } from 'react'
import { SafeAreaView, StyleSheet, View } from 'react-native'
import { Layout, Text } from '@ui-kitten/components'
import { StackNavigationProp } from '@react-navigation/stack'
import { RouteProp } from '@react-navigation/native'
import { Playlist } from '../../services/api'
import { PlaylistsParamList } from '../../navigation/playlists-navigator'
import { AuthContext } from '../../contexts'
import { TrackList } from '../../components/TrackList'
import { useTypedSelector } from '../../store/rootReducer'

type PlaylistScreenNavigationProp = StackNavigationProp<PlaylistsParamList, 'Playlist'>;
type Props = {
  navigation: PlaylistScreenNavigationProp;
  route: RouteProp<PlaylistsParamList, 'Playlist'>
}

export function PlaylistScreen({navigation, route}: Props) {
  const [playlist, setPlaylist] = useState<Playlist>()
  const {api} = useContext(AuthContext)
  const queue = useTypedSelector(state => state.queue)

  useEffect(() => {
    api.playlist(route.params.slug).then(r => setPlaylist(r))
  }, [route.params.slug, api])

  useEffect(() => {
    if (playlist) {
      navigation.setOptions({title: playlist.name})
    }
  }, [playlist])

  return (
    <Layout style={styles.container}>
      <SafeAreaView style={styles.view}>
        {(playlist && playlist.tracks) && (
          <>
            <View style={styles.header}>
              <Text style={styles.title}>{playlist.name}</Text>
            </View>

            <TrackList tracks={playlist.tracks} addAllOnPlay={queue.tracks.length < 1}/>
          </>
        )}
      </SafeAreaView>
    </Layout>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  view: {
    paddingHorizontal: 18,
    paddingVertical: 20,
  },
  cover: {
    marginBottom: 12,
    alignSelf: 'center',
  },
  artwork: {
    borderRadius: 6,
  },
  header: {
    height: 50,
    marginBottom: 12,
  },
  title: {
    fontWeight: '600',
    fontSize: 22,
  },
  trackTitle: {
    fontWeight: '600',
    fontSize: 18,
  },
  artist: {
    fontWeight: '600',
    marginTop: 2,
  },
})
