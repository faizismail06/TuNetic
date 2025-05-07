<div class="form-group">
    <label>Nama Lengkap</label>
    <input type="text" name="name"
        class="form-control @error('name')is-invalid @enderror" placeholder="Nama Lengkap"
        value="{{ old('name', $user->name ?? '') }}">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label>Alamat Email</label>
    <input type="email" name="email"
        class="form-control @error('email')is-invalid @enderror" placeholder="Alamat Email"
        value="{{ old('email', $user->email ?? '') }}">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" placeholder="Password">
    <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
</div>

<div class="form-group">
    <label>Role Pengguna</label>
    @foreach ($roles as $role)
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="role[]" class="custom-control-input"
                id="{{ $role->name . $role->id }}" value="{{ strtolower($role->name) }}"
                @if(isset($user) && in_array($role->name, $user->roles->pluck('name')->toArray())) checked @endif>
            <label class="custom-control-label" for="{{ $role->name . $role->id }}">
                {{ strtoupper($role->name) }}
            </label>
        </div>
    @endforeach
</div>

<div class="form-group">
    <label>Verified</label>
    <div class="input-group">
        <input type="checkbox" name="verified" data-bootstrap-switch data-off-color="danger"
            data-on-color="success"
            @if(isset($user) && !empty($user->email_verified_at)) checked @endif>
    </div>
</div>
