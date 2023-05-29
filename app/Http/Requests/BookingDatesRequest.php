<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\QueryParameter(
    name: 'start',
    required: true,
    schema: new OA\Schema(
        title: 'Start date for check ability booking',
        type: 'string',
        format: 'date',
        pattern: '^\d{4}-\d{2}-\d{2}$'
    )
)]
#[OA\QueryParameter(
    name: 'end',
    required: true,
    schema: new OA\Schema(
        title: 'End date for check ability booking',
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
