<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mis_data', function (Blueprint $table) {
            $table->id();
            // Let's assume you start from column B up to BF
            // (A might be some internal ID or you decided not to store it)
            $columns = range('B', 'Z'); 
            // after Z, continue with AA, AB,... BF
            // We'll do a quick approach to generate them manually for brevity 
            // or you can write your own logic to push in all up to BF.

            foreach ($columns as $col) {
                $table->text($col)->nullable();
            }

            // now columns from AA to BF
            $extendedCols = [];
            for ($i = ord('A'); $i <= ord('Z'); $i++) {
                for ($j = ord('A'); $j <= ord('Z'); $j++) {
                    $extendedCols[] = chr($i) . chr($j);
                }
            }
            // This generates AA..AZ, BA..BZ, ... up to ZZ
            // but we only need up to BF
            // We'll filter:
            $neededExtendedCols = array_filter($extendedCols, function($item) {
                // We only want from AA up to BF
                // comparing as strings should work if we carefully limit
                return $item >= 'AA' && $item <= 'BF';
            });

            foreach($neededExtendedCols as $col) {
                $table->text($col)->nullable();
            }

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mis_data');
    }
};
