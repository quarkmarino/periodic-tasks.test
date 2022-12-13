<?php

use App\Data\Enums\TimeScaleEnum;
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
        Schema::create('month_schedules', function (Blueprint $table) {
            $table->id();

            $table->json('nth_week_day')->nullable()->default(null);

            $table->integer('month_day')->nullable()->default(null);

            $table->string('month')->nullable()->default(null);

            $table->foreignId('task_id');

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
        Schema::dropIfExists('month_schedules');
    }
};
