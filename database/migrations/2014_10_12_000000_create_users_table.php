<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // App\Model\Country
        if (!Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('code')->nullable();
                $table->string('code_iso')->nullable();
                $table->string('prefix')->nullable(); // country code
                $table->string('postfix')->nullable(); // national code
                $table->boolean('is_active');
            });
        }

        // App\Model\City
        if (!Schema::hasTable('cities')) {
            Schema::create('cities', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('short_name')->nullable();
                $table->string('region')->nullable();
                $table->boolean('is_active');
            });
        }

        // App\Model\User
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->unique()->nullable();
                $table->string('password')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('full_name')->nullable();
                $table->date('birthday')->nullable();
                $table->enum('gender', ['MALE', 'FEMALE'])->nullable();
                $table->string('facebook_id')->nullable();
                $table->boolean('can_sell')->nullable();
                $table->integer('total_orders')->unsigned();
                $table->integer('total_spent')->unsigned();

                // foreign
                $table->integer('country_id')->unsigned()->nullable();

                $table->rememberToken();
                $table->timestamps();

                $table->foreign('country_id')->references('id')->on('countries');
            });
        }

        // App\Model\UserIdentifier - UUID
        if (!Schema::hasTable('user_identifiers')) {
            Schema::create('user_identifiers', function (Blueprint $table) {
                $table->increments('id');
                $table->string('uuid')->nullable();

                $table->integer('user_id')->unsigned();

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        // App\Model\UserIdentifierAccessToken
        if (!Schema::hasTable('user_identifier_access_token')) {
            Schema::create('user_identifier_access_token', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_identifier_id')->unsigned();
                $table->longText('access_token')->nullable();
                $table->boolean('is_valid')->nullable();
                $table->timestamps();

                $table->foreign('user_identifier_id')->references('id')->on('user_identifiers');
            });
        }

        // App\Model\UserIdentifierDeviceToken
        if (!Schema::hasTable('user_identifier_device_token')) {
            Schema::create('user_identifier_device_token', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_identifier_id')->unsigned();
                $table->string('device_token')->nullable();
                $table->string('platform')->nullable();
                $table->string('platform_version')->nullable();
                $table->timestamps();

                $table->foreign('user_identifier_id')->references('id')->on('user_identifiers');
            });
        }

        // App\Model\Activation
        if (!Schema::hasTable('activations')) {
            Schema::create('activations', function (Blueprint $table) {
                $table->increments('id');
                $table->string('token')->unique();
                $table->enum('form', [  'REG_EMAIL',
                                        'REG_PHONE',
                                        'FORGOT_EMAIL',
                                        'FORGOT_PHONE',
                                        'CHANGE_EMAIL',
                                        'CHANGE_PHONE',
                                        'SET_EMAIL',
                                        'SET_PHONE'])->nullable();
                $table->string('code')->nullable();
                $table->boolean('is_expired')->nullable();

                $table->integer('user_id')->unsigned();

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        // App\Model\Photo
        if (!Schema::hasTable('photos')) {
            Schema::create('photos', function (Blueprint $table) {
                $table->increments('id');
                $table->string('filename')->nullable();
                $table->string('original_path')->nullable();
                $table->string('full_path', 1024)->nullable();
                $table->string('resized')->nullable();
                $table->string('thumbnail')->nullable();
                $table->integer('user_id')->unsigned();

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        // App\Model\Payment (CASH, VISA, MASTERCARD)
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('short_name')->nullable();
                $table->string('desc')->nullable();
                $table->timestamps();
            });
        }

        // App\Model\Volume (Liters, Kilograms, Sacks, Head)
        if (!Schema::hasTable('volumes')) {
            Schema::create('volumes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('short_name')->nullable();
                $table->string('desc')->nullable();
                $table->timestamps();
            });
        }

        // App\Model\Product (Apple, Orange, Banana, Mango)
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('short_name')->nullable();
                $table->string('desc')->nullable();
                $table->timestamps();
            });
        }

        // App\Model\Good (Milk, Pig, Tomato, Rice)
        if (!Schema::hasTable('goods')) {
            Schema::create('goods', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('short_name')->nullable();
                $table->string('desc')->nullable();
                $table->integer('hits')->unsigned();
                $table->timestamps();
            });
        }

        // App\Model\Order
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->increments('id');
                $table->string('google_place_id')->nullable();
                $table->decimal('google_latitude', 13, 10)->nullable();
                $table->decimal('google_longitude', 13, 10)->nullable();
                $table->string('address')->nullable();
                $table->decimal('quantity', 20, 5)->nullable();
                $table->enum('schedule', ['ONCE', 'DAILY', 'WEEKLY', 'MONTHLY'])->nullable();
                $table->dateTime('once_date')->nullable();
                $table->dateTime('daily_start_date')->nullable();
                $table->dateTime('daily_end_date')->nullable();
                $table->dateTime('weekly_start_date')->nullable();
                $table->dateTime('weekly_end_date')->nullable();
                $table->integer('weekly_day')->nullable();
                $table->integer('monthly_start')->nullable();
                $table->integer('monthly_end')->nullable();
                $table->integer('monthly_days_of_month')->nullable();
                $table->decimal('charge_amount', 20, 5)->nullable();
                $table->dateTime('order_end_date')->nullable();

                // foreign
                $table->integer('user_id')->unsigned()->nullable();
                $table->integer('product_id')->unsigned()->nullable();
                $table->integer('country_id')->unsigned()->nullable();
                $table->integer('city_id')->unsigned()->nullable();
                $table->integer('volume_id')->unsigned()->nullable();
                $table->integer('payment_id')->unsigned()->nullable();

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('product_id')->references('id')->on('products');
                $table->foreign('country_id')->references('id')->on('countries');
                $table->foreign('city_id')->references('id')->on('cities');
                $table->foreign('volume_id')->references('id')->on('volumes');
                $table->foreign('payment_id')->references('id')->on('payments');
            });
        }

        // App\Model\MarketTransaction
        if (!Schema::hasTable('market_transactions')) {
            Schema::create('market_transactions', function (Blueprint $table) {
                $table->increments('id');
                $table->decimal('amount_charged', 20, 5)->nullable();
                $table->dateTime('transaction_date')->nullable();

                // foreign
                $table->integer('order_id')->unsigned()->nullable();

                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders');
            });
        }

        // App\Model\SellingTransaction
        if (!Schema::hasTable('selling_transactions')) {
            Schema::create('selling_transactions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('quantity')->unsigned();
                $table->boolean('is_available')->nullable();
                $table->decimal('price', 20, 5)->nullable();
                $table->longText('product_desc')->nullable();

                // foreign
                $table->integer('user_id')->unsigned()->nullable();
                $table->integer('good_id')->unsigned()->nullable();
                $table->integer('volume_id')->unsigned()->nullable();
                $table->integer('photo_id')->unsigned()->nullable();
                $table->integer('price_volume_id')->unsigned()->nullable();

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('good_id')->references('id')->on('goods');
                $table->foreign('volume_id')->references('id')->on('volumes');
                $table->foreign('photo_id')->references('id')->on('photos');
                $table->foreign('price_volume_id')->references('id')->on('volumes');
            });
        }

        // App\Model\BuyingTransaction
        if (!Schema::hasTable('buying_transactions')) {
            Schema::create('buying_transactions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('quantity')->unsigned();
                $table->boolean('is_available')->nullable();
                $table->decimal('price', 20, 5)->nullable();
                $table->longText('product_desc')->nullable();

                // foreign
                $table->integer('user_id')->unsigned()->nullable();
                $table->integer('good_id')->unsigned()->nullable();
                $table->integer('volume_id')->unsigned()->nullable();
                $table->integer('photo_id')->unsigned()->nullable();
                $table->integer('price_volume_id')->unsigned()->nullable();

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('good_id')->references('id')->on('goods');
                $table->foreign('volume_id')->references('id')->on('volumes');
                $table->foreign('photo_id')->references('id')->on('photos');
                $table->foreign('price_volume_id')->references('id')->on('volumes');
            });
        }

        // App\Model\Users
        if (!Schema::hasTable('selling_order_forms')) {
            Schema::create('selling_order_forms', function (Blueprint $table) {
                $table->integer('buyer_user_id')->unsigned();
                $table->integer('seller_user_id')->unsigned();
                $table->integer('transaction_id')->unsigned();

                $table->foreign('buyer_user_id')->references('id')->on('users');
                $table->foreign('seller_user_id')->references('id')->on('users');
                $table->foreign('transaction_id')->references('id')->on('selling_transactions');

                $table->primary(['buyer_user_id', 'seller_user_id']);
            });
        }

        // App\Model\Users
        if (!Schema::hasTable('buying_order_forms')) {
            Schema::create('buying_order_forms', function (Blueprint $table) {
                $table->integer('buyer_user_id')->unsigned();
                $table->integer('seller_user_id')->unsigned();
                $table->integer('transaction_id')->unsigned();

                $table->foreign('buyer_user_id')->references('id')->on('users');
                $table->foreign('seller_user_id')->references('id')->on('users');
                $table->foreign('transaction_id')->references('id')->on('buying_transactions');

                $table->primary(['buyer_user_id', 'seller_user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
