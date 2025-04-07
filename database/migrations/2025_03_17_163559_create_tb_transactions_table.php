<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('gateway');
            $table->dateTime('transaction_date');
            $table->string('account_number');
            $table->string('sub_account')->nullable();
            $table->decimal('amount_in', 15, 2)->default(0);
            $table->decimal('amount_out', 15, 2)->default(0);
            $table->decimal('accumulated', 15, 2)->default(0);
            $table->string('code');
            $table->text('transaction_content');
            $table->string('reference_number')->nullable();
            $table->string('body')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_transactions');
    }
};
