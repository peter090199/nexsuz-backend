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
        Schema::create('roleaccessmenus', function (Blueprint $table) {
            $table->id();
            $table->string('rolecode'); // Assuming role code is required
            $table->integer('transNo');
            $table->integer('menus_id'); // Assuming this references the sub_menus table
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
        Schema::dropIfExists('roleaccessmenus');
    }
};
