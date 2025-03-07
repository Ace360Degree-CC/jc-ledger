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
        Schema::create('excel_log', function (Blueprint $table) {
            $table->id();
            $table->string('uniqID', '20');
            $table->string('file');
            $table->string('month');
            $table->string('uploadFrom');
            $table->integer('uploadedBy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_log');
    }
};
