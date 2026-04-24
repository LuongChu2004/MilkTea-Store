@extends('admin.layouts.app')

@section('content')
<style>
    .card-custom { border-radius: 12px; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); background: #fff; padding: 20px; }
    .size-price-row { background: #f8f9fa; padding: 10px; border-radius: 8px; margin-bottom: 10px; }
</style>

<div class="container container-custom mt-4">
    <div class="card card-custom">
        <h3 class="text-center mb-4">{{ isset($product) ? '✏ Cập nhật Sản Phẩm' : '➕ Thêm Sản Phẩm' }}</h3>
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ isset($product) ? url('admin/product/'.$product->id) : url('admin/product') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Tên Sản Phẩm:</label>
                        <input required type="text" class="form-control" name="title" value="{{ old('title', $product->title ?? '') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Danh Mục:</label>
                        <select class="form-control" name="id_category" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (old('id_category', $product->id_category ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nội Dung Sản Phẩm:</label>
                        <textarea class="form-control" name="content" rows="5">{{ old('content', $product->content ?? '') }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Hình Ảnh:</label>
                        <input type="file" class="form-control" name="thumbnail" {{ isset($product) ? '' : 'required' }} accept="image/*">
                        @if(isset($product) && $product->thumbnail)
                            <div class="mt-2 text-center">
                                <img src="{{ asset('admin_assets/product/' . $product->thumbnail) }}" alt="Thumbnail" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="font-weight-bold mb-3">Kích Cỡ & Giá (Tối thiểu 1 mục)</h5>
            <div id="size-container">
                @if(isset($product) && $product->sizes->count() > 0)
                    @foreach($product->sizes as $index => $size)
                        <div class="row size-price-row align-items-center">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="sizes[]" value="{{ $size->size }}" placeholder="Tên Size (VD: S, M, L, Tiêu chuẩn)" required>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="number" class="form-control" name="prices[]" value="{{ $size->price }}" placeholder="Giá (VNĐ)" min="0" required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            <div class="col-md-3 text-end">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-size">Xóa</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row size-price-row align-items-center">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="sizes[]" placeholder="Tên Size (VD: S, M, L, Tiêu chuẩn)" required>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="number" class="form-control" name="prices[]" placeholder="Giá (VNĐ)" min="0" required>
                                <span class="input-group-text">VNĐ</span>
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" class="btn btn-danger btn-sm btn-remove-size">Xóa</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mb-4" id="btn-add-size">+ Thêm Kích Cỡ</button>

            <button type="submit" class="btn btn-success w-100 py-2 font-weight-bold">{{ isset($product) ? 'Cập Nhật Sản Phẩm' : 'Tạo Sản Phẩm Mới' }}</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('size-container');
        const btnAdd = document.getElementById('btn-add-size');

        btnAdd.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'row size-price-row align-items-center';
            row.innerHTML = `
                <div class="col-md-4">
                    <input type="text" class="form-control" name="sizes[]" placeholder="Tên Size (VD: S, M, L, Tiêu chuẩn)" required>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="number" class="form-control" name="prices[]" placeholder="Giá (VNĐ)" min="0" required>
                        <span class="input-group-text">VNĐ</span>
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-size">Xóa</button>
                </div>
            `;
            container.appendChild(row);
        });

        container.addEventListener('click', function(e) {
            if(e.target.classList.contains('btn-remove-size')) {
                const rows = container.querySelectorAll('.size-price-row');
                if(rows.length > 1) {
                    e.target.closest('.size-price-row').remove();
                } else {
                    alert('Sản phẩm phải có ít nhất 1 kích cỡ!');
                }
            }
        });
    });
</script>
@endsection
