@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
            <img src="{{asset('img/logo.svg')}}" class="logo" alt={{config('app.name')}} style="width: 300px;">
            @else
            {!! $slot !!}
            @endif
        </a>
    </td>
</tr>