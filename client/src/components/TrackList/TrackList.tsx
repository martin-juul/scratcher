import * as React from 'react';
import { useEffect, useState } from 'react';
import { Track } from '../../services/api';
import { ListRenderItemInfo, StyleSheet, View } from 'react-native';
import { List, ListItem, Text } from '@ui-kitten/components';
import { humanize } from '../../formatting/duration';
import { appTheme } from '../../theme';
import { useNavigation } from '@react-navigation/native';

export interface TrackListProps {
  albumSlug: string;
  tracks: Track[];
}

interface TrackItem extends Track {
  nextSha: string | undefined;
  prevSha: string | undefined;
}

export function TrackList({albumSlug, tracks}: TrackListProps) {
  const navigation = useNavigation();
  const [list, setList] = useState<TrackItem[]>();

  useEffect(() => {
    const items: TrackItem[] = [];

    for (let i = 0; i < tracks.length; i++) {
      items.push({
        ...tracks[i],
        nextSha: i < (tracks.length - 1) ? tracks[i + 1].sha256 : undefined,
        prevSha: i > 0 && i < tracks.length ? tracks[i - 1].sha256 : undefined,
      });
    }

    setList(items);
  }, [tracks]);

  const renderItem = ({item}: ListRenderItemInfo<TrackItem>) => (
    <ListItem onPress={() => navigation.navigate('Player', {
      albumSlug: albumSlug,
      sha: item.sha256,
      nextSha: item.nextSha,
      prevSha: item.prevSha,
    })}>
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
  );

  return (
    <>
      {list && (
        <List
          data={list}
          renderItem={renderItem}
        />
      )}
    </>
  );
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
});
