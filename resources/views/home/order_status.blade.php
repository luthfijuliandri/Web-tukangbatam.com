<!DOCTYPE html>
<html>

<head>
    @include('home.css')

    <style type="text/css">
        .info-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }

        .info-box h4 {
            color: #333;
            font-weight: bold;
        }

        .info-box ul {
            list-style-type: disc;
            padding-left: 20px;
            margin-top: 10px;
        }

        .info-box ul li {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .status-navigation {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .status-navigation .btn {
            padding: 10px 20px;
            font-size: 14px;
            white-space: nowrap;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .cicilan-status {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="hero_area">
        @include('home.header')
    </div>

    <section class="status_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h3>Status Pesanan Anda</h3>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tombol Navigasi Status -->
            <div class="status-navigation mb-4">
                @foreach ($statuses as $key => $value)
                    <a href="{{ route('order_status', $key) }}"
                       class="btn {{ $status == $key ? 'btn-warning' : 'btn-outline-warning' }}">
                        {{ $value }}
                    </a>
                @endforeach
            </div>

            <!-- Tabel Pesanan -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Jasa</th>
                            <th>Nama</th>
                            <th>Lokasi</th>
                            <th>Tanggal Pemesanan</th>
                            @if ($status === 'Penawaran Harga')
                                <th>Detail Penawaran</th>
                                <th>Aksi</th>
                            @elseif ($status === 'Status Pembayaran')
                                <th>Harga</th>
                                <th>Detail Cicilan</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->product->title }}</td>
                                <td>{{ $order->nama }}</td>
                                <td>{{ $order->lokasi }}</td>
                                <td>{{ $order->order_date }}</td>

                                @if ($status === 'Penawaran Harga')
                                    <td>
                                        @if ($order->penawaran_harga)
                                            <ul>
                                                @foreach (json_decode($order->penawaran_harga, true) as $item)
                                                    <li>{{ $item['item'] }} - Rp{{ number_format($item['price'], 0, ',', '.') }}</li>
                                                @endforeach
                                            </ul>
                                            <strong>Total:</strong> Rp{{ number_format($order->penawaran_total, 0, ',', '.') }}
                                        @else
                                            <em>Penawaran belum tersedia.</em>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('accept_penawaran', $order->order_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success">Setuju</button>
                                        </form>
                                        <form action="{{ route('reject_penawaran', $order->order_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger">Tolak</button>
                                        </form>
                                    </td>
                                @elseif ($status === 'Status Pembayaran')
                                    <td>
                                        Rp{{ number_format($order->penawaran_total, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <!-- Detail Cicilan -->
                                        @php
                                            $installments = $order->installments;
                                            $totalInstallments = 4;
                                            $installmentAmount = $order->penawaran_total / $totalInstallments;
                                        @endphp

                                        <ul>
                                            @for ($i = 1; $i <= $totalInstallments; $i++)
                                                @php
                                                    $installment = $installments->where('installment_number', $i)->first();
                                                @endphp
                                                <li>
                                                    Cicilan {{ $i }}: Rp{{ number_format($installmentAmount, 0, ',', '.') }}
                                                    @if ($installment && $installment->status === 'paid')
                                                        - <span class="text-success">Lunas</span>
                                                    @else
                                                        - <span class="text-danger">Belum Dibayar</span>
                                                        <form action="{{ route('upload_bukti_transfer', [$order->order_id, $i]) }}" method="POST" enctype="multipart/form-data" style="display:inline;">
                                                            @csrf
                                                            <input type="file" name="bukti_transfer" accept="image/*" required>
                                                            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                                        </form>
                                                    @endif
                                                </li>
                                            @endfor
                                        </ul>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    Tidak ada pesanan pada status ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Penjelasan dan Instruksi -->
            <div class="info-box mt-5">
                <h4 class="text-center mb-4">Informasi dan Instruksi</h4>
                <p>
                    Berikut adalah detail status pesanan Anda:
                </p>
                <ul>
                    <li><strong>In Progress:</strong> Pesanan Anda sedang diproses oleh tim kami.</li>
                    <li><strong>Proses Survei Tukang:</strong> Tim tukang akan melakukan survei untuk menentukan kebutuhan proyek Anda.</li>
                    <li><strong>Penawaran Harga:</strong> Admin telah menawarkan rincian harga untuk proyek Anda. Anda dapat menyetujui atau menolak penawaran tersebut.</li>
                    <li><strong>Proses Pengerjaan:</strong> Tukang telah mulai pengerjaan di lokasi Anda.</li>
                    <li><strong>Status Pembayaran:</strong> Silakan unggah bukti pembayaran untuk setiap cicilan.</li>
                    <li><strong>Pembayaran Berhasil:</strong> Kami telah menerima pembayaran Anda.</li>
                    <li><strong>Order Selesai:</strong> Pesanan Anda telah selesai diproses. Terima kasih telah menggunakan layanan kami!</li>
                </ul>
                <p>
                    Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami melalui email atau nomor telepon yang tertera di halaman ini.
                </p>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </section>

    @include('home.footer')
</body>

</html>
