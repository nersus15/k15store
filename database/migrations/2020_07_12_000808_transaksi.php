<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Transaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function(Blueprint $table){
            $table->string('id', 5)->primary();
            $table->timestamp('tanggal')->default(date('Y-m-d H:i:s'));
            $table->string('barang', 5);
            $table->string('pembeli', 100);
            $table->integer('jumlah');
            $table->enum('status', ['keranjang', 'bayar', 'konfirmasi', 'kirim', 'batal', 'permintaan kembalikan', 'kembali', 'selesai'])->default('keranjang');
            $table->string('origin', 250)->nullable();
            $table->string('destinasi', 250)->nullable();
            $table->string('detail_alamat', 350)->nullable();
            $table->enum('kurir', ['pos', 'jne', 'tiki']);
            $table->string('service', 25);
            $table->integer('ongkir')->nullable();
            $table->integer('total')->nullable();
            $table->string('estimasi', 35)->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamp('tanggal_update')->nullable();
            $table->foreign('barang')->references('id')->on('product')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pembeli')->references('username')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
