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
        Schema::create('usereducations', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->nullable(); 
            $table->integer('transNo')->nullable(); 
            $table->string('highest_education')->nullable(); 
            $table->string('school_name')->nullable();
            $table->string('start_month')->nullable();
            $table->integer('start_year')->nullable(); 
            $table->string('end_month')->nullable();
            $table->integer('end_year')->nullable(); 
            $table->string('status')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usereducations');
    }
};
