@extends('admin.layouts.master')

@section('content')
<div class="container">
    <h2>⚠️ Popup Notification Settings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.popup.update') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label>Popup Title</label>
            <input type="text" name="title" value="{{ $popup->title ?? 'Important Notice' }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Popup Content</label>
            <textarea name="content" class="form-control" rows="10" required>{{ $popup->content ?? '' }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" value="1" {{ isset($popup->is_active) && $popup->is_active ? 'checked' : '' }}>
            <label>Enable Popup</label>
        </div>

        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
    </form>
</div>
@endsection
