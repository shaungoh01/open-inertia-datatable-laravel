<?php

namespace Shaun\OpenInertiaDatatableLaravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class DatatableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('datatable', function (Array $relations = []) {
            $model = $this->getModel();
            $dt = new Datatable($model);
            return $dt->datatableQuery($this,$relations);
        });

        $this->publishes([
            __DIR__ . '/../Vue/' => resource_path('js/InertiaDatatable')
        ], 'datatable-vuejs');
    }
}
