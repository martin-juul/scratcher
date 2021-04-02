import * as React from 'react'
import { useEffect, useState } from 'react'
import { Text as KittenText, TextProps } from '@ui-kitten/components'

export function Text(props: TextProps) {
  const [fontFamily, setFontFamily] = useState('OpenSans')

  useEffect(() => {
    switch (props.category) {
      case 'c1':
      case 'c2':
        setFontFamily('OpenSans-SemiBold')
        break
      case 'h1':
        setFontFamily('OpenSans-ExtraBold')
        break
      case 'h2':
      case 'h3':
        setFontFamily('OpenSans-Bold')
        break
      case 'h4':
      case 'h5':
        setFontFamily('OpenSans-SemiBold')
        break
    }
  }, [props.category])

  return <KittenText {...props} style={[props.style]}/>
}
