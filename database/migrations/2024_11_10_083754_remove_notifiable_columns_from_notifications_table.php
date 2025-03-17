<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNotifiableColumnsFromNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Kiểm tra xem cột có tồn tại trước khi xóa
            if (Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->dropColumn('notifiable_type');
            }
            if (Schema::hasColumn('notifications', 'notifiable_id')) {
                $table->dropColumn('notifiable_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Thêm lại các cột nếu cần rollback
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
        });
    }
}
