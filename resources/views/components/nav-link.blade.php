@props(['href'])

@php
$isActive = request()->is(trim($href, '/'.'*'));
@endphp

<a href="{{$href}}"
   {{$attributes->merge([
    'class' => $isActive
    ? 'relative py-3 text-sm font-semibold text-white transition-colors after:absolute after:inset-x-0 after:-bottom-2 after:h-0.5 after:rounded-full after:bg-gradient-to-r after:from-blue-400 after:to-pink-400 after:shadow-[0_0_12px_rgba(96,165,250,0.8)] sm:text-base'
    : 'py-3 text-sm font-semibold text-slate-400 transition-colors hover:text-white sm:text-base'
])}} >
    {{$slot}}
</a>
