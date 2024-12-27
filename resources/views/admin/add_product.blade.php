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

            <h1>
                Add Product
            </h1>



          <div class="div_deg">
            <form action="{{url('upload_product')}}" method="Post" enctype="multipart/form-data">
                @csrf

                <div>
                    <label>Jasa</label>
                    <input type="text" name="jasa" required>
                </div>
                <div>
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" required></textarea>
                </div>
                <div>
                    <label>Estimasi Harga</label>
                    <input type="text" name="estimasi_harga" required>
                </div>
                <div>
                    <label>Image</label>
                    <input type="file" name="image">
                </div>
                <div>
                    <input class="btn btn-success" type="submit" value="Add Product">
                </div>
            </form>
          </div>

          <div>
          <table class="table_deg">
              <tr>
                  <th>Jasa</th>
                  <th>Deskripsi</th>
                  <th>Estimasi Harga</th> <!-- Kolom Estimasi Harga -->
                  <th>Image</th>

                  <th>Delete</th>
              </tr>

              @foreach ($product as $products)
                  <tr>
                      <td>{{ $products->title }}</td>
                      <td>{{ $products->Description }}</td>
                      <td>
                          <!-- Form untuk mengedit estimasi harga langsung di tabel -->
                          <form action="{{ url('update_estimasi_harga', $products->product_id) }}" method="POST">
                              @csrf
                              @method('PATCH')
                              <input type="text" name="estimasi_harga" value="{{ $products->estimasi_harga }}" class="form-control" onchange="this.form.submit()">
                          </form>
                      </td>
                      <td>
                          <img height="200px" width="200px" src="products/{{ $products->image }}">
                      </td>

                      <td>
                          <a class="btn btn-danger" href="{{ url('delete_product', $products->product_id) }}">Delete</a>
                      </td>
                  </tr>
              @endforeach
          </table>


          

        
         </div>

         <div class="page_deg">
         {{$product->links()}}
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