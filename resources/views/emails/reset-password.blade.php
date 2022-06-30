@component('mail::message')
<h1>We have received your request to reset your account password</h1>
<p>You can use the following code to reset your password:</p>

@component('mail::panel')
{{ $code }}
@endcomponent

<p>The code expires in an hour.</p>
@endcomponent