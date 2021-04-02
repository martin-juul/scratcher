import * as React from 'react'
import { useState } from 'react'
import { Animated, PanResponder, View } from 'react-native'

export interface ProgressProps {
  position: number;
  progressPercentage: number;
  onSlide: () => void;
  onSlideEnd: () => void;
}

export function Progress({position, progressPercentage, onSlide, onSlideEnd}: ProgressProps) {
  const [isSliding, setIsSliding] = useState(false)

  const panResponder = React.useRef(
    PanResponder.create({
      // Ask to be the responder:
      onStartShouldSetPanResponder: () => true,
      onStartShouldSetPanResponderCapture: () => true,
      onMoveShouldSetPanResponder: () => true,
      onMoveShouldSetPanResponderCapture: () => true,
      onPanResponderGrant: (evt, gestureState) => {
        // The gesture has started. Show visual feedback so the user knows
        // what is happening!
        // gestureState.d{x,y} will be set to zero now
        console.log('onPanResponderGrant', gestureState)
        setIsSliding(true)
        onSlide()
      },
      onPanResponderTerminationRequest: () => true,
      onPanResponderRelease: (evt, gestureState) => {
        // The user has released all touches while this view is the
        // responder. This typically means a gesture has succeeded
        console.log('onPanResponderRelease', gestureState)
        setIsSliding(false)
        onSlideEnd()
      },
      onPanResponderTerminate: () => {
        setIsSliding(false)
        onSlideEnd()
      },
    }),
  ).current

  return (
    <>
      <View style={{
        zIndex: 1,
        position: 'absolute',
        width: `${position}%`,
        height: 6,
        backgroundColor: '#707070',
        borderRadius: 20,
        opacity: 0.7,
      }}/>

      <Animated.View
        {...panResponder.panHandlers}
        style={{zIndex: 2}}
      >
        <View style={{
          position: 'absolute',
          zIndex: 2,
          height: 12,
          width: 12,
          backgroundColor: isSliding ? '#ccc' : '#b1b1b1',
          borderRadius: 50,
          top: -8,
          left: `${progressPercentage}%`,
        }}/>
      </Animated.View>
    </>
  )
}
