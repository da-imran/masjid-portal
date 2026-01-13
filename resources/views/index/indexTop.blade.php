<style>
  .image-text-container {
    align-items: center;
    background-color: rgb(31, 31, 31);
    color: white;
    display: flex;
    font-weight: 600;
    font-style: italic;
    font-size: 1.5rem;
    justify-content: center;
    letter-spacing: 1px;
    text-decoration: none;
    height: 5em;
  }
  .image-text-container img {
    margin-right: 10px;
    height: 5rem;
  }
  li.nav-item {
    font-weight: bold;
    color: black;
  }
  li.nav-link:hover {
    background-color: white;
    color: red;
  }
  .dropdown:hover .dropdown-menu{
    display: block;
    margin-top: 0;
  }
  .navbar {
    justify-content: center !important;
  }
  .navbar .nav-item:not(:last-child) {
    margin-right: 35px;
  }
  .dropdown-toggle::after {
    transition: transform 0.15s linear;
  }
  .show.dropdown .dropdown-toggle::after {
    transform: translateY(3px);
  }
  .dropdown-menu {
    margin-top: 0;
  }
</style>
@php
  $response = app('App\Http\Controllers\APIController')->ApiSolat();
  $waktuSolat = null;
  $errorMessage = null;

  if ($response->status() === 200) {
    $waktuSolat = $response->getData()->data;
  } else {
    $errorMessage = $response->getData()->error;
  }
@endphp
<div class="top-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-12 pt-1 desktop">
                <table width="100%" border="0" style="font-size:12px;">
                    <tbody>
                      <tr>
                        @if ($errorMessage)
                          <td colspan="14" align="center" valign="top" style="color: red; font-weight: bold;">
                            {{ $errorMessage }}
                          </td>
                        @else
                          <td colspan="2" align="center" valign="top" style="color: #000000;">
                            <a href="https://www.e-solat.gov.my/" target="_new" style="color: #000000"><u>WAKTU SOLAT</u></a> BAGI PULAU PINANG
                          </td>
                          <td align="center" valign="top"><strong>SUBUH</strong></td>
                          <td align="center" valign="top"> : <label style="font-weight:700;" id="fajr">{{$waktuSolat->fajr}}</label> | </td>
                          <td align="center" valign="top"><strong>SYURUK</strong></td>
                          <td align="center" valign="top"> : <label style="font-weight:700;" id="syuruk">{{$waktuSolat->syuruk}}</label> | </td>
                          <td align="center" valign="top"><strong>ZOHOR</strong></td>
                          <td align="center" valign="top"> : <label style="font-weight:700;" id="dhuhr">{{$waktuSolat->dhuhr}}</label> | </td>
                          <td align="center" valign="top"><strong>ASAR</strong></td>
                          <td align="center" valign="top"> : <label style="font-weight:700;" id="asr">{{$waktuSolat->asr}}</label> | </td>
                          <td align="center" valign="top"><strong>MAGHRIB</strong></td>
                          <td align="center" valign="top"> : <label style="font-weight:700;" id="maghrib">{{$waktuSolat->maghrib}}</label> | </td>
                          <td align="center" valign="top"><strong>ISYAK</strong></td>
                          <td align="center" valign="top"> : <label style="font-weight:700;" id="isyak">{{$waktuSolat->isha}}</label> </td>
                        @endif
                      </tr>
                </tbody></table>
            </div>
        </div>
    </div>
</div>
<div class="image-text-container">
    <img src="/images/masjid.jpeg" alt="Image Description">
    <p>Masjid Al Mustaghfirin, Bayan Lepas</p>
</div>
<div id="menu">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarTogglerDemo02">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">UTAMA</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              INFO KORPORAT
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="{{ route('corporate.perutusan') }}">Perutusan Imam Besar</a>
              <a class="dropdown-item" href="{{ route('corporate.sejarah') }}">Sejarah Masjid</a>
              <a class="dropdown-item" href="{{ route('corporate.profil') }}">Profil Korporat</a>
              <a class="dropdown-item" href="{{ route('corporate.logo') }}">Logo</a>
              <a class="dropdown-item" href="{{ route('corporate.carta') }}">Carta Organisasi</a>
              <a class="dropdown-item" href="{{ route('corporate.direktori') }}">Direktori Kakitangan</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              INFORMASI
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
              <a class="dropdown-item" href="{{ route('berita.index') }}">Berita Semasa</a>
              <a class="dropdown-item" href="{{ route('pengumuman.index') }}">Pengumuman</a>
              <a class="dropdown-item" href="{{ route('kemudahan') }}">Kemudahan</a>
              <a class="dropdown-item" href="{{ route('takwim') }}">Takwim</a>
              <a class="dropdown-item" href="{{ route('kutipan.index') }}">Kutipan Tabung Masjid</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              MUAT TURUN
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
              <a class="dropdown-item" href="{{ route('download.jadual') }}">Jadual Kuliah</a>
              <a class="dropdown-item" href="{{ route('download.nota') }}">Nota Kuliah</a>
              <a class="dropdown-item" href="{{ route('download.borang') }}">Borang</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('contact') }}">HUBUNGI KAMI</a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</div>
