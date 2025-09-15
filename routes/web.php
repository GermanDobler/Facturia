<?php

use App\Http\Controllers\ComprobanteReviewController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\MatriculadoCuotaController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\SliderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\AutoridadController;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\InformacionUtilController;
use App\Http\Controllers\InmobiliariaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MensajesController;
use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PanelMatriculadoController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\SliderSecundarioController;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\TerminoYCondicionController;
use App\Http\Controllers\UserController;

// Main Page Route
Route::get('/', [InicioController::class, 'index'])->name('inicio');


Route::get('/terminos_y_condiciones', [InicioController::class, 'terminosCondiciones'])->name('terminos_condiciones_inicio');
Route::get('/politica_privacidad', [InicioController::class, 'informacionUtil'])->name('informacion_util_inicio');
Route::get('/preguntas_frecuentes', [InicioController::class, 'faqs'])->name('faqs_inicio');
Route::get('/noticia/{slug}', [InicioController::class, 'showNoticia'])->name('getNoticia');
Route::get('/noticias', [InicioController::class, 'noticias'])->name('noticias_inicio');
Route::get('/contacto', [InicioController::class, 'contacto'])->name('contacto_inicio');
Route::get('/denuncias', [InicioController::class, 'denuncias'])->name('denuncia_inicio');
Route::post('/mensajes', [MensajesController::class, 'store'])->name('mensajes.store');
Route::get('/padron-matriculados', [InicioController::class, 'padronPublico'])->name('padron.publico');
Route::get('/inmobiliarias', [InicioController::class, 'listaInmobiliaria'])->name('inmobiliarias.lista');
Route::get('/autoridades', [InicioController::class, 'listaAutoridades'])->name('autoridades.inicio');
Route::get('/convenios', [InicioController::class, 'convenios'])->name('convenios_inicio');

//ADMIN - AUTH ROUTES
// Mostrar el formulario de inicio de sesión
Route::get('/admin/auth/login', [LoginBasic::class, 'index'])->name('login.form');
Route::post('/admin/auth/login', [LoginBasic::class, 'login'])->name('login');
Route::get('/admin/auth/logout', [LoginBasic::class, 'logout'])->name('logout');
//END ADMIN - AUTH ROUTES

// Para usuarios
Route::get('/user-login', [LoginController::class, 'showUserLoginForm'])->name('login.user');
Route::post('/user-login', [LoginController::class, 'login'])->name('login.user.post');
Route::post('/registro', [LoginController::class, 'registro'])->name('registro');

// Logout común
Route::post('/logout', [LoginController::class, 'logout'])->name('logout.user');

