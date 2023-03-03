import { format } from 'timeago.js'

export const timeAgo = (date: string): string => format(date)

export const dateFromString = (input: string): Date | undefined => {
    const date: Date = new Date(input)

    if (!isNaN(date.getTime())) {
        date.setHours(0, 0, 0, 0)

        return date
    }

    return undefined
}

export const dateAsLocaleString = (input: string, locales?: string | string[], options?: Intl.DateTimeFormatOptions): string | null => {
    return dateFromString(input)?.toLocaleDateString(locales, options) || null
}
