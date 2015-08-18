<?php

namespace CodeProject\Providers;

use Illuminate\Support\ServiceProvider;

class CodeProjectRepositoryProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \CodeProject\Repositories\ClientRepositoryInterface::class,
            \CodeProject\Repositories\ClientRepositoryEloquent::class
        );

        $this->app->bind(
            \CodeProject\Repositories\UserRepositoryInterface::class,
            \CodeProject\Repositories\UserRepositoryEloquent::class
        );

        $this->app->bind(
            \CodeProject\Repositories\ProjectRepositoryInterface::class,
            \CodeProject\Repositories\ProjectRepositoryEloquent::class
        );

        $this->app->bind(
            \CodeProject\Repositories\ProjectNoteRepositoryInterface::class,
            \CodeProject\Repositories\ProjectNoteRepositoryEloquent::class
        );

        $this->app->bind(
            \CodeProject\Repositories\ProjectTaskRepositoryInterface::class,
            \CodeProject\Repositories\ProjectTaskRepositoryEloquent::class
        );

        $this->app->bind(
            \CodeProject\Repositories\ProjectMemberRepositoryInterface::class,
            \CodeProject\Repositories\ProjectMemberRepositoryEloquent::class
        );

        $this->app->bind(
            \CodeProject\Repositories\ProjectFileRepositoryInterface::class,
            \CodeProject\Repositories\ProjectFileRepositoryEloquent::class
        );

    }
}
