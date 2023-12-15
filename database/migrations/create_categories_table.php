<?php

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-categories.database.prefix');
        $table_name = $prefix . '_categories';

        Schema::create($table_name, function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->text('description');
            $table->text('content');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table($table_name, function (Blueprint $table) use ($table_name) {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('content')
                ->references('id')
                ->on($table_name)
                ->nullOnDelete();
        });
    }
};
