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
        Schema::create('usertrainings', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // User code (you might want to add a foreign key constraint here if referencing another table)
            $table->integer('transNo')->nullable(); // Transaction number, nullable
            $table->string('training_title')->nullable(); // Training title, nullable
            $table->string('training_provider')->nullable(); // Training provider, nullable
            $table->date('date_completed')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usertrainings');
    }
};
