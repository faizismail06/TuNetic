@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <h4 class="mb-3 text-uppercase">Daftar Pengguna</h4>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-outline card-success">
                <div class="card-body table-responsive">
                    <div class="card-body table-responsive">
                    {{-- Tombol Tambah User --}}
                    <div class="mb-3 text-end">
                        <a href="{{ route('manage-user.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah User
                        </a>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Level</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                    <td class="text-center">{{ $user->level }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('manage-user.edit', $user->id) }}">
                                                        <i class="fas fa-edit text-warning me-1"></i> Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button"
                                                        class="dropdown-item text-danger btn-show-confirm"
                                                        data-user-name="{{ $user->name }}"
                                                        data-form-id="delete-form-{{ $user->id }}">
                                                        <i class="fas fa-trash me-1"></i> Hapus
                                                    </button>

                                                    <form id="delete-form-{{ $user->id }}"
                                                        action="{{ route('manage-user.destroy', $user->id) }}"
                                                        method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($users->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada pengguna</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS --}}
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p id="modal-text">Apakah Anda yakin ingin menghapus pengguna ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="btn-confirm-delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script>
        let formToSubmit = null;

        document.querySelectorAll('.btn-show-confirm').forEach(button => {
            button.addEventListener('click', function () {
                const userName = this.getAttribute('data-user-name');
                const formId = this.getAttribute('data-form-id');
                formToSubmit = document.getElementById(formId);
                document.getElementById('modal-text').innerText =
                    `Apakah Anda yakin ingin menghapus ${userName}?`;

                const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                modal.show();
            });
        });

        document.getElementById('btn-confirm-delete').addEventListener('click', function () {
            if (formToSubmit) formToSubmit.submit();
        });
    </script>
@endpush
