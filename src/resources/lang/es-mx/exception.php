<?php
return [
    "children" => ["not_found" => "Niño no encontrado. Código de error: :code_error"],
    "database" => [
        "query" => "Se produjo un error durante su consulta. Comuníquese con el administrador."
    ],
    "donation" => ["not_found" => "No se encontraron donaciones. Código de error: :code_error"],
    "donor" => [
        "donor_event_invalid" => "Este donante no pertenece a un paquete.",
        "error_during_save" => "Se produjo un error durante la creación del donante. Vuelve a intentarlo en unos instantes.",
        "invalid" => "El donante no es válido para ingresar al paquete porque: tiene una foto pendiente,\n        tu donación aún no ha sido aprobada o no está integrada con Simma. Código de error: :code_error",
        "not_found" => "No se encontraron donantes.",
        "not_linked" => "El donante no ha sido vinculado al paquete. Código de error: :code_error",
        "not_removed_at_package" => "El donante no se ha eliminado del paquete. Vuelve a intentarlo en unos instantes.",
        "not_removed_from_package" => "El donante no se ha eliminado del paquete. Código de error: :code_error",
        "without_media" => "El donante no tiene foto registrada."
    ],
    "donor_document" => ["type_not_configured" => "Tipo de documento no configurado"],
    "donor_media" => [
        "already_registered" => "El donante ya tiene una foto registrada.",
        "invalid_id" => "Identificación con foto no válida.",
        "invalid_token" => "Simbolo no valido.",
        "not_saved" => "Se produjo un error al guardar la imagen. Vuelve a intentarlo en unos instantes."
    ],
    "general" => [
        "curp_invalid_date" => "Fecha de documento no válida. Código de error: :code_error.",
        "donation_not_found" => "Donación no encontrada. Póngase en contacto con un administrador. Código de error: :code_error",
        "donation_not_refused" => "No se encontraron donaciones de pago pendientes.",
        "donor_or_children_not_found" => "Donante o hijo no encontrado",
        "email_not_send" => "No se pudo enviar el correo electrónico. Comuníquese con un administrador. Código de error: :code_error",
        "invalid_document" => "Documento inválido.",
        "zip_not_created" => "Se produjo un error al generar el archivo zip. Vuelve a intentarlo en unos instantes. Código de error: :code_error"
    ],
    "package" => [
        "date" => [
            "after" => "La fecha debe ser mayor que la fecha actual.",
            "not_created" => "Paquete no creado. Código de error: :code_error",
            "not_found" => "No se encontraron paquetes.",
            "not_updated" => "La fecha no se ha actualizado. Vuelve a intentarlo en unos instantes."
        ]
    ],
    "service" => [
        "aws_s3" => [
            "delete_error" => "Se produjo un error al eliminar la imagen en S3 Bucket. Vuelve a intentarlo en unos instantes.",
            "upload_error" => "Se produjo un error al guardar la imagen en S3 Bucket. Vuelve a intentarlo en unos instantes."
        ]
    ],
    "storage" => [
        "image" => [
            "error" => "Se produjo un error al guardar la imagen en el servidor. Vuelve a intentarlo en unos instantes."
        ]
    ],
    "user" => [
        "password_not_redefined" => "No se ha cambiado la contraseña. Código de error: :code_error"
    ]
];
