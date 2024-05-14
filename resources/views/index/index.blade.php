<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       @include('root.headerMetadata')
       <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    </head>
    <body>
        @include('index.indexTop')
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
        @include('index.indexContent')
        <div class="container mt-5"><hr></div>
        @include('index.indexLink')
        @include('index.indexBottom')
    </body>
    <script>
      const navLinks = document.querySelectorAll('.nav-link.dropdown-toggle');

      navLinks.forEach(link => {
          link.addEventListener('mouseover', () => {
              const dropdownMenu = link.nextElementSibling;
              dropdownMenu.classList.toggle('show');
          });
      });
    </script>
</html>
