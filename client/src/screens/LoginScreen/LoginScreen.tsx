import * as React from 'react'
import { useCallback, useContext, useState } from 'react'
import { Button, Input, Layout } from '@ui-kitten/components'
import { Text } from '../../components/Text'
import { AuthContext } from '../../contexts/AuthContext'
import { ApiService } from '../../services/api'
import { TextStyle, ViewStyle } from 'react-native'

export function LoginScreen() {
  const auth = useContext(AuthContext)
  const [email, setEmail] = useState<string>()
  const [password, setPassword] = useState<string>()

  const login = useCallback(() => {
    if (!email || !password) {
      return
    }

    const api = new ApiService()
    api.authenticate(email, password)
      .then((r) => {
        auth.setIsSignedIn(Boolean(r?.token))
        auth.setToken(r?.token || null)
      })

  }, [auth, email, password])

  return (
    <Layout style={{flex: 1}}>
      <Layout level="2" style={{marginTop: '50%'}}>
        <Input
          label={() => <Text style={LABEL}>Email</Text>}
          onChangeText={setEmail}
          style={FIELD}
          autoCapitalize="none"
          autoCorrect={false}
          autoFocus
          textContentType="emailAddress"
        />

        <Input
          label={() => <Text style={LABEL}>Password</Text>}
          onChangeText={setPassword}
          style={FIELD}
          secureTextEntry
        />
      </Layout>

      <Button
        onPress={() => login()}
        disabled={!email || !password}
        style={{marginTop: 25, marginHorizontal: 100}}
      >Login</Button>
    </Layout>
  )
}

const FIELD: ViewStyle = {
  marginHorizontal: 50,
  padding: 15,
}

const LABEL: TextStyle = {
  marginBottom: 10,
}
