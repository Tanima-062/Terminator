<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <!-- Yahoo App Android will strip this -->
</head>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=320, initial-scale=1" />
    <title> {{ __('Termin-ator Invitation', [], $user->language->code ?? 'en') }} </title>

    <style type="text/css">
        /* ----- Client Fixes ----- */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;1,400;1,700&display=swap');

        /* Force Outlook to provide a "view in browser" message */
        #outlook a {
            padding: 0;
        }

        /* Force Hotmail to display emails at full width */
        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        /* Force Hotmail to display normal line spacing */
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }


        /* Prevent WebKit and Windows mobile changing default text sizes */
        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Remove spacing between tables in Outlook 2007 and up */
        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        /* Allow smoother rendering of resized image in Internet Explorer */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* ----- Reset ----- */

        /* html, */
        body,
        .body-wrap,
        .body-wrap-cell {
            margin: 0;
            padding: 0;
            background: #ffffff;
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
            color: #464646;
            text-align: left;
            font-family: 'Poppins', sans-serif;
        }

        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        td,
        th {
            text-align: left;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #464646;
            line-height: 1.5em;
        }

        b a,
        .footer a {
            text-decoration: none;
            color: #464646;
        }

        a.blue-link {
            color: blue;
            text-decoration: underline;
        }

        /* ----- General ----- */

        td.center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .body-padding {
            /* padding: 24px 40px 40px; */
            margin: 0;
            padding: 0;
        }

        .border-bottom {
            /* border-bottom: 1px solid #D8D8D8; */
        }

        table.full-width-gmail-android {
            width: 100% !important;
        }


        /* ----- Header ----- */
        .header {
            font-weight: bold;
            font-size: 16px;
            line-height: 16px;
            height: 16px;
            padding-top: 19px;
            padding-bottom: 7px;
        }

        .header a {
            color: #464646;
            text-decoration: none;
        }

        /* ----- Body ----- */

        .body .body-padded {
            padding-top: 34px;
        }

        .body-thanks-cell {
            padding: 25px 0 10px;
        }

        .body-signature-cell {
            padding: 0 0 30px;
        }

        /* ----- Footer ----- */

        .footer a {
            font-size: 12px;
        }


        /* ----- Soapbox ----- */

        .soapbox .soapbox-title {
            text-align: center;
            font-size: 30px;
            padding-bottom: 20px;
            color: #464646;
        }

        /* ----- Status ----- */

        .status {
            border-collapse: collapse;
            margin-left: 15px;
            color: #656565;
        }

        .status .status-cell {
            border: 1px solid #b3b3b3;
            height: 50px;
            text-align: center;
            font-size: 15px;
            padding: 0 40px;
        }

        .status .status-cell.success,
        .status .status-cell.active {
            height: 65px;
        }

        .status .status-cell.success {
            background: #f2ffeb;
            color: #51da42;
        }

        .status .status-cell.active {
            background: #fffde0;
            width: 135px;
        }

        .status .status-title {
            font-size: 16px;
            font-weight: bold;
            line-height: 23px;
        }

        .status .status-image {
            vertical-align: text-bottom;
        }

        main-content {
            padding-top: 60px;
            padding-bottom: 25px;
            padding-left: 40px;
            padding-right: 40px;
        }
    </style>

    <style type="text/css" media="only screen">
        @media only screen and (max-width: 505px) {

            *[class*="w320"] {
                width: 320px !important;
            }

            table[class="status"] td[class*="status-cell"],
            table[class="status"] td[class*="status-cell"].active {
                display: block !important;
                width: auto !important;
            }

            table[class="status-container single"] table[class="status"] {
                width: 270px !important;
                margin-left: 0;
            }

            table[class="status"] td[class*="status-cell"],
            table[class="status"] td[class*="status-cell"].active,
            table[class="status"] td[class*="status-cell"] [class*="status-title"] {
                line-height: 65px !important;
                font-size: 18px !important;
                padding: 0 15px !important;
            }

            table[class="status-container single"] table[class="status"] td[class*="status-cell"],
            table[class="status-container single"] table[class="status"] td[class*="status-cell"].active,
            table[class="status-container single"] table[class="status"] td[class*="status-cell"] [class*="status-title"] {
                line-height: 51px !important;
            }

            table[class="status"] td[class*="status-cell"].active [class*="status-title"] {
                display: inline !important;
            }

            td[class="main-content"] {
                padding-top: 20px !important;
                padding-bottom: 15px !important;
                padding-left: 10px !important;
                padding-right: 10px !important;
            }


        }
    </style>

    <style type="text/css" media="only screen and (max-width: 650px)">
        @media only screen and (max-width: 650px) {
            * {
                font-size: 16px !important;
            }

            .header-title {
                font-size: 24px !important;
                line-height: 30px !important;
            }

            .full-width-gmail-android {
                margin-bottom: 0px !important;
            }


            table[class*="w320"] {
                width: 320px !important;
            }

            table[class*="main-table"] {
                margin: 0 auto !important;
            }

            td[class="mobile-center"],
            div[class="mobile-center"] {
                text-align: center !important;
            }

            td[class*="body-padding"] {
                padding: 20px !important;
            }

            td[class="mobile"] {
                text-align: right;
                vertical-align: top;
            }

            td[class=fullwidth] {
                width: 100% !important;
                float: left !important;
            }
        }
    </style>

</head>

