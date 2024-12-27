<!DOCTYPE html>
<html>

<head>
    @include('home.css')
</head>

<body>
    <div class="hero_area">
        <!-- header section -->
        @include('home.header')
    </div>

    <section class="order_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h3>
                    Isi detail pemesanan jasa {{$product->title}} anda!
                </h3>
            </div>

            <!-- Estimasi Harga -->
            <div class="estimasi-harga shadow p-3 mb-4 rounded">
                <h5>Estimasi Harga:</h5>
                <p>{{ $product->estimasi_harga ?? 'Estimasi harga belum tersedia' }}</p>
            </div>

            <div class="row">
                <div class="col">
                    <div class="ket">
                        <h5>Detail Kontak</h5>
                    </div>
                    <div class="fillform shadow p-3 mb-5 rounded">
                        <form action="{{ url('place_order/' . $product->product_id) }}" method="POST">
                            @csrf
                            <div>
                                <label>Nama</label>
                                <input type="text" name="nama" required>
                            </div>
                            <div>
                                <label>Lokasi Pemesanan</label>
                                <textarea name="lokasi" required></textarea>
                            </div>
                            <div>
                                <label>Nomor Handphone/Whatsapp</label>
                                <input type="text" name="no_handphone" required>
                            </div>
                            <div class="ket">
                                <h5>Informasi Pemesanan</h5>
                            </div>
                            <div>
                                <label>Tanggal Pemesanan</label>
                                <input type="date" name="order_date" required>
                            </div>
                            <div>
                                <label>Informasi Tambahan</label>
                                <textarea name="info_tambahan" required></textarea>
                            </div>
                            <div>
                                <input class="btn btn-warning" type="submit" value="Jadwalkan Sekarang">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('home.footer')
</body>

</html>
