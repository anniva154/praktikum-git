@extends('frontend.layouts.master')

@section('title', 'Checkout Page')

@section('main-content')
<div class="breadcrumbs">
    <div class="container">
        <ul class="bread-list">
            <li><a href="{{ route('home') }}">Home<i class="ti-arrow-right"></i></a></li>
            <li class="active"><a href="#">Checkout</a></li>
        </ul>
    </div>
</div>

<section class="checkout section">
    <div class="container">
        <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkout-form">
            @csrf
            <div class="checkout-wrapper p-4 border rounded shadow-sm">
                <h3 class="mb-4 font-weight-bold text-center">Checkout</h3>
                <div class="row">
                    <!-- Informasi Pengiriman -->
                    <div class="col-lg-7 col-12">
                        <div class="checkout-form">
                            <h4 class="font-weight-bold mb-4">Informasi Pengiriman</h4>
                            <div class="row">
                                <!-- Nama dan Email -->
                                <div class="form-group col-md-6 mb-3">
                                    <label for="name" class="font-weight-bold">Nama</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ auth()->user()->name }}" readonly>
                                </div>
                                <div class="form-group col-md-6 mb-3">
                                    <label for="email" class="font-weight-bold">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ auth()->user()->email }}" readonly>
                                </div>

                                <!-- Nomor Telepon -->
                                <div class="form-group col-md-12 mb-3">
                                    <label for="phone" class="font-weight-bold">Nomor Telepon</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        placeholder="Masukkan nomor telepon Anda" required>
                                </div>

                                <!-- Provinsi Asal dan Kota Asal -->
                                <div class="form-group col-md-6 mb-3">
                                    <label for="origin_province" class="font-weight-bold">Provinsi Asal</label>
                                    <input type="text" name="origin_province" id="origin_province" class="form-control"
                                        value="{{ $originProvince }}" readonly>
                                </div>
                                <div class="form-group col-md-6 mb-3">
                                    <label for="origin_city" class="font-weight-bold">Kota Asal</label>
                                    <input type="text" name="origin_city" id="origin_city" class="form-control"
                                        value="{{ $originCity }}" readonly>
                                </div>

                                <!-- Provinsi Tujuan -->
                                <div class="form-group col-md-6 mb-3">
                                    <label for="province" class="font-weight-bold">Provinsi Tujuan</label>
                                    <select id="province" name="province">
    @foreach($provinces as $province)
        <option value="{{ $province->province_id }}">{{ $province->province }}</option>
    @endforeach
