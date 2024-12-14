@extends('backend.layouts.master')

@section('main-content')
<div class="card">
    <h5 class="card-header">Add Product</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.store')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Enter title" value="{{old('title')}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{old('summary')}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Description</label>
          <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="is_featured">Is Featured</label><br>
          <input type="checkbox" name='is_featured' id='is_featured' value='1' checked> Yes                        
        </div>

        <div class="form-group">
          <label for="cat_id">Category <span class="text-danger">*</span></label>
          <select name="cat_id" id="cat_id" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->title}}</option>
              @endforeach
          </select>
          @error('cat_id')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group d-none" id="child_cat_div">
          <label for="child_cat_id">Sub Category</label>
          <select name="child_cat_id" id="child_cat_id" class="form-control">
              <option value="">--Select any sub category--</option>
          </select>
        </div>

        <div class="form-group">
                <label for="price" class="col-form-label">Price (Rp) <span class="text-danger">*</span></label>
                <input id="price" type="text" name="price" placeholder="Masukkan harga, contoh: 20000" value="{{old('price')}}" class="form-control format-rupiah">
                @error('price')
                <span class="text-danger">{{$message}}</span>
                @enderror
        </div>

        <div class="form-group">
          <label for="discount" class="col-form-label">Discount (%)</label>
          <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter discount" value="{{old('discount')}}" class="form-control">
          @error('discount')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>



        <div class="form-group">
          <label for="condition">Condition</label>
          <select name="condition" class="form-control">
              <option value="">--Select Condition--</option>
              <option value="default">Default</option>
              <option value="new">New</option>
              <option value="hot">Hot</option>
          </select>
        </div>

        <div class="form-group">
          <label for="stock">Stock <span class="text-danger">*</span></label>
          <input id="stock" type="number" name="stock" min="0" placeholder="Enter stock" value="{{old('stock')}}" class="form-control">
          @error('stock')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-secondary text-white">
                  <i class="fa fa-picture-o"></i> Choose
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="photo" value="{{old('photo')}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $('#lfm').filemanager('image');

// Inisialisasi Summernote untuk deskripsi singkat
$(document).ready(function() {
    $('#summary').summernote({
        placeholder: "Write short description.....",
        tabsize: 2,
        height: 100
    });
});

// Inisialisasi Summernote untuk deskripsi detail
$(document).ready(function() {
    $('#description').summernote({
        placeholder: "Write detailed description.....",
        tabsize: 2,
        height: 150
    });
});
// Fungsi untuk memformat angka menjadi Rupiah
function formatRupiah(angka, prefix = 'Rp') {
    let numberString = angka.replace(/[^,\d]/g, '').toString(),
        split = numberString.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix + rupiah;
}

// Event listener untuk input harga (untuk format Rupiah)
$(document).on('input', '#price', function() {
    let value = $(this).val();
    $(this).val(formatRupiah(value, 'Rp'));
});

// Fungsi untuk mengonversi harga dari format Rupiah ke angka
function convertToNumber(value) {
    return value.replace(/[^0-9]/g, ''); // Menghapus karakter selain angka
}

// Sebelum submit form, pastikan harga diubah menjadi angka
$('form').submit(function(event) {
    // Ambil nilai harga dari input
    let priceInput = $('#price').val();
    let numericPrice = convertToNumber(priceInput); // Konversi harga ke angka

    // Set harga numeric kembali ke input harga sebelum dikirim
    $('#price').val(numericPrice);
});



// AJAX untuk mendapatkan subkategori berdasarkan kategori yang dipilih
$('#cat_id').change(function() {
    let cat_id = $(this).val();
    if (cat_id != null) {
        $.ajax({
            url: "/admin/category/" + cat_id + "/child",
            data: {
                _token: "{{csrf_token()}}",
                id: cat_id
            },
            type: "POST",
            success: function(response) {
                if (typeof(response) != 'object') {
                    response = $.parseJSON(response);
                }
                let html_option = "<option value=''>----Select sub category----</option>";
                if (response.status) {
                    let data = response.data;
                    if (data) {
                        $('#child_cat_div').removeClass('d-none');
                        $.each(data, function(id, title) {
                            html_option += "<option value='" + id + "'>" + title + "</option>";
                        });
                    }
                } else {
                    $('#child_cat_div').addClass('d-none');
                }
                $('#child_cat_id').html(html_option);
            }
        });
    }
});

</script>
@endpush
