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
        Schema::create('usercertificates', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // Code field
            $table->integer('transNo'); // Transaction number
            $table->string('certificate_title')->nullable();  // certificate_title name (nullable)
            $table->string('certificate_provider')->nullable();  // certificate_provider (nullable)
            $table->date('date_completed')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usercertificates');
    }
};
