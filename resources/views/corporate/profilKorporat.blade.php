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
                <h6 class="text mt-3" style="font-size: 20px"><b>VISI</b></h6>
                <div class="mt-5 mb-5">
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b>MISI</b></h6>
                    <p> Informasi 1</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b>OBJEKTIF</b></h6>
                    <p> Informasi 2</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b>MOTO</b></h6>
                    <p> Informasi 3</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b>ETIKA KERJA</b></h6>
                    <p> Informasi 4</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b>PIAGAM PELANGGAN</b></h6>
                    <p> Informasi 5</p> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        @include('index.indexBottom')
    </body>
    <script>
      
    </script>
</html>
