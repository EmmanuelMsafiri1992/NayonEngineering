@extends('layouts.app')

@section('title', 'My Account')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>My Account</span>
            </div>
            <h1 class="page-title">My Account</h1>
        </div>
    </div>

    <!-- Account Section -->
    <section class="section">
        <div class="container">
            @if(session('success'))
                <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; max-width: 900px; margin: 0 auto 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; max-width: 900px; margin: 0 auto 20px;">
                    @foreach($errors->all() as $error)
                        <p style="margin: 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @auth
                <!-- Logged In User Dashboard -->
                <div style="background-color: #fff; border-radius: 8px; padding: 40px; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <h2 style="margin-bottom: 25px; color: #333;">Welcome, {{ Auth::user()->name }}!</h2>
                    <p style="color: #666; margin-bottom: 20px;">Email: {{ Auth::user()->email }}</p>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            @else
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; max-width: 900px; margin: 0 auto;">
                    <!-- Login Form -->
                    <div style="background-color: #fff; border-radius: 8px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <h2 style="margin-bottom: 25px; color: #333;">Login</h2>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 12px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; color: #333; font-size: 14px;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Password</label>
                                <input type="password" name="password" required style="width: 100%; padding: 12px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; color: #333; font-size: 14px;">
                            </div>
                            <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #666;">
                                    <input type="checkbox" name="remember">
                                    <span style="font-size: 14px;">Remember me</span>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div style="background-color: #fff; border-radius: 8px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <h2 style="margin-bottom: 25px; color: #333;">Create Account</h2>
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 12px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; color: #333; font-size: 14px;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 12px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; color: #333; font-size: 14px;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Password</label>
                                <input type="password" name="password" required style="width: 100%; padding: 12px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; color: #333; font-size: 14px;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Confirm Password</label>
                                <input type="password" name="password_confirmation" required style="width: 100%; padding: 12px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; color: #333; font-size: 14px;">
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </section>
@endsection
