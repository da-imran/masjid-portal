@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-9">
    <h6 class="text mt-3" style="font-size: 20px"><b>Kutipan Tabung Masjid</b></h6>
    <p class="text mt-2" style="font-size: 14px"><i>Jumlah kutipan adalah di dalam Ringgit Malaysia (RM)</i></h6>
    <div class="mt-3 mb-5 p-3 border">
      <table class="table table-striped" style="width:100%">
        <thead>
          <tr>
            <th>Hari</th>
            <th>Jumlah Harian</th>
            <th>Minggu</th>
            <th>Bulan / Tahun</th>
          </tr>
        </thead>
        <tbody>
          @php
            $currentWeek = null;
            $currentMonth = null;
            $weekTotal = 0;
            $monthTotal = 0;
          @endphp

          @foreach ( $kutipanList as $list )
            @php
              $isMonthBreak = ($currentMonth !== $list->month_name_ms . ' ' . $list->year);
              $isWeekBreak = ($currentWeek !== $list->week || $isMonthBreak);

              // Week footer: display before starting new week
              if ($isWeekBreak && $currentWeek !== null) {
            @endphp
                <tr class="week-footer" style="background-color: #e8f5e9; font-weight: bold;">
                  <td colspan="2">Jumlah Minggu Ke-{{$currentWeek}}</td>
                  <td colspan="2">RM {{ number_format($weekTotal, 2) }}</td>
                </tr>
            @php
                $weekTotal = 0;
              }

              // Month footer: display before starting new month
              if ($isMonthBreak && $currentMonth !== null) {
            @endphp
                <tr class="month-footer" style="background-color: #fff3e0; font-weight: bold;">
                  <td colspan="3">Jumlah Bulan {{$currentMonth}}</td>
                  <td>RM {{ number_format($monthTotal, 2) }}</td>
                </tr>
            @php
                $monthTotal = 0;
              }

              // Month header
              if ($isMonthBreak) {
            @endphp
                <tr class="group" style="background-color: #f0f0f0; font-weight: bold;">
                  <td colspan="4">{{$list->month_name_ms}} {{$list->year}}</td>
                </tr>
            @php
              }

              // Accumulate totals
              $weekTotal += $list->day_total;
              $monthTotal += $list->day_total;
              $currentWeek = $list->week;
              $currentMonth = $list->month_name_ms . ' ' . $list->year;
            @endphp

            <tr>
              <td>{{$list->day_name_ms}}</td>
              <td>{{$list->day_total}}</td>
              <td>{{$list->week}}</td>
              <td>{{$list->month_name_ms}} {{$list->year}}</td>
            </tr>

            @php
              // Handle last row - add week and month footers
              if ($loop->last) {
            @endphp
                <tr class="week-footer" style="background-color: #e8f5e9; font-weight: bold;">
                  <td colspan="2">Jumlah Minggu Ke-{{$currentWeek}}</td>
                  <td colspan="2">RM {{ number_format($weekTotal, 2) }}</td>
                </tr>
                <tr class="month-footer" style="background-color: #fff3e0; font-weight: bold;">
                  <td colspan="3">Jumlah Bulan {{$currentMonth}}</td>
                  <td>RM {{ number_format($monthTotal, 2) }}</td>
                </tr>
            @php
              }
            @endphp
          @endforeach
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-4">
        {{ $kutipanList->appends(['sort' => request('sort')])->links() }}
      </div>
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
@endsection
