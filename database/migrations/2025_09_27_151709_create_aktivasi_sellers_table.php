<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAktivasiSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aktivasi_sellers', function (Blueprint $table) {
            $table->id();
            $table->string('kantor')->index();
            $table->dateTime('tanggal')->default(now());
            $table->string('nama_olshop');
            $table->boolean('jenis_aktivasi_seller')->default(1);
            $table->string('nama_pemilik');
            $table->text('alamat_lengkap');
            $table->string('nomor_hp');
            $table->string('jenis_produk');
            $table->string('pesaing');
            $table->string('link_toko');
            $table->text('keterangan_lainnya')->nullable();
            $table->json('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aktivasi_sellers');
    }
}
