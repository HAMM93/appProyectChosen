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
    'greetings' => 'Olá :name',

    'follow_network_links' => 'Siga nossas redes',
    'email' => 'vmb_atendimento@wvi.org',
    'cellphone' => '11 98484.0442',
    'phone' => '0800 7070374',
    'footer_email' => 'Visão Mundial tem tolerância zero em relação a violência ou abuso contra crianças,<br />adolescentes ou adultos acusados por funcionários ou outros parceiros da Visão Mundial.',

    'email_revelation' => [
        'subject' => 'Revelação #Escolhido',
        'title' => 'Email de Revelação',
        'hashtag' => '#Escolhido',
        'button_revelation' => 'Hora da revelação!',
        'button_link' => config('app.front_base_url') . '/mural-crianca/:token',
        'lines' => [
            'first' => 'Chegou o grande dia,<br />você foi escolhido! 😍',
            'second' => '<p>Para descobrir quem te escolheu, clique no botão abaixo:</p>',
            'third' => 'Esperamos que essa nova amizade seja capaz de transformar o mundo.<br />Juntos, vocês serão capazes de criar novas oportunidades para o presente <br />e o futuro 😊',
            'fourth' => 'Se você também escolheu receber a <strong>revelação️ física,</strong> vamos te avisar<br />quando sua foto for postada nos correios. Então fique de olho nas suas<br />notificações de email e SMS!',
            'fifth' => 'Conte para seus amigos como foi sua experiência de ser #escolhido. É só <br />usar a hashtag <strong>#Escolhido</strong> e marcar a <strong>@visãomundialbr</strong> mas redes sociais.<br />E, se ficar com alguma dúvida, entre em contato com a gente.',
            'sixth' => 'Com carinho, <br /><strong> Visão Mundial.</strong>',
        ]
    ],

    'email_password' => [
        'subject' => 'Recuperação de Senha',
        'title' => 'Email Senha.',
        'hashtag' => '#ESCOLHIDO',
        'button_link' => config('app.front_base_url') . '/reset-password/:token',
        'lines' => [
            'first' => 'você solicitou uma <br />redefinição de senha',
            'second' => '<p>Para concluir o processo, clique no botão abaixo:</p>',
            'third' => 'Redefinir Senha',
            'fourth' => 'Caso não tenha feito essa solicitação ou acredita que um usuário não <br />autorizado tenha acessado, acesse <strong style="color: #ff5f00">senha.escolhido.com.br</strong> para redefinir <br />sua senha imediatamente.',
        ],
        'subject_forgot_password' => 'Redefinição de senha',
        'body_forgot_password' => 'Uma redefinição de senha foi solicitada.',
    ],

    'email_disapproved_photo' => [
        'subject' => 'Foto Reprovada',
        'title' => 'Foto Reprovada.',
        'hashtag' => '#ESCHOLHIDO',
        'button_link' => config('app.front_base_url') . '/donors/change-photo/:id/:token',
        'lines' => [
            'first' => 'A foto que enviou não passou nos critérios estabelecidos. É por meio
            dessa <br />foto que as crianças escolherão você, então observe
            atentamente nossas <br />
            recomendações e capricha!',
            'second' => 'Escolha uma foto bem iluminada, que se <br />
                    possa ver claramente o seu rosto',
            'third' => 'Dê preferência àquela foto que mostre seu <br />
                    sorriso! Evite expor sua imagem ou cenários <br />
                    inadequados.',
            'fourth' => 'A foto será impressa para as crianças lhe <br />
                    escolherem, portanto selecione a imagem <br />
                    mais adequada e de melhor qualidade <br />
                    possível',
            'fifth' => 'Ao clicar no botão para enviar nova foto, <br />
                  será solicitado um login de acesso.<br />
                  Para acessar, informe o email cadastrado e a senha seguir:',
            'sixth' => 'Enviar foto novamente',
            'seventh' => 'Exemplos de fotos aprovadas:',
            'eighth' => 'precisamos de uma <br />nova foto sua',
        ]
    ],

    'email_transaction' => [
        'subject' => 'Transação realizada',
        'body' => 'Transação realizada com sucesso.',
        'title' => 'Transação realizada com sucesso.',
        'hashtag' => '#ESCHOLHIDO',
        'lines' => [
            'first' => 'temos novidades<br />sobre seu cadastro',
            'second' => 'Informações sobre seu cadastro e doação foram registradas com sucesso!<br />Fique atento às próximas etapas:',
            'third' => 'Trabalhamos incansavelmente para que o processo entre o cadastro e a <br />revelação seja o mais breve possível. O período em média é de 30 dias mas <br />prometemos te deixar sempre por dentro do andamento por aqui.<br />',
            'fourth' => 'Conte para seus amigos como foi sua experiência de ser #escolhido.
              É só <br />usar a hashtag #Escolhido e marcar a @visãomundialbr mas redes sociais.<br />E, se ficar com alguma dúvida, entre em contato com a gente.',
            'fifth' => 'Com carinho, <br />
              <strong>Visão Mundial.</strong>',
            'sixth' => [
                'payment_confirmed' => 'Pagamento é confirmado',
                'photo_approved' => 'Foto é aprovada pela nossa equipe',
                'event_scheduled' => 'Evento de escolha é agendado',
                'was_chose' => 'Você é escolhido(a)',
                'revealed_child' => 'Nome da criança é revelado',
            ],
        ],
    ],

    'email_transaction_failed' => [
        'subject' => 'Falha na transação',
        'body' => 'Falha na transação',
        'title' => 'Falha na transação.',
        'hashtag' => '#ESCHOLHIDO',
        'lines' => [
            'first' => 'algo aconteceu com<br />
            seu pagamento',
            'second' => 'Seu pagamento de :card foi recusado. Para garantir seu<br />cadastro e participar do processo #Escolhido, revise a forma de pagamento <br />clicando no botão abaixo.',
            'third' => 'Detalhes do pagamento',
            'fourth' => ':child_quantity criança(s) (mensal)<strong>R$ :month_price</strong><br />Revelação Física (único) <strong>R$ :revelation_price</strong><br />Valor total: <strong>R$ :total_price</strong><br />',
            'fifth' => 'Alterar forma de pagamento',
            'sixth' => 'Tentaremos processar o pagamento novamente para que você possa<br />continuar conosco e participar desse projeto transformador.',
            'seventh' => 'Com carinho, <br /><strong>Visão Mundial.</strong>',
        ],
    ],
];
