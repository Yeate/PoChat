<?php

namespace Pokeface\Chating\Providers;

use Illuminate\Support\ServiceProvider;
use Pokeface\Chating\Facades\ChatServiceFacade;
use Pokeface\Chating\Facades\RecordServiceFacade;
use Pokeface\Chating\Facades\UserServiceFacade;
use Pokeface\Chating\Modules\ChatRoute;
use Pokeface\Chating\Services\ChatService;
use Pokeface\Chating\Services\RecordService;
use Pokeface\Chating\Services\UserService;

class ChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (! class_exists('CreatePermissionTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../Migrates/create_pochat_tables.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_pochat_tables.php",
                ], 'migrations');
            }
        $this->publishes([
            __DIR__.'/../../config/pochat.php' => config_path('pochat.php'), 
            __DIR__.'/../websocket.php' => base_path('routes/websocket.php'),
            __DIR__.'/../Controller/example' => app_path('WebSockets/'),
            __DIR__.'/../WebSocketService.php' => app_path('Services/WebSocketService.php'),
            
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ChatRoute', function () {
            return new ChatRoute();
        });
        $this->app->singleton('PoChatChat', function () {
            return new ChatService();
        });
        $this->app->singleton('PoChatUser', function () {
            return new UserService();
        });
        $this->app->singleton('PoChatRecord', function () {
            return new RecordService();
        });
        $this->app->booting(
            function () {
                $aliases = \Config::get('app.aliases');
                if(empty($aliases['ChatRoute'])){
                    $loader = \Illuminate\Foundation\AliasLoader::getInstance();
                    $loader->alias('ChatRoute','Pokeface\Chating\Facades\ChatRouteFacade');
                }
                if(empty($aliases['PoChatChat'])){
                    $loader = \Illuminate\Foundation\AliasLoader::getInstance();
                    $loader->alias('PoChatChat','Pokeface\Chating\Facades\ChatServiceFacade');
                }
                if(empty($aliases['PoChatUser'])){
                    $loader = \Illuminate\Foundation\AliasLoader::getInstance();
                    $loader->alias('PoChatUser','Pokeface\Chating\Facades\UserServiceFacade');
                }
                if(empty($aliases['PoChatRecord'])){
                    $loader = \Illuminate\Foundation\AliasLoader::getInstance();
                    $loader->alias('PoChatRecord','Pokeface\Chating\Facades\RecordServiceFacade');
                }
            }
        );

    }

    /**
     * @return array
     */
    public function provides()
    {
        return array('PoChat');
    }
}
