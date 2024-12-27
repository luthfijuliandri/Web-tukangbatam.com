<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')

    <style type="text/css">
        .div_deg
        {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 60px;
        }

        .page_deg
        {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 60px;
        }

        h1
        {
            color: white;
        }

        label
        {
            display: inline-block;
            width: 250px;
            font-size: 15px!important;
            color: white!important;

        }

        input[type='text']
        {
            width: 350px;
            height: 50px;
        }

        textarea
        {
            width: 450px;
            height: 80px;
        }

        .table_deg
        {
          text-align: center;
          margin: auto;
          border: 2px solid yellowgreen;
          margin-top: 50px;
        }

        th
        {
          background-color: skyblue ;
          padding: 15px;
          font-size: 20px;
          font-weight: bold;
          color: white;

        }

        td
        {
          color: white;
          padding: 10px;
          border: 1px solid skyblue;
        }
    </style>
  </head>
  <body>

    @include('admin.header')

    @include('admin.sidebar')

    
    
      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">

           

          <div class="container">
            <!-- Tabel 1: Informasi Pesanan -->
            <h3 class="text-center text-white">Informasi Pesanan</h3>
            <table class="table_deg">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jasa</th>
                        <th>Lokasi</th>
                        <th>Nomor Handphone</th>
                        <th>Informasi Tambahan</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->nama }}</td>
                            <td>{{ $order->product->title }}</td>
                            <td>{{ $order->lokasi }}</td>
                            <td>{{ $order->no_handphone }}</td>
                            <td>{{ $order->info_tambahan }}</td>
                            <td>{{ $order->order_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Tabel 2: Status, Harga, dan Plot Tukang -->
            <h3 class="text-center text-white mt-5">Status, Harga, dan Plot Tukang</h3>
            <table class="table_deg">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Harga</th>
                        <th>Plot Tukang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->nama }}</td>
                            <td>
                                <form action="{{ route('orders.updateStatus', $order->order_id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="form-control">
                                        <option value="in progress" {{ $order->status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Proses Survei Tukang" {{ $order->status == 'Proses Survei Tukang' ? 'selected' : '' }}>Proses Survei Tukang</option>
                                        <option value="Penawaran Harga" {{ $order->status == 'Penawaran Harga' ? 'selected' : '' }}>Penawaran Harga</option>
                                        <option value="Status Pembayaran" {{ $order->status == 'Status Pembayaran' ? 'selected' : '' }}>Status Pembayaran</option>
                                        <option value="Proses Pengerjaan" {{ $order->status == 'Proses Pengerjaan' ? 'selected' : '' }}>Proses Pengerjaan</option>
                                        
                                        <option value="Pembayaran Berhasil" {{ $order->status == 'Pembayaran Berhasil' ? 'selected' : '' }}>Pembayaran Berhasil</option>
                                        <option value="Order Selesai" {{ $order->status == 'Order Selesai' ? 'selected' : '' }}>Order Selesai</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                @if (in_array($order->status, ['Status Pembayaran', 'Pembayaran Berhasil', 'Order Selesai']))
                                    <form action="{{ route('orders.updateHarga', $order->order_id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" step="0.01" name="harga" value="{{ $order->harga }}"
                                            class="form-control" onchange="this.form.submit()">
                                    </form>
                                @else
                                    {{ $order->harga ?? '-' }}
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('orders.assignTukang', $order->order_id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="tukang_id" onchange="this.form.submit()" class="form-control">
                                        <option value="" disabled selected>Pilih Tukang</option>
                                        @foreach ($tukangs as $tukang)
                                            <option value="{{ $tukang->id_tukang }}"
                                                {{ $order->tukang_id == $tukang->id_tukang ? 'selected' : '' }}>
                                                {{ $tukang->nama_tukang }} - 
                                                {{ $tukang->keahlian->pluck('keahlian')->implode(', ') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $orders->links() }}
            </div>
        </div>



         

        


          </div>  
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="{{asset('/admincss/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('/admincss/vendor/popper.js/umd/popper.min.js')}}"> </script>
    <script src="{{asset('/admincss/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('/admincss/vendor/jquery.cookie/jquery.cookie.js')}}"> </script>
    <script src="{{asset('/admincss/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('/admincss/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('/admincss/js/charts-home.js')}}"></script>
    <script src="{{asset('/admincss/js/front.js')}}"></script>
  </body>
</html>