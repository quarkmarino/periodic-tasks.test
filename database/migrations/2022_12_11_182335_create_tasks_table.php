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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->text('description');

            $table->integer('every')->default(1);
            $table->string('scale')->nullable()->default(TimeScaleEnum::DAY_SCALE->value);
            $table->integer('times')->nullable()->default(null);

            $table->date('starts_at');
            $table->date('ends_at')->nullable()->default(null);

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
        Schema::dropIfExists('tasks');
    }
};
