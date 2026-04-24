@extends('admin.layouts.app')

@section('content')
<style>
    .card-custom { border-radius: 12px; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); background: #fff; padding: 20px; }
    table { border-radius: 10px; overflow: hidden; }
    thead { background: #343a40; color: #fff; }
</style>

<div class="container container-custom">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card card-custom">
        <h3 class="text-center mb-4">📂 Quản lý danh mục</h3>
        <a href="{{ url('admin/category/create') }}" class="mb-3 d-inline-block">
            <button class="btn btn-success">+ Thêm Danh Mục</button>
        </a>

        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th width="70px">STT</th>
                    <th>Tên danh mục</th>
                    <th width="80px">Sửa</th>
                    <th width="80px">Xoá</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td><a href="{{ url('admin/category/'.$item->id.'/edit') }}" class="btn btn-warning btn-sm">Sửa</a></td>
                    <td>
                        <form action="{{ url('admin/category/'.$item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá danh mục này không?');">
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
</div>
@endsection
