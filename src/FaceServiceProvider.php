<?php
namespace  Costa92\Face;
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2016/12/19
 * Time: 上午9:28
 */
use Illuminate\Support\ServiceProvider;
use Costa92\Face\Services\FaceService;
class FaceServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => base_path('config/face.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('FaceService', function($app){
            return new FaceService();
        });
    }

}