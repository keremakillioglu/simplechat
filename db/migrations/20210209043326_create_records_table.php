<?php

use App\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

class CreateRecordsTable extends Migration
{
    public function up()
    {
        $this->schema->create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('message_id');
            $table->dateTime('delivered_at')->useCurrent();
            $table->dateTime('read_at')->nullable();
            $table->foreign("user_id")->references("id")
            ->on("users")->onDelete("cascade");
            $table->foreign("message_id")->references("id")
            ->on("messages")->onDelete("cascade");
        });
    }

    public function down()
    {
        $this->schema->drop('records');
    }
}