Route::prefix('/admin')
    ->middleware(['admin.only'])
    ->group(function () {

        // Acceso solo para el administrador general
        Route::get('/', [NoticiasController::class, 'index'])->name('noticias');

        Route::get('users', [UserController::class, 'index'])->name('users.index');

        Route::get('users/create', [UserController::class, 'create'])->name('users.create');

        Route::post('users', [UserController::class, 'store'])->name('users.store');

        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/users/{id}/archivero', [ArchivoController::class, 'archivero'])->name('users.archivero');
        Route::post('/users/{id}/archivos', [ArchivoController::class, 'subir'])->name('archivos.subir');
        Route::post('/users/{id}/carpetas', [ArchivoController::class, 'crearCarpeta'])->name('archivos.crearCarpeta');
        Route::get('/archivos/{id}/descargar', [ArchivoController::class, 'descargar'])->name('archivos.descargar');
        Route::delete('/archivos/{id}', [ArchivoController::class, 'eliminar'])->name('archivos.eliminar');
        Route::delete('/archivos/eliminar-carpeta/{userId}/{carpeta}', [ArchivoController::class, 'eliminarCarpeta'])->name('archivos.eliminarCarpeta');
        Route::get('/archivos/{id}/ver', [ArchivoController::class, 'ver'])->name('archivos.ver');
        Route::get('/archivos/carpeta/{userId}/{carpeta}', [ArchivoController::class, 'archivosPorCarpeta'])->name('archivos.porCarpeta');
        Route::post('/archivos/eliminar-multiples', [ArchivoController::class, 'eliminarMultiples'])->name('archivos.eliminarMultiples');

        // layout
        Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
        Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
        Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
        Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
        Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

        // pages
        Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
        Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
        Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
        Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
        Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

        Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
        // Route::post('/register', [RegisterBasic::class, 'register'])->name('register');
        Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

        // cards
        Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

        // User Interface
        Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
        Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
        Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
        Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
        Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
        Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
        Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
        Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
        Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
        Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
        Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
        Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
        Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
        Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
        Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
        Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
        Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
        Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
        Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

        // extended ui
        Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
        Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

        // icons
        Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

        // form elements
        Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
        Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

        // form layouts
        Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
        Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

        // tables
        Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');

        Route::resource('sliders', SliderController::class);
        Route::post('/sliders/update-order', [SliderController::class, 'updateOrder'])->name('sliders.updateOrder');
        Route::delete('sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');
        Route::delete('sliders-secundario/{slider}', [SliderController::class, 'destroySecundario'])->name('sliders-secundario.destroy');

        // Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');
        // Route::delete('/sliders-secundario/{slider}', [SliderSecundarioController::class, 'destroy'])->name('sliders-secundario.destroy');
        Route::post('/sliders-secundario/store', [SliderSecundarioController::class, 'store'])->name('sliders-secundario.store');
        Route::post('/sliders-secundario/update-order', [SliderSecundarioController::class, 'updateOrder'])->name('sliders-secundario.updateOrder');

        Route::resource('faqs', FAQController::class);
        Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
        Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
        Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
        Route::delete('/faqs/{id}', [FaqController::class, 'destroy'])->name('faqs.destroy');
        Route::post('/faqs/reorder', [FaqController::class, 'reorder'])->name('faqs.reorder');

        Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto.index');
        Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

        Route::get('footer', [FooterController::class, 'index'])->name('footer.index');
        Route::post('footer', [FooterController::class, 'update'])->name('footer.update');

        Route::get('terminos_y_condiciones', [TerminoYCondicionController::class, 'index'])->name('terminos_y_condiciones.index');
        Route::put('terminos_y_condiciones', [TerminoYCondicionController::class, 'update'])->name('terminos_y_condiciones.update');

        Route::get('/politica_privacidad', [InformacionUtilController::class, 'index'])->name('informacion_util.index');
        Route::post('/politica_privacidad', [InformacionUtilController::class, 'update'])->name('informacion_util.update');

        Route::get('/contactos', [ContactoController::class, 'index'])->name('contactos.index');
        Route::get('/contactos/{id}', [ContactoController::class, 'show'])->name('contactos.show');
        Route::delete('/contactos/{id}', [ContactoController::class, 'destroy'])->name('contactos.destroy');

        // Añadir rutas para los controladores
        Route::resource('noticias', NoticiasController::class);

        Route::get('noticias', [NoticiasController::class, 'index'])->name('noticias.index');

        // Mostrar el formulario para crear una nueva noticia
        Route::get('noticias/create', [NoticiasController::class, 'create'])->name('noticias.create');

        // Almacenar una nueva noticia
        Route::post('noticias', [NoticiasController::class, 'store'])->name('noticias.store');

        Route::patch('/noticias/toggle-paid/{id}', [NoticiasController::class, 'togglePaid'])->name('noticias.togglePaid');


        Route::get('cuotas', [CuotaController::class, 'index'])->name('cuotas.index');

        Route::resource('periodos', PeriodoController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::post('periodos/{periodo}/abrir', [PeriodoController::class, 'abrir'])->name('periodos.abrir');
        Route::post('periodos/{periodo}/cancelar', [PeriodoController::class, 'cancelar'])->name('periodos.cancelar');
        Route::post('periodos/{periodo}/revertir-cancelacion', [PeriodoController::class, 'revertirCancelacion'])->name('periodos.revertir');
        Route::post('periodos/{periodo}/cerrar', [PeriodoController::class, 'cerrar'])->name('periodos.cerrar');
        // Route::put('/periodos', [PeriodoController::class, 'update'])->name('periodos.update');

        Route::post('pagos/{pago}/aprobar', [PagoController::class, 'aprobar'])->name('pagos.aprobar');
        Route::post('pagos/{pago}/rechazar', [PagoController::class, 'rechazar'])->name('pagos.rechazar');

        Route::get('/mensajes', [MensajesController::class, 'index'])->name('mensajes.index');
        Route::get('/mensajes/{id}', [MensajesController::class, 'show'])->name('mensajes.show');
        Route::delete('/mensajes/{id}', [MensajesController::class, 'destroy'])->name('mensajes.destroy');

        Route::get('archivos_generales', [ArchivoController::class, 'generalesIndex'])
            ->name('archivos.generales.index');

        Route::post('archivos_generales/subir', [ArchivoController::class, 'generalesSubir'])
            ->name('archivos.generales.subir');

        Route::post('archivos_generales/carpeta', [ArchivoController::class, 'generalesCrearCarpeta'])
            ->name('archivos.generales.carpeta');

        Route::delete('archivos_generales/carpeta/{carpeta}', [ArchivoController::class, 'generalesEliminarCarpeta'])
            ->name('archivos.generales.carpeta.eliminar');

        Route::delete('archivos_generales/{archivo}', [ArchivoController::class, 'generalesEliminar'])
            ->name('archivos.generales.eliminar');

        // Si querés descargar/ver desde el listado general podés reutilizar:
        Route::get('archivos_generales/descargar/{archivo}', [ArchivoController::class, 'descargar'])
            ->name('archivos.generales.descargar');

        Route::get('inmobiliarias',        [InmobiliariaController::class, 'index'])->name('inmobiliarias.index');
        Route::get('inmobiliarias/crear',  [InmobiliariaController::class, 'create'])->name('inmobiliarias.create');
        Route::post('inmobiliarias',       [InmobiliariaController::class, 'store'])->name('inmobiliarias.store');
        Route::get('inmobiliarias/{inmobiliaria}/editar', [InmobiliariaController::class, 'edit'])->name('inmobiliarias.edit');
        Route::put('inmobiliarias/{inmobiliaria}',        [InmobiliariaController::class, 'update'])->name('inmobiliarias.update');
        Route::delete('inmobiliarias/{inmobiliaria}',     [InmobiliariaController::class, 'destroy'])->name('inmobiliarias.destroy');

        Route::get('autoridades',            [AutoridadController::class, 'index'])->name('autoridades.index');
        Route::post('autoridades/sort', [AutoridadController::class, 'sort'])->name('autoridades.sort'); // 👈 nueva
        Route::get('autoridades/crear',      [AutoridadController::class, 'create'])->name('autoridades.create');
        Route::post('autoridades',           [AutoridadController::class, 'store'])->name('autoridades.store');
        Route::get('autoridades/{autoridad}/editar', [AutoridadController::class, 'edit'])->name('autoridades.edit');
        Route::put('autoridades/{autoridad}',        [AutoridadController::class, 'update'])->name('autoridades.update');
        Route::delete('autoridades/{autoridad}',     [AutoridadController::class, 'destroy'])->name('autoridades.destroy');
    });

Route::prefix('/mi-panel')
    ->middleware(['auth', 'paid.user'])
    ->name('panel.')
    ->group(function () {
        Route::get('/', [PanelMatriculadoController::class, 'index'])->name('matriculado');

        // Cuotas
        Route::get('mis-cuotas', [PanelMatriculadoController::class, 'misCuotas'])->name('cuotas');
        Route::post('cuotas/{cuota}/pagos', [PagoController::class, 'store'])->name('pagos.store');
        Route::get('archivero_general', [PanelMatriculadoController::class, 'archiveroGeneral'])->name('archivero.general');

        // Archivero (usuario)
        Route::prefix('archivero')->name('archivero.')->group(function () {
            Route::get('/', [PanelMatriculadoController::class, 'archiveroIndex'])->name('index');
            Route::get('/carpeta/{carpeta}', [PanelMatriculadoController::class, 'archiveroCarpeta'])->name('porCarpeta');
            Route::get('/carpeta/{carpeta}/json', [PanelMatriculadoController::class, 'archiveroCarpetaJson'])->name('porCarpeta.json');

            Route::post('/subir', [PanelMatriculadoController::class, 'archiveroSubir'])->name('subir');
            Route::post('/carpeta', [PanelMatriculadoController::class, 'archiveroCrearCarpeta'])->name('crearCarpeta');

            Route::get('/archivo/{id}/ver', [PanelMatriculadoController::class, 'archiveroVer'])->name('ver');
            Route::get('/archivo/{id}/descargar', [PanelMatriculadoController::class, 'archiveroDescargar'])->name('descargar');

            Route::delete('/archivo/{id}', [PanelMatriculadoController::class, 'archiveroEliminar'])->name('eliminar');
            Route::delete('/carpeta/{carpeta}', [PanelMatriculadoController::class, 'archiveroEliminarCarpeta'])->name('eliminarCarpeta');

            Route::post('/eliminar-multiples', [PanelMatriculadoController::class, 'archiveroEliminarMultiples'])->name('eliminarMultiples');
        });
    });
