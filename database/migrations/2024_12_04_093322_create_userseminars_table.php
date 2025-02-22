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
        Schema::create('userseminars', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // Code field
            $table->integer('transNo'); // Transaction number
            $table->string('seminar_title')->nullable(); // Seminar title (nullable)
            $table->string('seminar_provider')->nullable(); // Seminar provider (nullable)
            $table->date('date_completed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userseminars');
    }
};
