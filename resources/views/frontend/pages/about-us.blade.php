@extends('frontend.layouts.master')

@section('title','Ecommerce Laravel || About Us')

@section('main-content')

	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="index1.html">Trang chủ<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="blog-single.html">về chúng tôi</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->

	<!-- About Us -->
	<section class="about-us section">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-12">
						<div class="about-content">
							@php
								$settings=DB::table('settings')->get();
							@endphp
							<h3>Chào mừng đến với <span>Cửa hàng máy tính Diệp Anh </span></h3>
							<p> 

Chào mừng bạn đến với Diệp Anh – địa chỉ uy tín chuyên cung cấp máy tính, laptop, linh kiện và các sản phẩm công nghệ chất lượng cao. Với phương châm *"Uy tín – Chất lượng – Tận tâm"*, Diệp Anh tự hào mang đến cho khách hàng những sản phẩm chính hãng, giá cả cạnh tranh và dịch vụ hỗ trợ tốt nhất.  

Tại Diệp Anh, chúng tôi cung cấp đa dạng các sản phẩm như:  
- Laptop văn phòng, máy tính gaming, máy trạm hiệu năng cao.  
- Linh kiện máy tính: RAM, ổ cứng SSD, card đồ họa, bo mạch chủ, v.v.  
- Phụ kiện công nghệ: bàn phím, chuột, tai nghe, màn hình và nhiều sản phẩm khác.  

Đội ngũ nhân viên nhiệt tình và giàu kinh nghiệm của chúng tôi luôn sẵn sàng tư vấn để bạn lựa chọn được sản phẩm phù hợp nhất với nhu cầu. Hãy đến **Diệp Anh** để trải nghiệm những giải pháp công nghệ tiên tiến, dịch vụ tận tâm và sự hài lòng tuyệt đối.  

Diệp Anh – người bạn đồng hành tin cậy trong thế giới công nghệ!  </p>
							<div class="button">
								<a href="{{route('contact')}}" class="btn primary">Liên hệ với chúng tôi</a>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-12">
						<div class="about-img overlay">
							<div class="button">
								<a href="https://www.youtube.com/watch?v=7edcgCdiHVU" class="video video-popup mfp-iframe"><i class="fa fa-play"></i></a>
							</div>
							<img src="@foreach($settings as $data) {{$data->photo}} @endforeach" alt="@foreach($settings as $data) {{$data->photo}} @endforeach">
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- End About Us -->


	<!-- Start Shop Services Area -->
	<section class="shop-services section">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-12">
					<!-- Start Single Service -->
					<div class="single-service">
						<i class="ti-rocket"></i>
						<h4>Miễn phí vận chuyển</h4>
						<p>Đơn hàng trên 20000000đ</p>
					</div>
					<!-- End Single Service -->
				</div>
				<div class="col-lg-3 col-md-6 col-12">
					<!-- Start Single Service -->
					<div class="single-service">
						<i class="ti-reload"></i>
						<h4>Hoàn trả</h4>
						<p>trong vòng 30 ngày</p>
					</div>
					<!-- End Single Service -->
				</div>
				<div class="col-lg-3 col-md-6 col-12">
					<!-- Start Single Service -->
					<div class="single-service">
						<i class="ti-lock"></i>
						<h4>Thanh toán an toàn</h4>
						<p>Thanh toán an toàn 100%</p>
					</div>
					<!-- End Single Service -->
				</div>
				<div class="col-lg-3 col-md-6 col-12">
					<!-- Start Single Service -->
					<div class="single-service">
						<i class="ti-tag"></i>
						<h4>Giá tốt nhất</h4>
						<p>Giá đảm bảo</p>
					</div>
					<!-- End Single Service -->
				</div>
			</div>
		</div>
	</section>
	<!-- End Shop Services Area -->

	@include('frontend.layouts.newsletter')
@endsection
