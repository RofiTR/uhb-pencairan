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
        Schema::create('proposal_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('proposal_id');
            $table->uuid('type_id');
            $table->boolean('sppd');
            $table->uuid('category_id');
            $table->double('withdrawal', 15, 2);
            $table->double('realization', 15, 2);
            $table->double('saldo', 15, 2);
            $table->string('sign', 1)->nullable();
            $table->string('description');
            $table->uuid('approver_id')->nullable();
            $table->uuid('status_id');
            $table->uuid('user_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('proposal_reports');
    }
};
