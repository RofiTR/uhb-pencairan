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
        Schema::create('proposals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->string('no', 20);
            $table->uuid('type_id');
            $table->uuid('category_id');
            $table->boolean('sppd');
            $table->string('account_code')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('amount', 15, 2);
            $table->jsonb('withdrawal');
            $table->uuid('approver_id')->nullable();
            $table->uuid('status_id');
            $table->uuid('user_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('type_id')->references('id')->on('configurations');
            $table->foreign('category_id')->references('id')->on('configurations');
            $table->foreign('approver_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('configurations');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposals');
    }
};
