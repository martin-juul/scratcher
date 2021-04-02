import * as React from 'react'
import { createContext, useState } from 'react'

export interface AuthContextState {
  isSignedIn: boolean;
  setIsSignedIn: (value: boolean) => void;
  token: string | null;
  setToken: (value: string | null) => void;
}

export const AuthContext = createContext<AuthContextState>({
  isSignedIn: false,
  setIsSignedIn: () => {
  },
  token: '',
  setToken: () => {
  },
})

AuthContext.displayName = 'AuthContext'

const Provider = AuthContext.Provider

export function AuthContextProvider({children}: { children: React.ReactNode }) {
  const [isSignedIn, setIsSignedIn] = useState(false)
  const [token, setToken] = useState<string | null>(null)

  return (
    <Provider value={{isSignedIn, setIsSignedIn, token, setToken}}>
      {children}
    </Provider>
  )
}
