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
        Schema::table('documents', function (Blueprint $table) {
            // First, rename the column 'certificate' to 'certificate_id'
            $table->renameColumn('certificate', 'certificate_id');

            // Then add a new column 'certificate_name' after 'certificate_id'
            $table->string('certificate_name')->after('certificate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop the newly added column
            $table->dropColumn('certificate_name');

            // Rename 'certificate_id' back to 'certificate'
            $table->renameColumn('certificate_id', 'certificate');
        });
    }
};

