import * as React from 'react'
import { createContext, useCallback, useEffect, useState } from 'react'
import { ApiService } from '../services/api'
import { loadString, remove, saveString } from '../services/storage'

export interface AuthContextState {
  api: ApiService;
  isSignedIn: boolean;
  setIsSignedIn: (value: boolean) => void;
  token: string | null;
  setToken: (value: string | null) => void;
}

export const AuthContext = createContext<AuthContextState>({
  api: new ApiService(),
  isSignedIn: false,
  setIsSignedIn: () => {
  },
  token: '',
  setToken: () => {
  },
})

AuthContext.displayName = 'AuthContext'

const Provider = AuthContext.Provider

export const AUTH_TOKEN = 'AUTH_TOKEN'

export function AuthContextProvider({children}: { children: React.ReactNode }) {
  const [api] = useState(new ApiService())
  const [isSignedIn, setIsSignedIn] = useState(false)
  const [token, setAuthToken] = useState<string | null>(null)

  const setToken = useCallback((token: string | null) => {
    setAuthToken(token)
    api.setToken(token)

    if (token) {
      saveString(AUTH_TOKEN, token)
      setIsSignedIn(true)
    } else {
      remove(AUTH_TOKEN)
      setIsSignedIn(false)
    }
  }, [api])

  useEffect(() => {
    loadString(AUTH_TOKEN).then(token => setToken(token))
  }, [])

  return (
    <Provider value={{api, isSignedIn, setIsSignedIn, token, setToken}}>
      {children}
    </Provider>
  )
}
