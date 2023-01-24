@props(['feedback'])

{{ $before ?? '' }}

<input {!! $attributes->class(['text-slate-400', 'focus:text-[#344767]', 'dark:focus:text-slate-100', 'bg-white',
'dark:bg-slate-800', 'focus:border-[#344767]', 'is-invalid' => $errors->has($attributes['name']), 'active' =>
$attributes['value'] != '']) !!}>

{{ $after ?? '' }}

@if($attributes['type'] != 'hidden' && !isset($feedback))
@error($attributes['name'])
<span class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">
  {{ $message }}
</span>
@enderror
@endif