@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Pengeluaran</h1>

    <form action="{{ route('expenditure.update', $expenditure) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="text" name="amount" class="form-control" value="{{ $expenditure->amount }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <input type="text" name="category" class="form-control" value="{{ $expenditure->category }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="note" class="form-control">{{ $expenditure->note }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="datetime-local" name="spent_at" class="form-control" value="{{ $expenditure->spent_at ? $expenditure->spent_at->format('Y-m-d\TH:i') : '' }}">
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('expenditure.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
