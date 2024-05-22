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
            <div class="row justify-content-center">
            <h6 class="text mt-3" style="font-size: 20px"><b>Pengumuman</b></h6>
              <div class="col-md-8">
                <div class="mt-3 mb-5 p-3">
                  @foreach ($pengumumanList as $ls)
                    <p class="h3">{{$ls->pengumuman_titleMS}}</p>
                    <img class="img-fluid w-100" src="{{ asset('images/pengumuman/'.$ls->pengumuman_fileName.'.jpg')}}">
                    <p class="fs-4">{{$ls->pengumuman_descriptionMS}}</p>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        @include('index.indexBottom')
    </body>
    <script>
      
    </script>
</html>
