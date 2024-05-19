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
              <div class="col-md-4">
                <h6 class="text mt-3 fs-5"><b>Hubungi Kami</b></h6>
                <div class="mt-2">
                  <p class="fs-4">Alamat</p>
                  <p class="fs-6">Masjid Al Mustaghfirin, Bayan Lepas</p> 
                  <p class="fs-6">Jalan Sultan Azlan Shah, Kampung Sungai Tiram,</p>
                  <p class="fs-6">11900 Bayan Lepas,</p>
                  <p class="fs-6">Pulau Pinang</p> 
                </div>
                <div class="mt-2 mb-5">
                  <p class="fs-4">Telefon</p>
                  <p class="fs-6">+6019-320-0799</p>
                </div>
              </div>
              <div class="col-md-4">
                <h6 class="text mt-3 fs-5"><b>Layari Facebook</b></h6>
                <a href="https://www.facebook.com/MasjidAlMustaghfirinSungaiTiram/"><img class="img-fluid mt-3" style="width: 200px; height: 200px" src="{{ asset('images/fb_qr.png')}}"></a>
              </div>
              <div class="col-md-4">
                <h6 class="text mt-3 fs-5"><b>Lokasi Masjid</b></h6>
                <a href="https://maps.app.goo.gl/6Ho4nqzHfxZENYYLA"><img class="img-fluid mt-3"  style="width: 200px; height: 200px" src="{{ asset('images/map_qr.png')}}"></a>
              </div>
            </div>
          </div>
        @include('index.indexBottom')
    </body>
    <script>
      
    </script>
</html>
