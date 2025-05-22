@props(['url'])

<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
            <img src="{{ asset('storage/avatars/logo.png') }}" alt="OASP Logo" style="width: 210px; height: 100px;">

            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
