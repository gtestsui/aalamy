<?php


namespace Modules\DiscussionCorner\Providers;


use App\Http\Controllers\Classes\ApplicationModules;
use App\Modules\DiscussionCorner\Observers\PostObserver;
use App\Modules\DiscussionCorner\Observers\SurveyObserver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;

class DiscussionCornerServiceProvider extends ServiceProvider
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
        $moduleName = ApplicationModules::DISCUSSION_CORNER_MODULE_NAME;
        config([
            $moduleName => File::getRequire(__DIR__.$ds.'..'.$ds.'config'.$ds.'discussionCornerConfig.php')
        ]);
        $this->loadRoutesFrom(__DIR__.$ds.'..'.$ds.'routes'.$ds.'api.php');
        $this->loadRoutesFrom(__DIR__.$ds.'..'.$ds.'routes'.$ds.'web.php');
        $this->loadViewsFrom(__DIR__.$ds.'..'.$ds.'resources'.$ds.'views',$moduleName);
        $this->loadTranslationsFrom(__DIR__.$ds.'..'.$ds.'resources'.$ds.'lang',$moduleName);
        $this->loadMigrationsFrom(__DIR__.$ds.'..'.$ds.'database'.$ds.'migrations');
        DiscussionCornerSurvey::observe(SurveyObserver::class);
        DiscussionCornerPost::observe(PostObserver::class);

//        dd(Lang::getAll());
    }


}
