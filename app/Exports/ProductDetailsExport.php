<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductDetailsExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $data;

    // Nhận dữ liệu từ Controller
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Lấy dữ liệu
    public function collection()
    {
        return collect($this->data);
    }

    // Định nghĩa tiêu đề cho bảng trong Excel
    public function headings(): array
    {
        return [
            'Mã Đơn Hàng',
            'Ngày Đặt',
            'Số Lượng',
            'Giá',
            'Tổng Tiền'
        ];
    }

    // Map dữ liệu cho mỗi dòng
    public function map($order): array
    {
        $createdAt = is_string($order->created_at) ? \Carbon\Carbon::parse($order->created_at) : $order->created_at;
        // Đảm bảo $order là đối tượng và có thuộc tính
        return [
            $order->id,  // Truy cập id của đối tượng Order
            $createdAt->format('d-m-Y'), // Lấy ngày đặt và định dạng ngày
            $order->quantity,  // Lấy số lượng từ Cart
            number_format($order->price, 0, ',', '.'),  // Định dạng giá
            number_format($order->amount, 0, ',', '.')  // Định dạng tổng tiền
        ];
    }

    // Tiêu đề sheet
    public function title(): string
    {
        return 'Chi Tiết Sản Phẩm';
    }
}

