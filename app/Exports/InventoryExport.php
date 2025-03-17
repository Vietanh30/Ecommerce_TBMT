<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryExport implements FromCollection, WithHeadings
{
    protected $categoryId;

    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Lấy dữ liệu tồn kho
     */
    public function collection()
    {
        $query = Product::query();

        if ($this->categoryId) {
            $query->where('cat_id', $this->categoryId);
        }

        return $query->select('id', 'title', 'stock', 'cat_id') // Thêm cat_id để truy vấn
             ->with('cat_info:id,title') // Chỉ lấy các trường id và title của Category
             ->get()
             ->map(function ($product) {
                 return [
                     'id' => $product->id,
                     'title' => $product->title,
                     'cat_info' => $product->cat_info ? $product->cat_info->title : 'Không rõ', // Kiểm tra nếu cat_info không null
                     'stock' => $product->stock,
                 ];
             });

    }

    /**
     * Đặt tiêu đề cho các cột
     */
    public function headings(): array
    {
        return ['ID', 'Tên Sản Phẩm', 'Loại Sản Phẩm', 'Tồn Kho'];
    }
}
