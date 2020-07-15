<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('product', function(Blueprint $table){
            $table->string('id', 5)->primary();
            $table->string('owner', 100);
            $table->string('nama_product', 150);
            $table->mediumText('deskripsi')->nullable();
            $table->integer('harga');
            $table->integer('stok');
            $table->string('gambar', 250)->nullable()->default('productdef.jpg;');
            $table->string('kategori', 50)->default('tanpa kategori');
            $table->integer('berat')->nullable();
            $table->enum('kondisi', ['baru', 'bekas'])->default('baru');
            $table->integer('terjual')->default(0);
            $table->integer('batas_beli');
        });

        Schema::table('product', function (Blueprint $table) {
            $table->foreign('owner')->references('username')->on('users')->onDelete('cascade')->onUpdate('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
