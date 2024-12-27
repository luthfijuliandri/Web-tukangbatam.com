<!DOCTYPE html>
<html>
<head>
    @include('home.css')
</head>
<body>
    <div class="hero_area">
        @include('home.header')
    </div>

    <section class="payment_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h3>Proses Pembayaran</h3>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="shadow p-4 rounded">
                        <h5>Detail Pesanan</h5>
                        <p><strong>Nama:</strong> {{ $order->nama }}</p>
                        <p><strong>Jasa:</strong> {{ $order->product->title }}</p>
                        <p><strong>Harga:</strong> Rp{{ number_format($order->harga, 0, ',', '.') }}</p>
                        <p><strong>Lokasi:</strong> {{ $order->lokasi }}</p>
                        <p><strong>Tanggal Pemesanan:</strong> {{ $order->order_date }}</p>
                        <p><strong>Status:</strong> {{ $order->status }}</p>

                        <a href="#" class="btn btn-primary">Lanjutkan Pembayaran</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('home.footer')
</body>
</html>
