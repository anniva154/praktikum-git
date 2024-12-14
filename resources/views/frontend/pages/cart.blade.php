@php
    use App\Http\Helpers\Helper;
@endphp
@extends('frontend.layouts.master')
@section('title', 'Cart Page')
@section('main-content')

<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li><a href="{{ route('home') }}">Home<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="#">Cart</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Shopping Cart -->
<div class="shopping-cart section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('cart.update') }}" method="POST" id="cart_form">
                    @csrf
                    <table class="table shopping-summery">
                        <thead>
                            <tr class="main-hading">
                                <th><input type="checkbox" id="select_all"></th>
                                <th>PRODUCT</th>
                                <th>NAME</th>
                                <th class="text-center">UNIT PRICE</th>
                                <th class="text-center">QUANTITY</th>
                                <th class="text-center">TOTAL</th>
                                <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($cartItems = Helper::getAllProductFromCart())
                                @foreach($cartItems as $key => $cart)
                                @php
                                    $photo = explode(',', $cart->product['photo']); // Jika ada foto dalam format string dipisahkan koma
                                    $unit_price = $cart->price; // Ambil langsung dari kolom price
                                    $total_price = $unit_price * $cart->quantity; // Hitung total harga berdasarkan kuantitas
                                @endphp

                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_products[]" value="{{ $cart->id }}"
                                                class="select_product">
                                        </td>
                                        <td class="image"><img src="{{ $photo[0] }}" alt="{{ $cart->product['title'] }}"></td>
                                        <td class="product-des">
                                            <p class="product-name"><a
                                                    href="{{ route('product-detail', $cart->product['slug']) }}">{{ $cart->product['title'] }}</a>
                                            </p>
                                            <p class="product-des">{{ $cart['summary'] }}</p>
                                        </td>
                                        <td class="price"><span>Rp{{ number_format($unit_price, 0, ',', '.') }}</span></td>
                                        <td class="qty">
                                            <div class="input-group">
                                                <div class="button minus">
                                                    <button type="button" class="btn btn-primary btn-number"
                                                            data-type="minus"><i class="ti-minus"></i></button>
                                                </div>
                                                <input type="text" name="quant[{{ $key }}]" class="input-number" data-min="1"
                                                       value="{{ $cart->quantity }}">
                                                <div class="button plus">
                                                    <button type="button" class="btn btn-primary btn-number" data-type="plus"><i
                                                            class="ti-plus"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="total-amount">
                                            <span class="cart_single_price">Rp{{ number_format($total_price, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="action">
                                            <a href="{{ route('cart-delete', $cart->id) }}"><i class="ti-trash remove-icon"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 offset-lg-8">
                <div class="order-summary card shadow-sm">
                    <div class="card-body">
                        <h4 class="text-center mb-4">Order Summary</h4>
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Cart Subtotal
                                <span id="subtotal_price">Rp 0</span>
                            </li>

                            @if(session()->has('coupon'))
                                <li class="list-group-item d-flex justify-content-between align-items-center text-success">
                                    You Save
                                    <span id="discount_price">- Rp.
                                        {{ number_format(Session::get('coupon')['value'], 0, ',', '.') }}</span>
                                </li>
                            @endif

                            @php
                                $total_amount = Helper::totalCartPrice();
                                if (session()->has('coupon')) {
                                    $total_amount -= Session::get('coupon')['value'];
                                }
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                You Pay
                                <span id="you_pay_price">Rp 0</span>
                            </li>
                        </ul>
                        <div class="coupon d-flex gap-2">
                            <form action="{{ route('coupon-store') }}" method="POST" style="display: flex; align-items: center;">
                                @csrf
                                <input type="text" name="code" class="form-control" placeholder="Enter Valid Coupon"
                                       style="margin-right: 15px;">
                                <button type="submit" class="btn-custom-succ">Apply</button>
                            </form>
                        </div>
                        <br>
                        <form action="{{ route('checkout.index') }}" method="GET" id="checkout_form">
    @csrf
    <input type="hidden" name="coupon_code" value="{{ session('coupon')['code'] ?? '' }}">
    <input type="hidden" name="discount_price" id="discount_price" value="{{ session('coupon')['value'] ?? 0 }}">
    <input type="hidden" name="selected_products" id="selected_products">
    <button type="submit" class="btn btn-outline-secondary ms-2" id="checkout_btn">Checkout</button>
</form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Shopping Cart -->

@endsection
@push('styles')
    <style>
        .order-summary {
            border: 1px solid #ddd;

            background-color: #fff;
            padding: 20px;
        }

        .order-summary h4 {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .order-summary ul li {
            font-size: 16px;
        }

        .order-summary .coupon {
            margin-top: 15px;
        }

        .btn-custom-succ {
            background-color: #dc3545;
            /* Merah */
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-custom-succ:hover {
            background-color: #c82333;
            /* Merah lebih gelap saat hover */
        }


        li.shipping {
            display: inline-flex;
            width: 100%;
            font-size: 14px;
        }

        li.shipping .input-group-icon {
            width: 100%;
            margin-left: 10px;
        }

        .input-group-icon .icon {
            position: absolute;
            left: 20px;
            top: 0;
            line-height: 40px;
            z-index: 3;
        }

        .form-select {
            height: 30px;
            width: 100%;
        }

        .form-select .nice-select {
            border: none;
            border-radius: 0px;
            height: 40px;
            background: #f6f6f6 !important;
            padding-left: 45px;
            padding-right: 40px;
            width: 100%;
        }

        .list li {
            margin-bottom: 0 !important;
        }

        .list li:hover {
            background: #F7941D !important;
            color: white !important;
        }

        .form-select .nice-select::after {
            top: 14px;
        }
    </style>
@endpush

@push('scripts')
<script src="{{ asset('frontend/js/nice-select/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function () {
        // Inisialisasi Select2 dan Nice Select
        $("select.select2").select2();
        $('select.nice-select').niceSelect();

        // Update total harga saat shipping berubah
        $('.shipping select[name=shipping]').change(function () {
            let cost = parseFloat($(this).find('option:selected').data('price')) || 0;
            let subtotal = parseFloat($('.order_subtotal').data('price'));
            let coupon = parseFloat($('.coupon_price').data('price')) || 0;
            $('#order_total_price span').text('$' + (subtotal + cost - coupon).toFixed(2));
        });

        // Saat tombol checkout diklik
        $('#checkout_form').on('submit', function (e) {
            let selectedProducts = [];
            $('.select_product:checked').each(function () {
                let row = $(this).closest('tr');
                selectedProducts.push({
                    id: $(this).val(),
                    quantity: parseInt(row.find('.input-number').val()),
                    total_price: parseFloat(row.find('.cart_single_price').text().replace(/[^0-9]/g, ''))
                });
            });

            // Masukkan data produk yang dipilih ke input hidden
            $('#selected_products').val(JSON.stringify(selectedProducts));

            if (selectedProducts.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu produk untuk melanjutkan ke Checkout.');
            }
        });

        // Format angka menjadi Rupiah
        function formatRupiah(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
        }

        // Update subtotal dan total pembayaran
        function updateTotals() {
            let subtotal = 0;

            $('.select_product:checked').each(function () {
                let row = $(this).closest('tr');
                let totalPrice = parseFloat(row.find('.cart_single_price').text().replace(/[^0-9]/g, '')) || 0;
                subtotal += totalPrice;
            });

            let discount = parseFloat($('#discount_price').text().replace(/[^0-9]/g, '')) || 0;
            let totalPay = Math.max(0, subtotal - discount);

            $('#subtotal_price').text(formatRupiah(subtotal));
            $('#you_pay_price').text(formatRupiah(totalPay));
            $('#checkout_btn').prop('disabled', totalPay === 0);
        }

        // Terapkan kupon
        $('.btn-apply-coupon').on('click', function () {
            let couponCode = $('input[name="code"]').val().trim();
            if (!couponCode) {
                alert('Please enter a coupon code');
                return;
            }

            $.ajax({
                url: '{{ route("coupon-store") }}',
                type: 'POST',
                data: {
                    code: couponCode,
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.success) {
                        $('#discount_price').text(formatRupiah(response.discount));
                        updateTotals();
                        alert('Coupon applied successfully!');
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert('Failed to apply coupon. Try again.');
                }
            });
        });

        // Checkbox untuk select all
        $('#select_all').on('change', function () {
            let isChecked = $(this).prop('checked');
            $('.select_product').prop('checked', isChecked);
            updateTotals();
        });

        // Checkbox produk
        $('.select_product').on('change', function () {
            if (!$(this).prop('checked')) {
                $('#select_all').prop('checked', false);
            } else if ($('.select_product:checked').length === $('.select_product').length) {
                $('#select_all').prop('checked', true);
            }
            updateTotals();
        });

        // Update jumlah produk saat input jumlah berubah
        $(document).on('input', '.input-number', function () {
            let row = $(this).closest('tr');
            updateRowTotal(row);

            if (row.find('.select_product').prop('checked')) {
                updateTotals();
            }
        });

        // Tombol plus/minus
        $(document).on('click', '.btn-number', function () {
            let row = $(this).closest('tr');
            let input = row.find('.input-number');
            let currentValue = parseInt(input.val()) || 0;
            let type = $(this).data('type');
            let maxValue = parseInt(input.data('max')) || Infinity;
            let minValue = parseInt(input.data('min')) || 1;

            if (type === 'plus' && currentValue < maxValue) {
                currentValue++;
            } else if (type === 'minus' && currentValue > minValue) {
                currentValue--;
            }

            input.val(currentValue);
            updateRowTotal(row);

            if (row.find('.select_product').prop('checked')) {
                updateTotals();
            }
        });

        // Calculate and format row total price
function updateRowTotal(row) {
    let unitPrice = parseFloat(row.find('.price span').text().replace(/[^0-9]/g, '')) || 0;
    let quantity = parseInt(row.find('.input-number').val()) || 0;
    let totalPrice = unitPrice * quantity;
    row.find('.cart_single_price').text(formatRupiah(totalPrice));
}

// Format numbers as Rupiah
function formatRupiah(value) {
    return 'Rp ' + value.toLocaleString('id-ID');
}


        // Update subtotal awal
        updateTotals();
    });
</script>
@endpush
