<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\QueryParameter(
    parameter: 'dateStartInQuery',
    name: 'start',
    description: 'Date from',
    required: true,
    schema: new OA\Schema(
        type: 'string',
        format: 'date',
        pattern: '^\d{4}-\d{2}-\d{2}$'
    )
)]
#[OA\QueryParameter(
    parameter: 'dateEndInQuery',
    name: 'end',
    description: 'Date to',
    required: true,
    schema: new OA\Schema(
        type: 'string',
        format: 'date',
        pattern: '^\d{4}-\d{2}-\d{2}$',
    )
)]
class BookingDatesRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start' => 'required|date_format:Y-m-d|after_or_equal:today',
            'end' => 'required|date_format:Y-m-d|after:start'
        ];
    }
}
