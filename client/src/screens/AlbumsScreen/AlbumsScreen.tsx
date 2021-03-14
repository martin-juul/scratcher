import * as React from 'react';
import { useEffect, useState } from 'react';
import { Card, Layout, List, Text } from '@ui-kitten/components';
import { ListRenderItemInfo, Pressable, SafeAreaView, StyleSheet, TextStyle, View } from 'react-native';
import { Album, ApiService } from '../../services/api';
import { Artwork } from '../../components/Artwork';
import { BottomTabNavigationProp } from '@react-navigation/bottom-tabs';
import { AlbumsParamList } from '../../navigation/albums-navigator';

type AlbumsScreenNavigationProp = BottomTabNavigationProp<AlbumsParamList, 'Albums'>;
type Props = {
  navigation: AlbumsScreenNavigationProp;
}

export function AlbumsScreen({navigation}: Props) {

  const [albums, setAlbums] = useState<Omit<Album, 'tracks'>[]>([]);

  useEffect(() => {
    const api = new ApiService();
    api.albums().then(r => setAlbums(r.data));
  }, []);


  const renderItem = ({item}: ListRenderItemInfo<Omit<Album, 'tracks'>>) => (
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
        onPress={() => navigation.navigate('Album', {slug: item.slug})}
      >

        <View>
          {item.artwork && (
            <Artwork
              artwork={item.artwork}
              height={160}
              width={160}
              props={{borderRadius: 5, style: {opacity: 1}}}
            />
          )}
        </View>

        <View style={styles.info}>
          <Text numberOfLines={1} style={styles.artist}>{item.artist?.name}</Text>
          <Text numberOfLines={1} style={styles.title}>{item.title}</Text>
        </View>
      </Pressable>
    </Card>
  );

  return (
    <Layout style={styles.container}>
      <SafeAreaView>
        <List
          contentContainerStyle={styles.contentContainer}
          data={albums}
          renderItem={renderItem}
          numColumns={2}
        />
      </SafeAreaView>
    </Layout>
  );
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
});
