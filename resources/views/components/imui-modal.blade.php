@props(['static' => 'false', 'modalName' => 'modal', 'eventName' => 'open-modal', 'width' => 'max-w-fit', 'height' => 'max-h-fit', 'closeButton' => 'true', 'onClose' => "Livewire.emit('reset')"])

<div x-data="{ {{ $modalName }}: false }" x-on:{{ $eventName }}.window="{{ $modalName }} = true" @keyup.escape.window="{{ $modalName }} = false">

  @isset($trigger)
  <div class="max-w-fit" x-on:click="{{ $modalName }} = ! {{ $modalName }}">
    {{ $trigger }}
  </div>
  @endisset

  <div x-show="{{ $modalName }}">
    <div class="fixed inset-0 z-[1055] w-full h-full overflow-x-hidden overflow-y-auto transition-opacity backdrop-blur-sm bg-gray-500 bg-opacity-40 p-7" aria-labelledby="modal-title" role="dialog" aria-modal="true" :class="{{ $modalName }} ? 'block' : 'hidden'">

      <div x-cloak x-show="{{ $modalName }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="flex h-full items-center relative w-auto transition-all transform {{ $height }}">
        <div class="relative flex flex-col mx-auto bg-white dark:bg-slate-800 dark:border dark:border-slate-700 border rounded-2xl p-4 shadow-lg {{ $width }} {{ $height }}" @if($static == 'false') x-on:click.outside="{{ $modalName }} = false; {{ $onClose }}" @endif>
          <div class="flex items-center justify-between space-x-4 dark:text-slate-200">
            {{ $header }}
            @if ($closeButton == 'true')
            <button @click="{{ $modalName }} = false; {{ $onClose }}" class="shadow-none p-0 ml-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
            @endif
          </div>
          @isset ($body)
          <div class="mt-5">
            {{ $body }}
          </div>
          @endif
          @isset ($footer)
          <div class="mt-6">
            {{ $footer }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>