<?php

use \App\Types\{DonorMediaTypes, PaymentTypes};

return [

    'zipcode-not-found' => 'Zipcode not found.',
    'miss-configuration' => 'Miss configuration.',
    'model-not-found' => 'Unable to return data.',
    'runtime-exception' => 'Internal server error, error code: :error_code',
    'exception-not-handled' => 'Internal server error.',
    'error-while-saving' => 'Error occurred when saving donor',
    'error-while-get-phone' => 'Error occurred while searching phone field',
    'donor-media-successfully-created' => 'Successfully created donor photo',
    'deleted' => 'Deleted',
    'revelation_email_sending_to_donor' => 'Soon the donor will receive a disclosure email.',
    'error_to_send_email' => 'Error sending the e-mail.',
    'something_wrong' => 'Something wrong happened.',
    'token_invalid' => 'Invalid token.',
    'invalid_type_file' => 'Type file invalid',

    'filter' => [
        'donation' => [
            'all' => 'All',
            'pending' => 'Pending',
            'paid' => 'Paid',
            'processing' => 'Processing',
            'refused' => 'Refused'
        ],
        'donor_media' => [
            'all' => 'All',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'reproved' => 'Reproved'
        ],
        'package' => [
            'accomplished' => 'Accomplished',
            'scheduled' => 'Scheduled',
            'pending' => 'Pending',
            'all' => 'All',
        ],
        'revelation' => [
            'digital' => 'Digital',
            'physical' => 'Physical'
        ]
    ],

    'date_updated' => 'Updated date.',
    'unexpected-error' => 'An unexpected error has occurred. Try in a few moments, if the error persists contact an administrator.',

    'donor' => [
        'removed_from_package' => 'The donor :donor was removed from package :package'
    ]
];
