<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mis_schema', function (Blueprint $table) {
            $table->id();
            // The Excel column reference, e.g. A, B, C ...
            $table->string('column', 5)->unique();
            // The actual header name in Excel
            $table->string('name', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mis_schema');
    }
};
