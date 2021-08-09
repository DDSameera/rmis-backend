<?php


namespace App\Traits;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

trait InstallationTrait
{
    public function runComposerCommands(): bool
    {

        shell_exec('composer install');
        return true;
    }

    public function runServer(): bool
    {

        $output = new ConsoleOutput;
        $serverIp = config('app.url');

        $output->writeln("RMIS Backend API server started: Please COPY this Address & Paste it on Postman or Insomnia <$serverIp>");

        Artisan::call('serve');
        Artisan::output();

        return true;
    }

    public function runDBMigration(): bool
    {
        Artisan::call('migrate:fresh', ['--seed' => true]);
        echo Artisan::output();

        return true;

    }

    public function cloneEnvFile(): bool
    {


        if (!file_exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->optimize();
            return true;
        }

        return false;
    }


    public function setupAppEnvironment(): bool
    {
        $environmentInput = $this->choice(
            'Choose your Application environment?',
            ['production', 'local'],
            'local'
        );

        $this->modifyEnvFile('API_ENV=' . env('API_ENV'), 'API_ENV=' . $environmentInput);
        return true;
    }

    public function setupAPIConfig(): bool
    {

        if ($this->confirm('Do you wish to Change Default API Settings ?  ', false)) {
            $this->info('******** API Configuration ********');

            $apiRateLimit = $this->ask('Enter value of API Rate Limit ',100);
            $this->modifyEnvFile('API_RATE_LIMIT=' . env('API_RATE_LIMIT'), 'API_RATE_LIMIT=' . $apiRateLimit);

            $apiMaxAttempts = $this->ask('Enter value of  API Max Login Attempts ?','10');
            $this->modifyEnvFile('API_MAX_LOGIN_ATTEMPTS=' . env('API_MAX_LOGIN_ATTEMPTS'), 'API_MAX_LOGIN_ATTEMPTS=' . $apiMaxAttempts);

            $apiMaxLoginDelay = $this->ask('Enter value of API Max Login Delay',1);
            $this->modifyEnvFile('API_MAX_LOGIN_DELAY=' . env('API_MAX_LOGIN_DELAY'), 'API_MAX_LOGIN_DELAY=' . $apiMaxLoginDelay);

            $apiTokenExpire = $this->ask('Enter value of API Token Expire?',45);
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

        return true;
    }

    public function setupDBConfig(): bool
    {


        $dbName = Command::ask('Enter Value of Database Name', 'rmis');
        $this->modifyEnvFile('DB_DATABASE=' . env('DB_DATABASE'), 'DB_DATABASE=' . $dbName);


        $dbUsername = $this->ask('Enter Value of Database Username ','root');
        $this->modifyEnvFile('DB_USERNAME=' . env('DB_USERNAME'), 'DB_USERNAME=' . $dbUsername);


        if ($this->confirm('Do you like to set "empty" Database Password ? ', true)) {
            $this->modifyEnvFile('DB_PASSWORD=' . env('DB_PASSWORD'), 'DB_PASSWORD=');

        } else {
            $dbPassword = $this->ask('Enter Value of Database Password ',null);
            $this->modifyEnvFile('DB_PASSWORD=' . env('DB_PASSWORD'), 'DB_PASSWORD=' . $dbPassword);

        }
        return true;
    }


    public function modifyEnvFile($searchStr, $replaceStr): bool
    {

        $path = base_path('.env');

        if (file_exists($path)) {
            $replace = file_put_contents($path, str_replace(
                $searchStr, $replaceStr, file_get_contents($path)
            ));
            if (!$replace) {
                return false;
            }

        }

        return true;
    }

    public function optimize(): bool
    {

        Artisan::call('config:cache');
        echo Artisan::output();

        Artisan::call('config:clear');
        echo Artisan::output();

        Artisan::call('optimize');
        echo Artisan::output();

        Artisan::call('route:clear');
        echo Artisan::output();

        Artisan::call('route:cache');
        echo Artisan::output();

        return true;

    }

    public function appKeyGenerate(): bool
    {

        Artisan::call('key:generate', ['--force' => true]);
        echo Artisan::output();

        return true;
    }
}