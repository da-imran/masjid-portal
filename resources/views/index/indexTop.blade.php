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
            <div class="col-md-10 pt-1 desktop">
                <table width="100%" align="center" border="0" style="font-size:12px;">
                    <tbody>
                      <tr>
                        @if ($errorMessage)
                          <td colspan="14" align="center" valign="top" style="color: red; font-weight: bold;">
                            {{ $errorMessage }}
                          </td>
                        @else
                          <td align="right" valign="top"><a href="https://www.e-solat.gov.my/" target="_new" style="color: #000000"><u>WAKTU SOLAT</u></a> BAGI PULAU PINANG</td>
                          <td align="right" valign="top"><strong> &nbsp;&nbsp;- </strong></td>
                          <td align="right" valign="top">SUBUH</td><td align="left" valign="top"> : <label style="font-weight:700;" id="fajr">{{$waktuSolat->fajr}}</label> | </td>
                          <td align="right" valign="top">SYURUK</td><td align="left" valign="top"> : <label style="font-weight:700;" id="syuruk">{{$waktuSolat->syuruk}}</label> | </td>
                          <td align="right" valign="top">ZOHOR</td><td align="left" valign="top"> : <label style="font-weight:700;" id="dhuhr">{{$waktuSolat->dhuhr}}</label> | </td>
                          <td align="right" valign="top">ASAR</td><td align="left" valign="top"> : <label style="font-weight:700;" id="asr">{{$waktuSolat->asr}}</label> | </td>
                          <td align="right" valign="top">MAGHRIB</td><td align="left" valign="top"> : <label style="font-weight:700;" id="maghrib">{{$waktuSolat->maghrib}}</label> | </td>
                          <td align="right" valign="top">ISYAK</td><td align="left" valign="top"> : <label style="font-weight:700;" id="isyak">{{$waktuSolat->isha}}</label> </td>
                        @endif
                      </tr>
                </tbody></table>
            </div>
            {{-- <div class="col-md-2 languages text-right">
                <div class="moduletable">
                    <div class="mod-languages">
                    <ul class="lang-inline" dir="ltr">
                        <li class="lang-active">
                            <a href="http://masjid-portal.test/index">
                            <img src="/media/mod_languages/images/ms_my.gif" alt="Bahasa Melayu " title="Bahasa Melayu "></a>
                            </li>
                            <li>
                            <a href="/en/">
                            <img src="/media/mod_languages/images/en_gb.gif" alt="English (United Kingdom)" title="English (United Kingdom)"></a>
                        </li>
                    </ul>
                    </div>
                </div>
            </div> --}}
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
      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item">
            <a class="navbar-link" href="http://masjid-portal.test/utama">UTAMA</a>
          </li>
          <li class="nav-item dropdown">
            <a class="dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              INFO KORPORAT
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="{{url('/info-korporat/perutusan-imam-besar')}}">Perutusan Imam Besar</a>
              <a class="dropdown-item" href="{{url('/info-korporat/sejarah-masjid')}}">Sejarah Masjid</a>
              <a class="dropdown-item" href="{{url('/info-korporat/profil-korpora')}}t">Profil Korporat</a>
              <a class="dropdown-item" href="{{url('/info-korporat/logo')}}">Logo</a>
              <a class="dropdown-item" href="{{url('/info-korporat/carta-organisasi')}}">Carta Organisasi</a>
              <a class="dropdown-item" href="{{url('/info-korporat/direktori-kakitangan')}}">Direktori Kakitangan</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              INFORMASI
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="{{ url('/informasi/berita-semasa')}}">Berita Semasa</a>
              <a class="dropdown-item" href="{{ url('/informasi/kemudahan')}}">Kemudahan</a>
              <a class="dropdown-item" href="{{ url('/informasi/takwim')}}">Takwim</a>
              <a class="dropdown-item" href="{{ url('/informasi/kutipan-tabung')}}">Kutipan Tabung Masjid</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              MUAT TURUN
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="{{ url('/muat-turun/jadual-kuliah')}}">Jadual Kuliah</a>
              <a class="dropdown-item" href="{{ url('/muat-turun/nota-kuliah')}}">Nota Kuliah</a>
              <a class="dropdown-item" href="{{ url('/muat-turun/borang')}}">Borang</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="navbar-link" href="/hubungi-kami">HUBUNGI KAMI</a>
          </li>
        </ul>
        {{-- <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" style="font-size: 10px; border-radius: 15px 15px 15px 15px" type="search" placeholder="Carian">
            <button class="btn btn-outline-primary my-2 my-sm-0 btn-sm" type="submit">Carian</button>
        </form> --}}
      </div>
    </nav>
  </div>
</div>
<script>
  const navLinks = document.querySelectorAll('.nav-link.dropdown-toggle');

  navLinks.forEach(link => {
      link.addEventListener('mouseover', () => {
          const dropdownMenu = link.nextElementSibling;
          dropdownMenu.classList.toggle('show');
      });
  });
</script>