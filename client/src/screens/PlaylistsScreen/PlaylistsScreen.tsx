import * as React from 'react'
import { useContext, useEffect, useState } from 'react'
import { Button, Card, Layout, List, Text } from '@ui-kitten/components'
import { ListRenderItemInfo, Pressable, StyleSheet, TextStyle, View } from 'react-native'
import { Playlist } from '../../services/api'
import { BottomTabNavigationProp } from '@react-navigation/bottom-tabs'
import { PlaylistsParamList } from '../../navigation/playlists-navigator'
import { AuthContext } from '../../contexts'

type PlaylistsScreenNavigationProp = BottomTabNavigationProp<PlaylistsParamList, 'Playlists'>;
type Props = {
  navigation: PlaylistsScreenNavigationProp;
}

export function PlaylistsScreen({navigation}: Props) {
  const {api} = useContext(AuthContext)
  const [playlists, setPlaylists] = useState<Omit<Playlist, 'tracks'>[]>([])

  useEffect(() => {
    api.playlists().then(r => setPlaylists(r.data))
  }, [api])

  const renderItem = ({item}: ListRenderItemInfo<Omit<Playlist, 'tracks'>>) => (
    <Card
      style={styles.item}
    >
      <Pressable
        android_disableSound
        style={({pressed}) => [
          {
            width: '100%',
            backgroundColor: pressed
              ? 'rgba(0, 0, 0, 0.33)'
              : 'transparent',
          },
        ]}
        onPress={() => navigation.navigate('Playlist', {slug: item.slug})}
      >
        <View style={styles.info}>
          <Text numberOfLines={1} style={styles.title}>{item.name}</Text>
          <Text style={styles.tracksCount}>
            <>
              {item.trackCount} Songs
            </>
          </Text>
        </View>
      </Pressable>
    </Card>
  )

  return (
    <Layout style={styles.container}>
      <View style={styles.header}>
        <Button
          style={styles.createPlaylistButton}
          status="basic"
        >Create playlist</Button>
      </View>

      <List
        contentContainerStyle={styles.contentContainer}
        data={playlists}
        renderItem={renderItem}
        numColumns={1}
      />
    </Layout>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  contentContainer: {
    padding: 0,
    margin: 0,
    justifyContent: 'space-between',
  },
  header: {
    alignItems: 'center',
    padding: 15,
  },
  createPlaylistButton: {
    borderRadius: 150,
    height: 50,
    width: '60%',
  },
  item: {
    backgroundColor: 'transparent',
    borderColor: 'transparent',
  },
  info: {
    flexDirection: 'column',
    marginTop: 10,
  },
  artist: {
    color: '#fff',
    fontWeight: '500',
    fontSize: 14,
  } as TextStyle,
  title: {
    color: '#fff',
    fontWeight: '600',
    marginTop: 2.5,
    fontSize: 16,
  } as TextStyle,
  tracksCount: {
    color: '#ccc',
    fontWeight: '500',
    fontSize: 14,
    marginTop: 2.5,
  },
})
