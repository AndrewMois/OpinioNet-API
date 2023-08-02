<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameVoteIdColumnInVotesTable extends Migration
{
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->renameColumn('vote_id', 'id');
        });
    }

    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->renameColumn('id', 'vote_id');
        });
    }
}
