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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->onDelete('cascade');
            
            $table->enum('role', ['admin', 'member']);
            $table->uuid('token')->unique();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();

            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->timestamps();
            // Prevent duplicate active invites
            $table->unique(['email', 'company_id', 'accepted_at']);
            $table->index(['email', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('invitations')) {
           Schema::disableForeignKeyConstraints(); 
            Schema::dropIfExists('invitations');
            Schema::enableForeignKeyConstraints();
        }
    }
};
