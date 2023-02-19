import { format } from 'timeago.js'

export const timeAgo = (date: string): string => format(date)
