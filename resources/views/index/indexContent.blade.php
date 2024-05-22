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
                    @if (count($beritaList) % 2 !== 0 && $loop->iteration === count($beritaList->chunk(2)))
                      @foreach (array_slice($beritaList->toArray(), $index * 2) as $ls)
                      <div class="row">
                        <div class="col-md-3">
                          <img class="d-block w-100" src="{{ asset('images/berita/berita_2.jpg')}}" alt="Second slide">
                        </div>
                        <div class="media-body">
                          <h5 class="fs-5">Hari Riadah Keluarga Al-Mustaghfirin</h5>
                          <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
                          <br>
                          <a href="{{ route('berita.show', $ls->beritaID) }}">Baca Selanjutnya</a>
                        </div>
                      </div>
                      @endforeach
                    @endif
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
                  <li data-target="#carouselPengumuman" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselPengumuman" data-slide-to="1"></li>
                  <li data-target="#carouselPengumuman" data-slide-to="2"></li>
                  <li data-target="#carouselPengumuman" data-slide-to="3"></li>
                </ol>
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <div class="row mb-3">  
                      <div class="col-md-3"> 
                        <img class="d-block w-100" src="{{ asset('images/pengumuman/pengumuman_1.jpg')}}" alt="First slide">
                      </div>
                      <div class="media-body">
                        <h5 class="fs-5">Majlis Selawat Al-Mustaghfirin</h5>
                        <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
                        <br>
                        <a href="/informasi/berita-semasa">Baca Selanjutnya</a>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3">
                        <img class="d-block w-100" src="{{ asset('images/pengumuman/pengumuman_2.jpg')}}" alt="Second slide">
                      </div>
                      <div class="media-body">
                        <h5 class="fs-5">Hari Riadah Keluarga Al-Mustaghfirin</h5>
                        <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
                        <br>
                        <a href="/informasi/berita-semasa">Baca Selanjutnya</a>
                      </div>
                    </div>
                  </div>
                  <div class="carousel-item">
                    <div class="row mb-3">  
                      <div class="col-md-3"> 
                        <img class="d-block w-100" src="{{ asset('images/pengumuman/pengumuman_3.jpg')}}" alt="First slide">
                      </div>
                      <div class="media-body">
                        <h5 class="fs-5">Rehlah Dakwah</h5>
                        <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
                        <br>
                        <a href="/informasi/berita-semasa">Baca Selanjutnya</a>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3">
                        <img class="d-block w-100" src="{{ asset('images/pengumuman/pengumuman_4.jpg')}}" alt="Second slide">
                      </div>
                      <div class="media-body">
                        <h5 class="fs-5">Ceramah Perdana Ustaz Kazim Elias</h5>
                        <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
                        <br>
                        <a href="/informasi/berita-semasa">Baca Selanjutnya</a>
                      </div>
                    </div>
                  </div>
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