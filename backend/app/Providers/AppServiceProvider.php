<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Wallet;
use App\Policies\OrderPolicy;
use App\Policies\WalletPolicy;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\AuthenticationService;
use App\Services\Interfaces\AuthenticationServiceInterface;
use App\Services\Interfaces\OrderServiceInterface;
use App\Services\Order\Commission\CommissionStrategy;
use App\Services\Order\Commission\DefaultCommissionStrategy;
use App\Services\Order\OrderService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(AuthenticationServiceInterface::class, AuthenticationService::class);
        $this->app->bind(CommissionStrategy::class, DefaultCommissionStrategy::class);
        $this->app->bind (WalletRepositoryInterface::class, WalletRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Wallet::class,WalletPolicy::class);
        Gate::policy(Order::class,OrderPolicy::class);
    }
}
