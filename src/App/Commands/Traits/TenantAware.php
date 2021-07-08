<?php

namespace Asseco\Multitenancy\App\Commands\Traits;

use Asseco\Multitenancy\App\Models\Tenant;
use Asseco\Multitenancy\App\Traits\UsesTenantModel;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Trait for Artisan Commands only.
 *
 * It is impossible to extend or override Laravel Command class so that it
 * works natively, so this is a way to provide extra functionality to
 * existing commands.
 *
 * Trait TenantAware
 */
trait TenantAware
{
    use UsesTenantModel;

    /**
     * Laravel method override to add tenant argument to command definition.
     *
     * @return void
     */
    protected function configureUsingFluentDefinition()
    {
        $this->signature .= "\n{--tenant=* : Specify tenant ID for running the command for a single tenant}";

        parent::configureUsingFluentDefinition();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tenants = Arr::wrap($this->option('tenant'));

        $tenantQuery = $this->getTenantModel()::query()
            ->when(!blank($tenants), function ($query) use ($tenants) {
                $query->orWhereIn('id', Arr::wrap($tenants));
            });

        if ($tenantQuery->count() === 0) {
            $this->error('No tenant(s) found.');

            return -1;
        }

        return $tenantQuery
            ->cursor()
            ->map(function (Tenant $tenant) {
                $this->line('');
                $this->info("Running command for tenant `{$tenant->name}` (id: {$tenant->getKey()})...");
                $this->line('---------------------------------------------------------');

                $tenant->execute(fn () => (int) $this->laravel->call([$this, 'handle']));
            })
            ->sum();
    }
}
