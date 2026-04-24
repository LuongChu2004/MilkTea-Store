@extends('admin.layouts.app')

@section('content')
<style>
    .card-custom { border-radius: 12px; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); background: #fff; padding: 20px; }
</style>

<div class="container container-custom mt-5" style="max-width: 600px;">
    <div class="card card-custom">
        <h3 class="text-center mb-4">{{ isset($category) ? '✏ Cập nhật Danh Mục' : '➕ Thêm Danh Mục' }}</h3>
        
        <form method="POST" action="{{ isset($category) ? url('admin/category/'.$category->id) : url('admin/category') }}">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif

            <div class="form-group mb-3">
                <label for="name" class="font-weight-bold">Tên Danh Mục:</label>
                <input required type="text" class="form-control" id="name" name="name" 
                       value="{{ isset($category) ? $category->name : '' }}" placeholder="Nhập tên danh mục">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button class="btn btn-success w-100 mt-2">{{ isset($category) ? 'Lưu' : 'Thêm mới' }}</button>
        </form>
    </div>
</div>
@endsection
