@extends('layouts.backend')

@section('title', 'Data Jurusan')

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h3 class="fw-bold text-dark">Data Jurusan</h3>
                    <p class="text-muted mb-3">Lihat data jurusan pada SIMLAB.</p>
                    <div
                        style="height: 4px; width: 150px; background: linear-gradient(to right, #007bff, #00d4ff); border-radius: 2px;">
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