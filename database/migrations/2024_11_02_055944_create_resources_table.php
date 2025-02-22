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

        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->unique(); 
            $table->string('fname')->nullable();//done
            $table->string('lname')->nullable();//done
            $table->string('mname')->nullable();//done
            $table->string('fullname')->nullable();//done
            $table->string('contact_no')->nullable(); //done
            $table->string('age')->nullable(); //done
            $table->string('email')->unique(); //done
            $table->string('profession')->nullable(); //done
            $table->string('company')->nullable(); //done
            $table->string('industry')->nullable(); //done
            $table->string('companywebsite')->nullable(); //done
            $table->string('role_code')->nullable();
            $table->string('designation')->nullable();
            $table->date('date_birth')->nullable();
            $table->string('home_country')->nullable(); // Home country
            $table->string('current_location')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('resumepdf')->nullable();

            $table->string('h1_fname')->nullable();
            $table->string('h1_lname')->nullable();
            $table->string('h1_mname')->nullable();
            $table->string('h1_fullname')->nullable(); 
            $table->integer('h1_contact_no')->nullable();
            $table->string('h1_email')->nullable()->unique();
            $table->string('h1_address1')->nullable();
            $table->string('h1_address2')->nullable();
            $table->string('h1_city')->nullable();
            $table->string('h1_province')->nullable();
            $table->string('h1_postal_code')->nullable();
            $table->string('h1_companycode')->nullable();
            $table->integer('h1_rolecode')->nullable();
            $table->string('h1_designation')->nullable();
        
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
        Schema::dropIfExists('resources');
    }
};
