<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('user_proxies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proxy_id')->unsigned()->constrained('users')->onDelete('cascade');
            $table->foreignId('target_user_id')->unsigned()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('user_proxies');
    }
};
