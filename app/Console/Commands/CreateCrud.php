<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crud {model} {--web} {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new crud files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = Str::studly(Str::singular($this->argument('model')));
        $type = ($this->option("web") ? 'web' : ($this->option('api') ? 'api' : null));
        if($this->option('web') && $this->option('api'))
            $type = null;
        return $this->crud($model, $type);
    }

    private function crud($modelName, $type)
    {
        $apiVersion = 1;
        if(! File::exists("app/Models/$modelName" . ".php")){
            $this->info("making model and migration ...");
            $this->call("make:model", [
                "name" => $modelName,
                "-m" => true,
            ]);
        }else{
            $this->info('model already exists');
        }
        $this->makeRepository($modelName);
        $this->makeControllerService($modelName, $type, $apiVersion);
        $this->makeResourceCollection($modelName, $apiVersion);

    }

    private function makeRepository($modelName){
        $this->info("making repository files");
        $path = "app/Repository/{$modelName}RepositoryInterface.php";
        $content = $this->generateFileContents($modelName)['repositoryInterface'];
        $this->makeFile($path, $content);
        $path = "app/Repository/Eloquent/{$modelName}Repository.php";
        $content = $this->generateFileContents($modelName)['repositoryFile'];
        $this->makeFile($path, $content);
    }

    private function makeControllerService($modelName, $type, $apiVersion){
        if(is_null($type) || $this->option('web')){
            $this->info("making dashboard controller and service ...");
            if(!File::exists("app/Http/Controllers/Dashboard/$modelName/$modelName" . "Controller.php")){
                $this->call("make:controller", [
                    "name" => "Dashboard/$modelName/$modelName" . "Controller",
                    "--resource" => true,
                ]);
            }else{
                $this->info("dashboard controller already exists");
            }
            $this->info("making dashboard service ...");
            $path = "app/Http/Services/Dashboard/$modelName/{$modelName}Service.php";
            $content = $this->generateFileContents($modelName)['dashboardService'];
            $this->makeFile($path, $content);
        }
        if(is_null($type) || $this->option('api')){
            $this->info("making api controller and service ...");
            if(! File::exists("app/Http/Controllers/Api/V$apiVersion/$modelName/$modelName" . "Controller.php")){
                $this->call("make:controller", [
                    "name" => "Api/V$apiVersion/$modelName/$modelName" . "Controller",
                    "--api" => true,
                ]);
            }else{
                $this->info("api controller already exists");
            }
            $platform = null;
            while(is_null($platform)){
                $platform = $this->choice("choose api platform",[
                    "website & mobile",
                    "one platform",
                ]);
                switch($platform){
                    case "website & mobile":
                        $this->info("making abstract service file ...");
                        $path = "app/Http/Services/Api/V$apiVersion/$modelName/{$modelName}Service.php";
                        $content = $this->generateFileContents($modelName, $apiVersion)['apiAbstractService'];
                        $this->makeFile($path, $content);
                        $this->info("making mobile platform service ...");
                        $path = "app/Http/Services/Api/V$apiVersion/$modelName/{$modelName}MobileService.php";
                        $content = $this->generateFileContents($modelName, $apiVersion)['mobileChildService'];
                        $this->makeFile($path, $content);
                        $this->info("making web platform service ...");
                        $path = "app/Http/Services/Api/V$apiVersion/$modelName/{$modelName}WebService.php";
                        $content = $this->generateFileContents($modelName, $apiVersion)['webChildService'];
                        $this->makeFile($path, $content);
                        break ;
                    case "one platform" :
                        $this->info("making api service ...");
                        $path = "app/Http/Services/Api/V$apiVersion/$modelName/{$modelName}Service.php";
                        $content = $this->generateFileContents($modelName, $apiVersion)['onePlatformService'];
                        $this->makeFile($path, $content);
                        break;
                    default :
                    $this->info('Incorrect choice');
                    $platform = null;
                }
            }
        }
    }

    private function makeResourceCollection($modelName, $apiVersion){
        $this->info('making resource file ...');
        if(! File::exists("app/Resources/V$apiVersion/$modelName/{$modelName}Resource.php")){
            $this->call("make:resource",["name" => "V$apiVersion/$modelName/{$modelName}Resource"]);
        }else{
            $this->info("resource already exists");
        }
        $this->info("check for paginator resource");
        $path = "app/Http/Resources/V$apiVersion/PaginatorResource.php";
        $content = $this->generateFileContents($modelName, $apiVersion)['paginatorResource'];
        $this->makeFile($path, $content);
        $this->info("making collection file ...");
        $path = "app/Http/Resources/V$apiVersion/$modelName/{$modelName}Collection.php";
        $content = $this->generateFileContents($modelName, $apiVersion)['collection'];
        $this->makeFile($path, $content);
    }

    private function makeFile($path, $content)
    {
        $directoryPath = dirname($path);
        if(! File::exists($directoryPath))
            File::makeDirectory($directoryPath, 0755, true);
        if(! File::exists($path))
            return File::put($path, $content);
        return $this->info("file already exists");

    }

    public function generateFileContents($modelName , $apiVersion = null)
    {
        return [
            "repositoryInterface" =>
            <<<EOT
            <?php

            namespace App\Repository;

            interface {$modelName}RepositoryInterface extends RepositoryInterface
            {
                // Interface methods
            }
            EOT,

            "repositoryFile" =>
            <<<EOT
            <?php

            namespace App\Repository\Eloquent;

            use App\Models\\{$modelName};
            use App\Repository\Eloquent\Repository;
            use App\Repository\\{$modelName}RepositoryInterface;
            use Illuminate\Database\Eloquent\Model;

            class {$modelName}Repository extends Repository implements {$modelName}RepositoryInterface
            {
                protected Model \$model;

                public function __construct({$modelName} \$model){
                    parent::__construct(\$model);
                }
            }
            EOT,
            "dashboardService" =>
            <<<ETO
            <?php

            namespace App\Http\Services\Dashboard\\{$modelName};

            use App\Http\Services\Mutual\FileManagerService;
            use App\Repository\\{$modelName}RepositoryInterface;
            use Illuminate\Support\Facades\DB;

            class {$modelName}Service
            {
                public function __construct(private readonly {$modelName}RepositoryInterface \$Repository,
                                            private readonly FileManagerService \$fileManagerService)
                {}


            }
            ETO,
            "apiAbstractService" =>
            <<<ETO
            <?php

            namespace App\Http\Services\Api\V$apiVersion\\{$modelName};

            use App\Http\Services\Mutual\FileManagerService;
            use App\Http\Services\PlatformService;
            use App\Http\Traits\Responser;
            use App\Repository\\{$modelName}RepositoryInterface;
            use Illuminate\Support\Facades\DB;

            abstract class {$modelName}Service extends PlatformService
            {
                use Responser;
                public function __construct(private  readonly {$modelName}RepositoryInterface \$repository,
                                            private readonly FileManagerService \$fileManagerService){}


            }
            ETO,

            "webChildService" =>
            <<<ETO
            <?php

            namespace App\Http\Services\Api\V$apiVersion\\{$modelName};

            use App\Http\Services\Api\V$apiVersion\\{$modelName}\\{$modelName}Service;

            class {$modelName}WebService extends {$modelName}Service
            {

                public static function platform(): string
                {
                    return 'web';
                }
            }

            ETO,
            "mobileChildService" =>
            <<<ETO
            <?php

            namespace App\Http\Services\Api\V$apiVersion\\{$modelName};

            use App\Http\Services\Api\V$apiVersion\\{$modelName}\\{$modelName}Service;

            class {$modelName}WebService extends {$modelName}Service
            {

                public static function platform(): string
                {
                    return 'mobile';
                }
            }

            ETO,

            "onePlatformService" =>
            <<<ETO
            <?php

            namespace App\Http\Services\Api\V$apiVersion\\{$modelName};

            use App\Http\Traits\Responser;
            use App\Repository\\{$modelName}RepositoryInterface;

            class {$modelName}Service
            {
                use Responser;
                public function __construct(private {$modelName}RepositoryInterface \$repository){}


            }
            ETO,
            "collection" =>
            <<<ETO
            <?php

            namespace App\Http\Resources\V{$apiVersion}\\{$modelName};

            use App\Http\Resources\V$apiVersion\PaginatorResource;
            use Illuminate\Http\Request;
            use Illuminate\Http\Resources\Json\ResourceCollection;

            class {$modelName}Collection extends ResourceCollection
            {
                public function toArray(Request \$request)
                {
                    return [
                        "parent" => {$modelName}Resource::collection(\$this->collection),
                        "pagination" => PaginatorResource::make(\$this),
                    ];
                }
            }
            ETO,
            "paginatorResource" =>
            <<<ETO
            <?php

            namespace App\Http\Resources\V$apiVersion;

            use Illuminate\Http\Request;
            use Illuminate\Http\Resources\Json\JsonResource;

            class PaginatorResource extends JsonResource
            {
                /**
                 * Transform the resource into an array.
                 *
                 * @return array<string, mixed>
                 */
                public function toArray(Request \$request): array
                {
                    return [
                        'meta' => [
                            'total' => \$this->total(),
                            'currentPage' => \$this->currentPage(),
                            'lastPage' => \$this->lastPage(),
                            'perPage' => \$this->perPage(),
                        ],
                        'links' => [
                            'first' => \$this->url(1),
                            'last' => \$this->url(\$this->lastPage()),
                            'previous' => \$this->previousPageUrl(),
                            'next' => \$this->nextPageUrl(),
                        ],
                    ];
                }
            }
            ETO,
        ];
    }
}
//
