<style>
    .img-content {
        max-height: 80px;
        max-width: 80px;
    }
</style>
<div class="top">
    <div class="container">
        <div class="row">
            <div class="col-md-6 news">
              <h6 class="text" style="font-size: 20px"><b>Berita</b></h6>
              <div id="carouselBerita" class="carousel slide" data-ride="carousel" style="margin: 25px 0px 25px 0px; max-width: 100%; height:auto;">
                <ol class="carousel-indicators">
                  @for ($i = 0; $i < ceil(count($beritaList) / 2); $i++)
                    <li data-target="#carouselBerita" data-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></li>
                  @endfor
                </ol>
                <div class="carousel-inner">
                  @foreach ( $beritaList->chunk(2) as $index => $beritaRow )
                  <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    @foreach ($beritaRow as $ls)
                    <div class="row mb-3">  
                      <div class="col-md-3"> 
                        <img class="d-block w-100" src="{{ asset('images/berita/'.$ls->berita_fileName.'.jpg')}}" alt="First slide">
                      </div>
                      <div class="media-body">
                        <h5 class="fs-5">{{$ls->berita_titleMS}}</h5>
                        <p>{{$ls->berita_descriptionMS}}</p>
                        <br>
                        <a href="{{ route('berita.show', $ls->beritaID) }}">Baca Selanjutnya</a>
                      </div>
                    </div>
                    @endforeach
                  </div>
                  @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselBerita" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">&#10094;</span>
                </a>
                <a class="carousel-control-next" href="#carouselBerita" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">&#10095;</span>
                </a>
              </div>
            </div>
            <div class="col-md-6 announcements">
              <h6 class="text" style="font-size: 20px"><b>Pengumuman</b></h6>
              <div id="carouselPengumuman" class="carousel slide" data-ride="carousel" style="margin: 25px 0px 25px 0px; max-width: 100%; height:auto;">
                <ol class="carousel-indicators">
                  @for ($i = 0; $i < ceil(count($pengumumanList) / 2); $i++)
                    <li data-target="#carouselPengumuman" data-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></li>
                  @endfor
                </ol>
                <div class="carousel-inner">
                  @foreach ( $pengumumanList->chunk(2) as $index => $pengumumanRow )
                  <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    @foreach ($pengumumanRow as $ls)
                    <div class="row mb-3">  
                      <div class="col-md-3"> 
                        <img class="d-block w-100" src="{{ asset('images/pengumuman/'.$ls->pengumuman_fileName.'.jpg')}}" alt="First slide">
                      </div>
                      <div class="media-body">
                        <h5 class="fs-5">{{$ls->pengumuman_titleMS}}</h5>
                        <p>{{$ls->pengumuman_descriptionMS}}</p>
                        <br>
                        <a href="{{ route('pengumuman.show', $ls->pengumumanID) }}">Baca Selanjutnya</a>
                      </div>
                    </div>
                    @endforeach
                  </div>
                  @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselPengumuman" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">&#10094;</span>
                </a>
                <a class="carousel-control-next" href="#carouselPengumuman" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">&#10095;</span>
                </a>
              </div>
            </div>
        </div>
    </div>
</div>