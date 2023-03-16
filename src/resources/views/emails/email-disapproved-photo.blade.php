<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    />

    <title>Foto Reprovada!</title>
</head>
<body style="margin: 0; padding: 0; background: #ffffff">
<table height="96px" width="582px" align="center">
    <tr>
        <td>
            <img src="@lang('email.logo')" style="width: 146px"/>
        </td>
        <td>
          <span
              style="
              font-family: 'Roboto';
              font-weight: 700;
              font-style: normal;
              font-size: 20px;
              line-height: 24px;
              color: #ff5f00;
              margin-left: 300px;
            "
          >@lang('email.email_disapproved_photo.hashtag')</span
          >
        </td>
    </tr>
</table>
<table width="582px" align="center">
    <tr>
        <td style="height: 208px; background: #fff3eb; border-radius: 8px">
            <h3
                style="
              margin: 0;
              padding: 0;
              padding-top: 40px;
              font-family: 'Roboto';
              font-style: normal;
              font-size: 16px;
              line-height: 24px;
              font-weight: bold;
              color: #4c5263;
              text-align: center;
            "
            >
                @lang('email.greetings', ['name' => $name])
            </h3>
            <h1
                style="
              margin: 0;
              padding: 0;
              padding-top: 24px;
              font-family: 'Roboto';
              font-style: normal;
              font-weight: 600;
              font-size: 32px;
              line-height: 40px;
              color: #353945;
              text-align: center;
            "
            >
                @lang('email.email_disapproved_photo.lines.eighth')
            </h1>
        </td>
    </tr>
