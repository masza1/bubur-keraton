@props(['disabled' => false, 'inputType' => 'input'])
@if ($inputType == 'input')
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'mt-1 border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 focus-within:text-primary-600']) !!}>
@elseif($inputType == 'select')
    <select {!! $attributes !!}
        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
        {{ $slot ?? '' }}
    </select>
@elseif($inputType == 'textarea')
    <textarea {!! $attributes !!}
        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
        rows="3" placeholder="Enter some long form content.">
        {{ $slot ?? '' }}
    </textarea>
@endif
