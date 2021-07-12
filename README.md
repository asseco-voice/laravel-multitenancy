<p align="center"><a href="https://see.asseco.com" target="_blank"><img src="https://github.com/asseco-voice/art/blob/main/evil_logo.png" width="500"></a></p>

# Multitenancy

Purpose of this repository is to enable multitenancy with multiple DB setup.

# Installation

Require the package with ``composer require asseco-voice/laravel-multitenancy``.
Service provider will be registered automatically.

# Setup

## DB setup

Since this is a multi-DB tenancy package be sure to provide separate env values for landlord
connection as well as connection for tenants. 

Landlord is a separate DB connection which holds all shared migrations. The main one given through the
package is ``tenants`` table which will hold configuration for all the tenants in the system.
Add Landlord env variables (format is the same as native DB connection):

```
LANDLORD_CONNECTION=
LANDLORD_HOST=
LANDLORD_PORT=
LANDLORD_DATABASE=
LANDLORD_USERNAME=
LANDLORD_PASSWORD=
``` 

Use native DB connection to provide connection for tenants but comment out ``DB_DATABASE`` as 
this will be dynamically switched during runtime.

```
DB_CONNECTION=
DB_HOST=
DB_PORT=
# DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

This means that all the tenants will be on a single connection but on separate databases.
If you want to provide custom connection for some (or all) tenants, you can do so by providing
that config within a DB table. More on that [later](#separate-tenant-connections). 

## Tenant aware commands

Some native Artisan commands are modified to provide two additional options:

- ``--tenant`` - where you can provide a tenant ID for command to run on (i.e. `--tenant=1`).
If you omit this argument, the command will run for **all** tenants. 
- ``--landlord`` - will assume to run on landlord connection.

Commands currently available are:

- ``migrate``
- ``db:seed``
- ``db:wipe``

## Migrations

Publish the migration by running:

`php artisan vendor:publish --tag="asseco-multitenancy-migrations"`

This will create a single migration within a ``migrations/landlord`` directory. Landlord
represents a shared set of migrations which should be accessible independently of a chosen
Tenant. 

The default migration (`tenants` table) should hold all data related to tenants in the system.
You are free to add other migrations there which would represent a shared set of tables across
all tenants.

Migrate landlord files by running ``php artisan migrate --landlord``

Once you have a ``tenants`` table, you can add a new tenant by manually filling out the DB or
using Tinker (TODO: API + auto create).

## Separate tenant connections

To provide a tenant with a completely custom DB connection edit its configuration in DB by filling
out all the `db_` values in the ``tenants`` table. If one of the values is missing from the configuration,
default will be used. Rest of the values will in that case be ignored. 

## Extending the package

Publish the configuration file by running:

`php artisan vendor:publish --tag="asseco-multitenancy-config"`

Extensible parts:

- ``tenant_resolver`` is the class responsible for figuring out who the current
tenant is. Default resolution is by a domain. You can create a new class implementing
``TenantResolver`` interface and use it instead of the default one. 

- ``tenant_model`` can be overridden, just be sure to extend the default `Tenant` model
from the package.  

- ``switch_tenant_tasks`` is an array of classes implementing `SwitchTenantTask` interface
which are being ran when tenant is switched. 

# Final notes

Due to our specific use cases, this package was created as a merge of these
2 amazing multi tenancy packages:

- [Spatie multi-tenancy](https://spatie.be/docs/laravel-multitenancy/v2/introduction)

- [Archtechx tenancy](https://tenancyforlaravel.com/)
