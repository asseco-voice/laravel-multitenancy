<?php

declare(strict_types=1);

namespace Asseco\Multitenancy;

use Asseco\Multitenancy\Actions\ForgetCurrentTenant;
use Asseco\Multitenancy\Actions\MakeTenantCurrent;
use Asseco\Multitenancy\Http\Middleware\NeedsTenant;
use Asseco\Multitenancy\Tasks\TasksCollection;
use Asseco\Multitenancy\TenantResolvers\TenantResolver;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class MultitenancyServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/asseco-multitenancy.php', 'asseco-multitenancy');
        $this->mergeConfigFrom(__DIR__ . '/../config/landlord-database.php', 'landlord-database');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishableFiles();
        $this->bindClasses();

        if (!$this->app->runningInConsole()) {
            $this->determineCurrentTenant();
        }

        $this->prependMiddleware();
    }

    protected function publishableFiles(): void
    {
        $this->publishes([
            __DIR__ . '/../config/asseco-multitenancy.php' => config_path('asseco-multitenancy.php'),
            __DIR__ . '/../config/landlord-database.php'   => config_path('landlord-database.php'),
        ], 'asseco-multitenancy-config');

        if (!class_exists('CreateLandlordTenantsTable')) {
            $this->publishes([
                __DIR__ . '/../migrations/landlord/create_landlord_tenants_table.php.stub' => database_path('migrations/landlord/' . date('Y_m_d_His', time()) . '_create_landlord_tenants_table.php'),
            ], 'migrations');
        }
    }

    protected function bindClasses(): void
    {
        $this->app->bind(TenantResolver::class, config('asseco-multitenancy.tenant_resolver'));

        $this->app->singleton(TasksCollection::class, function () {
            $tasks = config('asseco-multitenancy.switch_tenant_tasks');

            return new TasksCollection($tasks);
        });

        $this->app->bind('make-tenant-current', MakeTenantCurrent::class);
        $this->app->bind('forget-current-tenant', ForgetCurrentTenant::class);
    }

    protected function determineCurrentTenant(): void
    {
        /** @var TenantResolver $tenantResolver */
        $tenantResolver = app(TenantResolver::class);

        $tenant = $tenantResolver->resolve(request());

        optional($tenant)->makeCurrent();
    }

    protected function prependMiddleware(): void
    {
        $router = app(Router::class);

        $router->prependMiddlewareToGroup('api', NeedsTenant::class);
    }
}
