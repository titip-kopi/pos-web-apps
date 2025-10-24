@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Pengeluaran</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('expenditure.create') }}" class="btn btn-primary mb-3">Tambah Pengeluaran</a>

    <table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenditures as $item)
            <tr>
                <td>{{ $item->spent_at ? $item->spent_at->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ $item->category }}</td>
                <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                <td>{{ $item->note }}</td>
                <td>
                    <a href="{{ route('expenditure.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('expenditure.destroy', $item) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengeluaran ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $expenditures->links() }}
</div>
@endsection
