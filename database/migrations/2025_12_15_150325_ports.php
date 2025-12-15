<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->char('unlocode', 5)->unique(); // CNNBO, CLVAP
            $table->string('name', 100);           // Ningbo, Valparaíso
            $table->char('country_iso2', 2);       // FK lógica
            $table->timestamps();

            $table->foreign('country_iso2')
                  ->references('iso2')
                  ->on('countries')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
