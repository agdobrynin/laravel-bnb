import { InterfaceApiError } from '@/Services/InterfaceApiError'
import type  { IBookableItem } from '@/Types/IBookableItem'
import { IBookableList } from '@/Types/IBookableList'

export interface InterfaceHttpService {
    /**
     * Get list of bookable items
     */
    getBookables(): Promise<IBookableList | InterfaceApiError>

    /**
     * Get bookable item by id
     */
    getBookable(id: string): Promise<IBookableItem | InterfaceApiError>
}
