@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container">
    <h1 style="text-align: center; margin-bottom: 2rem; color: var(--primary);">Edit Profil</h1>
    
    @if(session('success'))
        <div class="alert alert-success" style="background: #d1fae5; color: #065f46; padding: 1rem 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card" style="max-width: 500px; margin: 0 auto;">
        <div class="card-body" style="padding: 2rem;">
            <form method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Foto Profil -->
                <div class="form-group" style="margin-bottom: 1.5rem; text-align: center;">
                    <label style="display: block; font-weight: 600; color: var(--text-dark); margin-bottom: 0.75rem;">Foto Profil</label>
                    <div style="margin-bottom: 1rem;">
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" 
                                 style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary);">
                        @else
                            <div style="width: 120px; height: 120px; border-radius: 50%; background: var(--gradient-gold); display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 2.5rem; font-weight: 700;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <input type="file" name="foto" class="form-control" accept="image/*" style="padding: 0.5rem;">
                    @error('foto')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>
                
                <!-- Nama -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; color: var(--text-dark); margin-bottom: 0.5rem;">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>
                
                <!-- Role & Nama Format -->
                <div class="form-group" style="margin-bottom: 1.5rem; text-align: center; padding: 1rem; background: #f9f6f0; border-radius: 0.5rem;">
                    <label style="display: block; font-weight: 600; color: var(--text-dark); margin-bottom: 0.5rem;">Format Profil</label>
                    <div style="font-size: 1.1rem; color: var(--primary); font-weight: 700;">
                        {{ ucwords(str_replace('_', ' ', $user->role)) }} / {{ $user->name }}
                    </div>
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <a href="javascript:history.back()" class="btn btn-secondary" style="flex: 1; text-align: center;">Kembali</a>
                    <button type="submit" class="btn btn-primary" style="flex: 1; background: var(--gradient-gold); border: none;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection