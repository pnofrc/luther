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
            Schema::create('places', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('keyword_id');
                $table->string('title_it');
                $table->string('title_de');
                $table->string('title_en');
                $table->text('content_it');
                $table->text('content_de');
                $table->text('content_en');
                $table->decimal('latitude', 10, 7);
                $table->decimal('longitude', 10, 7);
                $table->timestamps();
                
                $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
