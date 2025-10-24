@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Pengeluaran</h1>

    <form action="{{ route('expenditure.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="text" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <input type="text" name="category" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="note" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="datetime-local" name="spent_at" class="form-control">
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('expenditure.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
