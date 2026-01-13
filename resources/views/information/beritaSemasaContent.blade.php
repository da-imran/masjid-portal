@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <h6 class="text mt-3" style="font-size: 20px"><b>Berita Semasa</b></h6>
  <div class="col-md-8">
    <div class="mt-3 mb-5 p-3">
      @if(isset($berita) && $berita instanceof \App\Models\BeritaSemasa)
        <!-- Single item display -->
        <p class="h3">{{$berita->title_ms}}</p>
        @if($berita->image_name)
        <img class="img-fluid w-100" src="{{ asset('storage/' . $berita->image_name) }}">
        @endif
        <p class="fs-4">{!! $berita->description_ms !!}</p>
      @else
        <!-- List display -->
        @foreach ($beritaList as $ls)
          <a href="{{ route('berita.show', $ls->id) }}" style="text-decoration: none; color: inherit;">
            <p class="h3">{{$ls->title_ms}}</p>
          </a>
          @if($ls->image_name)
          <img class="img-fluid w-100" src="{{ asset('storage/' . $ls->image_name) }}">
          @endif
          <p class="fs-4">{!! $ls->description_ms !!}</p>
          <hr class="my-4">
        @endforeach
      @endif
    </div>
  </div>
</div>
@endsection
