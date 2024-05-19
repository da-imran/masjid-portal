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
                <h6 class="text mt-3" style="font-size: 20px"><b>Muat Turun Borang</b></h6>
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
