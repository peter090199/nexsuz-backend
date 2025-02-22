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
        Schema::create('submenus', function (Blueprint $table) {
            $table->id();
            $table->integer('transNo'); // Assuming this references the menus table
            $table->string('desc_code'); 
            $table->string('description'); // Assuming description is required
            $table->string('icon'); // Assuming icon is required
            $table->string('class'); // Assuming class is required
            $table->string('routes'); // Assuming routes is required
            $table->integer('sort')->nullable(); // Assuming sort can be nullable
            $table->string('status')->default('A');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps(); // Includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submenus');
    }
};
