<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       @include('root.headerMetadata')
       <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    </head>
    <style>
       .image-organisasi{
        width: 500px;
        max-width: 900px;
        margin: auto;
        display: block;
       }
    </style>
    <body>
        @include('index.indexTop')
          <div class="container">
            <div class="row">
              <div class="col">
                <h6 class="text mt-3" style="font-size: 20px"><b>Carta Organisasi</b></h6>
                <div class="col-md-12 mb-3">
                  <div class="image-organisasi border">
                    <img src="/images/masjid-organisasi.png" alt="Carta Organisasi">
                  </div>
                </div>
                <div class="mt-5 mb-5">
                  <p> Halaman ini sedang dibangunkan</p>
                </div>
              </div>
            </div>
          </div>
        @include('index.indexBottom')
    </body>
    <script>
      
    </script>
</html>
