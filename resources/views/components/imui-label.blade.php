@props(['value'])

<label {!! $attributes->class(['text-[#344767]', 'dark:text-slate-300', 'bg-white', 'dark:bg-slate-800', 'form-label',
  'text-red-500' => $errors->has($attributes['for'])]) !!}>
  {{ $value ?? $slot }}
</label>