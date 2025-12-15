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
        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            $table->timestamp('searched_at');
            $table->string('transport_type');
            $table->string('pol_code');
            $table->string('pod_code');
            $table->string('result_page_url')->nullable();
            $table->integer('total_rates_found');
            $table->boolean('success');
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
