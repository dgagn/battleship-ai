<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partie_id')->constrained()->cascadeOnDelete();
            $table->char('coordonnee', 4);
            $table->integer('resultat')->nullable();
            $table->unique(['partie_id', 'coordonnee']);
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
        Schema::dropIfExists('missiles');
    }
};
