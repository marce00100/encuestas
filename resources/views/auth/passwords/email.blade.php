@extends('layouts.auth')

@section('content')
  <!-- Forgotten Password Container -->
  <div id="forgotten-container">
     <h3>Recuperar Contraseña</h3>
    <span class="close-btn">
      <img src="https://cdn4.iconfinder.com/data/icons/miu/22/circle_close_delete_-128.png"></img>
    </span>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
      {{ csrf_field() }}

      <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"  placeholder="E-mail" required>
      @if ($errors->has('email'))
          <span class="help-block">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
      @endif
      <button type="submit" class="btn btn-primary iniciar-session orange-btn">
          Send Password Reset Link
      </button>
  </form>
  </div>
@endsection
