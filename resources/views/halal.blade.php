@extends('layouts.app')

@section('content')
<main>
    <div class="container mt-5 text-center" style="min-height: 50vh; padding-top: 10vh;">
        <h1 class="display-4 font-weight-bold" style="color: #003366;">HALAL CERTIFICATION</h1>
        <p class="lead mt-4">Thông tin về chứng nhận Halal của chúng tôi đang được cập nhật.</p>
        <p>Vui lòng quay lại sau!</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Về Trang Chủ</a>
    </div>
</main>
@endsection
