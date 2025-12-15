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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('search_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_line_id')->constrained()->cascadeOnDelete();

            $table->date('valid_until');
            $table->integer('gp20')->nullable();
            $table->integer('gp40')->nullable();
            $table->integer('hq40')->nullable();
            $table->integer('closing')->nullable();
            $table->integer('transit_time')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
