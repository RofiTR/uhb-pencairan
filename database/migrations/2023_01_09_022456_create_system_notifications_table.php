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
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('context');
            $table->uuid('context_id');
            $table->uuid('causer_id');
            $table->text('description');
            $table->uuid('user_id');
            $table->boolean('is_read');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('causer_id')->on('users')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_notifications');
    }
};
