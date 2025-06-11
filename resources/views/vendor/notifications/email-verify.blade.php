<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin:0; padding:0; font-family: 'Nunito', Arial, sans-serif; background-color: #299e63;">
    <!-- Wrapper to create white padding on the sides -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#299e63">
        <tr>
            <td align="center">
                <!-- White background container -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="max-width: 600px;">
                    <!-- Logo Section -->
                    <tr>
                        <td style="padding: 30px 30px 20px 30px; background-color: #299e63;">
                            <img src="https://i.imgur.com/SCU5T7B.png" alt="TuNetic" width="120" style="display: block;">
                        </td>
                    </tr>

                    <!-- Content Section with Text and Image -->
                    <tr>
                        <td style="background-color: #299e63;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <!-- Left Column: Heading -->
                                    <td width="50%" valign="down" style="padding: 0 0 0 30px;">
                                        <h1 style="font-size: 23px; color: #ffffff; margin: 0; font-weight: 700; line-height: 1.1;">
                                            Ayo, Verifikasi<br>Email Kamu!
                                        </h1>
                                    </td>

                                    <!-- Right Column: Worker Image -->
                                    <td width="50%" align="right" valign="top" style="padding-right: 20px;">
                                        <img src="https://i.imgur.com/PfKUY3C.png" alt="Petugas Verifikasi" width="230" style="display: block;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- White Background Section -->
                    <tr>
                        <td style="background-color: #ffffff;">
                            <!-- Text -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding: 40px 30px 25px 30px;">
                                        <p style="color: #000000; font-size: 18px; line-height: 1.5; margin: 0;">
                                            Bantu kami pastikan kalau ini benar email kamu.<br>
                                            Cukup klik tombol di bawah, ya!
                                        </p>
                                    </td>
                                </tr>

                                <!-- Button -->
                                <tr>
                                    <td style="padding: 0 30px 40px 30px;">
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td>
                                                    <a href="{{ $actionUrl }}" 
                                                       style="display: block; background-color: #ffc107; color: #ffffff; text-decoration: none; padding: 15px 20px; border-radius: 8px; font-weight: 600; font-size: 18px; text-align: center; width: 100%; box-sizing: border-box;">
                                                        Verifikasi Email Kamu
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Footer -->
                                <tr>
                                    <td style="padding: 20px 30px 50px 30px;">
                                        <p style="margin: 0; font-size: 22px; color: #000000; font-weight: 700;">TuNetic</p>
                                        <p style="margin: 0; font-size: 16px; color: #555555;">#Small Steps, Big Impact</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
