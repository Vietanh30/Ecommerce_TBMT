@extends('frontend.layouts.master')

@section('title','Checkout page')

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
                <form class="form" method="POST" action="{{route('cart.order')}}">
                    @csrf
                    <div class="row">

                        <div class="col-lg-8 col-12">
                            <div class="checkout-form">
                            <h2>Hoàn tất đơn hàng của bạn</h2>
                            <p>Chỉ còn vài bước nữa để hoàn tất mua hàng của bạn một cách an toàn!</p>

                                <!-- Form -->
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Họ tên người nhận <span>*</span></label>
                                            <input type="text" name="fullname" placeholder="" value="{{old('fullname')}}" required>
                                            @error('fullname')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Email <span>*</span></label>
                                            <input type="email" name="email" placeholder="" value="{{old('email')}}" required>
                                            @error('email')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Số điện thoại <span>*</span></label>
                                            <input type="number" name="phone" placeholder="" required value="{{old('phone')}}">
                                            @error('phone')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Địa chỉ</label>
                                            <input type="text" name="address" placeholder="" value="{{old('address')}}">
                                            @error('address')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12">
    <div class="">
        <label for="province">Tỉnh/Thành phố:</label>
        <label id="province">Thành phố Hà Nội</label>

    </div>
</div>

<div class="col-lg-4 col-md-4 col-12">
    <div class="form-group1">
    <label for="district">Huyện/Quận:</label>
    <select  id="district">

    </select>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-12">
    <div class="form-group1">
    <label for="ward">Phường/Xã:</label>
    <select id="ward" >

    </select>
    </div>
</div>



                                </div>
                                <!--/ End Form -->
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
    <div class="order-details">
        <!-- Order Widget -->
        <div class="single-widget">
            <h2>TỔNG GIÁ TRỊ GIỎ HÀNG</h2>
            <div class="content">
                <ul>
                <li class="order_subtotal" data-price="{{ Helper::totalCartPrice() }}">
    Tổng phụ giỏ hàng
    <span>{{ Helper::totalCartPrice() }}₫</span>
</li>

                    <li class="fee-shipping">Phí vận chuyển : <span>0₫</span></li>

                    @if(session('coupon'))
                    <li class="coupon_price" data-price="{{session('coupon')['value']}}">Giảm giá<span>{{ number_format(session('coupon')['value'], 0, ',', '.') }}₫</span></li>
                    @endif
                    @php
                        $total_amount = Helper::totalCartPrice();
                        if(session('coupon')){
                            $total_amount = $total_amount - session('coupon')['value'];
                        }
                    @endphp

                    @if(session('coupon'))
                        <li class="last" id="order_total_price">Tổng cộng<span>{{ number_format($total_amount, 0, ',', '.') }}₫</span></li>
                    @else
                        <li class="last" id="order_total_price">Tổng cộng<span>{{ number_format($total_amount, 0, ',', '.') }}₫</span></li>
                    @endif
                    
                </ul>
            </div>
        </div>
        <!--/ End Order Widget -->

        <!-- Order Widget -->
        <div class="single-widget">
            <h2>Phương thức thanh toán</h2>
            <div class="content">
                <div class="checkbox">
                    <form-group>
                        <input name="payment_method" type="radio" value="cod" required> <label> Thanh toán khi nhận hàng</label><br>
                        <input name="payment_method" type="radio" value="cardpay" required> <label> Thanh toán qua thẻ</label><br>
                        <input name="payment_method" type="radio" value="qrpay" required> 
                        <label> Thanh toán bằng QR</label><br>
                        <!-- Credit Card Details -->
                        <div id="creditCardDetails" style="display: none;">
                            <label for="cardNumber">Số thẻ:</label>
                            <input type="text" id="cardNumber" name="card_number" maxlength="16"><br>

                            <label for="cardName">Tên trên thẻ:</label>
                            <input type="text" id="cardName" name="card_name"><br>

                            <label for="expirationDate">Ngày hết hạn:</label>
                            <input type="text" id="expirationDate" name="expiration_date" maxlength="5"><br>

                            <label for="cvv">CVV:</label>
                            <input type="text" id="cvv" name="cvv" maxlength="3"><br>
                        </div>
                    </form-group>
                </div>
            </div>
        </div>
        <!--/ End Order Widget -->

        <!-- Payment Method Widget -->
        <div class="single-widget payement">
            <div class="content">
                <img src="{{('backend/img/payment-method.png')}}" alt="#">
            </div>
        </div>
        <!--/ End Payment Method Widget -->

        <!-- Button Widget -->
        <div class="single-widget get-button">
            <div class="content">
                <div class="button">
                    <button type="submit" class="btn">Tiến hành thanh toán</button>
                </div>
            </div>
        </div>
        <!--/ End Button Widget -->
    </div>
</div>
</div>
</form>
</div>
</section>

    <!--/ End Checkout -->

    <!-- Start Shop Services Area  -->
    <section class="shop-services section home">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-rocket"></i>
                    <h4>Miễn phí vận chuyển</h4>
                    <p>Mọi đơn hàng</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-reload"></i>
                    <h4>Đổi trả miễn phí</h4>
                    <p>Đổi trả trong vòng 30 ngày</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-lock"></i>
                    <h4>Thanh toán an toàn</h4>
                    <p>Thanh toán 100% an toàn</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-tag"></i>
                    <h4>Giá tốt nhất</h4>
                    <p>Cam kết giá tốt</p>
                </div>
                <!-- End Single Service -->
            </div>
        </div>
    </div>
