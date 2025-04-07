<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

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
    if (request()->server('HTTP_X_FORWARDED_PROTO') == 'https' || request()->secure()) {
      URL::forceScheme('https');
    }
  }
}
//public function boot()
    //{
      //  if(config('app.env')=== 'local'){ URL::forceScheme('https');}
//
//
  //      Schema::defaultStringLength(191);// other code
//
  //  }