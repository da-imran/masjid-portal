@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <h6 class="text mt-3" style="font-size: 20px"><b>Pengumuman</b></h6>
  <div class="col-md-8">
    <div class="mt-3 mb-5 p-3">
      @if(isset($pengumuman) && $pengumuman instanceof \App\Models\Pengumuman)
        <!-- Single item display -->
        <p class="h3">{{$pengumuman->title_ms}}</p>
        @if($pengumuman->image_name)
        <img class="img-fluid w-100" src="{{ asset('storage/' . $pengumuman->image_name) }}">
        @endif
        <p class="fs-4">{!! $pengumuman->content_ms !!}</p>
      @else
        <!-- List display -->
        @foreach ($pengumumanList as $ls)
          <a href="{{ route('pengumuman.show', $ls->id) }}" style="text-decoration: none; color: inherit;">
            <p class="h3">{{$ls->title_ms}}</p>
          </a>
          @if($ls->image_name)
          <img class="img-fluid w-100" src="{{ asset('storage/' . $ls->image_name) }}">
          @endif
          <p class="fs-4">{!! $ls->content_ms !!}</p>
          <hr class="my-4">
        @endforeach
      @endif
    </div>
  </div>
</div>
@endsection
