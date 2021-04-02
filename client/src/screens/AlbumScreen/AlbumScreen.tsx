import * as React from 'react'
import { useEffect, useState } from 'react'
import { SafeAreaView, StyleSheet, View } from 'react-native'
import { Layout, Text } from '@ui-kitten/components'
import { StackNavigationProp } from '@react-navigation/stack'
import { AlbumsParamList } from '../../navigation/albums-navigator'
import { RouteProp } from '@react-navigation/native'
import { Album } from '../../services/api'
import { Artwork } from '../../components/Artwork'
import { TrackList } from '../../components/TrackList'
import { setAlbum as storeSetAlbum } from '../../store/album'
import { useAppDispatch } from '../../store'
import { useApi } from '../../services/api/use-api'

type AlbumsScreenNavigationProp = StackNavigationProp<AlbumsParamList, 'Album'>;
type Props = {
  navigation: AlbumsScreenNavigationProp;
  route: RouteProp<AlbumsParamList, 'Album'>
}

export function AlbumScreen({navigation, route}: Props) {
  const [album, setAlbum] = useState<Album>()
  const api = useApi()
  const dispatch = useAppDispatch()

  useEffect(() => {
    api.album(route.params.slug).then(r => setAlbum(r))
  }, [route.params.slug, api])

  useEffect(() => {
    if (album) {
      navigation.setOptions({title: album.title})
      dispatch(storeSetAlbum(album))
    }
  }, [album])

  return (
    <Layout style={styles.container}>
      <SafeAreaView style={styles.view}>
        {album && (
          <>
            <View style={{height: '43%'}}>
              <View style={styles.cover}>
                {album.artwork && (
                  <Artwork
                    artwork={album.artwork}
                    height={200}
                    width={200}
                    props={{style: styles.artwork}}
                  />
                )}
              </View>

              <View style={styles.header}>
                <Text style={styles.title}>{album.title}</Text>
                <Text style={styles.artist}>{album.artist?.name}</Text>
              </View>
            </View>

            <View style={{height: '60%'}}>
              <TrackList albumSlug={album.slug} tracks={album.tracks}/>
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
  artist: {
    fontWeight: '600',
    marginTop: 8,
  },
})
