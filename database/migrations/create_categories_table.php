<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-categories.database.prefix');
        $table_name = $prefix . '_' . config('filament-categories.database.categories_table');

        Schema::create($table_name, function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->text('description');
            $table->text('content');

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
