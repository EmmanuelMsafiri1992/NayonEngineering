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
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; max-width: 900px; margin: 0 auto;">
                <!-- Login Form -->
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px;">
                    <h2 style="margin-bottom: 25px;">Login</h2>
                    <form action="#" method="POST">
                        @csrf
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email Address</label>
                            <input type="email" name="email" required style="width: 100%; padding: 12px 15px; background-color: var(--dark-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--white); font-size: 14px;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Password</label>
                            <input type="password" name="password" required style="width: 100%; padding: 12px 15px; background-color: var(--dark-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--white); font-size: 14px;">
                        </div>
                        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="remember">
                                <span style="font-size: 14px;">Remember me</span>
                            </label>
                            <a href="#" style="color: var(--primary-blue); font-size: 14px;">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                    </form>
                </div>

                <!-- Register Form -->
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px;">
                    <h2 style="margin-bottom: 25px;">Create Account</h2>
                    <form action="#" method="POST">
                        @csrf
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Full Name</label>
                            <input type="text" name="name" required style="width: 100%; padding: 12px 15px; background-color: var(--dark-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--white); font-size: 14px;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email Address</label>
                            <input type="email" name="email" required style="width: 100%; padding: 12px 15px; background-color: var(--dark-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--white); font-size: 14px;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Password</label>
                            <input type="password" name="password" required style="width: 100%; padding: 12px 15px; background-color: var(--dark-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--white); font-size: 14px;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Confirm Password</label>
                            <input type="password" name="password_confirmation" required style="width: 100%; padding: 12px 15px; background-color: var(--dark-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--white); font-size: 14px;">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
