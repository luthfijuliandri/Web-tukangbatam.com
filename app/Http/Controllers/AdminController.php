<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Tukang;
use App\Models\KeahlianTukang;
use App\Models\Order;
use App\Models\Installment;

class AdminController extends Controller
{
 
    public function view_product()
    {
        $product = Product::paginate(4);
        return view('admin.add_product', compact('product'));
    }
    
    public function upload_product(Request $request)
    {
        $data = new Product;
        $data->title = $request->jasa;
        $data->Description = $request->deskripsi;
        $data->estimasi_harga = $request->estimasi_harga; // Menyimpan estimasi harga
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->move('products', $imagename);
            $data->image = $imagename;
        }
    
        $data->save();
        return redirect()->back()->with('success', 'Product added successfully!');
    }
    

    public function delete_product($product_id)
    {
        $data = Product::find($product_id);

        $data->delete();
        return redirect()->back();
    }

    public function view_order()
{
    $orders = Order::with('product', 'tukang') // Relasi ke product dan tukang
        ->paginate(10); // Pagination, 10 data per halaman

    $tukangs = Tukang::with('keahlian')->where('status_tukang', 'available')->get(); // Tukang yang available
    return view('admin.view_order', compact('orders', 'tukangs'));
}



    public function updateStatus(Request $request, $id)
    {
    $order = Order::findOrFail($id);

    $request->validate([
        'status' => 'required|in:in progress,Proses Survei Tukang,Penawaran Harga,Status Pembayaran,Proses Pengerjaan,Pembayaran Berhasil,Order Selesai',
    ]);

    $order->status = $request->status;
    $order->save();

    return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    public function add_tukang(Request $request)
{
    $request->validate([
        'nama_tukang' => 'required|string|max:255',
        'nomorhp_tukang' => 'required|string|max:20',
        'status_tukang' => 'required|in:available,not available',
        'keahlian_tukang' => 'required|array',
        'keahlian_tukang.*' => 'in:Tukang Batu,Tukang Sipil,Tukang Interior,Tukang Listrik,Tukang AC,Tukang Atap,Tukang Cat Dinding,Tukang Kayu,Tukang Sanitasi,Tukang Taman',
    ]);

    $tukang = Tukang::create([
        'nama_tukang' => $request->nama_tukang,
        'nomorhp_tukang' => $request->nomorhp_tukang,
        'status_tukang' => $request->status_tukang,
    ]);

    foreach ($request->keahlian_tukang as $keahlian) {
        KeahlianTukang::create([
            'tukang_id' => $tukang->id_tukang,
            'keahlian' => $keahlian,
        ]);
    }

    return redirect()->back()->with('success', 'Tukang berhasil ditambahkan.');
}

public function showTukangForm()
{
    $tukangs = Tukang::with('keahlian')->get(); // Memuat tukang beserta keahliannya
    return view('admin.add_tukang', compact('tukangs'));
}

public function delete_tukang($id)
{
    // Cari tukang berdasarkan ID
    $tukang = Tukang::findOrFail($id);

    // Hapus keahlian terkait
    $tukang->keahlian()->delete(); // Menghapus data relasi dari tabel keahlian_tukang

    // Hapus data tukang
    $tukang->delete();

    return redirect()->route('add_tukang_form')->with('success', 'Tukang berhasil dihapus.');
}

public function update_status_tukang(Request $request, $id)
{
    $tukang = Tukang::findOrFail($id);

    // Toggle status antara 'available' dan 'not available'
    $tukang->status_tukang = $tukang->status_tukang === 'available' ? 'not available' : 'available';
    $tukang->save();

    return redirect()->route('add_tukang_form')->with('success', 'Status tukang berhasil diperbarui.');
}

public function assignTukang(Request $request, $order_id)
{
    $request->validate([
        'tukang_id' => 'required|exists:tukang,id_tukang',
    ]);

    $tukang = Tukang::where('id_tukang', $request->tukang_id)->where('status_tukang', 'available')->first();
    if (!$tukang) {
        return redirect()->back()->with('error', 'Tukang yang dipilih tidak tersedia.');
    }

    $order = Order::findOrFail($order_id);
    $order->tukang_id = $tukang->id_tukang;
    $order->save();

    return redirect()->back()->with('success', 'Tukang berhasil diplot ke order.');
}

public function updateHarga(Request $request, $id)
{
    $request->validate([
        'harga' => 'required|numeric|min:0',
    ]);

    $order = Order::findOrFail($id);

    // Perbarui harga hanya jika status memenuhi syarat
    if (in_array($order->status, ['Status Pembayaran', 'Pembayaran Berhasil', 'Order Selesai'])) {
        $order->harga = $request->harga;
        $order->save();

        return redirect()->back()->with('success', 'Harga berhasil diperbarui.');
    }

    return redirect()->back()->with('error', 'Harga hanya dapat diubah pada status Menunggu Pembayaran, Pembayaran Berhasil, atau Order Selesai.');
}



public function updateEstimasiHarga(Request $request, $id)
{
    $product = Product::findOrFail($id);
    $product->estimasi_harga = $request->estimasi_harga; // Update estimasi harga
    $product->save();

    return redirect()->back()->with('success', 'Estimasi harga berhasil diperbarui!');
}
public function viewPenawaran()
{
    $orders = DB::table('orders')->where('status', 'Penawaran Harga')->paginate(10);
    return view('admin.penawaran', compact('orders'));
}

public function addPenawaranHargaItem(Request $request, $order_id)
{
    // Gunakan where untuk mencocokkan order_id
    $order = DB::table('orders')->where('order_id', $order_id)->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
    }

    $items = json_decode($order->penawaran_harga, true) ?? [];
    $items[] = [
        'item' => $request->item,
        'price' => (float) $request->price, // Pastikan tipe data price adalah float
    ];

    // Hitung ulang total harga
    $total = array_sum(array_column($items, 'price'));

    // Update kolom penawaran_harga dan penawaran_total
    DB::table('orders')->where('order_id', $order_id)->update([
        'penawaran_harga' => json_encode($items),
        'penawaran_total' => $total, // Update total harga
    ]);

    return redirect()->back()->with('success', 'Item berhasil ditambahkan ke penawaran harga.');
}




