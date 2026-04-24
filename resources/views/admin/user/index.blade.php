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
        <h3 class="text-center mb-4">👥 Quản lý người dùng</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th width="70px">STT</th>
                        <th>Họ Tên</th>
                        <th>Tên Đăng Nhập</th>
                        <th>Số Điện Thoại</th>
                        <th>Email</th>
                        <th width="80px">Xoá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $item)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->username }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->email }}</td>
                        <td>
                            <form action="{{ url('admin/user/'.$item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá người dùng này không?');">
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
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
