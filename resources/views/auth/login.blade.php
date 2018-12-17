@extends('layouts.auth')

@section('content')

<style media="screen">
  .help-block {

    color: #c46042;

  }
</style>
<div  style="color: #333; font-family: arial; margin: 0 0 0 100px"><h2>Herramienta para encuestas v1.1</h2></div>
<div id="container">
  
    <h1>login</h1>

    <span class="close-btn">
      {{-- <img src="/img/close.png"> --}}
    </span>

    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
      {{ csrf_field() }}
      @if ($errors->has('username'))
         <p style="margin-left: 25px;">
          <span class="help-block">
              <strong>{{ $errors->first('username') }}</strong>
          </span>
        </p>
      @endif
      <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
          <div class="col-md-12">
              <input id="username" type="username" name="username" value="{{ old('username') }}" placeholder="Usuario" required autofocus>
          </div>
      </div>
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
          <div class="col-md-12">
              <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
          </div>
      </div>
      <button type="submit" class="btn iniciar-session" style="color:#fff;">
          Iniciar 
      </button>
      {{-- <div id="remember-container">
        <input type="checkbox" name="remember" class="checkbox" {{ old('remember') ? 'checked' : '' }}>
        <span id="remember">Recordarme</span>
        <a id="forgotten" href="{{ route('password.request') }}">
            Olvidaste tu contraseña?
        </a>
      </div> --}}
    </form>
</div>

@endsection
