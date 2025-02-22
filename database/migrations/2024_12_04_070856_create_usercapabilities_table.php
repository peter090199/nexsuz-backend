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
        Schema::create('usercapabilities', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->nullable(); // Unique code for capabilities
            $table->integer('transNo')->nullable(); // Transaction number
            $table->text('language')->nullable(); // Languages known by the user
            $table->timestamps(); // Created_at and Updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usercapabilities');
    }
};
