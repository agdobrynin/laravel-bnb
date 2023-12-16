<?php

// Main config for BnB application
return [
    'index_per_page' => (int) env('PAGINATION_BOOKABLE_LIST_PER_PAGE', 12),
    'index_description_short_length' => 80,
    'review_per_page' => env('PAGINATION_REVIEW_LIST_PER_PAGE', 5),
    'without_review_per_page' => env('PAGINATION_BOOKING_WITHOUT_REVIEW_PER_PAGE', 10),
];
