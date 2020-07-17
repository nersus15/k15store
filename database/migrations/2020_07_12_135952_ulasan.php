<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ulasan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ulasan', function(Blueprint $table){
            $table->string('id', 5)->primary();
            $table->timestamp('tanggal')->default(date('Y-m-d H:i:s'));
            $table->string('barang', 5);
            $table->string('dari', 100);
            $table->text('ulasan');
            $table->string('gambar', 30)->nullable();
            $table->foreign('barang')->references('id')->on('product')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('dari')->references('username')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ulasan');
    }
}
