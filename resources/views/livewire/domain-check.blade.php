<div>
    <form wire:submit.prevent="checkDomains" class="space-y-4">
        <textarea
            wire:model="domains"
            placeholder="example.com, example.org&#10;или напишите каждый домен на отдельной строке"
            rows="5"
            class="w-full p-3 border rounded-lg @error('domains') border-red-500 @enderror"
        ></textarea>
        @error('domains') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Проверять</span>
            <span wire:loading>
                <i class="animate-spin">↻</i> Проверка...
            </span>
        </button>
    </form>

    @error('api_error')
    <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg">
        {{ $message }}
    </div>
    @enderror

    @if($isLoading)
        <div class="mt-4 p-3 bg-blue-100 text-blue-700 rounded-lg">
            Проверка доменов...
        </div>
    @endif

    @if(!empty($results))
        <div class="mt-6 space-y-3">
            @foreach($results as $result)
                <div class="p-4 border rounded-lg {{ $result['isAvailable'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <div class="flex justify-between items-center">
                        <span class="font-mono font-semibold">{{ $result['domain'] }}</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $result['isAvailable'] ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                            {{ $result['isAvailable'] ? 'Доступно' : 'Занято' }}
                        </span>
                    </div>

                    @unless($result['isAvailable'])
                        <div class="mt-2 text-sm text-gray-600">
                            Регистрация заканчивается: {{ $result['expiryDate'] ?? 'Неизвестный' }}
                        </div>
                    @endunless
                </div>
            @endforeach
        </div>
    @endif
</div>
