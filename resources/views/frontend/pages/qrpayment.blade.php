
<div class="qrpayment-container">
<div class="row">
    <div class="col-md-8">
         <h1><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-check-circle text-success" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
  <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
</svg> Đặt hàng thành công</h1>
<span class="text-muted">Mã đơn hàng #DH{{ $order_number }}</span>
<div id="success_pay_box" class="p-2 text-center pt-3 border border-2 mt-5" style="display:none">
    <h2 class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-check-circle text-success" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
  <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
</svg> Thanh toán thành công</h2>
<p class="text-center text-success">Chúng tôi đã nhận được thanh toán, đơn hàng sẽ được chuyển đến quý khách trong thời gian sớm nhất!</p>
</div>
    <span class="text-muted">Mã đơn hàng #DH{{ $order_number }}</span>
   
    <div class="payment-options">
        <!-- Cách 1 -->
        <div class="payment-option">
            <p>Cách 1: Mở app ngân hàng và quét mã QR</p>
            <div class="text-center my-3">
                <img src="https://qr.sepay.vn/img?bank=ACB&acc=18721251&template=compact&amount={{$total_amount}}&des={{ $order_number }}" alt="QR Code">
                <p class="mt-2">
                <td><span class="fw-bold">Tổng</span></td>
                <td class="text-end fw-bold">{{ number_format($total_amount, 0, ',', '.') }}₫</td>
                    <span>Trạng thái: Chờ thanh toán...</span>
                    <div class="spinner-border" role="status"></div>
                </p>
            </div>
        </div>

        <!-- Cách 2 -->
        <div class="payment-option">
            <p>Cách 2: Chuyển khoản thủ công theo thông tin</p>
            <div class="text-center my-3">
                <img src="https://qr.sepay.vn/assets/img/banklogo/MB.png" alt="Bank Logo" style="max-height: 50px;">
                <p>Ngân hàng MBBank</p>
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <td>Chủ tài khoản:</td>
                        <td><b>Nguyễn Văn Hậu</b></td>
                    </tr>
                    <tr>
                        <td>Số TK:</td>
                        <td><b>88806072003</b></td>
                    </tr>
                    <tr>
                        <td>Số tiền:</td>
                        <td><b>{{ number_format($total_amount, 0, ',', '.') }}₫</b></td>
                    </tr>
                    <tr>
                        <td>Nội dung CK:</td>
                        <td><b>DH{{ $order_number }}</b></td>
                    </tr>
                </tbody>
            </table>
            <p class="bg-light p-2">Lưu ý: Vui lòng giữ nguyên nội dung chuyển khoản DH{{ $order_number }} để hệ thống tự động xác nhận thanh toán.</p>
        </div>
    </div>
<div class="text-center mt-4">
    <a href="javascript:history.back()" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zM8 0a8 8 0 1 1 0 16A8 8 0 0 1 0 8z"/>
            <path fill-rule="evenodd" d="M8 4.5a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 .5-.5z"/>
        </svg>
        Quay lại
    </a>
</div>
<style>
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 16px;
        text-decoration: none;
        color: white;
        background-color: #6c757d;
        border-radius: 4px;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        text-decoration: none;
        color: white;
    }

    .btn-secondary svg {
        vertical-align: middle;
    }
    .qrpayment-container {
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .qrpayment-container h1,
    .qrpayment-container h2 {
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .qrpayment-container .success-message {
        display: flex;
        align-items: center;
        gap: 10px;
        color: green;
    }

    .qrpayment-container img {
        max-width: 100%;
        height: auto;
    }

    .payment-options {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .payment-option {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
    }

    .payment-option p {
        margin-bottom: 10px;
        font-weight: bold;
    }

    .payment-option .spinner-border {
        width: 20px;
        height: 20px;
    }

    .table {
        margin-top: 10px;
    }

    .table td {
        vertical-align: middle;
        padding: 8px 12px;
    }

    .order-summary {
        margin-top: 30px;
    }

    .order-summary p {
        font-weight: bold;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
    }

    .order-summary .table {
        margin-top: 10px;
    }

    .order-summary .table td {
        vertical-align: middle;
        padding: 8px 12px;
    }

    @media (max-width: 768px) {
        .payment-options {
            flex-direction: column;
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      
      
      <?php
        // Nếu đang ở giao diện checkout
      if(isset($order_id)) {?>
      <script>
      var pay_status = 'Unpaid';
      
      // Hàm kiểm tra trạng thái đơn hàng
      // Sử dụng Ajax để lấy trạng thái đơn hàng. Nếu thanh toán thành công thì hiển thị Box đã thanh toán thành công, ẩn box checkout
      function check_payment_status() {
          if(pay_status == 'Unpaid') {
               $.ajax({
                    type: "POST",
                    data: {order_id: <?= $order_id;?>},
                    url: "https://payment-gateway-demo.sepay.dev/check_payment_status.php",
                    dataType:"json",
                    success: function(data){
                        if(data.payment_status == "Paid") {
                            $("#checkout_box").hide();
                            $("#success_pay_box").show();
                            pay_status = 'Paid';
                        }
                    }
                  });
              }
          }
        //Kiểm tra trạng thái đơn hàng 1 giây một lần
        setInterval(check_payment_status, 1000);
      </script>
      <?php } ?>
