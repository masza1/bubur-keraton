<!DOCTYPE html>
<html x-data="data" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .loader {
            border-top-color: #3498db;
            -webkit-animation: spinner 1.5s linear infinite;
            animation: spinner 1.5s linear infinite;
        }

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

    </style>
</head>

<body>
    <div id="loader" wire:loading
        class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-700 flex flex-col items-center justify-center">
        <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4"></div>
        <h2 class="text-center text-white text-xl font-semibold">Loading...</h2>
        <p class="w-1/3 text-center text-white">This may take a few seconds, please don't close this page.</p>
    </div>
    <div class="hidden h-screen bg-gray-50" :class="{ 'overflow-hidden': isSideMenuOpen }" id="bodyContent">
        <!-- Desktop sidebar -->
        @include('layouts.navigation')
        <!-- Mobile sidebar -->
        <!-- Backdrop -->
        @include('layouts.navigation-mobile')
        <div class="flex flex-col flex-1 w-full">
            @include('layouts.top-menu')
            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">
                    <h2 class="my-6 text-2xl font-semibold text-gray-700">
                        {{ $header }}
                    </h2>

                    {{ $slot }}
                </div>
            </main>
            <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-30 flex bg-black bg-opacity-50 items-center justify-center">

                <x-base-modal id="modalDelete" :modalWidth="__('w-4/12')" :withForm="true" :formId="__('formDelete')">
                    <x-slot name="modalTitle">Hapus data</x-slot>
                    <x-slot name="formMethod">Delete</x-slot>
                    <x-slot name="textSubmit">Hapus</x-slot>
                    <p>Anda yakin ingin menghapus data ini ?</p>
                </x-base-modal>

                {{ $modals ?? '' }}
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/init-alpine.js') }}"></script>
    <script src="{{ asset('js/focus-trap.js') }}"></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-form.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    {{ $js ?? '' }}
    <script>
        $(document).ready(function() {
            $(document).find('#bodyContent').removeClass('hidden').addClass('flex')
            $(document).find('#loader').removeClass('flex').addClass('hidden')
        })
    </script>
</body>

</html>
