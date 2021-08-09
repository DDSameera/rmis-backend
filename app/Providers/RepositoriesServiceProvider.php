<?php

namespace App\Providers;


use App\Repositories\InstallationWizard\InstallationWizardInterface;
use App\Repositories\InstallationWizard\InstallationWizardRepository;
use App\Repositories\OnboardProcess\OnboardProcessInterface;
use App\Repositories\OnboardProcess\OnboardProcessRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
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
        //Bind UserRepository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        //Bind OnboardProcessRepository
        $this->app->bind(OnboardProcessInterface::class, OnboardProcessRepository::class);


    }
}
