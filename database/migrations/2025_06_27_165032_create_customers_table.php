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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable();
            $table->string('slug')->unique();
            $table->string('email', 500)->nullable()->unique();
            $table->string('phone', 100)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('city', 200)->nullable();
            $table->string('state', 200)->nullable();    
            $table->softDeletes();         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
