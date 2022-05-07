@props(['header' => false, 'headCard' => false])
@if ($headCard)
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        {{ $fieldCard }}
    </div>
@endif
@if ($header)
    <div class="flex justify-between">
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            {{ $tableHeader ?? 'Table' }}
        </h4>
        {!! $buttonHeader ?? '' !!}
    </div>
@endif
<div class="overflow-hidden mb-8 w-full rounded-lg border shadow-xs">
    <div class="overflow-x-auto w-full">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                    {{ $thead }}
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-cols-9">
        {{ $paginate ?? '' }}
    </div>
</div>
