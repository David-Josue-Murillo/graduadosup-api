<x-mail::message>
# Introduction
## Pruebas desde el componente de correo
The body of your message.

<x-mail::button :url="'https://www.linkedin.com/in/david-murillo-471a132a0/'">
My Linkedin
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
