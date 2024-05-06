<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       @include('root.header')
       <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    </head>
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
    </body>
</html>
