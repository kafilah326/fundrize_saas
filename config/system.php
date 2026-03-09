<?php

return [
    /*
    |--------------------------------------------------------------------------
    | System Fee Percentage
    |--------------------------------------------------------------------------
    |
    | This value is used to calculate operational costs or system fees
    | from total donations. It is stored as a whole number (e.g., 5 for 5%).
    |
    */
    'system_fee_percentage' => env('SYSTEM_FEE_PERCENTAGE', 5),
];
