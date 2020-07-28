<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('username', 100)->primary();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('nohp')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('isActive')->unsigned()->default(1);
            $table->enum('role', ['pembeli', 'pedagang', 'admin'])->default('pembeli');
            $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
            $table->timestamp('updated_at')->nullable();
            $table->string('nama_lengkap', 250)->nullable();
            $table->string('alamat', 250)->nullable();
            $table->string('kab_kota', 3)->nullable();
            $table->string('image', 100)->nullable()->default('default.jpg');
            $table->string('riwayat_cari')->nullable();
            $table->string('minat', 250)->nullable();
            $table->enum('jenis_kelamin', ['P', 'L'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('users');
    }
}
