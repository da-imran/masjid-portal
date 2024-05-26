<style>
  .footer {
    color: white;
    background-color: rgb(31, 31, 31);
  }
</style>
<div class="footer">
    <div class="container">
        <div class="row">
          <div class="col-6 col-md-4 mt-3 mb-3">
            <div><h1 style="font-size: 25px"><b>Jumlah Pelawat</b></h1></div>
            <div class="row">
              <div class="col-md-3">
                <div>Hari Ini</div> 
                <div>Bulan Ini</div> 
                <div>Keseluruhan</div> 
              </div>
              <div class="col-md-3">
                <div>{{$visitorCount->original['dailyCount']}}</div> 
                <div>{{$visitorCount->original['monthlyCount']}}</div> 
                <div>{{$visitorCount->original['overallCount']}}</div> 
              </div>
            </div>
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
            <p>Masjid Al Mustaghfirin, Bayan Lepas</p> 
            <p>Jalan Sultan Azlan Shah, Kampung Sungai Tiram,</p>
            <p>11900 Bayan Lepas,</p>
            <p>Pulau Pinang</p> 
            <p class="font-italic">Telefon: +019-320-0799</div> 
          </div>
        </div>
    </div>
  </div>
  <script>