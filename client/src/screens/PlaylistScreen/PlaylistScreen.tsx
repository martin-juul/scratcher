import * as React from 'react';
import { useEffect, useState } from 'react';
import { SafeAreaView, StyleSheet, View } from 'react-native';
import { Layout, Text } from '@ui-kitten/components';
import { StackNavigationProp } from '@react-navigation/stack';
import { RouteProp } from '@react-navigation/native';
import { ApiService, Playlist } from '../../services/api';
import { useAppDispatch } from '../../store';
import { PlaylistsParamList } from '../../navigation/playlists-navigator';

type PlaylistScreenNavigationProp = StackNavigationProp<PlaylistsParamList, 'Playlist'>;
type Props = {
  navigation: PlaylistScreenNavigationProp;
  route: RouteProp<PlaylistsParamList, 'Playlist'>
}

export function PlaylistScreen({navigation, route}: Props) {
  const [playlist, setPlaylist] = useState<Playlist>();
  const dispatch = useAppDispatch();

  useEffect(() => {
    const api = new ApiService();

    api.playlist(route.params.slug).then(r => setPlaylist(r));
  }, [route.params.slug]);

  useEffect(() => {
    if (playlist) {
      navigation.setOptions({title: playlist.name});
      // dispatch(storeSetAlbum(album))
    }
  }, [playlist]);

  return (
    <Layout style={styles.container}>
      <SafeAreaView style={styles.view}>
        {playlist && (
          <>
            <View style={styles.header}>
              <Text style={styles.title}>{playlist.name}</Text>
            </View>

            <View style={{ flexDirection: 'column' }}>
              {playlist.tracks?.map(track => (
                <View style={{ width: '100%' }}>
                  <Text>{track.title}</Text>
                  <Text>{track.artists.join(', ')}</Text>
                </View>
              ))}
            </View>
          </>
        )}
      </SafeAreaView>
    </Layout>
  );
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
});
