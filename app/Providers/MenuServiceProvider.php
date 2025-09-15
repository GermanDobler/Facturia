<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Definir el archivo de menú por defecto
            $menuFile = base_path('resources/menu/verticalMenu.json');

            // Verificar si hay un usuario logueado
            if (Auth::check()) {
                $userRole = Auth::user()->role; // Suponiendo que el campo 'role' existe en la BD
                
                // Buscar el archivo de menú correspondiente
                $roleMenuPath = base_path("resources/menu/{$userRole}.json");

                // Si el archivo existe, cambiar la referencia al menú específico del rol
                if (file_exists($roleMenuPath)) {
                    $menuFile = $roleMenuPath;
                }
            }

            // Cargar el menú correspondiente
            $menuJson = file_get_contents($menuFile);
            $menuData = json_decode($menuJson);

            // Compartir la variable solo con la vista actual
            $view->with('menuData', [$menuData]);
        });
    }
}
