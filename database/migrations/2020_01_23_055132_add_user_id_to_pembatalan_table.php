<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPembatalanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembatalan', function (Blueprint $table) {
            //
            $table->integer('user_id')->index('user_id_foreign')->after('pemesanan_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembatalan', function (Blueprint $table) {
            //
            $table->dropColumn('user_id');

        });
    }
}
