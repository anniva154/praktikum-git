@extends('layouts.backend')

@section('title', 'Data Jurusan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            <!-- HEADER -->
            <div class="card-header">
                <h5 class="mb-0">Data Jurusan</h5>
            </div>

            <!-- TABLE -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="text-muted border-bottom">
                            <tr>
                                <th>No</th>
                                <th>Nama Jurusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jurusan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_jurusan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">
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
@endsection
