<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'logo' => config('mail.AWS_STORAGE_LINK_EMAIL').'/ico-vm.png',
    'greetings' => 'Hello :name',

    'follow_network_links' => 'Follow our networks',
    'email' => 'vmb_atendimento@wvi.org',
    'cellphone' => '11 98484.0442',
    'phone' => '0800 7070374',
    'footer_email' => 'World Vision has zero tolerance for violence or abuse against children,<br />teens or adults accused by World Vision staff or other partners.',

    'email_revelation' => [
        'subject' => 'Revelation #Chosen',
        'title' => 'Revelation Email',
        'hashtag' => '#Chosen',
        'button_revelation' => 'Reveal time!',
        'button_link' => config('app.front_base_url') . '/mural-crianca/:token',
        'lines' => [
            'first' => 'The big day has arrived,<br />you have been chosen! 😍',
            'second' => '<p>To find out who chose you, click on the button below:</p>',
            'third' => 'We hope that this new friendship will be able to transform the world.<br />Together you will be able to create new opportunities for the present<br />and the future 😊',
            'fourth' => 'If you also chose to receive physical <strong>disclosure,</strong> we will let you know<br />when your photo is posted in the mail. So keep an eye on your<br />email and SMS notifications!',
            'fifth' => 'Tell your friends about your experience of being #chosen. Just <br />use the hashtag <strong>#Chosen</strong> and mark the <strong>@worldviewbr</strong> but social networks.<br />And if you have any questions, please get in touch with we.',
            'sixth' => 'With love, <br /><strong> Visão Mundial.</strong>',
        ]
    ],

    'email_password' => [
        'subject' => 'Password recovery',
        'title' => 'Password Email.',
        'hashtag' => '#Chosen',
        'button_link' => config('app.front_base_url') . '/reset-password/:token',
        'lines' => [
            'first' => 'you requested a <br />password reset',
            'second' => '<p>To complete the process, click on the button below.:</p>',
            'third' => 'Redefine password',
            'fourth' => 'If you have not made this request or if you believe that an unauthorized <br />user has logged in, please access <strong style="color: #ff5f00">password.eselect.com.br</strong> to reset <br />your password immediately.',
        ],
        'subject_forgot_password' => 'Password recovery',
        'body_forgot_password' => 'A password reset was requested.',
    ],

    'email_disapproved_photo' => [
        'subject' => 'Disapproved Photo',
        'title' => 'Disapproved Photo.',
        'hashtag' => '#Chosen',
        'button_link' => config('app.front_base_url') . '/donors/change-photo/:id/:token',
        'lines' => [
            'first' => 'The photo you sent did not pass the established criteria. Its through
             of this <br />picture that the kids will choose from you, so watch
             carefully our <br />
             recommendations and whimsy!',
            'second' => 'Choose a well-lit photo, which <br />
                     can see your face clearly',
            'third' => 'Prefer that photo that shows your <br />
                     smile! Avoid exposing your image or scenery <br />
                     inadequate.',
            'fourth' => 'The photo will be printed for the children you<br />
                     choose, so select the image <br />
                     more suitable and of better quality<br />
                     possible',
            'fifth' => 'By clicking the button to upload new photo, <br />
                   you will be asked for an access login.<br />
                   To access, enter the registered email and password below:',
            'sixth' => 'Send photo again',
            'seventh' => 'Examples of approved photos:',
            'eighth' => 'we need a <br />new picture of you',
        ]
    ],

    'email_transaction' => [
        'subject' => 'Transaction performed',
        'body' => 'Transaction performed successfully.',
        'title' => 'Transaction performed successfully.',
        'hashtag' => '#Chosen',
        'lines' => [
            'first' => 'We have news<br />about your registration',
            'second' => 'Information about your registration and donation has been successfully registered!<br />Stay tuned for the next steps:',
            'third' => 'We work tirelessly to ensure that the process between registration and <br />disclosure is as short as possible. The average period is 30 days but <br />we promise to always let you in on the progress here.<br />',
            'fourth' => 'Tell your friends about your experience of being #chosen.
              Just <br />use the hashtag #Eselected and mark @visãomundialbr but social networks.<br />And if you have any questions, get in touch with us.',
            'fifth' => 'With love, <br />
              <strong>Visão Mundial.</strong>',
            'sixth' => [
                'payment_confirmed' => 'Payment is confirmed',
                'photo_approved' => 'Photo is approved by our team',
                'event_scheduled' => 'Chosen event is scheduled',
                'was_chose' => 'You are chosen',
                'revealed_child' => 'Child name is revealed',
            ],
        ],
    ],

    'email_transaction_failed' => [
        'subject' => 'Transaction failed',
        'body' => 'Transaction failed',
        'title' => 'Transaction failed.',
        'hashtag' => '#Chosen',
        'lines' => [
            'first' => 'something happened to<br />
             your payment',
            'second' => 'Your :card payment was declined. To ensure your<br />registration and participate in the #Chosen process, review the payment method <br />by clicking the button below.',
            'third' => 'Payment details',
            'fourth' => ':child_quantity children (monthly)<strong>R$ :month_price</strong><br />Physical Revelation (single) <strong>R$ :revelation_price</strong><br />Total value: <strong>R$ : total_price</strong><br />',
            'fifth' => 'Change payment method',
            'sixth' => 'We will try to process your payment again so that you can<br />stay with us and participate in this transformative project.',
            'seventh' => 'With love, <br /><strong>Visão Mundial.</strong>',
        ],
    ],
];
