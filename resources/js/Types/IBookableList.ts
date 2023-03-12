import type { IBookable } from '@/Types/IBookable'
import type { IPagination } from '@/Types/IPagination'

export interface IBookableList extends IPagination {
    data: IBookable[]
}
