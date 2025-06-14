<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin:0; padding:0; font-family: 'Nunito', Arial, sans-serif; background-color: #299e63;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#299e63">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="max-width: 600px; margin-top: 20px; margin-bottom: 20px; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td style="padding: 30px 30px 30px 30px; background-color: #299e63; text-align: center;">
                            <img src="https://i.imgur.com/SCU5T7B.png" alt="TuNetic" width="120" style="display: block; margin: 0 auto 25px auto;">
                            <h1 style="font-size: 28px; color: #ffffff; margin: 0; font-weight: 700; line-height: 1.2;">
                                Lupa Password Kamu?
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #ffffff;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding: 40px 30px 25px 30px;">
                                        <p style="color: #000000; font-size: 18px; line-height: 1.5; margin: 0 0 15px 0;">
                                            Halo,
                                        </p>
                                        <p style="color: #000000; font-size: 18px; line-height: 1.5; margin: 0;">
                                            Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Tidak masalah, kami bantu!
                                            <br><br>
                                            Klik tombol di bawah ini untuk membuat kata sandi baru:
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0 30px 30px 30px;">
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td>
                                                    {{-- Di sini Anda perlu memasukkan variabel $actionUrl dari Laravel --}}
                                                    <a href="{{ $actionUrl }}"
                                                       style="display: block; background-color: #ffc107; color: #ffffff; text-decoration: none; padding: 15px 20px; border-radius: 8px; font-weight: 600; font-size: 18px; text-align: center; width: 100%; box-sizing: border-box;">
                                                        Reset Password
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                     <td style="padding: 0 30px 20px 30px;">
                                         {{-- $count biasanya config('auth.passwords.'.config('auth.defaults.passwords').'.expire') --}}
                                         <p style="color: #555555; font-size: 14px; line-height: 1.5; margin: 0; text-align: center;">
                                             Tautan ini akan kedaluwarsa dalam <strong>{{ $count ?? 60 }} menit</strong>.
                                         </p>
                                          <p style="color: #555555; font-size: 14px; line-height: 1.5; margin: 10px 0 0 0; text-align: center;">
                                             Jika Anda tidak meminta reset password, Anda bisa mengabaikan email ini.
                                         </p>
                                     </td>
                                </tr>

                                <tr>
                                     <td style="padding: 0 30px 40px 30px; border-top: 1px solid #eeeeee; margin-top: 20px;">
                                          <p style="color: #888888; font-size: 12px; line-height: 1.5; margin: 20px 0 0 0; text-align: center;">
                                             Jika Anda kesulitan mengklik tombol "Reset Password", salin dan tempel URL di bawah ini ke browser web Anda:
                                             <br>
                                             <span style="display: block; word-break: break-all; margin-top: 5px; color: #299e63;">{{ $actionUrl }}</span>
                                         </p>
                                     </td>
                                 </tr>


                                <tr>
                                    <td style="padding: 20px 30px 50px 30px; background-color: #f7f7f7; text-align: center;">
                                        <p style="margin: 0; font-size: 22px; color: #000000; font-weight: 700;">TuNetic</p>
                                        <p style="margin: 5px 0 0 0; font-size: 16px; color: #555555;">#Small Steps, Big Impact</p>
                                         <p style="margin: 15px 0 0 0; font-size: 12px; color: #999999;">© {{ date('Y') }} TuNetic. Semua Hak Dilindungi.</p>
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