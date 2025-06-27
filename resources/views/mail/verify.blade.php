@component('mail::message')
# 👋 Hello {{ $notifiable->name }},

Welcome to **Mini Amazon** — your favorite place to shop!

To get started, please verify your email address:

@component('mail::button', ['url' => $url])
✅ Verify Email Address
@endcomponent

If you didn’t create this account, feel free to ignore this message.

Thanks for joining us!
**– Mini Amazon Team**
@endcomponent