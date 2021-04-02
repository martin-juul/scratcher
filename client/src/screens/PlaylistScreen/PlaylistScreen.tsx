import * as React from 'react'
import { useContext, useEffect, useState } from 'react'
import { SafeAreaView, StyleSheet, View } from 'react-native'
import { Layout, Text } from '@ui-kitten/components'
import { StackNavigationProp } from '@react-navigation/stack'
import { RouteProp } from '@react-navigation/native'
import { Playlist } from '../../services/api'
import { useAppDispatch } from '../../store'
import { PlaylistsParamList } from '../../navigation/playlists-navigator'
import { AuthContext } from '../../contexts'

type PlaylistScreenNavigationProp = StackNavigationProp<PlaylistsParamList, 'Playlist'>;
type Props = {
  navigation: PlaylistScreenNavigationProp;
  route: RouteProp<PlaylistsParamList, 'Playlist'>
}

export function PlaylistScreen({navigation, route}: Props) {
  const [playlist, setPlaylist] = useState<Playlist>()
  const { api } = useContext(AuthContext)
  const dispatch = useAppDispatch()

  useEffect(() => {
    api.playlist(route.params.slug).then(r => setPlaylist(r))
  }, [route.params.slug, api])

  useEffect(() => {
    if (playlist) {
      navigation.setOptions({title: playlist.name})
      // dispatch(storeSetAlbum(album))
    }
  }, [playlist])

  return (
    <Layout style={styles.container}>
      <SafeAreaView style={styles.view}>
        {playlist && (
          <>
            <View style={styles.header}>
              <Text style={styles.title}>{playlist.name}</Text>
            </View>

            <View style={{flexDirection: 'column'}}>
              {playlist.tracks?.map(track => (
                <View style={{width: '100%'}} key={track.sha256 + track.order}>
                  <Text style={styles.trackTitle}>{track.title}</Text>
                  <Text style={styles.artist}>{track.artists.slice().map(x => x.name).join(', ')}</Text>
                </View>
              ))}
            </View>
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
