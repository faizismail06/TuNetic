@component('mail::message')

{{-- HEADER HIJAU --}}
<div style="background-color: #28a745; padding: 30px; color: white; text-align: center;">
  <h1 style="margin: 0;">✔️ Akun Anda Telah Disetujui!</h1>
</div>

Halo **{{ $user->name }}**,

Selamat! Permintaan Anda telah kami **setujui**.  
Sekarang Anda bisa login ke sistem kami dan mulai berkontribusi.

{{-- TOMBOL LOGIN --}}
@component('mail::button', ['url' => url('/login'), 'color' => 'success'])
Login Sekarang
@endcomponent

Terima kasih telah bergabung bersama kami. Kami menantikan kontribusi terbaik dari Anda! 💪

{{-- FOOTER --}}
<div style="background-color: #fff3cd; padding: 20px; text-align: center; color: #856404;">
  <strong>TuNetic</strong><br>
  <small>#Small Steps, Big Impact</small>
</div>

@endcomponent
