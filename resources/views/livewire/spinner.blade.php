<div class="w-full h-full fixed block top-0 left-0 bg-white opacity-75 z-50">
    <div class="loader ease-linear content-center items-center justify-center absolute rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div>
</div>

@push("styles")
    <style>
        .loader {
            z-index: 99999;
            position: absolute;
            margin: auto;
            top:0;
            bottom: 0;
            left: 0;
            right: 0;
            border-top-color: #3498db;
            -webkit-animation: spinner 1.5s linear infinite;
            animation: spinner 1.5s linear infinite;
        }

        @-webkit-keyframes spinner {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spinner {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush
