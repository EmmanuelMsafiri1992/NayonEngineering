@extends('admin.layouts.app')

@section('title', 'View Message')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{ $message->subject }}</h3>
            <div>
                <a href="mailto:{{ $message->email }}" class="btn btn-primary">
                    <i class="fas fa-reply"></i> Reply
                </a>
                <a href="{{ route('admin.messages.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
                <div>
                    <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">From</label>
                    <p style="margin: 5px 0 0; font-weight: 500;">{{ $message->name }}</p>
                </div>
                <div>
                    <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Email</label>
                    <p style="margin: 5px 0 0;"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
                </div>
                <div>
                    <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Phone</label>
                    <p style="margin: 5px 0 0;">{{ $message->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Date</label>
                    <p style="margin: 5px 0 0;">{{ $message->created_at->format('M d, Y \a\t H:i') }}</p>
                </div>
            </div>

            <div>
                <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; margin-bottom: 10px; display: block;">Message</label>
                <div style="background: var(--light); padding: 20px; border-radius: 6px; line-height: 1.7;">
                    {!! nl2br(e($message->message)) !!}
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border); display: flex; gap: 10px;">
                <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-primary">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
                <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="Are you sure you want to delete this message?" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
