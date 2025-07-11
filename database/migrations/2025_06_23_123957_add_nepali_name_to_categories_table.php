<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->string('nepali_name', 100)->after('name');
    });
}

public function down()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->dropColumn('nepali_name');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
