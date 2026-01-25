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
                <div class="account-dashboard">
                    <h2>Welcome, {{ Auth::user()->name }}!</h2>
                    <p class="user-email">Email: {{ Auth::user()->email }}</p>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            @else
                <div class="account-forms-grid">
                    <!-- Login Form -->
                    <div class="account-form-card">
                        <h2>Login</h2>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" required>
                            </div>
                            <div class="form-group remember-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="remember">
                                    <span>Remember me</span>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div class="account-form-card">
                        <h2>Create Account</h2>
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                        </form>
                    </div>
                </div>
            @endauth

            <style>
                .account-dashboard {
                    background-color: #fff;
                    border-radius: 8px;
                    padding: 40px;
                    max-width: 600px;
                    margin: 0 auto;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                .account-dashboard h2 {
                    margin-bottom: 25px;
                    color: #333;
                }
                .account-dashboard .user-email {
                    color: #666;
                    margin-bottom: 20px;
                }
                .account-forms-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 40px;
                    max-width: 900px;
                    margin: 0 auto;
                }
                .account-form-card {
                    background-color: #fff;
                    border-radius: 8px;
                    padding: 40px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                .account-form-card h2 {
                    margin-bottom: 25px;
                    color: #333;
                }
                .account-form-card .form-group {
                    margin-bottom: 20px;
                }
                .account-form-card label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: 500;
                    color: #333;
                }
                .account-form-card input[type="email"],
                .account-form-card input[type="password"],
                .account-form-card input[type="text"] {
                    width: 100%;
                    padding: 12px 15px;
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    border-radius: 4px;
                    color: #333;
                    font-size: 14px;
                    box-sizing: border-box;
                }
                .account-form-card .remember-group {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .account-form-card .checkbox-label {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    cursor: pointer;
                    color: #666;
                    margin-bottom: 0;
                }
                .account-form-card .checkbox-label span {
                    font-size: 14px;
                    font-weight: normal;
                }
                .account-form-card .btn-block {
                    width: 100%;
                }

                /* Mobile Responsive Styles */
                @media (max-width: 768px) {
                    .account-forms-grid {
                        grid-template-columns: 1fr;
                        gap: 20px;
                        padding: 0 15px;
                    }
                    .account-form-card {
                        padding: 25px 20px;
                    }
                    .account-form-card h2 {
                        font-size: 20px;
                        margin-bottom: 20px;
                    }
                    .account-dashboard {
                        margin: 0 15px;
                        padding: 25px 20px;
                    }
                }
            </style>
        </div>
    </section>
@endsection
