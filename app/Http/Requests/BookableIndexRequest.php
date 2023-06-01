<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\QueryParameter(parameter: 'bookableCategoryIdInQuery', name: 'bookableCategoryId', description: 'Bookable category', required: false, schema: new OA\Schema(type: 'integer'))]
#[OA\QueryParameter(parameter: 'priceMinInQuery', name: 'priceMin', description: 'Bookable min regular price per pay', required: false, schema: new OA\Schema(type: 'integer'))]
#[OA\QueryParameter(parameter: 'priceMaxInQuery', name: 'priceMax', description: 'Bookable max regular price per pay', required: false, schema: new OA\Schema(type: 'integer'))]
#[OA\QueryParameter(parameter: 'priceWeekendMinInQuery', name: 'priceWeekendMin', description: 'Bookable min weekend price per pay', required: false, schema: new OA\Schema(type: 'integer'))]
#[OA\QueryParameter(parameter: 'priceWeekendMaxInQuery', name: 'priceWeekendMax', description: 'Bookable max weekend price per pay', required: false, schema: new OA\Schema(type: 'integer'))]
class BookableIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bookableCategoryId' => 'nullable|integer',
            'priceMin' => 'nullable|integer',
            'priceMax' => 'nullable|integer',
            'priceWeekendMin' => 'nullable|integer',
            'priceWeekendMax' => 'nullable|integer',
        ];
    }
}
