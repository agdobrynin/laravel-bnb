export class DateRange {
    private dateEnd: Date | undefined
    constructor(private dateStart: Date, private offsetEnd: number = 1) {
        this.dateEnd = this.nextDate(dateStart, offsetEnd)
    }

    set end (input: string) {
        const date: Date = new Date(input)

        if (!isNaN(date.getTime())) {
            date.setHours(0, 0, 0, 0)

            if (date.getTime() >= this.dateStart.getTime()) {
                this.dateEnd = date
            }
        } else {
            this.dateEnd = undefined
        }
    }

    get end () : string {
        return this.dateEnd ? this.formatted(this.dateEnd) : ''
    }

    get endMin (): string {
        return this.formatted(this.dateStart)
    }

    set start (input: string) {
        const date: Date = new Date(input)

        if (!isNaN(date.getTime())) {
            date.setHours(0, 0, 0, 0)

            if (date.getTime() >= this.dateToday.getTime()) {
                this.dateStart = date

                if (this.dateEnd && this.dateStart.getTime() > this.dateEnd.getTime()) {
                    // set offset
                    this.dateEnd = this.nextDate(this.dateStart, this.offsetEnd)
                }
            }
        }
    }

    get start (): string {
        return this.formatted(this.dateStart)
    }
    get startMin (): string {
        return this.formatted(this.dateToday)
    }

    private formatted(date: Date): string {
        const year = date.toLocaleString('default', { year: 'numeric' })
        const month = date.toLocaleString('default', { month: '2-digit' })
        const day = date.toLocaleString('default', { day: '2-digit' })

        return `${year}-${month}-${day}`
    }

    private get dateToday(): Date {
        const dateToday = new Date()
        dateToday.setHours(0, 0, 0, 0)

        return dateToday
    }

    private nextDate(date: Date, offset: number): Date {
        // set offset
        const next = new Date(date)
        next.setDate(next.getDate() + offset)

        return next
    }
}
