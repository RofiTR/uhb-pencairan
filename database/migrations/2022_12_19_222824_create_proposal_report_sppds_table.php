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
        Schema::create('proposal_report_sppds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('proposal_report_id');
            $table->string('destination')->nullable();
            $table->datetime('departure')->nullable();
            $table->datetime('arrive')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('proposal_report_id')->references('id')->on('proposal_reports')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_report_sppds');
    }
};