<body style="padding:0; margin:0; display:block; background:#E5EBEF; -webkit-text-size-adjust:none;">
    <table height="62px;">
        <tr>
            <td></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0"
        style="width:500px; margin: 0 auto;border-radius:32px;background:#ffffff;">
        <tbody>
            <tr>
                <td class="main-content" align="left" valign="top" width="100%"
                    style="padding-top:55px;padding-bottom:25px;padding-left:60px;padding-right:60px;">
                    <center>
                        <table class="w320 full-width-gmail-android" style="background-color: transparent;"
                            border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#f9f8f8">
                            <tbody>
                                <tr>
                                    <td valign="top" width="100%">
                                        <table class="full-width-gmail-android" border="0" width="100%"
                                            cellspacing="0" cellpadding="0" style="margin: 0px 0px 32px 0px;">
                                            <tbody>
                                                <tr>
                                                    <td class="header center" width="100%"
                                                        style="margin: 0;padding:0;">

                                                        <h1 class="header-title"
                                                            style="margin:0;padding:0;font-family: 'Poppins', sans-serif;font-style: normal;font-weight: 600;font-size: 33px;line-height: 36px;color: #C52E62;">
                                                            {{ __('Termin-ator Invitation', [], $user->language->code ?? 'en') }}
                                                        </h1>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width: 95.3808%;" width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr style="height: 10px;">
                                    <td style="width: 100%;" align="center">
                                        <center>
                                            <table class="w320" width="500" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td class="body-padding mobile-padding" style="width: 433px;">
                                                            <table class="soapbox" style="width: 100%;" width="100%"
                                                                cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr style="height: 10px;">
                                                                        <td class="soapbox-title"
                                                                            style="font-family: 'Poppins', sans-serif;font-style: normal;font-weight: 600;font-size: 15px;line-height: 22px;text-align: center;color: #5F4C5C;margin:0;padding:0;">
                                                                            {{ __('Hello', [], $user->language->code ?: 'en') }}
                                                                            {{ $user['name'] ?? '' }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table class="body"
                                                                style="height: 100px;background:transparent;border:none;width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="body-padded" style="padding:0;">
                                                                            <table class="body-text" align="center">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="body-text-cell">
                                                                                            <p
                                                                                                style="font-family: 'Poppins', sans-serif ;font-style: normal;font-weight: 400;font-size: 15px;line-height: 22px;text-align: center;color: #7F707D;margin:0;padding:0;">
                                                                                                {{ __('You have been invited for Termin-ator as administrator for your company “:Company“ invited', ['Company' => $user->customerCompanyAdmin->customerCompany->name], $user->language->code ?? 'en') }}<br>
                                                                                                {{ __('Please confirm your email address first by clicking on the following link:', [], $user->language->code ?? 'en') }}
                                                                                            </p>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div align="center">
                                                                                                {{-- <a style="width:310px;padding: 11px 23px 11px 23px;border-radius: 32px; color: #ffffff; display: inline-block; font-family: 'Inter', sans-serif; font-size: 16px; line-height:19px; font-weight: 700;text-align: center; text-decoration: none;  -webkit-text-size-adjust: none; margin-top: 32px;  background-image: linear-gradient(to right, #8B387F -44.07%, #C52E62 155.41%);" --}}
                                                                                                <a style="width:310px;padding: 11px 23px 11px 23px;border-radius: 32px; color: #ffffff; display: inline-block; font-family: 'Inter', sans-serif; font-size: 16px; line-height:19px; font-weight: 700;text-align: center; text-decoration: none;  -webkit-text-size-adjust: none; margin-top: 32px;  background:#C52E62"
                                                                                                    href="{{ $url }}"
                                                                                                    class="btn">{{ __('Email Address Verification', [], $user->language->code ?? 'en') }}</a>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div align="center">
                                                                                                <p
                                                                                                    style="
                                                                                                    color: #898989;
                                                                                                    display: block;
                                                                                                    font-family: 'Inter', sans-serif;
                                                                                                    font-size: 12px;
                                                                                                    line-height:18px;
                                                                                                    font-weight: 400;
                                                                                                    text-align: center;
                                                                                                    text-decoration: none;
                                                                                                    -webkit-text-size-adjust: none;
                                                                                                    margin: 0;
                                                                                                    margin-top: 32px;
                                                                                                    word-break: break-word;">
                                                                                                    {{ __('If the button is not visible or does not work, you can select the following link or copy and and paste it into your internet browser and open it:', [], $user->language->code ?? 'en') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="display:block;text-align:center;"
                                                                                            class="fullwidth"
                                                                                            align="center"
                                                                                            width="100%">
                                                                                            <a style="color:#107CAD;font-family: 'Poppins', sans-serif;font-weight: 400;line-height: 16px;font-size:11px;text-decoration: none;  -webkit-text-size-adjust: none;"
                                                                                                href="{{ $url }}">
                                                                                                {{ $url }}</a>
                                                                                        </td>
                                                                                    </tr>

                                                                                </tbody>
                                                                            </table>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 100%;">

                                                                            <center>
                                                                                <img src="{{ asset('images/logo_terminator.png') }}"
                                                                                    alt="Ihr Termin-ator Team"
                                                                                    height="25"
                                                                                    style="margin-top:22px;width:100%;display:block;max-width:201px;border:0px;"
                                                                                    width="201" />
                                                                            </center>

                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                </td>
            </tr>
        </tbody>
    </table>
    <table height="50px;">
        <tr>
            <td></td>
        </tr>
    </table>
</body>

</html>
