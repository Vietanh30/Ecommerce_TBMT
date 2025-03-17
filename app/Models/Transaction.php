<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'tb_transactions';

    // Khai báo từng thông tin
    public int $id;
    public string $gateway;
    public string $transactionDate;
    public string $accountNumber;
    public ?string $subAccount;
    public ?string $code;
    public string $content;
    public string $transferType;
    public ?string $description;
    public int $transferAmount;
    public ?string $referenceCode;
    public ?\Illuminate\Support\Carbon $created_at;
    public ?\Illuminate\Support\Carbon $updated_at;
}