public function sendPenawaranHarga($order_id)
{
    $order = DB::table('orders')->where('order_id', $order_id)->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
    }

    // Pastikan nilai enum diberikan sebagai string
    DB::table('orders')->where('order_id', $order_id)->update([
        'status_penawaran' => 'Penawaran Harga', // Enum harus berupa string
    ]);

    return redirect()->back()->with('success', 'Penawaran harga berhasil dikirim ke customer.');
}


public function updatePenawaranHargaItem(Request $request, $order_id, $index)
{
    $order = DB::table('orders')->where('order_id', $order_id)->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
    }

    $items = json_decode($order->penawaran_harga, true) ?? [];

    // Validasi index
    if (!isset($items[$index])) {
        return redirect()->back()->with('error', 'Item penawaran tidak ditemukan.');
    }

    // Update item di index tertentu
    $items[$index]['item'] = $request->item;
    $items[$index]['price'] = (float) $request->price;

    // Hitung ulang total harga
    $total = array_sum(array_column($items, 'price'));

    // Update database
    DB::table('orders')->where('order_id', $order_id)->update([
        'penawaran_harga' => json_encode($items),
        'penawaran_total' => $total, // Update total harga
        'status_penawaran' => 'Penawaran Harga', // Enum harus berupa string
    ]);

    return redirect()->back()->with('success', 'Item penawaran berhasil diperbarui.');
}



/**
 * Tandai cicilan sebagai lunas.
 */


public function viewInstallments()
{
    $installments = Installment::with('order.product')->paginate(10);
    return view('admin.admin_installments', compact('installments'));
}

public function approveInstallment($installment_id)
{
    $installment = Installment::findOrFail($installment_id);

    if ($installment->status === 'paid') {
        return redirect()->back()->with('error', 'Cicilan ini sudah disetujui.');
    }

    if (!$installment->payment_proof) {
        return redirect()->back()->with('error', 'Bukti pembayaran tidak ditemukan.');
    }

    $installment->update(['status' => 'paid']);

    return redirect()->back()->with('success', 'Cicilan berhasil disetujui.');
}

public function rejectInstallment($installment_id)
{
    $installment = Installment::findOrFail($installment_id);

    if (!$installment->payment_proof) {
        return redirect()->back()->with('error', 'Bukti pembayaran tidak ditemukan.');
    }

    if ($installment->status === 'paid') {
        return redirect()->back()->with('error', 'Cicilan sudah disetujui, tidak bisa ditolak.');
    }

    // Hapus bukti pembayaran
    if (file_exists(public_path('uploads/bukti_transfer/' . $installment->payment_proof))) {
        unlink(public_path('uploads/bukti_transfer/' . $installment->payment_proof));
    }

    // Reset status dan hapus bukti pembayaran
    $installment->update([
        'status' => 'pending',
        'payment_proof' => null,
    ]);

    return redirect()->back()->with('success', 'Bukti pembayaran ditolak.');
}




}
