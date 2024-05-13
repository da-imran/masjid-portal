<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       @include('root.header')
       <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    </head>
    <style>
        a.btn.btn-light {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: flex-end;
          text-align: center;
          padding-bottom: 10px;
        }
        .img-button {
          max-height: 80px;
          max-width: 80px;
        }
        .image-text-container {
          background-color: rgb(31, 31, 31);
        }
        .footer {
          color: white;
          background-color: rgb(31, 31, 31);
        }
    </style>
    <body>
        <div class="top-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 pt-1 desktop">
                        <table width="100%" align="center" border="0" style="font-size:11px;">
                            <tbody><tr>
                                <td colspan="14" align="center"><a href="https://www.e-solat.gov.my/" target="_new" style="color: #2e3537"><u>WAKTU SOLAT</u></a> BAGI PULAU PINANG</td>
                                <td align="center"><strong> - </strong></td>
                                <td align="right" valign="top">IMSAK</td><td align="left" valign="top"> : <label style="font-weight:700;" id="imsak">5:42 AM</label> | </td>
                                <td align="right" valign="top">SUBUH</td><td align="left" valign="top"> : <label style="font-weight:700;" id="fajr">5:52 AM</label> | </td>
                                <td align="right" valign="top">SYURUK</td><td align="left" valign="top"> : <label style="font-weight:700;" id="syuruk">7:02 AM</label> | </td>
                                <td align="right" valign="top">ZOHOR</td><td align="left" valign="top"> : <label style="font-weight:700;" id="dhuhr">1:13 PM</label> | </td>
                                <td align="right" valign="top">ASAR</td><td align="left" valign="top"> : <label style="font-weight:700;" id="asr">4:32 PM</label> | </td>
                                <td align="right" valign="top">MAGHRIB</td><td align="left" valign="top"> : <label style="font-weight:700;" id="maghrib">7:20 PM</label> | </td>
                                <td align="right" valign="top">ISYAK</td><td align="left" valign="top"> : <label style="font-weight:700;" id="isyak">8:32 PM</label> </td>
                            </tr>
                        </tbody></table>
                    </div>
                    <div class="col-md-2 languages text-right">
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
                <div class="row">
                    <div class="col-md-10 div-menu">
                       
                    </div>
                </div>
            </div>
        </div>
        <div class="banner">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div id="carouselImages" class="carousel slide" data-ride="carousel" style="margin: 25px 0px 25px 0px; max-width: 100%; height:auto;">
                            <ol class="carousel-indicators">
                              <li data-target="#carouselImages" data-slide-to="0" class="active"></li>
                              <li data-target="#carouselImages" data-slide-to="1"></li>
                            </ol>
                            <div class="carousel-inner">
                              <div class="carousel-item active">
                                <img class="d-block w-100" src="{{ asset('images/slide1.png')}}" alt="First slide">
                              </div>
                              <div class="carousel-item">
                                <img class="d-block w-100" src="{{ asset('images/slide2.jpg')}}" alt="Second slide">
                              </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselImages" role="button" data-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="sr-only">&#10094;</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselImages" role="button" data-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="sr-only">&#10095;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="top">
            <div class="container">
                <div class="row">
                  <div class="col">
                    Content 1
                  </div>
                  <div class="col">
                    Content 2
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Content 1
                  </div>
                  <div class="col">
                    Content 2
                  </div>
                </div>
            </div>
        </div>
        <div class="container"><hr></div>
        <div class="bottom mt-5">
          <h6 class="text-center" style="font-size: 20px"><b>Pautan Agensi</b></h6>
          <div class="container">
              <div class="row">
                <div class="col">
                  <a href="https://www.islam.gov.my/ms/" class="btn btn-light" role="button"><img class="img-button d-block w-100" src="{{ asset('images/jakim.png')}}" alt="https://www.islam.gov.my/ms/" title="Jabatan Kemajuan Islam Malaysia">Jabatan Kemajuan Islam </br> Malaysia</a>
                </div>
                <div class="col">
                  <a href="https://www.penang.gov.my/index.php?lang=ms" class="btn btn-light" role="button"><img class="img-button d-block w-100" src="{{ asset('images/jata-pp.png')}}" alt="https://www.penang.gov.my/index.php?lang=ms" title="Portal Rasmi Kerajaan Negeri Pulau Pinang">Portal Rasmi Kerajaan Negeri</br>Pulau Pinang</a>
                </div>
                <div class="col">
                  <a href="https://jaipp.penang.gov.my/" class="btn btn-light" role="button"><img class="img-button d-block w-100" src="{{ asset('images/jata-pp.png')}}" alt="https://jaipp.penang.gov.my/" title="Jabatan Hal Ehwal Agama Islam Pulau Pinang">Jabatan Hal Ehwal Agama Islam</br>Pulau Pinang</a>
                </div>
                <div class="col">
                  <a href="https://mufti.penang.gov.my/" class="btn btn-light" role="button"><img class="img-button d-block w-100" src="{{ asset('images/mufti-pp.png')}}" alt="https://mufti.penang.gov.my/" title="Jabatan Mufti Negeri Pulau Pinang">Jabatan Mufti Negeri</br>Pulau Pinang</a>
                </div>
                <div class="col">
                  <a href="https://www.mainpp.gov.my/" class="btn btn-light" role="button"><img class="img-button d-block w-100" src="{{ asset('images/main-pp.png')}}" alt="https://www.mainpp.gov.my/" title="Majlis Agama Islam Negeri Pulau Pinang">Majlis Agama Islam Negeri</br>Pulau Pinang</a>
                </div>
                <div class="col">
                  <a href="https://zakatpenang.com/" class="btn btn-light" role="button"><img class="img-button d-block w-100" src="{{ asset('images/zakat-pp.png')}}" alt="https://zakatpenang.com/" title="Zakat Pulau Pinang">Zakat Pulau Pinang</a>
                </div>
                <div class="col">
                  <a href="https://wakafpenang.com.my/" class="btn btn-light" role="button"><img class="img-button d-block w-100" src="{{ asset('images/wakaf-pp.png')}}" alt="https://wakafpenang.com.my/" title="Wakaf Pulau Pinang">Wakaf Pulau Pinang</a>
                </div>
              </div>
          </div>
        </div>
        <div class="footer">
            <div class="container">
                <div class="row">
                  <div class="col-6 col-md-4 mt-3 mb-3">
                    <div><h1 style="font-size: 25px"><b>Jumlah Pelawat</b></h1></div>
                    <div>Hari Ini</div> 
                    <div>Keseluruhan</div> 
                  </div>
                  <div class="col-6 col-md-4  mt-3 mb-3">
                    <div><h1 style="font-size: 25px"><b>Pautan Web</b></h1></div>
                    <div>
                      <a href="#" class="text" alt="Data Terbuka" title="Data Terbuka">Data Terbuka</a> <br>
                      <a href="#" class="text" alt="Dasar Privasi" title="Dasar Privasi">Dasar Privasi</a> <br>
                      <a href="https://infaqpay.my/go/masjidalmustaghfirinsungaitiram" class="text" alt="Jom Infaq" title="Jom Infaq">Jom Infaq</a> <br>
                      <a href="https://www.facebook.com/MasjidAlMustaghfirinSungaiTiram" class="text" alt="Laman Facebook" title="Laman Facebook">Laman Facebook</a> <br>
                    </div>
                  </div>
                  <div class="col-6 col-md-4 mt-3 mb-3">
                    <div><h1 style="font-size: 25px"><b>Alamat</b></h1></div>
                    <div>Jalan Sultan Azlan Shah, Kampung Sungai Tiram, 11900 Bayan Lepas, Pulau Pinang</div> 
                    <div>Telefon: 0193200799</div> 
                  </div>
                </div>
            </div>
        </div>
    </body>
    <script>
       
    </script>
</html>
