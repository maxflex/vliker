<?php

use App\Enums\BanReason;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddBanReasonToTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('ban_reason', BanReason::getValues())->nullable()->index();
        });
        DB::table('tasks')
            ->where('is_banned', true)
            ->update([
                'ban_reason' => BanReason::Report,
            ]);
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('is_banned');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
}
