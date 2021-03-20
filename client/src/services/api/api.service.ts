import { ApisauceInstance, create } from 'apisauce';
import { Album, AlbumCollectionResponse, Collection, CollectionRequest, Playlist, Response, Track } from './types';
import { APP_URL } from '@env';

export class ApiService {
  private client: ApisauceInstance;

  constructor() {
    this.client = create({
      baseURL: APP_URL,
      headers: {
        'X-Client': 'Scratcher App',
      },
    });
  }

  async albums(options?: Partial<CollectionRequest>): Promise<AlbumCollectionResponse> {
    options = this.collectionOptions(options);
    const res = await this.client.get('/api/albums', {
      page: options.page,
    });

    return res.data as AlbumCollectionResponse;
  }

  async album(slug: string): Promise<Album> {
    const res = await this.client.get(`/api/albums/${slug}`);

    return (res.data as Response<Album>).data;
  }

  async track(album: Album | string, sha256: string) {
    const slug = typeof album === 'string' ? album : album.slug;

    const res = await this.client.get(`/api/albums/${slug}/tracks/${sha256}`);

    return (res.data as Response<Track>).data;
  }

  async playlists(options?: Partial<CollectionRequest>) {
    options = this.collectionOptions(options);
    const res = await this.client.get('/api/playlists', {
      page: options.page,
    });

    return (res.data as Collection<Playlist>);
  }

  async createPlaylist({name, isPublic}: { name: string, isPublic: boolean }) {
    const res = await this.client.post('/api/playlists', {name, isPublic});

    return res.data;
  }

  private collectionOptions(options?: Partial<CollectionRequest>): CollectionRequest {
    return {
      page: options?.page ?? 1,
    };
  }
}
