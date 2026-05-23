<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('business_name');
            $table->string('owner_name');
            $table->string('phone')->index();
            $table->string('email')->nullable();
            $table->string('city')->index();
            $table->text('address')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_last_four')->nullable();
            $table->string('status')->default('pending')->index();
            $table->text('rejection_reason')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('blocked_at')->nullable();
            $table->timestamps();
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->index(['vendor_id', 'status']);
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('venue_images', function (Blueprint $table) {
            $table->string('media_type')->default('image')->after('path');
            $table->string('video_url')->nullable()->after('media_type');
        });

        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->string('status')->default('pending')->index();
            $table->dateTime('available_at')->nullable();
            $table->timestamps();
            $table->unique(['vendor_id', 'booking_id']);
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->index();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('posted')->index();
            $table->string('reference')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('notification_id')->nullable()->constrained('notifications')->nullOnDelete();
            $table->string('channel')->default('web')->index();
            $table->string('delivery_status')->default('queued')->index();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('earnings');

        Schema::table('venue_images', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'video_url']);
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
        });
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
        });
        Schema::table('venues', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
        });

        Schema::dropIfExists('vendors');
    }
};