</select>
                                </div>

                                <!-- Kota Tujuan -->
                                <div class="form-group col-md-6 mb-3">
                                    <label for="city" class="font-weight-bold">Kota Tujuan</label>
                                    <select id="city" name="city" class="form-control">
                                        <option value="">Pilih Kota</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->city_id }}">{{ $city->type }} {{ $city->city_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Catatan untuk Penjual -->
                                <div class="form-group col-md-12 mb-3">
                                    <label for="note" class="font-weight-bold">Catatan untuk Penjual</label>
                                    <textarea name="note" id="note" rows="3" class="form-control"
                                        placeholder="Tulis catatan jika diperlukan"></textarea>
                                </div>

                                <!-- Kurir dan Layanan Pengiriman -->
                                <div class="form-group col-md-12 mb-3">
                                    <label for="courier" class="font-weight-bold">Pilih Kurir</label>
                                    <select id="courier" name="courier" class="form-control" required>
                                        <option value="">Pilih Kurir</option>
                                        <option value="jne">JNE</option>
                                        <option value="tiki">TIKI</option>
                                        <option value="pos">POS</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 mb-3">
                                    <label for="courier-services" class="font-weight-bold">Pilih Layanan Pengiriman</label>
                                    <div id="courier-services" class="p-3 border rounded bg-light text-muted">
                                        Memuat layanan pengiriman...
                                    </div>
                                    <input type="hidden" name="courier_service_price" id="courier_service_price" value="">
                                    <input type="hidden" name="shipping_id" id="shipping_id" value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Pesanan -->
                    <div class="col-lg-5 col-12">
                        <div class="order-details">
                            <h4 class="font-weight-bold">Ringkasan Pesanan</h4>
                            <hr>
                            <ul class="list-group mb-3">
                                @foreach ($cartItems as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $item['title'] }} x {{ $item['quantity'] }}
                                        <span>Rp{{ number_format($item['total_price'], 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between align-items-center text-danger">
                                    Diskon Kupon
                                    <span>- Rp{{ number_format($discount_price ?? 0, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Ongkir
                                    <span>Rp <span id="shipping-cost">0</span></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    Total
                                    <span>Rp <span id="total-cost">{{ number_format($subtotal - $discount_price, 0, ',', '.') }}</span></span>
                                </li>
                            </ul>
                            <button type="submit" class="btn btn-primary w-100">Buat Pesanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
    .checkout-form .form-group {
    display: block;  /* Membuat elemen berada pada blok terpisah */
    margin-bottom: 20px;
}

.checkout-form label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;  /* Agar label berada di atas input */
}

.checkout-form select, .checkout-form input, .checkout-form textarea {
    width: 100%;  /* Membuat input dan select box memenuhi lebar kolom */
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.checkout-form textarea {
    height: 100px;
}

</style>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"data-client-key="{{ config('midtrans.client_key') }}"></script>
                
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Event saat provinsi dipilih
        $('#province').on('change', function () {
            let provinceId = $(this).val();
            $('#city').html('<option value="">Memuat...</option>').attr('disabled', true);

            if (provinceId) {
                $.ajax({
                    url: `/api/cities/${provinceId}`,
                    type: 'GET',
                    success: function (data) {
                        if (Array.isArray(data)) {
                            let options = '<option value="">Pilih Kota</option>';
                            data.forEach(function (city) {
                                options += `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
                            });
                            $('#city').html(options).removeAttr('disabled');
                        } else {
                            alert('Data kota tidak valid.');
                            $('#city').html('<option value="">Pilih Kota</option>').attr('disabled', true);
                        }
                    },
                    error: function () {
                        alert('Gagal memuat data kota.');
                        $('#city').html('<option value="">Pilih Kota</option>').attr('disabled', true);
                    }
                });
            } else {
                $('#city').html('<option value="">Pilih Kota</option>').attr('disabled', true);
            }
        });

        // Event saat kurir atau kota dipilih untuk menghitung ongkir
        $('#courier, #city').on('change', function () {
            const courier = $('#courier').val();
            const city = $('#city').val();
            const weight = 1000; // Berat default (1 kg)

            if (courier && city) {
                $.ajax({
                    url: '/calculate-shipping',
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { destination: city, courier: courier, weight: weight },
                    success: function (response) {
                        const services = response.results[0]?.costs || [];
                        const container = $('#courier-services');
                        container.empty();

                        if (services.length > 0) {
                            services.forEach(service => {
                                const cost = service.cost[0]?.value || 0;
                                const etd = service.cost[0]?.etd || 'N/A';
                                const description = service.service;

                                container.append(`
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="courier_service" value="${cost}" id="service-${description}">
                                        <label class="form-check-label" for="service-${description}">
                                            ${description} - Rp${cost.toLocaleString()} (ETD: ${etd} hari)
                                        </label>
                                    </div>
                                `);
                            });
                        } else {
                            container.append('<p>Opsi layanan pengiriman tidak tersedia.</p>');
                        }
                    },
                    error: function () {
                        alert('Gagal menghitung ongkos kirim. Silakan coba lagi.');
                    }
                });
            }
        });

        // Update total biaya setelah memilih layanan pengiriman
        $(document).on('change', 'input[name="courier_service"]', function () {
            const shippingCost = parseInt($(this).val()) || 0;
            const total = {{ $subtotal }} + shippingCost - ({{ $discount_price ?? 0 }});
            $('#total-cost').text(total.toLocaleString());
            $('#courier_service_price').val(shippingCost);
        });

        // Handle form submit untuk checkout
        $('#checkout-form').on('submit', function (e) {
            e.preventDefault();

            const totalCost = parseInt($('#total-cost').text().replace(/\./g, '')) || 0;
            const courierServicePrice = parseInt($('#courier_service_price').val()) || 0;

            $.ajax({
                url: "{{ route('checkout.snapToken') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    total_cost: totalCost,
                    courier_service_price: courierServicePrice,
                    cart_items: @json($cartItems),
                },
                success: function (response) {
                    if (response.snap_token) {
                        snap.pay(response.snap_token, {
                            onSuccess: function (result) {
                                alert('Pembayaran berhasil!');
                                storeResponse(result);
                            },
                            onPending: function (result) {
                                alert('Pembayaran pending!');
                                console.log(result);
                            },
                            onError: function (result) {
                                alert('Pembayaran gagal!');
                                console.log(result);
                            }
                        });
                    } else {
                        alert('Gagal mendapatkan Snap Token.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan.');
                }
            });

            function storeResponse(response) {
                $.ajax({
                    url: '/payment',
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        checkoutdata: JSON.stringify(response),
                        courier: $('#courier').val(),
                        courier_service: $('input[name="courier_service"]:checked').val(),
                    },
                    success: function (data) {
                        alert('Transaksi berhasil disimpan!');
                        window.location.href = '/checkout/success';
                    },
                    error: function (xhr) {
                        console.error('Gagal menyimpan transaksi:', xhr.responseJSON);
                    }
                });
            }
        });
    });
</script>


@endsection

