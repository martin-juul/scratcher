import * as React from 'react'
import { useEffect, useState } from 'react'
import { Card, Layout, List, Text } from '@ui-kitten/components'
import { ListRenderItemInfo, Pressable, SafeAreaView, StyleSheet, TextStyle, View } from 'react-native'
import { Playlist } from '../../services/api'
import { BottomTabNavigationProp } from '@react-navigation/bottom-tabs'
import { PlaylistsParamList } from '../../navigation/playlists-navigator'
import { useApi } from '../../services/api/use-api'

type PlaylistsScreenNavigationProp = BottomTabNavigationProp<PlaylistsParamList, 'Playlists'>;
type Props = {
  navigation: PlaylistsScreenNavigationProp;
}

export function PlaylistsScreen({navigation}: Props) {
  const api = useApi()
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
        </View>
      </Pressable>
    </Card>
  )

  return (
    <Layout style={styles.container}>
      <SafeAreaView>
        <List
          contentContainerStyle={styles.contentContainer}
          data={playlists}
          renderItem={renderItem}
          numColumns={1}
        />
      </SafeAreaView>
    </Layout>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    flexDirection: 'row',
  },
  contentContainer: {
    padding: 0,
    justifyContent: 'space-between',
  },
  item: {
    backgroundColor: 'transparent',
    borderColor: 'transparent',
    alignItems: 'center',
    width: '50%',
  },
  info: {
    marginTop: 10,
  },
  artist: {
    color: '#fff',
    fontWeight: '500',
    fontSize: 14,
  } as TextStyle,
  title: {
    color: '#fff',
    fontWeight: '500',
    marginTop: 2.5,
    fontSize: 12,
  } as TextStyle,
})
