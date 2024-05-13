<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Employee Profile</title>

  <link rel="icon" href="{{ asset("build/assets/img/logo.png") }}" type="image/icon type">
  <link href="{{ asset('build/assets/css/app.css') }}" rel="stylesheet">
  @vite(['resources/scss/app.scss'])
  @livewireStyles

</head>
<body class="font-sans antialiased">
  <div class="container-fluid">
    @livewire('navbar')
    @livewire('table')
  </div>




  @vite(['resources/js/app.js'])


</body>
</html>