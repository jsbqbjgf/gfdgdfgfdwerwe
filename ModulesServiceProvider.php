<?php
    namespace Src\Modules;
    /**
    *   Сервис провайдер для подключения модулей
    */
    class ModulesServiceProvider extends \Illuminate\Support\ServiceProvider
    {
        public function boot() 
        {
            //регистрируем конфиг
            $this->mergeConfigFrom(__DIR__ . '/config/module.php', 'module');
            $this->publishes([__DIR__.'/config' => base_path('config')]);
            $this->publishes([__DIR__.'/Controllers' => base_path('/app/Http/Controllers')]);
        	$this->publishes([__DIR__ . '/Migration/' =>database_path('migrations')]);
            $modules = config("module.modules");
            $dir = app_path().'/Modules';
            if($modules) {
                while (list(,$module) = each($modules)){
                      
                    //Подключаем роуты для модуля
                    if(file_exists($dir.'/'.$module.'/Routes/routes.php')) {
                        $this->loadRoutesFrom($dir.'/'.$module.'/Routes/routes.php');
                    }
                    //Загружаем View
                    //view('test::admin')
                    if(is_dir($dir.'/'.$module.'/Views')) {
                        $this->loadViewsFrom($dir.'/'.$module.'/Views', $module);
                    }
                    
                    //Подгружаем миграции
                    if(is_dir($dir.'/'.$module.'/Controllers')) {
                        $this->loadMigrationsFrom($dir.'/'.$module.'/Controllers');
                    }

                    if(is_dir($dir.'/'.$module.'/Migration')) {
                        $this->loadMigrationsFrom($dir.'/'.$module.'/Migration');
                    }
                    
                    //Подгружаем переводы
                    //trans('test::messages.welcome')
                    if(is_dir($dir.'/'.$module.'/Lang')) {
                        $this->loadTranslationsFrom($dir.'/'.$module.'/Lang', $module);
                    }
                }
            }

        }
        public function register() 
        {
        }
    } 
