@props(['colorButton' => 'purple', 'colorText' => 'text-black'])
<button
    {{ $attributes->merge(['type' => 'submit','class' =>'px-4 py-2 text-sm font-medium leading-5 items-center text-center transition-colors duration-150 border border-transparent rounded-lg focus:outline-none focus:ring ' .$colorText .' bg-' .$colorButton .'-600 active:bg-' .$colorButton .'-600 hover:bg-' .$colorButton .'-700']) }}>
    {{ $slot }}
</button>
