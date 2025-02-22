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

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('transNo'); 
            $table->string('desc_code'); 
            $table->string('description'); // Assuming description is required
            $table->string('icon'); // Assuming icon is required
            $table->string('class'); // Assuming class is required
            $table->string('routes'); // Assuming routes is required
            $table->integer('sort')->nullable(); // Assuming sort can be nullable
            $table->string('status')->default('A');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
