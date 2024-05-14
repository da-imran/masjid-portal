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
              <div class="col">
                <h6 class="text mt-3" style="font-size: 20px"><b>Sejarah Masjid</b></h6>
                <div class="mt-5 mb-5">
                  <p> Masjid ini dibina pada .... Dan ia mula digunakan dengan rasminya pada ... 
                    Pada asalnya, masjid ini bernama Masjid Jamek Sungai Tiram. Pada masa ia diasaskan, masjid ini dikelilingi oleh sebuah kampung, dengan lorong - lorong kecil 
                    masuk dari jalan utama Bayan Lepas atau dikenali sebagai Jalan Sultan Azlan Shah. Jalan yang sibuk ini menghubungkan Zon Perindustrian Bebas Bayan Lepas ke 
                    Lapangan Terbang Antarabangsa Pulau Pinang.</p>
                    <br>
                  <p>Pada sekitar tahun 2019, masjid ini telah mempunyai rupa yang baharu. Pembinaan masjid yang baharu ini dapat menempatkan ramai lagi jemaah yang hadir dengan
                    keluasan yang lebih besar dari  masjid yang dahulu. Struktur asal masjid yang terdahulu masih juga dapat dilihat pada bahagian tengah bangunan masjid yang baru dibina.
                    Nama asal masjid ini juga telah digantikan dengan nama yang terkini, Masjid Al Mustaghfirin, Bayan Lepas.
                  </p>
                  <br>
                  <p>Pembangunan masjid yang baru ini amat penting kerana ia seiring dengan kepesatan pembangunan di sekitar Bayan Lepas. Dengan penambahan penduduk yang pesat di 
                    sekitar Bayan Lepas, lebih banyak jemaah yang dapat ditampung oleh masjid yang baharu ini.
                  </p>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b><i>Pintu Gerbang</i></b></h6>
                    <p> Informasi 1</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b><i>Menara</i></b></h6>
                    <p> Informasi 2</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b><i>Kubah Utama</i></b></h6>
                    <p> Informasi 3</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b><i>Mimbar Masjid</i></b></h6>
                    <p> Informasi 4</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b><i>Aras Satu</i></b></h6>
                    <p> Informasi 5</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b><i>Aras Bawah</i></b></h6>
                    <p> Informasi 6</p> 
                  </div>
                  <div class="mt-5">
                    <h6 class="text" style="font-size: 15px;"><b><i>Tarikan Pelancong</i></b></h6>
                    <p> Informasi 7</p> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        @include('index.indexBottom')
    </body>
    <script>
      
    </script>
</html>
