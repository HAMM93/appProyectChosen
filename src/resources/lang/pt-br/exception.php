<?php

return [
    'user' => [
        'password_not_redefined' => 'A senha não foi alterada. Código do erro: :code_error'
    ],
    'donor' => [
        'error_during_save' => 'Erro ocorrido durante a criação do Doador. Tente novamente em alguns instantes.',
        'not_found' => 'Nenhum doador encontrado.',
        'donor_event_invalid' => 'Este doador não pertence a um pacote.',
        'not_removed_from_package' => 'O doador não foi removido do pacote. Código do erro: :code_error',
        'not_removed_at_package' => 'O doador não foi removido do pacote. Tente novamente em alguns instantes.',
        'without_media' => 'O doador não possui foto cadastrada.',
        'not_linked' => 'O doador não foi vinculado ao pacote. Código do erro: :code_error',
        'invalid' => 'O doador não está válido para entrar no pacote pois: está com foto pendente,
        sua doação ainda não foi aprovada ou não possui integração com o Simma. Código do erro: :code_error'
    ],
    'donor_document' => [
        'type_not_configured' => 'Tipo de documento não configurado'
    ],
    'hubspot_integration' => [
        'error_on_create_contact' => 'Erro ao registrar novo contato no Hubspot',
        'error_on_consult' => 'Erro ao consultar o contato no Hubspot',
        'error_on_add_contact_to_list' => 'Erro ao adicionar contato a lista',
],
    'package' => [
        'date' => [
            'after' => 'A data precisa ser maior que a data atual.',
            'not_updated' => 'A data não foi atualizada. Tente novamente em alguns instantes.',
            'not_created' => 'Pacote não criado. Código do erro: :code_error',
            'not_found' => 'Nenhum pacote encontrado.',
        ],
    ],
    'donor_media' => [
        'already_registered' => 'O doador já possui uma foto cadastrada.',
        'not_saved' => 'Erro ocorrido ao salvar a imagem. Tente novamente em alguns instantes.',
        'invalid_token' => 'Token inválido.',
        'invalid_id' => 'Id da foto inválido.'
    ],
    'storage' => [
        'image' => [
            'error' => 'Erro ocorrido ao salvar a imagem no servidor. Tente novamente em alguns instantes.',

        ]
    ],
    'service' => [
        'aws_s3' => [
            'upload_error' => 'Erro ocorrido ao salvar imagem no S3 Bucket. Tente novamente em alguns instantes.',
            'delete_error' => 'Erro ocorrido ao deletar imagem no S3 Bucket. Tente novamente em alguns instantes.'
        ]
    ],
    'database' => [
        'query' => 'Erro ocorrido durante sua consulta. Contate o administrador.'
    ],
    'children' => [
        'not_found' => 'Criança não encontrada. Código do erro: :code_error'
    ],
    'donation' => [
        'not_found' => 'Nenhuma doação encontrada. Código do erro: :code_error'
    ],
    'general' => [
        'zip_not_created' => 'Erro ocorrido ao gerar o arquivo zip. Tente novamente em alguns instantes. Código do erro: :code_error',
        'email_not_send' => 'Falha ao enviar e-mail. Contate um administrador. Código do erro: :code_error',
        'donation_not_found' => 'Doação não encontrada. Contate um administrador.Código do erro: :code_error',
        'donation_not_refused' => 'Nenhum doação com pagamento pendente encontrada.',
        'donor_or_children_not_found' => 'Doador ou criança não encontrado',
        'curp_invalid_date' => 'Data de documento inválida. Código do erro: :code_error.',
        'invalid_document' => 'Documento inválido.'
    ]
];
