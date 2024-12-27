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
            <h1 class="text-center text-white">Daftar Cicilan</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped text-white">
                <thead>
                    <tr>
                        <th>Pesanan</th>
                        <th>Cicilan Ke</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Bukti Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($installments as $installment)
                        <tr>
                            <td>{{ $installment->order->product->title ?? 'Pesanan Tidak Ditemukan' }}</td>
                            <td>{{ $installment->installment_number }}</td>
                            <td>Rp{{ number_format($installment->amount, 0, ',', '.') }}</td>
                            <td>
                                @if ($installment->status === 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning">Belum Dibayar</span>
                                @endif
                            </td>
                            <td>
                                @if ($installment->payment_proof)
                                    <a href="{{ asset('uploads/bukti_transfer/' . $installment->payment_proof) }}" target="_blank">
                                        <img src="{{ asset('uploads/bukti_transfer/' . $installment->payment_proof) }}" alt="Bukti Transfer" style="width: 100px; height: auto;">
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($installment->status === 'pending' && $installment->payment_proof)
                                    <!-- Tombol Setujui -->
                                    <form action="{{ route('approve_installment', $installment->installment_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-success btn-sm">Setujui</button>
                                    </form>
                                    <!-- Tombol Tolak -->
                                    <form action="{{ route('reject_installment', $installment->installment_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-danger btn-sm">Tolak</button>
                                    </form>
                                @elseif ($installment->status === 'paid')
                                    <span class="text-success">Lunas</span>
                                @else
                                    <span class="text-danger">Belum Dibayar</span>
                                @endif
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $installments->links() }}
            </div>
        </div>
    </div>
</body>
</html>
