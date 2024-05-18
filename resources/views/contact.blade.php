<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       @include('root.headerMetadata')
       <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    </head>
    <style>
    </style>
    <body>
        @include('index.indexTop')
          <div class="container">
            <div class="row">
              <div class="col">
                <h6 class="text mt-3 fs-5"><b>Hubungi Kami</b></h6>
                <div class="mt-2">
                  <p class="fs-4">Alamat</p>
                  <p class="fs-6">Jalan Sultan Azlan Shah, Kampung Sungai Tiram, 11900 Bayan Lepas, Pulau Pinang</p>
                </div>
                <div class="mt-2 mb-5">
                  <p class="fs-4">Telefon</p>
                  <p class="fs-6">+6019-320-0799</p>
                </div>
              </div>
            </div>
          </div>
        @include('index.indexBottom')
    </body>
    <script>
      
    </script>
</html>
