<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dseva | Cyber-security awareness</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Trust Dseva to keep your sensitive information safe and secure.">
    <meta name="keywords" content="Protection,Prevention,Cyber threats,Data security,Online safety,Threat detection,Malware,Phishing,Encryption,Multi-factor authentication">
    <meta name="author" content="Dseva">

    <!-- [Favicon] icon -->
    <link rel="icon" href="/dashboard/assets/images/favicon.png" type="image/x-icon">
    <!-- [Font] Family -->
    <link rel="stylesheet" href="/dashboard/assets/fonts/inter/inter.css" id="main-font-link" />

    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="/dashboard/assets/fonts/tabler-icons.min.css" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="/dashboard/assets/fonts/feather.css" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="/dashboard/assets/fonts/fontawesome.css" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="/dashboard/assets/fonts/material.css" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="/dashboard/assets/css/style.css" id="main-style-link" />
    <link rel="stylesheet" href="/dashboard/assets/css/style-preset.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    @stack('head')
</head>

<body>
@yield('content')
<!-- [ Main Content ] end -->
<!-- Required Js -->
<script src="/dashboard/assets/js/plugins/popper.min.js"></script>
<script src="/dashboard/assets/js/plugins/simplebar.min.js"></script>
<script src="/dashboard/assets/js/plugins/bootstrap.min.js"></script>
<script src="/dashboard/assets/js/fonts/custom-font.js"></script>
<script src="/dashboard/assets/js/config.js"></script>
<script src="/dashboard/assets/js/pcoded.js"></script>
<script src="/dashboard/assets/js/plugins/feather.min.js"></script>

<script src="/dashboard/assets/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "debug": false,
        "positionClass": "toast-bottom-right",
        "onclick": null,
        "fadeIn": 300,
        "fadeOut": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "preventDuplicates": true,
    }
</script>
@include('layouts.toastr')
@stack('script')
</body>

</html>
