<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmis:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install RMIS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //1.Optimize
        $this->optimize();

        //2. Clone ENV File
        $this->cloneEnvFile();

        //3. Setup DB CConfig
        $this->setupDBConfig();

        //4. Setup API Config
        $this->setupAPIConfig();

        //5. Setup Environment
        $this->setupAppEnvironment();


        //6. APP Key Generate
        $this->appKeyGenerate();

        //7.Optimize
        $this->optimize();

        return 1;

    }

    public function cloneEnvFile()
    {
        if (!file_exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->optimize();
        }
    }


    public function setupAppEnvironment()
    {
        $environmentInput = $this->choice(
            'Choose your Application environment?',
            ['production', 'local'],
            'local'
        );

        return $this->modifyEnvFile('APP_ENV=' . env('APP_ENV'), 'APP_ENV=' . $environmentInput);

    }

    public function setupAPIConfig()
    {
        if ($this->confirm('Do you wish to Change Default API Settings ?  ', false)) {
            $this->info('******** API Configuration ********');

            $apiRateLimit = $this->ask('Please specify API Rate Limit ?');
            $this->modifyEnvFile('API_RATE_LIMIT=' . env('API_RATE_LIMIT'), 'API_RATE_LIMIT=' . $apiRateLimit);

            $apiMaxAttempts = $this->ask('Please specify API Max Login Attempts ?');
            $this->modifyEnvFile('API_MAX_LOGIN_ATTEMPTS=' . env('API_MAX_LOGIN_ATTEMPTS'), 'API_MAX_LOGIN_ATTEMPTS=' . $apiMaxAttempts);

            $apiMaxLoginDelay = $this->ask('Please specify API Max Login Deplay ?');
            $this->modifyEnvFile('API_MAX_LOGIN_DELAY=' . env('API_MAX_LOGIN_DELAY'), 'API_MAX_LOGIN_DELAY=' . $apiMaxLoginDelay);

            $apiTokenExpire = $this->ask('Please specify API Token Expire?');
            $this->modifyEnvFile('API_TOKEN_EXPIRE=' . env('API_TOKEN_EXPIRE'), 'API_TOKEN_EXPIRE=' . $apiTokenExpire);


        } else {
            $apiRateLimit = 100;
            $apiMaxAttempts = 10;
            $apiMaxLoginDelay = 1;
            $apiTokenExpire = 45;

            $this->modifyEnvFile('API_RATE_LIMIT=' . env('API_RATE_LIMIT'), 'API_RATE_LIMIT=' . $apiRateLimit);
            $this->modifyEnvFile('API_MAX_LOGIN_ATTEMPTS=' . env('API_MAX_LOGIN_ATTEMPTS'), 'API_MAX_LOGIN_ATTEMPTS=' . $apiMaxAttempts);
            $this->modifyEnvFile('API_MAX_LOGIN_DELAY=' . env('API_MAX_LOGIN_DELAY'), 'API_MAX_LOGIN_DELAY=' . $apiMaxLoginDelay);
            $this->modifyEnvFile('API_TOKEN_EXPIRE=' . env('API_TOKEN_EXPIRE'), 'API_TOKEN_EXPIRE=' . $apiTokenExpire);

        }
    }

    public function setupDBConfig()
    {
        //Setup DB Configuration
        $this->info('******** DB Configuration ********');

        $dbName = $this->ask('Please specify DB Name ?');
        $this->modifyEnvFile('DB_DATABASE=' . env('DB_DATABASE'), 'DB_DATABASE=' . $dbName);


        $dbUsername = $this->ask('Please specify DB Username ?');
        $this->modifyEnvFile('DB_USERNAME=' . env('DB_USERNAME'), 'DB_USERNAME=' . $dbUsername);


        if ($this->confirm('Do you like to set "empty" DB Password for localhost?', true)) {
            $this->modifyEnvFile('DB_PASSWORD=' . env('DB_PASSWORD'), 'DB_PASSWORD=');

        } else {
            $dbPassword = $this->ask('Please specify DB Password ?');
            $this->modifyEnvFile('DB_PASSWORD=' . env('DB_PASSWORD'), 'DB_PASSWORD=' . $dbPassword);

        }
    }


    public function modifyEnvFile($searchStr, $replaceStr)
    {

        $path = base_path('.env');

        if (file_exists($path)) {
            $replace = file_put_contents($path, str_replace(
                $searchStr, $replaceStr, file_get_contents($path)
            ));
            return $replace;

        }

        return 0;
    }

    public function optimize()
    {
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        Artisan::call('optimize');
        Artisan::call('route:clear');
        Artisan::call('route:cache');

    }

    public function appKeyGenerate()
    {
        Artisan::call('key:generate', ['--force' => true]);
    }
}
