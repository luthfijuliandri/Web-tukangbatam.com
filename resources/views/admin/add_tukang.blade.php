<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')

    <style type="text/css">
        .div_deg
        {
            
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

        .form-check 
        {
            margin-bottom: 10px;
        }

    </style>


        
  </head>
  <body>

    @include('admin.header')

    @include('admin.sidebar')

    
    
      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">

            <h1>
                Add Tukang
            </h1>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

           

            <div class="div_deg">
            <form action="{{ route('add_tukang') }}" method="POST">
                @csrf
                <div>
                    <label>Nama Tukang:</label>
                    <input type="text" name="nama_tukang" required>
                </div>
                <div>
                    <label>Nomor HP Tukang:</label>
                    <input type="text" name="nomorhp_tukang" required>
                </div>
                <div>
                    <label>Status Tukang:</label>
                    <select name="status_tukang" required>
                        <option value="available">Available</option>
                        <option value="not available">Not Available</option>
                    </select>
                </div>
                <div>
                    <label>Keahlian Tukang:</label>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang Batu" class="form-check-input" id="keahlian1">
                        <label class="form-check-label" for="keahlian1">Tukang Batu</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang Sipil" class="form-check-input" id="keahlian2">
                        <label class="form-check-label" for="keahlian2">Tukang Sipil</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang Interior" class="form-check-input" id="keahlian3">
                        <label class="form-check-label" for="keahlian3">Tukang Interior</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang Listrik" class="form-check-input" id="keahlian4">
                        <label class="form-check-label" for="keahlian4">Tukang Listrik</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang AC" class="form-check-input" id="keahlian5">
                        <label class="form-check-label" for="keahlian5">Tukang AC</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang Atap" class="form-check-input" id="keahlian6">
                        <label class="form-check-label" for="keahlian6">Tukang Atap</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang Cat Dinding" class="form-check-input" id="keahlian7">
                        <label class="form-check-label" for="keahlian7">Tukang Cat Dinding</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang Kayu" class="form-check-input" id="keahlian8">
                        <label class="form-check-label" for="keahlian8">Tukang Kayu</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang Sanitasi" class="form-check-input" id="keahlian9">
                        <label class="form-check-label" for="keahlian9">Tukang Sanitasi</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="keahlian_tukang[]" value="Tukang Taman" class="form-check-input" id="keahlian10">
                        <label class="form-check-label" for="keahlian10">Tukang Taman</label>
                    </div>
                </div>

                <button class="btn btn-success" type="submit">Tambah Tukang</button>
            </form>

           
            <table class='table_deg' border="1">
                <thead>
                    <tr>
                        <th>ID Tukang</th>
                        <th>Nama Tukang</th>
                        <th>Nomor HP</th>
                        <th>Status</th>
                        <th>Keahlian</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tukangs as $tukang)
                        <tr>
                            <td>{{ $tukang->id_tukang }}</td>
                            <td>{{ $tukang->nama_tukang }}</td>
                            <td>{{ $tukang->nomorhp_tukang }}</td>
                            <td>{{ $tukang->status_tukang }}</td>
                            <td>
                                @foreach ($tukang->keahlian as $keahlian)
                                    <span>{{ $keahlian->keahlian }}</span><br>
                                @endforeach
                            </td>
                            <td>
                            <!-- Form untuk Update Status -->
                                <form action="{{ route('update_status_tukang', $tukang->id_tukang) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $tukang->status_tukang === 'available' ? 'btn-warning' : 'btn-success' }}">
                                        {{ $tukang->status_tukang === 'available' ? 'Set Not Available' : 'Set Available' }}
                                    </button>
                                </form>

                                <!-- Form untuk Delete -->
                                <form action="{{ route('delete_tukang', $tukang->id_tukang) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tukang ini?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Tidak ada data tukang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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