</section>

    <!-- End Shop Services -->

   <!-- Start Shop Newsletter  -->
<section class="shop-newsletter section">
    <div class="container">
        <div class="inner-top">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-12">
                    <!-- Start Newsletter Inner -->
                    <div class="inner">
                        <h4>Bản tin</h4>
                        <p>Đăng ký nhận bản tin của chúng tôi và nhận <span>10%</span> giảm giá cho đơn hàng đầu tiên</p>
                        <form action="mail/mail.php" method="get" target="_blank" class="newsletter-inner">
                            <input name="EMAIL" placeholder="Địa chỉ email của bạn" required="" type="email">
                            <button class="btn">Đăng ký</button>
                        </form>
                    </div>
                    <!-- End Newsletter Inner -->
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- End Shop Newsletter -->
@endsection
@push('styles')
	<style>
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')

<script>
$(document).ready(function() {
        $('input[name="payment_method"]').change(function() {
            if ($(this).val() === 'qrpay') {
                $('#qrPaymentDetails').show();
                $('#creditCardDetails').hide();
                generateQRCode(); // Call the QR code generation function
            } else {
                $('#qrPaymentDetails').hide();
                $('#creditCardDetails').hide();
            }
        });

        function generateQRCode() {
            const totalAmount = '{{ $total_amount }}'; // Use the Blade variable for total amount
            const qrData = {
                account_number: "5253060723", // Example account number
                bank_name: "Techcombank",      // Example bank name
                amount: totalAmount,          // Use the total amount here
                currency: "VND",              // Currency
                description: "Thanh toán đơn hàng" // Description
            };

            // Send the QR data to the backend to generate the QR code
            fetch('/generate-qr', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                body: JSON.stringify(qrData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.qrCodeUrl) {
                    $('#qrCodeImage').attr('src', data.qrCodeUrl); // Set the QR code image source
                }
            })
            .catch(error => console.error('Error generating QR code:', error));
        }
    });

$(document).ready(function() {
  // Fetch dữ liệu từ file JSON
  fetch("{{ asset('frontend/js/full_json_generated_data_vn_units.json') }}")
    .then((response) => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then((jsonData) => {
      data = jsonData;
      loadProvinces();
    })
    .catch((error) => {
      console.error('Có vấn đề khi tải dữ liệu:', error);
    });

  function loadProvinces() {
    const provinceCode = '01';
    const province = data.find(p => String(p.Code) === provinceCode);
    const $wardSelect = $('#ward');
    const $districtSelect = $('#district');

    if (province.District.length > 0) {
      // Xóa các option cũ
      $districtSelect.empty();
      $districtSelect.append('<option value="">-- Chọn Huyện --</option>');

      // Thêm các huyện vào select
      $.each(province.District, function(index, district) {
        $districtSelect.append('<option value="' + district.FullName + '">' + district.FullName + '</option>');
      });

      // Cập nhật lại niceSelect
      $districtSelect.niceSelect('update');

      // Hiển thị dropdown huyện
      $districtSelect.parent().show();

      // Ẩn xã khi chưa chọn huyện
      $wardSelect.empty();
      $wardSelect.append('<option value="">-- Chọn Xã --</option>');
      $wardSelect.niceSelect('update');
      $wardSelect.parent().hide();
    } else {
      // Nếu không có huyện, ẩn dropdown huyện và xã
      $districtSelect.empty();
      $districtSelect.append('<option value="">-- Chọn Huyện --</option>');
      $districtSelect.niceSelect('update');
      $districtSelect.parent().hide();

      // Ẩn dropdown xã
      $wardSelect.empty();
      $wardSelect.append('<option value="">-- Chọn Xã --</option>');
      $wardSelect.niceSelect('update');
      $wardSelect.parent().hide();
    }
  }

  // Khi người dùng chọn huyện, cập nhật xã
  $('#district').on('change', function() {
    const districtName = String($(this).val());
    const provinceName = 'Thành phố Hà Nội';
    const province = data.find(p => String(p.FullName) === provinceName);
    const district = province ? province.District.find(d => String(d.FullName) === districtName) : null;

    const $wardSelect = $('#ward');

    if (district && district.Ward) {
      // Xóa các option cũ
      $wardSelect.empty();
      $wardSelect.append('<option value="">-- Chọn Xã --</option>');

      // Thêm các xã vào select
      $.each(district.Ward, function(index, ward) {
        $wardSelect.append('<option value="' + ward.FullName + '">' + ward.FullName + '</option>');
      });

      // Cập nhật lại niceSelect
      $wardSelect.niceSelect('update');
      $wardSelect.parent().show();  // Hiển thị dropdown xã
    } else {
      $wardSelect.empty();
      $wardSelect.append('<option value="">-- Chọn Xã --</option>');
      $wardSelect.niceSelect('update');
      $wardSelect.parent().hide();
    }
  });
});

</script>

<script>
    $(document).ready(function() {
        $('input[name="payment_method"]').change(function() {
            if ($(this).val() === 'cardpay') {
                $('#creditCardDetails').show();
            } else {
                $('#creditCardDetails').hide();
            }
        });
    });
</script>
<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>


@endpush
