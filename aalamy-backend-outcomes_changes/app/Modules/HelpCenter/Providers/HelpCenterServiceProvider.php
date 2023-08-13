<?php


namespace Modules\HelpCenter\Providers;


use App\Http\Controllers\Classes\ApplicationModules;
use Modules\HelpCenter\Observers\HelpCenterCategoryObserver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Modules\HelpCenter\Models\HelpCenterCategory;

class HelpCenterServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $ds = DIRECTORY_SEPARATOR;
        $moduleName = ApplicationModules::HELP_CENTER_MODULE_NAME;
        config([
            $moduleName => File::getRequire(__DIR__.$ds.'..'.$ds.'config'.$ds.'helpCenterConfig.php')
        ]);
        $this->loadRoutesFrom(__DIR__.$ds.'..'.$ds.'routes'.$ds.'api.php');
        $this->loadRoutesFrom(__DIR__.$ds.'..'.$ds.'routes'.$ds.'web.php');
        $this->loadViewsFrom(__DIR__.$ds.'..'.$ds.'resources'.$ds.'views',$moduleName);
        $this->loadTranslationsFrom(__DIR__.$ds.'..'.$ds.'resources'.$ds.'lang',$moduleName);
        $this->loadMigrationsFrom(__DIR__.$ds.'..'.$ds.'database'.$ds.'migrations');
        HelpCenterCategory::observe(HelpCenterCategoryObserver::class);

//        dd(Lang::getAll());
    }


}
