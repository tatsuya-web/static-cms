<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            /*
                type string [note: 'cmn/page']
                name string
                show_name string
                single_value_name string
                multi_value_name string
                description string
                create_user_id bigint [ref: > users.id]
            */
            $table->string('type')->comment('テンプレートタイプ');
            $table->string('name')->comment('テンプレート名');
            $table->string('show_name')->comment('表示名');
            $table->string('single_value_name')->comment('単一値名');
            $table->string('multi_value_name')->comment('複数値名');
            $table->string('description')->comment('説明');
            $table->foreignId('user_id')->constrained('users')->comment('作成ユーザーID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
