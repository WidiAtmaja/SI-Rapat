@if (session('success'))
    <meta name="success-message" content="{{ session('success') }}">
@endif

@if (session('error'))
    <meta name="error-message" content="{{ session('error') }}">
@endif

@if (session('warning'))
    <meta name="warning-message" content="{{ session('warning') }}">
@endif

@if (session('info'))
    <meta name="info-message" content="{{ session('info') }}">
@endif
