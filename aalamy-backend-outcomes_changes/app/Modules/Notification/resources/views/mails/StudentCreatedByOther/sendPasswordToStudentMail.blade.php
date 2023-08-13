<!doctype html>
<html lang="en-US">

@include('Notification::mails.Template.header',['title' => 'your account information'])

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
<!-- 100% body table -->
<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
       style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
    <tr>
        <td>
            <table style="background-color: #f2f3f8; max-width:670px; margin:0 auto;" width="100%" border="0"
                   align="center" cellpadding="0" cellspacing="0">

                @include('Notification::mails.Template.logo')

                <tr>
                    <td>
                        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                               style="max-width:670px; background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                            <tr>
                                <td style="height:40px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="padding:0 35px;">
                                    <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">
                                        Get started
                                    </h1>
                                    <p style="font-size:15px; color:#455056; margin:8px 0 0; line-height:24px;">
                                        Your account has been created on the classkits. Below are your system generated credentials,
                                        <br>
                                        <strong>Please change the password immediately after login</strong>.
                                    </p>
                                    <span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;">

                                    </span>
                                    <p style="color:#455056; font-size:18px;line-height:20px; margin:0; font-weight: 500;">
                                        <strong style="display: block;font-size: 13px; margin: 0 0 4px; color:rgba(0,0,0,.64); font-weight:normal;">
                                            Email
                                        </strong>
                                        {{$email}}
                                        <strong style="display: block; font-size: 13px; margin: 24px 0 4px 0; font-weight:normal; color:rgba(0,0,0,.64);">
                                            Password
                                        </strong>
                                        {{$password}}
                                    </p>

                                    <a href="https://site.aalamy.org" style="background:#20e277;text-decoration:none !important; display:inline-block; font-weight:500; margin-top:24px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">
                                        Login to your Account
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:40px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="height:20px;">&nbsp;</td>
                </tr>

                @include('Notification::mails.Template.copyRight')

                <tr>
                    <td style="height:80px;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!--/100% body table-->
</body>

</html>

