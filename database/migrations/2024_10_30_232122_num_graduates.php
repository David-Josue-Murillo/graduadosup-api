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
        Schema::create('num_graduates', function (Blueprint $table){
            $table->id();
            $table->integer('quantity');
            $table->integer('year');
            $table->unsignedBigInteger('campus_id');
            $table->unsignedBigInteger('career_id');
            $table->timestamps();

            // ForeignKey
            $table->foreign('campus_id')->references('id')->on('campus')->onDelete('cascade');
            $table->foreign('career_id')->references('id')->on('careers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('num_graduates');
    }
};
