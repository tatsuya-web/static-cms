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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            /*
                path string
                method string
                ip_address string
                user_agent string
                request_header text
                request_body text
                response status integer
                user_id bigint [ref: > users.id]
            */
            $table->string('path')->comment('リクエストパス');
            $table->string('method')->comment('リクエストメソッド');
            $table->string('ip_address')->comment('IPアドレス');
            $table->string('user_agent')->comment('ユーザーエージェント');
            $table->text('request_header')->comment('リクエストヘッダー');
            $table->text('request_body')->comment('リクエストボディ');
            $table->integer('response_status')->comment('レスポンスステータス');
            $table->foreignId('user_id')->nullable()->constrained('users')->comment('ユーザーID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
