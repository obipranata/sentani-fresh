{{-- @extends('layouts.app') --}}

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sentani Fresh</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="admin/assets/css/bootstrap.css" />
    <link
      rel="stylesheet"
      href="admin/assets/vendors/bootstrap-icons/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="admin/assets/css/app.css" />
    <link rel="stylesheet" href="admin/assets/css/pages/auth.css" />
  </head>

  <body>
    <div id="auth">
      <div class="row h-100">
        <div class="col-lg-5 col-12">
          <div id="auth-left">
            {{-- <div class="auth-logo">
              <a href="">
                  <img src="admin/assets/images/logo/logo2.png" alt="Logo"/>
              </a>
            </div> --}}
            <h1 class="auth-title">Sentani Fresh.</h1>
            <p class="auth-subtitle mb-5">
              Login dengan data yang telah terdaftar
            </p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
              <div class="form-group position-relative has-icon-left mb-4">
                <input id="username" type="text" class="form-control form-control-xl @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="username">

                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span> 
                @enderror
                <div class="form-control-icon">
                  <i class="bi bi-person"></i>
                </div>
              </div>
              <div class="form-group position-relative has-icon-left mb-4">
                <input id="password" type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="form-control-icon">
                  <i class="bi bi-shield-lock"></i>
                </div>
              </div>
              <div class="form-check form-check-lg d-flex align-items-end">
                <input class="form-check-input me-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label text-gray-600" for="remember">
                    {{ __('Remember Me') }}
                </label>
              </div>

              <input type="hidden" name="lat" id="lat">
              <input type="hidden" name="lng" id="lng">

              <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
                Log in
              </button>
            </form>
            <div class="text-center mt-2 text-lg fs-4">
              <p>
                <a class="font-bold" href="/">
                  home
                </a>
              </p>
            </div>
            <div class="text-center mt-5 text-lg fs-4">
              <p>
                @if (Route::has('password.request'))
                <a class="font-bold" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
                @endif
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <img src="admin/assets/images/1.jpg" class="img-fluid"/>
        </div>
      </div>
    </div>



  <script type="text/javascript">
    var cord = [];
  </script>

  <div id="googleMap" class="bg-white" style="height: 0;"></div>
  <script src="/assets/js/jquery.min.js"></script>
  </body>
</html>

<script type="text/javascript">
  function myMap() {
      var mapProp = {
          center: new google.maps.LatLng(-2.53371, 140.71813),
          zoom: 13,
      };
      var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
      var marker;

      window.onload = function() {
          var startPos;
          var geoSuccess = function(position) {
              startPos = position;
              console.log(startPos.coords.latitude);
              console.log(startPos.coords.longitude);

              $("#lat").val(startPos.coords.latitude);
              $("#lng").val(startPos.coords.longitude);

              marker = new google.maps.Marker({
                  position: new google.maps.LatLng(startPos.coords.latitude, startPos.coords.longitude),
                  map: map,
                  icon: "/assets/lokasi2.png",
                  animation: google.maps.Animation.BOUNCE
              });
              var infowindowText = "<div class='text-center'><strong>Posisi Saat Ini</strong> Lat : " +
                  startPos.coords.latitude + " | Long: " + startPos.coords.longitude + "</strong></div>";
              infowindow.setContent(infowindowText);
              infowindow.open(map, marker);
              marker.addListener('click', function() {
                  infowindow.open(map, marker);
              });
          };
          navigator.geolocation.getCurrentPosition(geoSuccess);
      };

      var infowindow = new google.maps.InfoWindow({});

  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLsBUFqgsrYYjB_jXFkC1Esh8qQv-Yzw4&callback=myMap"></script>

