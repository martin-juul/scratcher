import { useCallback, useEffect, useState } from 'react'
import { Audio, AVPlaybackStatus } from 'expo-av'
import { Sound } from 'expo-av/build/Audio/Sound'
import { Track } from '../../services/api'
import { nextInQueue } from '../../store/queue'
import { useAppDispatch } from '../../store'
import { useTypedSelector } from '../../store/rootReducer'

export function usePlayer() {
  const [track, setTrack] = useState<Track>()
  const [sliderPosition, setSliderPosition] = useState(0)
  const [sliderInitiated, setSliderInitiated] = useState(false)

  const [error, setError] = useState<string>()
  const [isLoaded, setIsLoaded] = useState(false)
  const [isBuffering, setIsBuffering] = useState(false)
  const [isPlaying, setIsPlaying] = useState(false)
  const [status, setStatus] = useState<AVPlaybackStatus>()
  const [sound, setSound] = useState<Sound>()

  const [progress, setProgress] = useState(0)
  const [progressPercentage, setProgressPercentage] = useState(0)

  const dispatch = useAppDispatch()
  const queue = useTypedSelector(state => state.queue)

  useEffect(() => {
    if (!queue.nowPlaying.track) {
      dispatch(nextInQueue())
    } else {
      if (!track || track.self !== queue.nowPlaying.track.self) {
        setTrack(queue.nowPlaying.track)
      }
    }
  }, [queue.nowPlaying.track])

  useEffect(() => {
    Audio.setAudioModeAsync({
      playsInSilentModeIOS: true,
      staysActiveInBackground: true,
      shouldDuckAndroid: false,
    })

    return sound
      ? () => {
        console.log('Unloading Sound')
        sound.unloadAsync()
      }
      : undefined
  }, [sound])

  useEffect(() => {
    const onPlaybackStatusUpdate = (status: AVPlaybackStatus) => {
      setStatus(status)

      if (!status.isLoaded) {
        if (status.error) {
          setError(status.error)
        }
      } else {
        setIsLoaded(true)
        setIsBuffering(status.isBuffering)
        setIsPlaying(status.isPlaying)
        setProgress(status.positionMillis / 1000)
      }
    }

    if (track) {
      const source = {
        uri: track.stream,
        overrideFileExtensionAndroid: track.file_format,
      }

      if (sound) {
        sound.loadAsync(source, {shouldPlay: true})
      } else {
        Audio.Sound.createAsync(source, {
          shouldPlay: true,
        }).then(({sound}) => {
          sound._subscribeToNativeEvents()
          sound.setOnPlaybackStatusUpdate(onPlaybackStatusUpdate)

          setSound(sound)
        }).catch(console.error)
      }
    }
  }, [track])

  useEffect(() => {
    if (!sliderInitiated) {
      setSliderPosition(progressPercentage)
    }
  }, [sliderInitiated, progressPercentage])

  useEffect(() => {
    if (isPlaying && !sliderInitiated) {
      if (track && track.length) {
        const percentage = progress / track?.length * 100
        setProgressPercentage(percentage)
      }
    }
  }, [isPlaying, sliderInitiated, progress, track])

  const togglePlayPause = useCallback(() => {
    isPlaying
      ? sound?.pauseAsync()
      : sound?.playAsync()
  }, [isPlaying])

  return {
    track,
    sliderPosition,
    setSliderPosition,
    sliderInitiated,
    setSliderInitiated,
    error,
    isLoaded,
    isBuffering,
    status,
    progress,
    progressPercentage,
    togglePlayPause,
    isPlaying,
  }
}
