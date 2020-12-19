<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soldiers', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('apellido',100);
            $table->date('fecha_nacimiento');
            $table->date('fecha_incorporacion');
            //pongo string porque no se si el numero de placa tiene letras, como el dni por ejemplo.
            $table->String('placa')->unique();
            $table->enum('rango',['soldado','sargento','capitan']);
            $table->enum('estado',['activo','retirado','baja']);

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
        Schema::dropIfExists('soldiers');
    }
}
