@extends('layouts.master')

@section('content')

<h3>Người dùng</h3>

<table class="table table-bordered">
    <tr>
        <th>Tên</th>
        <th>Email</th>
        <th>Role</th>
        <th></th>
    </tr>

    @foreach($users as $u)
    <tr>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>
            <form method="POST" action="{{ route('admin.users.update',$u->id) }}">
                @csrf @method('PUT')
                <select name="role" onchange="this.form.submit()">
                    <option {{ $u->role=='admin'?'selected':'' }}>admin</option>
                    <option {{ $u->role=='staff'?'selected':'' }}>staff</option>
                    <option {{ $u->role=='user'?'selected':'' }}>user</option>
                    <option {{ $u->role=='shipper'?'selected':'' }}>shipper</option>
                </select>
            </form>
        </td>
        <td>
            <form method="POST" action="{{ route('admin.users.destroy',$u->id) }}">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Xóa</button>
            </form>
        </td>
    </tr>
    @endforeach

</table>

@endsection