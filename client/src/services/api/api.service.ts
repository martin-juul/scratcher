import { ApisauceInstance, create } from 'apisauce'
import { setupCache } from 'axios-cache-adapter'
import { APP_URL } from '@env'
import {
  Album,
  AlbumCollectionResponse,
  AuthToken,
  Collection,
  CollectionRequest,
  Playlist,
  Response,
  Track,
} from './types'
import { AsyncStorage, load, remove, save } from '../storage'

export class ApiService {
  private client: ApisauceInstance

  constructor() {
    const cache = setupCache({
      maxAge: 15 * 60 * 1000,
      store: {
        getItem: (key: string) => {
          return load(key)
        },
        async setItem(key: string, value: string | object) {
          await save(`api-service:${key}`, value)
          return value
        },
        async removeItem(key: string) {
          await remove(key)
        },
        async clear() {
          const keys = await AsyncStorage.getAllKeys()
          if (!keys) return
          const items = keys.filter(x => x.startsWith('api-service:'))
          await AsyncStorage.multiRemove(items)
        },
        async length() {
          const keys = await AsyncStorage.getAllKeys()
          if (!keys) {
            return 0
          }
          return keys.filter(x => x.startsWith('api-service:')).length
        },
      },
    })

    this.client = create({
      baseURL: APP_URL,
      adapter: cache.adapter,
    })
  }

  async authenticate(email: string, password: string) {
    const res = await this.client.post<AuthToken>(`/api/auth`, {client_name: 'Scratcher App iOS', email, password})

    return res.data
  }

  async albums(options?: Partial<CollectionRequest>): Promise<AlbumCollectionResponse> {
    options = this.collectionOptions(options)
    const res = await this.client.get('/api/albums', {
      page: options.page,
    })

    return res.data as AlbumCollectionResponse
  }

  async album(slug: string): Promise<Album> {
    const res = await this.client.get(`/api/albums/${slug}`)

    return (res.data as Response<Album>).data
  }

  async track(url: string) {
    const res = await this.client.get(url)

    return (res.data as Response<Track>).data
  }

  async playlist(slug: string) {
    const res = await this.client.get(`/api/playlists/${slug}`)

    return (res.data as Response<Playlist>).data
  }

  async playlists(options?: Partial<CollectionRequest>) {
    options = this.collectionOptions(options)
    const res = await this.client.get('/api/playlists', {
      page: options.page,
    })

    return (res.data as Collection<Playlist>)
  }

  async createPlaylist({name, isPublic}: { name: string, isPublic: boolean }) {
    const res = await this.client.post('/api/playlists', {name, isPublic})

    return res.data
  }

  setToken(token: string | null) {
    if (token) {
      this.client.setHeader('authorization', `Bearer ${token}`)
    } else {
      this.client.deleteHeader('authorization')
    }
  }

  private collectionOptions(options?: Partial<CollectionRequest>): CollectionRequest {
    return {
      page: options?.page ?? 1,
    }
  }
}
