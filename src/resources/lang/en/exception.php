<?php

return [
    'user' => [
        'password_not_redefined' => 'The password has not been changed. Error code: :code_error'
    ],
    'donor' => [
        'error_during_save' => 'Error occurred during Donor creation. Please try again in a moment.',
        'not_found' => 'No donors found.',
        'donor_event_invalid' => 'This donor does not belong to a package..',
        'not_removed_from_package' => 'The donor has not been removed from the package. Error code: :code_error',
        'not_removed_at_package' => 'The donor has not been removed from the package. Please try again in a moment.',
        'without_media' => 'The donor does not have a registered photo.'
    ],
    'donor_document' => [
        'type_not_configured' => 'Type of document not configured'
    ],
    'package' => [
        'date' => [
            'after' => 'The date must be greater than the current date.',
            'not_updated' => 'The date has not been updated. Please try again in a moment.'
        ],
        'not_found' => 'No packages found.',

    ],
    'donor_media' => [
        'already_registered' => 'The donor already has a registered photo.',
        'not_saved' => 'Error occurred saving image. Please try again in a moment.',
        'invalid_token' => 'Invalid token.',
        'invalid_id' => 'Invalid photo id.'
    ],
    'storage' => [
        'image' => [
            'error' => 'Error occurred while saving image to server. Please try again in a moment.',

        ]
    ],
    'service' => [
        'aws_s3' => [
            'upload_error' => 'Error occurred when saving image to S3 Bucket. Please try again in a moment.',
            'delete_error' => 'Error occurred when deleting image in S3 Bucket. Please try again in a moment.'
        ]
    ],
    'database' => [
        'query' => 'Error occurred during your query. Contact the administrator.'
    ],
    'children' => [
        'not_found' => 'Child not found. Error code: :code_error'
    ],
    'donation' => [
        'not_found' => 'No donations found. Error code: :code_error'
    ],
    'general' => [
        'zip_not_created' => 'Error occurred while generating zip file. Please try again in a moment. Error code: :code_error',
        'email_not_send' => 'Failed to send email. Contact an administrator. Error code: :code_error',
        'donation_not_found' => 'Donation not found. Contact an administrator. Error code: :code_error',
        'donation_not_refused' => 'No pending payment donations found.',
        'donor_or_children_not_found' => 'Donor or child not found.',
        'curp_invalid_date' => 'Invalid document date. Error code: :code_error'
    ]
];
