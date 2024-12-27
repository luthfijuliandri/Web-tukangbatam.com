<section class="shop_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Jasa Yang Kami Tawarkan!
        </h2>
      </div>
      <div class="row">

        @foreach($product as $products)

      <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="box">
              <div class="title">
                <h6>
                {{$products->title}}
                </h6>
              </div>
              <div class="img-box">
                <img src="products/{{$products->image}}" alt="">
              </div>
              <div class="detail-box">
                <p>
                  {{$products->Description}}
                </p>
              </div>
              <div class="btn-box">
              <button type="button" class="btn btn-outline-warning">
                <a href="{{url('order_details',$products->product_id)}}">
                Selengkapnya!
                </a>
              </button>
              </div>
          </div>
        </div>



        @endforeach
        
        
      
    </div>
  </section>