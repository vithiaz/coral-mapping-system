<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('corals', function (Blueprint $table) {
            $table->id();
            $table->string('condition');
            $table->integer('radius');
            $table->double('long');
            $table->double('lat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corals');
    }
};
