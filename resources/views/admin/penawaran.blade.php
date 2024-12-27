<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
</head>
<body>
    @include('admin.header')
    @include('admin.sidebar')

    <div class="page-content">
        <div class="container">
            <h1 class="text-center text-white">Penawaran Harga</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped text-white">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Item Penawaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->nama }}</td>
                            <td>{{ $order->lokasi }}</td>
                            <td>
                                <!-- Form Tambah Item -->
                                <form action="{{ route('add_penawaran_harga_item', $order->order_id) }}" method="POST" style="margin-bottom: 10px;">
                                    @csrf
                                    <input type="text" name="item" placeholder="Nama Item" required>
                                    <input type="number" name="price" placeholder="Harga" required>
                                    <button type="submit" class="btn btn-success btn-sm">Tambah</button>
                                </form>

                                <!-- Daftar Item -->
                                <ul>
                                    @foreach (json_decode($order->penawaran_harga, true) ?? [] as $index => $item)
                                        <li>
                                            <form action="{{ route('update_penawaran_harga_item', [$order->order_id, $index]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="item" value="{{ $item['item'] }}" required>
                                                <input type="number" name="price" value="{{ $item['price'] }}" required>
                                                <button type="submit" class="btn btn-sm btn-warning">Update</button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Status Persetujuan -->
                            <td>
                                @if ($order->status_penawaran === 'Penawaran Ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif ($order->status_penawaran === 'Penawaran Harga')
                                    <span class="badge bg-warning">Menunggu Persetujuan</span>
                                @elseif ($order->status_penawaran === 'Setuju')
                                    <span class="badge bg-success">Disetujui</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td>
                                @if ($order->status === 'Penawaran Ditolak')
                                    <span class="text-danger">Penawaran ditolak, silakan revisi.</span>
                                @else
                                    <form action="{{ route('send_penawaran_harga', $order->order_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Kirim Penawaran</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
