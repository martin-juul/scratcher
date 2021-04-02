import { useContext, useEffect, useState } from 'react'
import { AuthContext } from '../../contexts/AuthContext'
import { ApiService } from './api.service'

export function useApi() {
  const auth = useContext(AuthContext)
  const [api] = useState(new ApiService())

  useEffect(() => {
    if (auth.isSignedIn && auth.token) {
      api.setToken(auth.token)
    }
  }, [auth.isSignedIn, auth.token])

  return api
}
