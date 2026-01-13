@extends('layouts.app')

@section('styles')
<style>
    .content-wrapper {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <h6 class="text fs-5 mb-4"><b>Hubungi Kami</b></h6>
    <div class="row">
      <div class="col-md-6">
        <p class="fs-4 mb-1">Alamat</p>
        <p class="fs-6">Masjid Al Mustaghfirin, Bayan Lepas</p>
        <p class="fs-6">Jalan Sultan Azlan Shah, Kampung Sungai Tiram,</p>
        <p class="fs-6">11900 Bayan Lepas,</p>
        <p class="fs-6">Pulau Pinang</p>
        <div class="mt-3">
          <p class="fs-4 mb-1">Telefon</p>
          <p class="fs-6">+6019-320-0799</p>
        </div>
      </div>
      <div class="col-md-3 text-center">
        <h6 class="text fs-5 mb-3"><b>Layari Facebook</b></h6>
        <a href="https://www.facebook.com/MasjidAlMustaghfirinSungaiTiram/">
          <img class="img-fluid" style="width: 200px; height: 200px" src="{{ asset('images/fb_qr.png')}}" alt="Facebook QR">
        </a>
      </div>
      <div class="col-md-3 text-center">
        <h6 class="text fs-5 mb-3"><b>Lokasi Masjid</b></h6>
        <a href="https://maps.app.goo.gl/6Ho4nqzHfxZENYYLA">
          <img class="img-fluid" style="width: 200px; height: 200px" src="{{ asset('images/map_qr.png')}}" alt="Map QR">
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
