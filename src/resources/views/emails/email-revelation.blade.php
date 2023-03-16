<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />

    <title>@lang('email.email_revelation.title')</title>
  </head>
  <body>
    <table height="96px" width="582px" align="center">
      <tr>
        <td>
          <img src="@lang('email.logo')" style="width: 146px" />
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
            >@lang('email.email_revelation.hashtag')</span
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
              font-family: 'Poppins';
              font-style: normal;
              font-weight: 600;
              font-size: 32px;
              line-height: 40px;
              color: #353945;
              text-align: center;
            "
          >
            @lang('email.email_revelation.lines.first')
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
            padding-top: 66px;
            margin-bottom: 31px;
          "
        >
            @lang('email.email_revelation.lines.second')
          <a
            href="@lang('email.email_revelation.button_link', ['token' => $token])"
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
              text-decoration: none;
            "
          >
            @lang('email.email_revelation.button_revelation')
          </a>
          <section>
            <p
              style="
                padding-top: 20px;
                text-align: center;
                font-family: Roboto;
                font-style: normal;
                font-weight: 500;
                font-size: 16px;
                line-height: 24px;
                color: #353945;
              "
            >
              @lang('email.email_revelation.lines.third')
            </p>
            <p
              style="
                padding-top: 20px;
                text-align: center;
                font-family: Roboto;
                font-style: normal;
                font-weight: 500;
                font-size: 16px;
                line-height: 24px;
                color: #353945;
              "
            >
              @lang('email.email_revelation.lines.fourth')
            </p>
            <p
              style="
                padding-top: 20px;
                text-align: center;
                font-family: Roboto;
                font-style: normal;
                font-weight: 500;
                font-size: 16px;
                line-height: 24px;
                color: #353945;
              "
            >
              @lang('email.email_revelation.lines.fifth')
            </p>
            <p
              style="
                padding-top: 20px;
                text-align: center;
                font-family: Roboto;
                font-style: normal;
                font-weight: 500;
                font-size: 16px;
                line-height: 24px;
                color: #353945;
              "
            >
              @lang('email.email_revelation.lines.sixth')
            </p>
          </section>
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
                        <img src="{{ config('mail.AWS_STORAGE_LINK_EMAIL') }}/ico-whatsapp.png" style="padding-right: 10px" />
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
                             style="padding-right: 10px" /><span>@lang('email.phone')</span>
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
                             style="padding-right: 10px" /><span>@lang('email.email')</span>
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
