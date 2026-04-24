@extends('admin.layouts.app')

@section('content')
<style>
    .card-custom { border-radius: 12px; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); background: #fff; padding: 20px; }
    table { border-radius: 10px; overflow: hidden; }
    thead { background: #343a40; color: #fff; }
</style>

<div class="container-fluid container-custom">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card card-custom">
        <h3 class="text-center mb-4">📦 Quản lý Sản phẩm</h3>
        <a href="{{ url('admin/product/create') }}" class="mb-3 d-inline-block">
            <button class="btn btn-success">+ Thêm Sản Phẩm</button>
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th width="70px">STT</th>
                        <th>Ảnh</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Danh Mục</th>
                        <th>Kích cỡ & Giá</th>
                        <th width="80px">Sửa</th>
                        <th width="80px">Xoá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $item)
                    <tr>
                        <td>{{ $products->firstItem() + $index }}</td>
                        <td>
                            @if($item->thumbnail)
                                <img src="{{ asset('admin_assets/product/' . $item->thumbnail) }}" alt="{{ $item->title }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                            @else
                                <span class="text-muted">Không có ảnh</span>
                            @endif
                        </td>
                        <td class="text-start">{{ $item->title }}</td>
                        <td>{{ $item->category ? $item->category->name : 'N/A' }}</td>
                        <td class="text-start">
                            @foreach($item->sizes as $size)
                                <span class="badge bg-info text-dark mb-1">{{ $size->size }} - {{ number_format($size->price, 0, ',', '.') }}đ</span><br>
                            @endforeach
                        </td>
                        <td><a href="{{ url('admin/product/'.$item->id.'/edit') }}" class="btn btn-warning btn-sm">Sửa</a></td>
                        <td>
                            <form action="{{ url('admin/product/'.$item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xoá</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
