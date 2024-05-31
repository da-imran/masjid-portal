<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       @include('root.headerMetadata')
    </head>
    <style>
       
    </style>
    <body>
        @include('index.indexTop')
          <div class="container">
            <div class="row">
              <div class="col-md-9">
                <h6 class="text mt-3" style="font-size: 20px"><b>Kutipan Tabung Masjid</b></h6>
                <p class="text mt-2" style="font-size: 14px"><i>Jumlah kutipan adalah di dalam Ringgit Malaysia (RM)</i></h6>
                <div class="mt-3 mb-5 p-3 border">
                  <table id="kutipanTabung" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Hari</th>
                        <th>Jumlah Harian</th>
                        <th>Minggu</th>
                        <th>Jumlah Mingguan</th>
                        <th>Bulan</th>
                        <th>Jumlah Bulanan</th>
                        <th>Tahun</th>
                        <th>Jumlah Keseluruhan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ( $kutipanList as $list )
                        <tr>
                          <td>{{$count++}}</td>
                          <td>{{$dayName[$list->kutipanID]}}</td>
                          <td>{{$list->kutipanDayTotal}}</td>
                          <td>{{$list->kutipanWeek}}</td>
                          <td>{{$list->kutipanWeekTotal}}</td>
                          <td>{{$monthName[$list->kutipanID]}}</td>
                          <td>{{$list->kutipanMonthTotal}}</td>
                          <td>{{$list->kutipanYear}}</td>
                          <td>{{$list->kutipanTotal}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-3">
                <h6 class="text mt-3" style="font-size: 20px"><b>Jom Infaq</b></h6>
                <p class="text mt-2" style="font-size: 14px"><i>Sumbangan boleh dilakukan dengan mengimbas atau klik kod QR</i></h6>
                <div class="mt-3 mb-5">
                  <a href="https://infaqpay.my/go/masjidalmustaghfirinsungaitiram"><img class="img-fluid mt-3" style="width: 200px; height: 250px" src="{{ asset('images/infaq_qr.png')}}"></a>
                </div>
              </div>
            </div>
          </div>
        @include('index.indexBottom')
    </body>
    <script>
      // new DataTable('#kutipanTabung', {
      //   info: true,
      //   ordering: true,
      //   paging: true,
      //   columns: [{width: '5%', targets: 0}, null, null, null, null, null, null, null, null],
      //   lengthMenu: [[10, 20, -1],[10, 20, 'All']],
      //   columnDefs: [
      //       { orderable: true, className: 'reorder', targets: [1,3,5,7] },
      //       { orderable: false, targets: '_all' }
      //   ]
      // });

      var groupColumn = 5;
      var table = $('#kutipanTabung').DataTable({
        columnDefs: [{ visible: false, targets: groupColumn }],
        order: [[groupColumn, 'asc']],
        lengthMenu: [[10, 20, -1],[10, 20, 'All']],
        displayLength: 10,
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;
    
            api.column(groupColumn, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows)
                            .eq(i)
                            .before(
                                '<tr class="group"><td colspan="10">' +
                                    group +
                                    '</td></tr>'
                            );
                        last = group;
                    }
                });
        }
      });

      $('#kutipanTabung tbody').on('click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
            table.order([groupColumn, 'desc']).draw();
        }
        else {
            table.order([groupColumn, 'asc']).draw();
        }
      });
    </script>
</html>
