<x-mail::message>
{{-- resources/views/emails/custom_reset.blade.php --}}
<x-mail::button :url="$Url" >
{{ $actionText }}
</x-mail::button>
</x-mail::message>

