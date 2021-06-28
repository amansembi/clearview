<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTechnicianStatusToTechnicianlatlngsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technicianlatlngs', function (Blueprint $table) {
            $table->string('technicianStatus')->nullable()->after('technician_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technicianlatlngs', function (Blueprint $table) {
            //
        });
    }
}
