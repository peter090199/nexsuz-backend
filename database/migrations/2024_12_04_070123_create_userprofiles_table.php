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
        Schema::create('userprofiles', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->nullable(); // For the user's unique code
            $table->integer('transNo')->nullable(); // Transaction number
            $table->string('photo_pic')->nullable(); // Path to photo
            $table->string('contact_no')->nullable(); // Contact number
            $table->boolean('contact_visibility')->default(0); // 0 = hide, 1 = show
            $table->string('email')->unique()->nullable(); // Email address
            $table->boolean('email_visibility')->default(0); // 0 = hide, 1 = show
            $table->string('summary')->nullable();
            $table->date('date_birth')->nullable(); // Date of birth
            $table->string('home_country')->nullable(); // Home country
            $table->string('home_state')->nullable(); // Home country
            $table->string('current_location')->nullable(); 
            $table->string('current_state')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userprofiles');
    }
};
