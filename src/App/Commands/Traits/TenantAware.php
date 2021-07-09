<?php

namespace Asseco\Multitenancy\App\Commands\Traits;

use Asseco\Multitenancy\App\Models\Tenant;
use Asseco\Multitenancy\App\Traits\UsesTenantModel;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    protected string $tenantDescription = 'Specify tenant ID for running the command for a single tenant';
    protected string $landlordDescription = 'Run landlord migrations';

    /**
     * Laravel method override to add tenant argument to command definition.
     *
     * @return void
     */
    protected function configureUsingFluentDefinition()
    {
        $this->signature .= "\n{--tenant=* : {$this->tenantDescription}}";
        $this->signature .= "\n{--landlord : {$this->landlordDescription}}";

        parent::configureUsingFluentDefinition();
    }

    /**
     * When signature isn't used, we need to provide options through this method.
     *
     * @return array[]
     */
    protected function getOptions()
    {
        return array_merge(
            parent::getOptions(),
            [
                ['tenant', null, InputOption::VALUE_OPTIONAL, $this->tenantDescription],
                ['landlord', null, InputOption::VALUE_NONE, $this->landlordDescription],
            ]
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $landlord = $this->option('landlord');

        if ($landlord) {
            return $this->executeLandlordCommand($input, $output);
        }

        return $this->executeTenantCommand();
    }

    protected function executeLandlordCommand(InputInterface $input, OutputInterface $output)
    {
        $this->line('');
        $this->info('Running landlord command...');
        $this->line('---------------------------');

        if ($this->hasOption('database')) {
            $this->input->setOption('database', config('database.landlord-default'));
        }

        if ($this->hasOption('path')) {
            $this->input->setOption('path', 'database/migrations/landlord');
        }

        return parent::execute($input, $output);
    }

    protected function executeTenantCommand()
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
