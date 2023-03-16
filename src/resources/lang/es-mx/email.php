<?php
return [
    'logo' => config('mail.AWS_STORAGE_LINK_EMAIL').'/VM-mex.png',
    "cellphone" => "11 98484.0442",
    "email" => "vmb_atendimento@wvi.org",
    "email_disapproved_photo" => [
        'button_link' => config('app.front_base_url') . '/donors/change-photo/:id/:token',
        "hashtag" => "#Elegido",
        "lines" => [
            "eighth" => "necesitamos una <br />nueva foto tuya",
            "fifth" => "Al hacer clic en el botón para enviar una nueva foto, <br />se le pedirá que inicie sesión.<br />Para acceder, ingrese el correo electrónico registrado y la contraseña a continuación:",
            "first" => "La foto que enviaste no pasó los criterios establecidos. Es a través de' de esta <br />foto que los niños elegirán de ti, así que mira cuidadosamente nuestro <br />recomendaciones y extravagancias!",
            "fourth" => "La foto se imprimirá para los niños que <br /> elegir, así que seleccione la imagen <br /> más adecuado y de mejor calidad <br /> posible",
            "second" => "Elija una foto bien iluminada que sea <br /> puedo ver tu cara claramente",
            "seventh" => "Ejemplos de fotografías aprobadas:",
            "sixth" => "enviar foto de nuevo",
            "third" => "Prefiere esa foto que muestra tu <br /> ¡sonrisa! Evite exponer su imagen o paisaje <br /> inadecuado."
        ],
        "subject" => "Foto rechazada",
        "title" => "Foto rechazada."
    ],
    "email_password" => [
        "body_forgot_password" => "Se solicitó un restablecimiento de contraseña.",
        "hashtag" => "#Elegido",
        'button_link' => config('app.front_base_url') . '/reset-password/:token',
        "lines" => [
            "first" => "solicitaste un <br />restablecimiento de contraseña",
            "fourth" => "Si no ha realizado esta solicitud o si cree que un usuario no autorizado <br />ha accedido, acceda a <strong style=\"color: #ff5f00\">password.esnhado.com.br</strong> para restablecer <br />su contraseña inmediatamente.",
            "second" => "<p>Para completar el proceso, haga clic en el botón a continuación:</p>",
            "third" => "Restablecer contraseña"
        ],
        "subject" => "Recuperación de contraseña",
        "subject_forgot_password" => "Restablecer contraseña",
        "title" => "Contraseña de Email."
    ],
    "email_revelation" => [
        "button_revelation" => "¡Tiempo de avance!",
        'button_link' => config('app.front_base_url') . '/mural-crianca/:token',
        "hashtag" => "#Elegido",
        "lines" => [
            "fifth" => "Cuéntales a tus amigos sobre tu experiencia de ser #elegido. Simplemente <br />use el hashtag <strong>#Elegido</strong> y marque <strong>@visãomundialbr</strong> pero redes sociales.<br />Y si tiene alguna pregunta, comuníquese con nosotros.",
            "first" => "Ha llegado el gran día,<br />¡has sido elegido! 😍",
            "fourth" => "Si también eligió recibir la <strong>revelación física,</strong> le avisaremos<br />cuando su foto se publique por correo. ¡Esté atento a sus<br />notificaciones por correo electrónico y SMS!",
            "second" => "<p>Para saber quién lo eligió, haga clic en el botón a continuación:</p>",
            "sixth" => "",
            "third" => "Esperamos que esta nueva amistad pueda transformar el mundo.<br />Juntos podrán crear nuevas oportunidades para el presente <br />y el futuro 😊"
        ],
        "subject" => "Revelación # Elegido",
        "title" => "Correo electrónico de revelación"
    ],
    "email_transaction" => [
        "body" => "Transacción completada con éxito.",
        "hashtag" => "#Elegido",
        "lines" => [
            "fifth" => "",
            "first" => "Tenemos<br />noticias sobre su registro.",
            "fourth" => "Cuéntales a tus amigos sobre tu experiencia de ser elegido. Usa el <br /> #Elegido en redes socciales @worldvisionmx.<br />¿Tienes preguntas? ¡Contáctanos!.",
            "second" => "¡La información sobre su registro y donación se ha guardado correctamente!<br />Esté atento a los próximos pasos:",
            "sixth" => [
                "event_scheduled" => "El evento de elección está programado",
                "payment_confirmed" => "El pago esta confirmado",
                "photo_approved" => "La foto es aprobada por nuestro equipo.",
                "revealed_child" => "Se revela el nombre del niño",
                "was_chose" => "Eres elegido"
            ],
            "third" => ""
        ],
        "subject" => "Transacción realizada",
        "title" => "Transacción completada con éxito."
    ],
    "email_transaction_failed" => [
        "body" => "Transacción fallida",
        "hashtag" => "#Elegido",
        "lines" => [
            "fifth" => "Cambiar método de pago",
            "first" => "algo le pasó a<br /> su pago",
            "fourth" => ":child_quantity niño (s) (mensual)<strong>R \$ :month_price</strong><br />Desarrollo físico (único) <strong>R \$ :revelation_price</strong><br />Importe total: <strong>R \$ :total_price</strong><br />",
            "second" => "Su pago de :card ha sido rechazado. Para asegurarse de su<br />registro y participar en el proceso #Elegido, revise el <br />método de pago haciendo clic en el botón a continuación.",
            "seventh" => "",
            "sixth" => "Intentaremos procesar el pago nuevamente para que pueda<br />quedarse con nosotros y participar en este proyecto transformador.",
            "third" => "Detalles del pago"
        ],
        "subject" => "Transacción fallida",
        "title" => "Transacción fallida."
    ],
    "follow_network_links" => "sigue nuestras redes",
    "footer_email" => "World Vision tiene tolerancia cero con la violencia o el abuso contra niños,<br />adolescentes o adultos acusados por los empleados de World Vision u otros socios.",
    "greetings" => "Hola :name",
    "phone" => "0800 7070374"
];
