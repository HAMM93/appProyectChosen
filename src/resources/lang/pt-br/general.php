<?php

use App\Types\{DonorMediaTypes, PaymentTypes, PackageTypes};


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
    'zipcode-not-found' => 'CEP não encontrado',
    'miss-configuration' => 'Falta de configuração',
    'model-not-found' => 'Não foi possível retornar os dados',
    'runtime-exception' => 'Erro interno do servidor, código do erro: :error_code',
    'exception-not-handled' => 'Erro interno do servidor',
    'error-while-saving' => 'Erro ocorrido ao salvar o doador',
    'error-while-get-phone' => 'Erro ocorrido ao buscar o campo telefone',
    'donor-media-successfully-created' => 'Imagem do doador salva com sucesso',
    'deleted' => 'Deletado',
    'revelation_email_sending_to_donor' => 'Logo o doador receberá um e-mail de revelação',
    'error_to_send_email' => 'Erro ao enviar o e-mail',
    'something_wrong' => 'Alguma coisa de errado aconteceu',
    'token_invalid' => 'Token inválido',
    'invalid_type_file' => 'Tipo de arquivo inválido',

    'filter' => [
        'donation' => [
            'all' => 'Todos',
            'pending' => 'Pendente',
            'paid' => 'Pago',
            'processing' => 'Processando',
            'refused' => 'Recusado'
        ],
        'donor_media' => [
            'all' => 'Todos',
            'pending' => 'Pendente',
            'approved' => 'Aprovado',
            'reproved' => 'Reprovado'
        ],
        'package' => [
            'accomplished' => 'Realizado',
            'scheduled' => 'Agendado',
            'pending' => 'Pendente',
            'all' => 'Todos',
        ],
        'revelation' => [
            'digital' => 'Digital',
            'physical' => 'Físico'
        ]
    ],

    'date_updated' => 'Data atualizada.',
    'unexpected-error' => 'Aconteceu um erro inesperado. Tenta em alguns instantes, se o erro persistir contate um administrador.',

    'donor' => [
        'removed_from_package' => 'O (a) doador (a) :donor foi removido do pacote :package'
    ],
    "origin" => 'origem',
    "system_name" => 'Escolhido Brasil',
];
