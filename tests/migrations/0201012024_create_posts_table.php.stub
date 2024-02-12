<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-news.database.prefix');
        $table_name = $prefix . '_posts';

        Schema::create(
            $table_name,
            function (Blueprint $table) use ($prefix) {
                $table->id();

                if (config('filament-news.use_main_category', true)) {
                    $table->unsignedBigInteger('category_id')
                        ->nullable();

                    $table->foreign('category_id')
                        ->references('id')
                        ->on($prefix . '_categories')
                        ->cascadeOnDelete();
                }

                $table->string('title');

                $table->date('date')
                    ->nullable();

                $table->text('summary')
                    ->nullable();

                $table->text('content')
                    ->nullable();

                $table->timestamps();
                $table->softDeletes();
            }
        );
    }
};
