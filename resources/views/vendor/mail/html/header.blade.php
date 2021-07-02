<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="/assets/logo.png" class="logo" alt="Sentani Fresh Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
