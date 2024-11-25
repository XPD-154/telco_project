<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkedlnIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function ($table) {
            $table->string('linkedln_provider');
            $table->string('linkedln_provider_id')->nullable();
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropColumns('linkedln_provider');
	    Schema::dropColumns('linkedln_provider_id');
    }
}
