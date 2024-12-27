<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\Product;

use App\Models\Installment;

use App\Models\User;

use App\Models\Order;


use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view("admin.index");
    }

    public function home()
    {
        $product = Product::all();
        return view('home.index',compact('product'));
    }

    public function login_home()
    {
        $product = Product::all();
        return view('home.index',compact('product'));
    }

    public function order_details($product_id)
    {
        $product = Product::find($product_id);
        return view('home.order_details',compact('product'));
        
    }

    public function place_order(Request $request, $product_id)
    {
        // Pastikan produk ada
        $product = Product::find($product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Buat pesanan
        $order = new Order();
        $order->user_id = auth()->id();
        $order->product_id = $product->product_id;
        $order->nama = $request->input('nama');
        $order->lokasi = $request->input('lokasi');
        $order->no_handphone = $request->input('no_handphone');
        $order->order_date = $request->input('order_date');
        $order->info_tambahan = $request->input('info_tambahan');
        $order->status = 'in progress';

        // Simpan pesanan
        $order->save();

        // Redirect ke halaman status pesanan
        return redirect()->route('order_status')->with('success', 'Pesanan berhasil dibuat!');
    }


    public function order_status($status = 'in progress')
{
    $statuses = [
        'in progress' => 'Dalam Proses',
        'Proses Survei Tukang' => 'Proses Survei Tukang',
        'Penawaran Harga' => 'Penawaran Harga',
        'Status Pembayaran' => 'Status Pembayaran',
        'Proses Pengerjaan' => 'Proses Pengerjaan',
        'Pembayaran Berhasil' => 'Pembayaran Berhasil',
        'Order Selesai' => 'Order Selesai',
    ];

    // Validasi jika status tidak ada dalam daftar
    if (!array_key_exists($status, $statuses)) {
        abort(404, 'Status tidak ditemukan');
    }

    // Ambil pesanan berdasarkan status
    $orders = Order::with('product', 'tukang')
        ->where('user_id', auth()->id())
        ->where('status', $status)
        ->paginate(10);

    return view('home.order_status', compact('orders', 'statuses', 'status'));
}

public function approvePenawaran($order_id)
{
    // Cari order berdasarkan order_id
    $order = Order::where('order_id', $order_id)->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
    }

    // Update status pesanan
    $order->update([
        'status_penawaran' => 'Setuju',
    ]);

    // Panggil fungsi generateInstallments untuk membuat cicilan
    $this->generateInstallments($order_id);

    return redirect()->back()->with('success', 'Penawaran telah disetujui dan cicilan berhasil dibuat.');
}

public function rejectPenawaran($order_id)
{
    // Cari order berdasarkan order_id
    $order = DB::table('orders')->where('order_id', $order_id)->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
    }

    // Update status pesanan menjadi "Penawaran Harga" atau status lain yang sesuai
    DB::table('orders')->where('order_id', $order_id)->update([
        'status_penawaran' => 'Penawaran Harga',
    ]);

    return redirect()->back()->with('success', 'Anda telah menolak penawaran harga.');
}

public function generateInstallments($order_id) {
    $order = Order::findOrFail($order_id);

    // Hapus cicilan sebelumnya jika ada
    $order->installments()->delete();

    $totalAmount = $order->penawaran_total; // Ambil harga dari order
    $installmentCount = 4; // Jumlah cicilan
    $installmentAmount = $totalAmount / $installmentCount;

    for ($i = 1; $i <= $installmentCount; $i++) {
        Installment::create([
            'order_id' => $order->order_id,
            'installment_number' => $i,
            'amount' => $installmentAmount,
            'status' => 'pending',  
        ]);
    }

    return redirect()->back()->with('success', 'Cicilan berhasil dibuat.');
}

public function uploadBuktiTransfer(Request $request, $order_id, $installment_number)
{
    // Validasi file bukti transfer
    $request->validate([
        'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
    ]);

    // Cari order berdasarkan order_id
    $order = Order::where('order_id', $order_id)->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
    }

    // Simpan file bukti transfer
    $file = $request->file('bukti_transfer');
    $filename = 'bukti_transfer_' . $order_id . '_cicilan_' . $installment_number . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('uploads/bukti_transfer'), $filename);

    // Perbarui tabel installments hanya untuk bukti pembayaran
    DB::table('installments')
        ->where('order_id', $order_id)
        ->where('installment_number', $installment_number)
        ->update([
            'payment_proof' => $filename,
        ]);

    return redirect()->back()->with('success', 'Bukti transfer berhasil diunggah. Menunggu persetujuan admin.');
}


public function updateOrderStatus(Request $request, $order_id)
{
    $order = Order::findOrFail($order_id);

    // Update status pesanan
    $order->update(['status' => $request->status]);

    // Logika agar cicilan tetap tampil di Status Pembayaran
    if ($request->status === 'Proses Pengerjaan') {
        return redirect()->route('order_status', 'Status Pembayaran')
            ->with('success', 'Status diperbarui. Cicilan tetap tersedia di Status Pembayaran.');
    }

    return redirect()->route('order_status', $request->status)
        ->with('success', 'Status pesanan berhasil diperbarui.');
}


// HomeController.php
public function orderStatus($status)
{
    $statuses = [
        'Penawaran Harga' => 'Penawaran Harga',
        'Status Pembayaran' => 'Status Pembayaran',
        'Proses Pengerjaan' => 'Proses Pengerjaan',
        'Order Selesai' => 'Order Selesai',
    ];

    // Ambil data orders sesuai status
    $orders = Order::with(['installments', 'product'])
        ->where(function ($query) use ($status) {
            $query->where('status', $status);

            // Tetap tampilkan cicilan jika status Proses Pengerjaan
            if ($status === 'Status Pembayaran') {
                $query->orWhere('status', 'Proses Pengerjaan');
            }
        })
        ->get();

    return view('home.order_status', compact('statuses', 'status', 'orders'));
}





// Display the order status with payment installments

}
