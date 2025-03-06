<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('excel_data', function (Blueprint $table) {
            $table->id();
            $table->string('uniqID', '20');
            $table->string('B', 20);
            $table->string('C', 10);
            $table->string('D', 20);
            $table->string('E', 30);
            $table->string('F', 30);
            $table->string('G', 20);
            $table->string('H', 15);
            $table->string('I', 30);
            $table->string('J', 20);
            $table->string('K', 20);
            $table->string('L', 20);
            $table->string('M', 20);
            $table->string('N', 20);
            $table->string('O', 10);
            $table->string('P', 10);
            $table->string('Q', 10);
            $table->string('R', 10);
            $table->string('S', 10);
            $table->string('T', 10);
            $table->string('U', 10);
            $table->string('V', 10);
            $table->string('W', 10);
            $table->string('X', 10);
            $table->string('Y', 10);
            $table->string('Z', 10);
            $table->string('AA', 10);
            $table->string('AB', 10);
            $table->string('AC', 10);
            $table->string('AD', 10);
            $table->string('AE', 10);
            $table->string('AF', 10);
            $table->string('AG', 10);
            $table->string('AH', 10);
            $table->string('AI', 10);
            $table->string('AJ', 10);
            $table->string('AK', 10);
            $table->string('AL', 10);
            $table->string('AM', 10);
            $table->string('AN', 10);
            $table->string('AO', 10);
            $table->string('AP', 10);
            $table->string('AQ', 10);
            $table->string('AR', 10);
            $table->string('AS', 10);
            $table->string('AT', 10);
            $table->string('AU', 10);
            $table->string('AV', 10);
            $table->string('AW', 10);
            $table->string('AX', 10);
            $table->string('AY', 10);
            $table->string('AZ', 10);
            $table->string('BA', 10);
            $table->string('BB', 10);
            $table->string('BC', 10);
            $table->string('BD', 10);
            $table->string('BE', 10);
            $table->string('BF', 10);
            $table->string('BG', 10);
            $table->string('BH', 10);
            $table->string('BI', 10);
            $table->string('BJ', 10);
            $table->string('BK', 10);
            $table->string('BL', 10);
            $table->string('BM', 10);
            $table->string('BN', 10);
            $table->string('BO', 10);
            $table->string('BP', 10);
            $table->string('BQ', 10);
            $table->string('BR', 10);
            $table->string('BS', 10);
            $table->string('BT', 10);
            $table->string('BU', 10);
            $table->string('BV', 10);
            $table->string('BW', 10);
            $table->string('BX', 10);
            $table->string('BY', 10);
            $table->string('BZ', 10);
            $table->string('CA', 10);
            $table->string('CB', 10);
            $table->string('CC', 10);
            $table->string('CD', 10);
            $table->string('CE', 10);
            $table->string('CF', 10);
            $table->string('CG', 10);
            $table->string('CH', 10);
            $table->string('CI', 10);
            $table->string('CJ', 10);
            $table->string('CK', 10);
            $table->string('CL', 10);
            $table->string('CM', 10);
            $table->string('CN', 10);
            $table->string('CO', 10);
            $table->string('CP', 10);
            $table->string('CQ', 10);
            $table->string('CR', 10);
            $table->string('CS', 10);
            $table->string('CT', 10);
            $table->string('CU', 10);
            $table->string('CV', 10);
            $table->string('CW', 10);
            $table->string('CX', 10);
            $table->string('CY', 10);
            $table->string('CZ', 10);
            $table->string('DA', 10);
            $table->string('DB', 10);
            $table->string('DC', 10);
            $table->string('DD', 10);
            $table->string('DE', 10);
            $table->string('DF', 10);
            $table->string('DG', 10);
            $table->string('DH', 10);
            $table->string('DI', 10);
            $table->string('DJ', 10);
            $table->string('DK', 10);
            $table->string('DL', 10);
            $table->string('DM', 10);
            $table->string('DN', 10);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_data');
    }
};
