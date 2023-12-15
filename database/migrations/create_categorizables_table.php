<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-categories.database.prefix');
        $table_name = $prefix . '_categorizables';

        Schema::create($table_name, function (Blueprint $table) use ($prefix) {

            $table->foreignId('category_id')
                ->references('id')
                ->on($prefix .'_categories')
                ->cascadeOnDelete();


            $table->morphs('categorizable');

        });
    }
};
