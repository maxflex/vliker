<?php

namespace App\Providers;

use App\Models\{Task, Action, User, Report};
use App\Observers\{TaskObserver, ActionObserver, UserObserver, ReportObserver};
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Task::observe(TaskObserver::class);
        Action::observe(ActionObserver::class);
        User::observe(UserObserver::class);
        Report::observe(ReportObserver::class);
        JsonResource::withoutWrapping();
        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format('Y-m-d H:i:s');
        });
    }
}
