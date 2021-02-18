
<?php

use App\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        $this->schema->create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->text('body');
            $table->uuid('uuid')->unique();
            $table->dateTime('created_at')->useCurrent();
            $table->foreign("sender_id")->references("id")
                ->on("users")->onDelete("cascade");
            $table->foreign("receiver_id")->references("id")
                ->on("users")->onDelete("cascade");
        });
    }

    public function down()
    {
        $this->schema->drop('messages');
    }
}
