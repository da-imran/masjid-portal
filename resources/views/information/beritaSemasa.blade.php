<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       @include('root.headerMetadata')
       <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    </head>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content-wrapper {
            flex: 1;
        }
    </style>
    <body>
        @include('index.indexTop')
          <div class="container content-wrapper">
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
                      @php $count = 1; @endphp
                      @foreach ( $beritaList as $list )
                        <tr>
                          <td>{{$count++}}</td>
                          <td><a href="{{ route('berita.show', $list->id) }}">{{$list->title_ms}}</a></td>
                          <td>{{ \Carbon\Carbon::parse($list->created_at)->format('d M Y') }}</td>
                          <td>{{$list->view_count}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          @include('index.indexBottom')
          <script>
            new DataTable('#beritaSemasa', {
              info: true,
              ordering: false,
              paging: true,
              columns: [{width: '5%', targets: 0}, {width: '70%', targets: 1}, null, null],
              lengthMenu: [[10, 20, -1],[10, 20, 'All']]
            });
          </script>
    </body>


</html>
