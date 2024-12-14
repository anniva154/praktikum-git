@extends('backend.layouts.master')

@section('main-content')
<!-- Product Index -->
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Product Lists</h6>
        <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm float-right" title="Add Product">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if($products->count() > 0)
            <table class="table table-bordered table-hover" id="product-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->title }}</td>
                        <td>
                            {{ $product->cat_info->title ?? 'No Category' }}
                            <sub>{{ $product->sub_cat_info->title ?? '' }}</sub>
                        </td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>

                        <td>
                            @if($product->stock > 0)
                            <span class="badge badge-primary">{{ $product->stock }}</span>
                            @else
                            <span class="badge badge-danger">Out of Stock</span>
                            @endif
                        </td>
                        <td>
                            @php
                            $photo = explode(',', $product->photo);
                            @endphp
                            <img src="{{ $photo[0] ?? asset('backend/img/thumbnail-default.jpg') }}" class="img-fluid zoom" style="max-width:80px" alt="Product Image">
                        </td>
                        <td>
                            @if($product->status == 'active')
                            <span class="badge badge-success">{{ ucfirst($product->status) }}</span>
                            @else
                            <span class="badge badge-warning">{{ ucfirst($product->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px; border-radius:50%" data-toggle="tooltip" title="Edit" data-placement="bottom">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="d-inline-block delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete-btn" style="height:30px; width:30px; border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="float-right">
                {{ $products->links() }}
            </div>
            @else
            <h6 class="text-center">No Products Found! Please Add Product.</h6>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<style>
    .zoom {
        transition: transform 0.2s;
    }

    .zoom:hover {
        transform: scale(2.5);
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        $('#product-dataTable').DataTable({
            columnDefs: [{
                orderable: false,
                targets: [5, 6, 7]
            }]
        });

        // SweetAlert for Delete Confirmation
        $('.delete-form').on('submit', function (e) {
            e.preventDefault();
            let form = this;
            swal({
                title: "Are you sure?",
                text: "Once deleted, this data cannot be recovered!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
