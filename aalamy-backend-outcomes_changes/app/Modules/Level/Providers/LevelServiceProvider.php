<?php


namespace Modules\Level\Providers;


use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\Level;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Subject;
use Modules\Level\Models\Unit;
use Modules\Level\Observers\LessonObserver;
use Modules\Level\Observers\LevelObserver;
use Modules\Level\Observers\LevelSubjectObserver;
use Modules\Level\Observers\SubjectObserver;
use Modules\Level\Observers\UnitObserver;

class LevelServiceProvider extends ServiceProvider
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
        $moduleName = ApplicationModules::LEVEL_MODULE_NAME;
        config([
            $moduleName => File::getRequire(__DIR__.$ds.'..'.$ds.'config'.$ds.'levelConfig.php')
        ]);
        $this->loadRoutesFrom(__DIR__.$ds.'..'.$ds.'routes'.$ds.'api.php');
        $this->loadRoutesFrom(__DIR__.$ds.'..'.$ds.'routes'.$ds.'web.php');
        $this->loadViewsFrom(__DIR__.$ds.'..'.$ds.'resources'.$ds.'views',$moduleName);
        $this->loadTranslationsFrom(__DIR__.$ds.'..'.$ds.'resources'.$ds.'lang',$moduleName);
        $this->loadMigrationsFrom(__DIR__.$ds.'..'.$ds.'database'.$ds.'migrations');
        Lesson::observe(LessonObserver::class);
        Level::observe(LevelObserver::class);
        LevelSubject::observe(LevelSubjectObserver::class);
        Subject::observe(SubjectObserver::class);
        Unit::observe(UnitObserver::class);
//        dd(Lang::getAll());
    }


}
