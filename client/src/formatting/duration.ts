export function humanize(duration: number): string {
  let hours = ~~(duration / 3600)
  let minutes = ~~((duration % 3600) / 60)
  let seconds = ~~duration % 60

  let formatted = ''

  if (hours > 0) {
    formatted += `${hours}:${minutes < 10 ? '0' : ''}`
  }

  formatted += `${minutes}:${seconds < 10 ? '0' : ''}`
  formatted += seconds

  return formatted
}
