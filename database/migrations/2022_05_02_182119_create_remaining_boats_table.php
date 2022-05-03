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
        Schema::create('remaining_boats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bateau_id')->constrained('bateaux')->cascadeOnDelete();
            $table->unique(['partie_id', 'bateau_id']);
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
        Schema::dropIfExists('remaining_boats');
    }
};
