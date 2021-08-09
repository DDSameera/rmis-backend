<?php

namespace App\Console\Commands;


use App\Traits\InstallationTrait;
use Illuminate\Console\Command;
use File;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

class InstallCommand extends Command
{

   use InstallationTrait;

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
    protected $description = 'InstallCommand RMIS';

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

        //Welcome
        $this->info('******** Welcome to the RMIS Command line Installation Wizard ********');

        //1.Optimize
        $this->info('******** Optimization Process [1/10] *******');
        $this->optimize();

        //2. Clone ENV File
        $this->info('******** ENV File Cloned  [2/10] *******');
        $this->cloneEnvFile();

        //3. Setup DB Config
        $this->info('******** DB Configuration [3/10] ********');
        $this->setupDBConfig();

        //4. Setup API Config
        $this->info('******** API Settings [4/10] ********');
        $this->setupAPIConfig();

        //5. Setup Environment
        $this->info('******** App Environment [5/10] *******');
        $this->setupAppEnvironment();


        //6. APP Key Generate
        $this->info('******** Secure App Key Generated [6/10]*******');
        $this->appKeyGenerate();




        //7.Optimize
        $this->info('******** Optimization Process [8/10] *******');
        $this->optimize();


        //8.DB Migration & Seed
        $this->info('******** DB Migration & Seed Run [9/10]*******');
        $this->runDBMigration();


        //9.Run Server
        $this->info('******** All Process Completed [10/10] .Server is running *******');
        $this->runServer();

    }



}
