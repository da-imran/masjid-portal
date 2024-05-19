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
              <div class="col-md-10">
                <h6 class="text mt-3" style="font-size: 20px"><b>Berita Semasa</b></h6>
                <div class="mt-5 mb-5 p-3 border">
                  <table id="beritaSemasa" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Tajuk</th>
                        <th>Tarikh Dicipta</th>
                        <th>Dikunjungi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ( $beritaList as $list )
                        <tr>
                          <td>{{$count++}}</td>
                          <td><a href="{{ route('berita.show', $list->beritaID) }}">{{$list->berita_titleMS}}</a></td>
                          <td>{{Carbon\Carbon::parse($list->berita_createdAt)->format('d M Y')}}</td>
                          <td>{{$list->berita_visitCount}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        @include('index.indexBottom')
    </body>
    <script>
      new DataTable('#beritaSemasa', {
        info: false,
        ordering: false,
        paging: false,
        columns: [{width: '5%', targets: 0}, {width: '70%', targets: 1}, null, null]
      });
    </script>
</html>
