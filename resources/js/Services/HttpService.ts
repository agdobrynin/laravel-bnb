import type { AxiosError } from 'axios'
import axios from 'axios'

import { ApiError } from '@/Services/ApiError'
import type { InterfaceApiError } from '@/Services/InterfaceApiError'
import type { InterfaceHttpService } from '@/Services/InterfaceHttpService'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IBookableList } from '@/Types/IBookableList'

export default class HttpService implements InterfaceHttpService {
    constructor(private readonly endpoint?: string) {
        if (this.endpoint === undefined) {
            //@ts-ignore
            this.endpoint = import.meta.env.VITE_API_ENDPOINT
        }
    }

    getBookables(): Promise<IBookableList|InterfaceApiError> {
        return axios
            .get(`${this.endpoint}/bookables`)
            .then(response => <IBookableList>response.data)
            .catch((reason: AxiosError) => Promise.reject(new ApiError(reason)))
    }

    getBookable(id: string): Promise<IBookableItem|InterfaceApiError> {
        return axios
            .get(`${this.endpoint}/bookables/${id}`)
            .then(response => <IBookableItem>response.data)
            .catch((reason: AxiosError) => Promise.reject(new ApiError(reason)))
    }
}
