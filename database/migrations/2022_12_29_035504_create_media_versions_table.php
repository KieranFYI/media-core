<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_versions', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('name');
            $table->string('storage')
                ->index();
            $table->foreignId('media_id')
                ->nullable()
                ->constrained('media')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('file_hash')
                ->index()
                ->nullable();
            $table->string('content_type')
                ->index();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
};