</table>
<table width="582px" align="center">
    <tr>
        <td
            style="
            text-align: center;
            font-family: Roboto;
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 24px;
            color: #353945;
            padding-top: 56px;
          "
        >
            <p
                style="
              text-align: center;
              font-family: 'Roboto';
              font-style: normal;
              font-weight: 400;
              font-size: 16px;
              line-height: 24px;
              color: #353945;
            "
            >
                @lang('email.email_disapproved_photo.lines.first')
            </p>
            <table align="center">
                <tr>
                    <td>
                        <ul style="list-style: outside; display: block">
                            <li
                                style="
                      margin-top: 18px;
                      font-family: Roboto;
                      font-weight: 500;
                      font-style: normal;
                      color: #353945;
                      font-size: 16px;
                      line-height: 24px;
                    "
                            >
                                @lang('email.email_disapproved_photo.lines.second')
                            </li>
                            <li
                                style="
                      margin-top: 18px;
                      font-family: Roboto;
                      font-weight: 500;
                      font-style: normal;
                      color: #353945;
                      font-size: 16px;
                      line-height: 24px;
                    "
                            >
                                @lang('email.email_disapproved_photo.lines.third')
                            </li>
                            <li
                                style="
                      margin-top: 18px;
                      font-family: Roboto;
                      font-weight: 500;
                      font-style: normal;
                      color: #353945;
                      font-size: 16px;
                      line-height: 24px;
                    "
                            >
                                @lang('email.email_disapproved_photo.lines.fourth')
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <a href="@lang('email.email_disapproved_photo.button_link', ['id' => $id, 'token' => $token])">
                <button
                    style="
              font-family: Roboto;
              font-style: normal;
              font-weight: bold;
              font-size: 16px;
              line-height: 24px;
              color: #ffffff;
              background: #ff5f00;
              padding: 16px 24px;
              border-radius: 90px;
              border: none;
              cursor: poRoboto;
              margin-top: 40px;
            "
                >
                    @lang('email.email_disapproved_photo.lines.sixth')
                </button>
            </a>
            <p
                style="
              font-family: Roboto;
              font-style: normal;
              font-weight: bold;
              font-size: 16px;
              line-height: 24px;
              text-align: center;
              color: #353945;
              margin-top: 40px;
            "
            >
                <strong>@lang('email.email_disapproved_photo.lines.seventh')</strong>
            </p>
            <table align="center">
                <tr>
                    <td>
                        <ul style="width: 460px; list-style: none; margin: 0">
                            <li style="display: inline">
                                <img
                                    src="{{ env('MY_AWS_STORAGE_LINK_EMAIL') }}/image-1.png"
                                    style="width: 150px; margin-top: 16px"
                                />
                            </li>
                            <li style="display: inline">
                                <img
                                    src="{{ env('MY_AWS_STORAGE_LINK_EMAIL') }}/image-2.png"
                                    style="width: 149px; margin-top: 16px"
                                />
                            </li>
                            <li style="display: inline">
                                <img
                                    src="{{ env('MY_AWS_STORAGE_LINK_EMAIL') }}/image-3.png"
                                    style="width: 150px; margin-top: 16px"
                                />
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="582px" align="center" style="background: #f2f2f2; height: 164px; margin-top: 56px">
    <tr>
        <td valign="top">
            <div style="margin-left: 72px;">
                <p style="
                    text-align: center;
            margin: 0;
            font-family: Roboto;
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 24px;
            color: #777e90;
            margin-top: 46px;

          ">
                    @lang('email.follow_network_links')
                </p>
                <ul style="
            padding: 0;
            width: 100%;
            list-style: none;
            margin: 0;
            margin-top: 22px;
          ">
                    <li style="display: inline;">
                        <a href="{{ config('app.social_networks.facebook') }}">
                            <img src="{{ config('mail.AWS_STORAGE_LINK_EMAIL') }}/ico-facebook.png"/>
                        </a>
                    </li>
                    <li style="display: inline;">
                        <a href="{{ config('app.social_networks.instagram') }}">
                            <img src="{{ config('mail.AWS_STORAGE_LINK_EMAIL') }}/ico-instagram.png"/>
                        </a>
                    </li>
                    <li style="display: inline;">
                        <a href="{{ config('app.social_networks.youtube') }}">
                            <img src="{{ config('mail.AWS_STORAGE_LINK_EMAIL') }}/ico-youtube.png"/>
                        </a>
                    </li>
                    <li style="display: inline;">
                        <a href="{{ config('app.social_networks.twitter') }}">
                            <img src="{{ config('mail.AWS_STORAGE_LINK_EMAIL') }}/ico-twitter.png"/>
                        </a>
                    </li>
                </ul>
            </div>
        </td>
        <td>
            <div style="margin-left: 50px; width: 70%; height: 90px">
                <p style="
            margin: 0;
            font-family: Roboto;
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            color: #777e90;
            display: flex;
            align-items: center;
          ">
                    <img src="{{ config('mail.AWS_STORAGE_LINK_EMAIL') }}/ico-whatsapp.png"
                         style="padding-right: 10px"/>
                    <spam>@lang('email.cellphone')</spam>
                </p>
                <p style="
            margin: 0;
            font-family: Roboto;
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            color: #777e90;
            display: flex;
            align-items: center;
            margin-top: 10px;
          ">
                    <img src="{{ config('mail.AWS_STORAGE_LINK_EMAIL') }}/ico-phone.png"
                         style="padding-right: 10px"/><span>@lang('email.phone')</span>
                </p>
                <p style="
            margin: 0;
            font-family: Roboto;
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            color: #777e90;
            display: flex;
            align-items: center;
            margin-top: 10px;
          ">
                    <img src="{{ config('mail.AWS_STORAGE_LINK_EMAIL') }}/ico-letter.png"
                         style="padding-right: 10px"/><span>@lang('email.email')</span>
                </p>
            </div>
        </td>
    </tr>
</table>
<table width="582px" align="center">
    <tr>
        <td>
            <p style="
              margin: 0;
              font-family: Roboto;
              font-style: normal;
              font-weight: normal;
              font-size: 12px;
              line-height: 20px;
              text-align: center;
              color: #777e90;
              margin-top: 24px;
            ">
                @lang('email.footer_email')
            </p>
        </td>
    </tr>
</table>
</body>
</html>
