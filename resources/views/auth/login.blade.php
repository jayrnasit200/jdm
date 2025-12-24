<x-guest-layout>
                <!-- Card -->
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-muted ">
                        {{-- <h4>{{ __('Login') }}</h4>
                         --}}
                         <div class="logo-wrapper mb-1 text-center">
                         <a href="{{ url('/') }}">
                            {{-- Replace with your actual logo image --}}
                            <img src="{{ asset('assets/JDMLOGO.avif') }}" hight="50" alt="JDM Logo">
                        </a>
                    </div>
                    </div>

                    <div class="card-body">
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success mb-3">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email address') }}</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                    autocomplete="current-password"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember_me"
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="remember_me">
                                    {{ __('Remember me') }}
                                </label>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-primary">
                                    {{ __('Log in') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- <div class="card-footer text-center">
                        <small>
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}" class="text-primary">{{ __('Register') }}</a>
                        </small>
                    </div> --}}
                </div>
</x-guest-layout>
