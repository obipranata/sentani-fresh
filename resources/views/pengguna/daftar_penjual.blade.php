@extends('layouts.templates')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('assets/images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Registrasi</span></p>
                <h1 class="mb-0 bread">Registrasi Akun Penjual</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section contact-section bg-light">
    <div class="container">
      <div class="row block-9">
        <div class="col-md-6 order-md-last d-flex">
          <form action="{{ route('register') }}" class="bg-white p-5 contact-form" method="POST">
            @csrf
            <input type="hidden" name="jenis_daftar" value="2">

            <div class="form-group">
              <input type="text" class="form-control" id="lat" placeholder="latitude" name="lat">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" id="lng" placeholder="longitude" name="lng">
            </div>

            <div class="form-group">
              <input type="text" class="form-control @error('nama_toko') is-invalid @enderror" placeholder="nama toko" name="nama_toko" required value="{{ old('nama_toko') }}">
            </div>
            @error('nama_toko')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror

            <div class="form-group">
              <input type="text" class="form-control" placeholder="nama penjual" name="nama" required>

              @error('nama')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
            <div class="form-group">
              <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="username">

              @error('username')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
            <div class="form-group">
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email">

              @error('email')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>

            <div class="form-group">
              <input type="text" class="form-control" placeholder="alamat" name="alamat" required>
            </div>

            <div class="form-group">
              <input type="text" class="form-control" placeholder="no hp" name="no_hp" required>
            </div>

            <div class="form-group">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="masukan password">

              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
            <div class="form-group">
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="masukan ulang password">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary py-3 px-5">Daftar</button>
            </div>
          </form>
        
        </div>

      <script type="text/javascript">
          var cord = [];
      </script>

        <div class="col-md-6">
          <div id="googleMap" class="bg-white" style="height: 600px;"></div>
        </div>
      </div>
    </div>
  </section>


@endsection