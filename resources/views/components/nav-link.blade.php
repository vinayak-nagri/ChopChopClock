@props(['href'])

@php
$isActive = request()->is(trim($href, '/'.'*'));
@endphp

<a href="{{$href}}"
   {{$attributes->merge([
    'class' => $isActive
    ? 'text-white font-semibold border-b-2 border-blue-600/80 transition-colors'
    : 'text-white/70 hover:text-white transition-colors'
])}} >
    {{$slot}}
</a>
