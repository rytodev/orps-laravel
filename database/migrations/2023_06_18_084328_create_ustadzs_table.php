<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ustadzs', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('niun');
            $table->text('photo')->nullable()->default(NULL);
            $table->date('birth_date')->nullable()->default(NULL);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ustadzs');
    }
};
