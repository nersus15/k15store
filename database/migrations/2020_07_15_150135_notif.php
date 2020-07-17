<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Notif extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notif', function(Blueprint $table)
        {
            $table->string('id', 5)->primary();
            $table->timestamp('tanggal')->default(date('Y-m-d H:i:s'));
            $table->mediumText('pesan', 150)->nullable();
            $table->string('judul', 50)->default('pemberitahuan system');
            $table->string('pembaca', 100);
            $table->timestamp('tanggal_baca')->nullable();
            $table->foreign('pembaca')->references('username')->on('users')->onDelete('cascade')->onUpdate('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notif');
    }
}
