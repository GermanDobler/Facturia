<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id(); // id INT(11) AUTO_INCREMENT PRIMARY KEY
            $table->string('titulo', 200); // titulo VARCHAR(200)
            $table->string('subtitulo', 250); // subtitulo VARCHAR(250)
            $table->mediumText('contenido_html'); // contenido_html MEDIUMTEXT
            $table->string('imagen_url', 110); // imagen_url VARCHAR(110)
            $table->string('estado', 15); // estado VARCHAR(15)
            $table->string('etiqueta', 50)->collation('utf8mb4_bin'); // etiqueta VARCHAR(50) con collation
            $table->boolean('featured')->default(0); // featured TINYINT(1) DEFAULT 0
            $table->integer('prioridad')->default(0); // prioridad INT(11) DEFAULT 0
            $table->timestamps(); // created_at DATETIME y updated_at DATETIME
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('noticias');
    }
}