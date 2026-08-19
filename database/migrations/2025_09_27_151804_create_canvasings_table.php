<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canvasings', function (Blueprint $table) {
            $table->id();
            $table->string('kantor')->index();
            $table->dateTime('tanggal')->default(now());
            $table->text('alamat_canvasing')->nullable();
            $table->string('jenis_canvasing');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('canvasings');
    }
};
