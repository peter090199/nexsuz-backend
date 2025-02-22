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
        Schema::create('useremploymentrecords', function (Blueprint $table) {
            $table->id();
            $table->string('code'); 
            $table->integer('transNo'); 
            $table->string('company_name')->nullable();  
            $table->string('position')->nullable();  
            $table->string('job_description')->nullable(); 
            $table->date('date_completed')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('useremploymentrecords');
    }
};
