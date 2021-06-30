<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Models;

use Asseco\Multitenancy\App\Facades\ForgetCurrentTenant;
use Asseco\Multitenancy\App\Facades\MakeTenantCurrent;
use Asseco\Multitenancy\App\Traits\HasDbConnections;
use Asseco\Multitenancy\Database\Factories\TenantFactory;
use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
    use HasDbConnections;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected static function newFactory()
    {
        return TenantFactory::new();
    }

    public function getConnectionName()
    {
        return $this->getLandlordDbConnection();
    }

    public function makeCurrent(): self
    {
        if ($this->isCurrent()) {
            return $this;
        }

        static::forgetCurrent();

        MakeTenantCurrent::execute($this);

        return $this;
    }

    public function isCurrent(): bool
    {
        return optional(static::current())->id === $this->id;
    }

    public static function current(): ?self
    {
        $containerKey = config('asseco-multitenancy.current_tenant_container_key');

        if (!app()->has($containerKey)) {
            return null;
        }

        return app($containerKey);
    }

    public static function forgetCurrent(): ?Tenant
    {
        $currentTenant = static::current();

        if (is_null($currentTenant)) {
            return null;
        }

        ForgetCurrentTenant::execute($currentTenant);

        return $currentTenant;
    }

    public static function checkCurrent(): bool
    {
        return static::current() !== null;
    }

    public function execute(callable $callable)
    {
        $originalCurrentTenant = Tenant::current();

        $this->makeCurrent();

        return tap($callable($this), static function () use ($originalCurrentTenant) {
            $originalCurrentTenant
                ? $originalCurrentTenant->makeCurrent()
                : Tenant::forgetCurrent();
        });
    }

    public function callback(callable $callable): Closure
    {
        return fn () => $this->execute($callable);
    }
}
