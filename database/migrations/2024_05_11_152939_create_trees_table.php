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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            /*
                name string
                type string [note: 'file/folder']
                status string [note: 'publish/draft']
                create_user_id bigint [ref: > users.id]
                parent_id bigint [ref: > trees.id]
                template_id bigint [ref: > templates.id]
            */
            $table->string('name')->comment('ファイル/フォルダ名');
            $table->string('type')->comment('コンテンツタイプ');
            $table->string('status')->comment('公開状態');
            $table->foreignId('user_id')->constrained('users')->comment('作成ユーザーID');
            $table->foreignId('parent_id')->nullable()->constrained('trees')->comment('親ID');
            $table->foreignId('template_id')->nullable()->constrained('templates')->comment('テンプレートID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};
