<?php
// Main config for BnB application
return [
    'index_per_page' => (int)env('PAGINATION_BOOKABLE_LIST_PER_PAGE', 12),
    'index_description_short_length' => 80,
];
