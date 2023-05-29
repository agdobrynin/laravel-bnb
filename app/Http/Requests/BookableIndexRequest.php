<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\PathParameter(name: 'bookableCategoryId', required: false, schema: new OA\Schema(title: 'Bookable category', type: 'integer'))]
#[OA\PathParameter(name: 'priceMin', required: false, schema: new OA\Schema(title: 'Bookable min regular price per pay', type: 'integer'))]
#[OA\PathParameter(name: 'priceMax', required: false, schema: new OA\Schema(title: 'Bookable max regular price per pay', type: 'integer'))]
#[OA\PathParameter(name: 'priceWeekendMin', required: false, schema: new OA\Schema(title: 'Bookable min weekend price per pay', type: 'integer'))]
#[OA\PathParameter(name: 'priceWeekendMax', required: false, schema: new OA\Schema(title: 'Bookable max weekend price per pay', type: 'integer'))]
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
