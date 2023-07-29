<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLikesFromMicropostsTable extends Migration
{
    public function up()
    {
        Schema::table('microposts', function (Blueprint $table) {
            $table->dropColumn('likes');
        });
    }

    public function down()
    {
        Schema::table('microposts', function (Blueprint $table) {
            $table->integer('likes')->default(0);
        });
    }
}
