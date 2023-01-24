@unless ($breadcrumbs->isEmpty())
<ol class="flex flex-wrap text-sm text-gray-800">
  @foreach ($breadcrumbs as $breadcrumb)

  @if (!$loop->last)
  <li class="breadcrumb-item"><a href="{{ $breadcrumb->url ?? '#' }}" class="text-blue-600 dark:text-slate-100 hover:text-blue-900 dark:hover:text-slate-300 hover:underline focus:text-blue-900 focus:underline">{{ $breadcrumb->title }}</a></li>
  <li class="text-slate-500 px-2">/</li>
  @else
  <li class="text-slate-500">{{ $breadcrumb->title }}</li>
  @endif

  @endforeach
</ol>
@endunless