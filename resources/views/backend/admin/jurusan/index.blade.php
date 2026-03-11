@extends('layouts.backend')

@section('title', 'Data Jurusan')

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Header Card Data Jurusan --}}
<div class="card mb-4" style="border-radius: 20px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center">
            <div class="me-3 bg-purple-subtle p-3 rounded-circle d-flex align-items-center justify-content-center"
                style="width: 55px; height: 55px; background-color: #f5f0ff;"> 
                {{-- Catatan: Jika bg-purple-subtle tidak muncul di CSS kamu, gunakan background-color: #f5f0ff --}}
                <i class="ti ti-school text-primary fs-7" style="color: #6610f2 !important;"></i>
            </div>
            
            <div>
                <h3 class="fw-bold text-dark mb-0"
                    style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                    Data Jurusan
                </h3>
                <p class="text-muted mb-0" style="font-size: 13px;">
                    Lihat data jurusan pada SIMLAB.
                </p>
            </div>
        </div>

        <div class="mt-3"
            style="height: 4px; width: 150px; background: linear-gradient(to right, #6610f2, #a06ee1); border-radius: 2px;">
        </div>
    </div>
</div>

            <div class="card " style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="text-muted border-bottom">
                                <tr>
                                    <th style="width: 80px;">No</th>
                                    <th>Nama Jurusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jurusan as $index => $item)
                                    <tr>
                                        <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                        <td>{{ $item->nama_jurusan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">
                                            Data jurusan belum tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection