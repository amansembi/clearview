<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserchatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userchats', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('sender_id')->nullable();
			$table->string('receiver_id')->nullable();
            $table->string('message')->nullable();
            $table->string('chat_time')->nullable();
            $table->string('type')->nullable();
            $table->string('is_sent')->nullable();
            $table->string('is_recieved')->nullable();
            $table->string('thread_id')->nullable();            
            $table->string('image')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userchats');
    }
}
