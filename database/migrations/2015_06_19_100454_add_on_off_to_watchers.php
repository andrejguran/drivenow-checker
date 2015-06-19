<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnOffToWatchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('watchers', function ($table) {
            $table->boolean('on')->default(true);
            $table->string('last_check')->nullable();
            $table->decimal('latitude', 14, 10);
            $table->decimal('longtitude', 14, 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('watchers', function ($table) {
            $table->dropColumn('on');
            $table->dropColumn('last_check');
            $table->dropColumn('latitude');
            $table->dropColumn('longtitude');
        });
    }
}
