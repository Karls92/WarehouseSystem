<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Backend\AuthController@getLogin');

/**
 * Rutas del panel administrativo
 */

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function ()
{
    #grupo de rutas de reportes
    Route::group(['prefix' => 'generar_reporte'], function ()
    {
        Route::get('/general', [
            'as'   => 'report.reports',
            'uses' => 'Backend\ReportsController@reports'
        ]);
        Route::post('/general', [
            'as'   => 'report.reports',
            'uses' => 'Backend\ReportsController@generate_report'
        ]);
    
        Route::get('/orden', [
            'as'   => 'report.orders',
            'uses' => 'Backend\ReportsController@orders'
        ]);
        Route::post('/orden', [
            'as'   => 'report.orders',
            'uses' => 'Backend\ReportsController@generate_order'
        ]);
        Route::post('/ordenes-del-client', [
            'as'   => 'report.orders_client',
            'uses' => 'Backend\ReportsController@ajax_orders_client'
        ]);
    
        Route::get('/inventario', [
            'as'   => 'report.inventory',
            'uses' => 'Backend\ReportsController@inventory'
        ]);
        Route::post('/inventario', [
            'as'   => 'report.inventory',
            'uses' => 'Backend\ReportsController@generate_inventory'
        ]);
        Route::post('/productos-del-cliente', [
            'as'   => 'report.products_client',
            'uses' => 'Backend\ReportsController@ajax_products_client'
        ]);

    });
    
    #grupo de rutas de operaciones principales
    Route::group(['prefix' => 'operaciones-principales'], function ()
    {
        #grupo de rutas de productos de la orden
        Route::group(['prefix' => '{order_type}/productos-de-la-orden/{order_code}'], function ()
        {
            Route::get('/', [
                'as'   => 'order_product.index',
                'uses' => 'Backend\OrderProductsController@index'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+','order_type' => '(ordenes-de-entrada)|(ordenes-de-salida)|(ordenes-de-devolucion)']);
            Route::get('busqueda/{id}', [
                'as'   => 'order_product.search',
                'uses' => 'Backend\OrderProductsController@index'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+','order_type' => '(ordenes-de-entrada)|(ordenes-de-salida)|(ordenes-de-devolucion)','id' => '[0-9]+']);
        
            Route::get('agregar', [
                'as'   => 'order_product.store',
                'uses' => 'Backend\OrderProductsController@add'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+','order_type' => '(ordenes-de-entrada)|(ordenes-de-salida)|(ordenes-de-devolucion)']);
            Route::post('agregar', [
                'as'   => 'order_product.store',
                'uses' => 'Backend\OrderProductsController@store'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+','order_type' => '(ordenes-de-entrada)|(ordenes-de-salida)|(ordenes-de-devolucion)']);
        
            Route::get('editar/{id}', [
                'as'   => 'order_product.update',
                'uses' => 'Backend\OrderProductsController@edit'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+','order_type' => '(ordenes-de-entrada)|(ordenes-de-salida)|(ordenes-de-devolucion)','id' => '[0-9]+']);
            Route::post('editar/{id}', [
                'as'   => 'order_product.update',
                'uses' => 'Backend\OrderProductsController@update'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+','order_type' => '(ordenes-de-entrada)|(ordenes-de-salida)|(ordenes-de-devolucion)','id' => '[0-9]+']);
        
            Route::get('eliminar/{id}', [
                'as'   => 'order_product.delete',
                'uses' => 'Backend\OrderProductsController@delete'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+','order_type' => '(ordenes-de-entrada)|(ordenes-de-salida)|(ordenes-de-devolucion)','id' => '[0-9]+']);
        });
    
        #grupo de rutas de ordenes de devolucion
        Route::group(['prefix' => 'ordenes-de-devolucion'], function ()
        {
            Route::get('/', [
                'as'   => 'devolution_order.index',
                'uses' => 'Backend\DevolutionOrdersController@index'
            ]);
            Route::get('busqueda/{slug}', [
                'as'   => 'devolution_order.search',
                'uses' => 'Backend\DevolutionOrdersController@index'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('/', [
                'as'   => 'devolution_order.index',
                'uses' => 'Backend\DevolutionOrdersController@ajax_orders'
            ]);
            Route::get('procesar/{id}', [
                'as'   => 'devolution_order.process',
                'uses' => 'Backend\DevolutionOrdersController@process'
            ])->where(['id' => '[0-9]+']);
            Route::get('generar-orden/{order_code}', [
                'as'   => 'devolution_order.report',
                'uses' => 'Backend\DevolutionOrdersController@report'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+']);
        
            Route::get('agregar', [
                'as'   => 'devolution_order.store',
                'uses' => 'Backend\DevolutionOrdersController@add'
            ]);
            Route::post('agregar', [
                'as'   => 'devolution_order.store',
                'uses' => 'Backend\DevolutionOrdersController@store'
            ]);
        
            Route::get('editar/{slug}', [
                'as'   => 'devolution_order.update',
                'uses' => 'Backend\DevolutionOrdersController@edit'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('editar/{slug}', [
                'as'   => 'devolution_order.update',
                'uses' => 'Backend\DevolutionOrdersController@update'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('eliminar/{id}', [
                'as'   => 'devolution_order.delete',
                'uses' => 'Backend\DevolutionOrdersController@delete'
            ])->where(['id' => '[0-9]+']);
        });
    
        #grupo de rutas de ordenes de salida
        Route::group(['prefix' => 'ordenes-de-salida'], function ()
        {
            Route::get('/', [
                'as'   => 'out_order.index',
                'uses' => 'Backend\OutOrdersController@index'
            ]);
            Route::get('busqueda/{slug}', [
                'as'   => 'out_order.search',
                'uses' => 'Backend\OutOrdersController@index'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('/', [
                'as'   => 'out_order.index',
                'uses' => 'Backend\OutOrdersController@ajax_orders'
            ]);
            Route::get('procesar/{id}', [
                'as'   => 'out_order.process',
                'uses' => 'Backend\OutOrdersController@process'
            ])->where(['id' => '[0-9]+']);
            Route::get('generar-orden/{order_code}', [
                'as'   => 'out_order.report',
                'uses' => 'Backend\OutOrdersController@report'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+']);
        
            Route::get('agregar', [
                'as'   => 'out_order.store',
                'uses' => 'Backend\OutOrdersController@add'
            ]);
            Route::post('agregar', [
                'as'   => 'out_order.store',
                'uses' => 'Backend\OutOrdersController@store'
            ]);
        
            Route::get('editar/{slug}', [
                'as'   => 'out_order.update',
                'uses' => 'Backend\OutOrdersController@edit'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('editar/{slug}', [
                'as'   => 'out_order.update',
                'uses' => 'Backend\OutOrdersController@update'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('eliminar/{id}', [
                'as'   => 'out_order.delete',
                'uses' => 'Backend\OutOrdersController@delete'
            ])->where(['id' => '[0-9]+']);
        });
        
        #grupo de rutas de ordenes de entrada
        Route::group(['prefix' => 'ordenes-de-entrada'], function ()
        {
            Route::get('/', [
                'as'   => 'entry_order.index',
                'uses' => 'Backend\EntryOrdersController@index'
            ]);
            Route::get('busqueda/{slug}', [
                'as'   => 'entry_order.search',
                'uses' => 'Backend\EntryOrdersController@index'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('/', [
                'as'   => 'entry_order.index',
                'uses' => 'Backend\EntryOrdersController@ajax_orders'
            ]);
            Route::get('procesar/{id}', [
                'as'   => 'entry_order.process',
                'uses' => 'Backend\EntryOrdersController@process'
            ])->where(['id' => '[0-9]+']);
            Route::get('generar-orden/{order_code}', [
                'as'   => 'entry_order.report',
                'uses' => 'Backend\EntryOrdersController@report'
            ])->where(['order_code' => '[0-9a-zA-Z_-]+']);
        
            Route::get('agregar', [
                'as'   => 'entry_order.store',
                'uses' => 'Backend\EntryOrdersController@add'
            ]);
            Route::post('agregar', [
                'as'   => 'entry_order.store',
                'uses' => 'Backend\EntryOrdersController@store'
            ]);
        
            Route::get('editar/{slug}', [
                'as'   => 'entry_order.update',
                'uses' => 'Backend\EntryOrdersController@edit'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('editar/{slug}', [
                'as'   => 'entry_order.update',
                'uses' => 'Backend\EntryOrdersController@update'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('eliminar/{id}', [
                'as'   => 'entry_order.delete',
                'uses' => 'Backend\EntryOrdersController@delete'
            ])->where(['id' => '[0-9]+']);
        });
    });
    
    #grupo de rutas de productos
    Route::group(['prefix' => 'productos'], function ()
    {
        Route::get('/', [
            'as'   => 'product.index',
            'uses' => 'Backend\ProductsController@index'
        ]);
        Route::get('busqueda/{slug}', [
            'as'   => 'product.search',
            'uses' => 'Backend\ProductsController@index'
        ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        Route::post('/', [
            'as'   => 'product.index',
            'uses' => 'Backend\ProductsController@ajax_products'
        ]);
        
        Route::get('agregar', [
            'as'   => 'product.store',
            'uses' => 'Backend\ProductsController@add'
        ]);
        Route::post('agregar', [
            'as'   => 'product.store',
            'uses' => 'Backend\ProductsController@store'
        ]);
        
        Route::get('editar/{slug}', [
            'as'   => 'product.update',
            'uses' => 'Backend\ProductsController@edit'
        ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        Route::post('editar/{slug}', [
            'as'   => 'product.update',
            'uses' => 'Backend\ProductsController@update'
        ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
        Route::get('eliminar/{id}', [
            'as'   => 'product.delete',
            'uses' => 'Backend\ProductsController@delete'
        ])->where(['id' => '[0-9]+']);
    });
    
    #grupo de rutas de clientes
    Route::group(['prefix' => 'clientes'], function ()
    {
        Route::get('/', [
            'as'   => 'client.index',
            'uses' => 'Backend\ClientsController@index'
        ]);
        Route::get('busqueda/{slug}', [
            'as'   => 'client.search',
            'uses' => 'Backend\ClientsController@index'
        ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        Route::post('/', [
            'as'   => 'client.index',
            'uses' => 'Backend\ClientsController@ajax_clients'
        ]);
        
        Route::get('agregar', [
            'as'   => 'client.store',
            'uses' => 'Backend\ClientsController@add'
        ]);
        Route::post('agregar', [
            'as'   => 'client.store',
            'uses' => 'Backend\ClientsController@store'
        ]);
        
        Route::get('editar/{slug}', [
            'as'   => 'client.update',
            'uses' => 'Backend\ClientsController@edit'
        ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        Route::post('editar/{slug}', [
            'as'   => 'client.update',
            'uses' => 'Backend\ClientsController@update'
        ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
        Route::get('eliminar/{id}', [
            'as'   => 'client.delete',
            'uses' => 'Backend\ClientsController@delete'
        ])->where(['id' => '[0-9]+']);
    
        Route::post('ajax_out_orders', [
            'as'   => 'client.out_orders',
            'uses' => 'Backend\ClientsController@ajax_out_orders'
        ]);
    });
    
    #grupo de rutas de ubicaciones
    Route::group(['prefix' => 'ubicacion'], function ()
    {
        #grupo de rutas de estados
        Route::group(['prefix' => 'estados'], function ()
        {
            Route::get('/', [
                'as'   => 'state.index',
                'uses' => 'Backend\StatesController@index'
            ]);
            Route::get('busqueda/{slug}', [
                'as'   => 'state.search',
                'uses' => 'Backend\StatesController@index'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('agregar', [
                'as'   => 'state.store',
                'uses' => 'Backend\StatesController@add'
            ]);
            Route::post('agregar', [
                'as'   => 'state.store',
                'uses' => 'Backend\StatesController@store'
            ]);
        
            Route::get('editar/{slug}', [
                'as'   => 'state.update',
                'uses' => 'Backend\StatesController@edit'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('editar/{slug}', [
                'as'   => 'state.update',
                'uses' => 'Backend\StatesController@update'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('eliminar/{id}', [
                'as'   => 'state.delete',
                'uses' => 'Backend\StatesController@delete'
            ])->where(['id' => '[0-9]+']);
    
            Route::post('ajax_cities', [
                'as'   => 'state.cities',
                'uses' => 'Backend\StatesController@ajax_cities'
            ]);
        });
    
        #grupo de rutas de ciudades
        Route::group(['prefix' => 'ciudades'], function ()
        {
            Route::get('/', [
                'as'   => 'city.index',
                'uses' => 'Backend\CitiesController@index'
            ]);
            Route::get('busqueda/{slug}', [
                'as'   => 'city.search',
                'uses' => 'Backend\CitiesController@index'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('agregar', [
                'as'   => 'city.store',
                'uses' => 'Backend\CitiesController@add'
            ]);
            Route::post('agregar', [
                'as'   => 'city.store',
                'uses' => 'Backend\CitiesController@store'
            ]);
        
            Route::get('editar/{slug}', [
                'as'   => 'city.update',
                'uses' => 'Backend\CitiesController@edit'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('editar/{slug}', [
                'as'   => 'city.update',
                'uses' => 'Backend\CitiesController@update'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('eliminar/{id}', [
                'as'   => 'city.delete',
                'uses' => 'Backend\CitiesController@delete'
            ])->where(['id' => '[0-9]+']);
        });
    
    });
    
    #grupo de rutas de configuración de producto
    Route::group(['prefix' => 'configuracion_de_productos'], function ()
    {
        #grupo de rutas de marcas
        Route::group(['prefix' => 'marcas'], function ()
        {
            Route::get('/', [
                'as'   => 'brand.index',
                'uses' => 'Backend\BrandsController@index'
            ]);
            Route::get('busqueda/{slug}', [
                'as'   => 'brand.search',
                'uses' => 'Backend\BrandsController@index'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('agregar', [
                'as'   => 'brand.store',
                'uses' => 'Backend\BrandsController@add'
            ]);
            Route::post('agregar', [
                'as'   => 'brand.store',
                'uses' => 'Backend\BrandsController@store'
            ]);
        
            Route::get('editar/{slug}', [
                'as'   => 'brand.update',
                'uses' => 'Backend\BrandsController@edit'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('editar/{slug}', [
                'as'   => 'brand.update',
                'uses' => 'Backend\BrandsController@update'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('eliminar/{id}', [
                'as'   => 'brand.delete',
                'uses' => 'Backend\BrandsController@delete'
            ])->where(['id' => '[0-9]+']);
    
            Route::post('ajax_models', [
                'as'   => 'brand.models',
                'uses' => 'Backend\BrandsController@ajax_models'
            ]);
        });
    
        #grupo de rutas de modelos
        Route::group(['prefix' => 'modelos'], function ()
        {
            Route::get('/', [
                'as'   => 'model.index',
                'uses' => 'Backend\ModelsController@index'
            ]);
            Route::get('busqueda/{slug}', [
                'as'   => 'model.search',
                'uses' => 'Backend\ModelsController@index'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('agregar', [
                'as'   => 'model.store',
                'uses' => 'Backend\ModelsController@add'
            ]);
            Route::post('agregar', [
                'as'   => 'model.store',
                'uses' => 'Backend\ModelsController@store'
            ]);
        
            Route::get('editar/{slug}', [
                'as'   => 'model.update',
                'uses' => 'Backend\ModelsController@edit'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('editar/{slug}', [
                'as'   => 'model.update',
                'uses' => 'Backend\ModelsController@update'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('eliminar/{id}', [
                'as'   => 'model.delete',
                'uses' => 'Backend\ModelsController@delete'
            ])->where(['id' => '[0-9]+']);
        });
        
        #grupo de rutas de unidad de medida
        Route::group(['prefix' => 'unidades_de_medida'], function ()
        {
            Route::get('/', [
                'as'   => 'unit_of_measure.index',
                'uses' => 'Backend\UnitsOfMeasureController@index'
            ]);
            Route::get('busqueda/{slug}', [
                'as'   => 'unit_of_measure.search',
                'uses' => 'Backend\UnitsOfMeasureController@index'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            
            Route::get('agregar', [
                'as'   => 'unit_of_measure.store',
                'uses' => 'Backend\UnitsOfMeasureController@add'
            ]);
            Route::post('agregar', [
                'as'   => 'unit_of_measure.store',
                'uses' => 'Backend\UnitsOfMeasureController@store'
            ]);
            
            Route::get('editar/{slug}', [
                'as'   => 'unit_of_measure.update',
                'uses' => 'Backend\UnitsOfMeasureController@edit'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('editar/{slug}', [
                'as'   => 'unit_of_measure.update',
                'uses' => 'Backend\UnitsOfMeasureController@update'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            
            Route::get('eliminar/{id}', [
                'as'   => 'unit_of_measure.delete',
                'uses' => 'Backend\UnitsOfMeasureController@delete'
            ])->where(['id' => '[0-9]+']);
        });
    
        #grupo de rutas de clasificaciones
        Route::group(['prefix' => 'clasificaciones'], function ()
        {
            Route::get('/', [
                'as'   => 'classification.index',
                'uses' => 'Backend\ClassificationsController@index'
            ]);
            Route::get('busqueda/{slug}', [
                'as'   => 'classification.search',
                'uses' => 'Backend\ClassificationsController@index'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('agregar', [
                'as'   => 'classification.store',
                'uses' => 'Backend\ClassificationsController@add'
            ]);
            Route::post('agregar', [
                'as'   => 'classification.store',
                'uses' => 'Backend\ClassificationsController@store'
            ]);
        
            Route::get('editar/{slug}', [
                'as'   => 'classification.update',
                'uses' => 'Backend\ClassificationsController@edit'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
            Route::post('editar/{slug}', [
                'as'   => 'classification.update',
                'uses' => 'Backend\ClassificationsController@update'
            ])->where(['slug' => '[0-9a-zA-Z_-]+']);
        
            Route::get('eliminar/{id}', [
                'as'   => 'classification.delete',
                'uses' => 'Backend\ClassificationsController@delete'
            ])->where(['id' => '[0-9]+']);
        });
    });
    
    #grupos de rutas de visitas
    Route::group(['prefix' => 'visitas'], function ()
    {
        Route::get('/', [
            'as'   => 'visits.index',
            'uses' => 'Backend\VisitsController@index'
        ]);
        Route::post('/', [
            'as'   => 'visits.index',
            'uses' => 'Backend\VisitsController@ajax_visits'
        ]);
        Route::get('referidos', [
            'as'   => 'visits.referrers',
            'uses' => 'Backend\VisitsController@referrers'
        ]);
        Route::get('resumen', [
            'as'   => 'visits.summary',
            'uses' => 'Backend\VisitsController@summary'
        ]);
    });
    
    #grupos de rutas de usuarios
    Route::group(['prefix' => 'usuarios'], function ()
    {
        Route::get('/', [
            'as'   => 'users.index',
            'uses' => 'Backend\UsersController@index'
        ]);
        
        Route::get('agregar', [
            'as'   => 'users.store',
            'uses' => 'Backend\UsersController@add'
        ]);
        Route::post('agregar', [
            'as'   => 'users.store',
            'uses' => 'Backend\UsersController@store'
        ]);
    
        Route::get('editar/{username}', [
            'as'   => 'users.update',
            'uses' => 'Backend\UsersController@edit'
        ])->where(['username' => '^(?!agregar)[0-9a-zA-Z_-]+$']);
        Route::post('editar/{username}', [
            'as'   => 'users.update',
            'uses' => 'Backend\UsersController@update'
        ])->where(['username' => '^(?!agregar)[0-9a-zA-Z_-]+$']);
    
        Route::get('eliminar/{id}', [
            'as'   => 'users.delete',
            'uses' => 'Backend\UsersController@delete'
        ])->where(['id' => '[0-9]+']);
    });
    
    #grupos de rutas de configuracion
    Route::group(['prefix' => 'configuracion'], function ()
    {
        Route::group(['middleware' => 'admin'], function ()
        {
            Route::get('logo', [
                'as' => 'settings.logo',
                'uses' => 'Backend\ConfigurationsController@logo'
            ]);
            Route::post('logo', [
                'as' => 'settings.logo',
                'uses' => 'Backend\ConfigurationsController@update_logo'
            ]);
        
            Route::get('redes_sociales', [
                'as' => 'settings.social_networks',
                'uses' => 'Backend\ConfigurationsController@social_networks'
            ]);
            Route::post('redes_sociales', [
                'as' => 'settings.social_networks',
                'uses' => 'Backend\ConfigurationsController@update_social_networks'
            ]);
        
            Route::get('contacto', [
                'as' => 'settings.contact',
                'uses' => 'Backend\ConfigurationsController@contact'
            ]);
            Route::post('contacto', [
                'as' => 'settings.contact',
                'uses' => 'Backend\ConfigurationsController@update_contact'
            ]);
        });
    
        Route::get('panel_de_administracion', [
            'as' => 'settings.panel',
            'uses' => 'Backend\ConfigurationsController@administrative_panel'
        ]);
        Route::post('panel_de_administracion', [
            'as' => 'settings.panel',
            'uses' => 'Backend\ConfigurationsController@update_administrative_panel'
        ]);
    });
    
    #Ruta del Resumen
    Route::get('resumen', [
        'as'   => 'dashboard',
        'uses' => 'Backend\DashboardController@index',
    ]);
    Route::get('/', function()
    {
        return redirect(route('dashboard'));
    });
    
    #editar perfil
    Route::get('editar_perfil', [
        'as'   => 'profile.edit',
        'uses' => 'Backend\UsersController@profile',
    ]);
    Route::post('editar_perfil', [
        'as'   => 'profile.edit',
        'uses' => 'Backend\UsersController@update_profile',
    ]);
    
    #ver perfil
    Route::get('perfil/{username}', [
        'as'   => 'profile.details',
        'uses' => 'Backend\UsersController@details',
    ])->where(['username' => '^(?!agregar)[0-9a-zA-Z_-]+$']);
    
    #actividades recientes
    Route::get('actividades_recientes', [
        'as'   => 'recent_activities',
        'uses' => 'Backend\RecentActivitiesController@index',
        'middleware' => 'admin'
    ]);
    Route::post('actividades_recientes', [
        'as'   => 'recent_activities',
        'uses' => 'Backend\RecentActivitiesController@ajax_recent_activities',
        'middleware' => 'admin'
    ]);
});

/**
 * Rutas de autenticación
 */

Route::get('login', [
    'uses' => 'Backend\AuthController@getLogin',
    'as'   => 'login',
]);

Route::post('login', [
    'uses' => 'Backend\AuthController@postLogin',
    'as'   => 'login',
]);

Route::get('logout', [
    'uses' => 'Backend\AuthController@getLogout',
    'as'   => 'logout',
]);

Route::get('test', [
    'uses' => 'Backend\TestsController@jasper',
    'as'   => 'test',
]);

/**
 * Rutas para enviar Correo Electronico de recuperacion de contraseña
 Route::resource('mail','EmailController')
 */

 Route::get('password/email', [
    'uses' => 'Auth\PasswordController@getEmail',
    'as'   => 'password/email',
]);

Route::post('password/email', [
    'uses' => 'Auth\PasswordController@postEmail',
    'as'   => 'password/email',
]);


/**
 * nuevas Rutas para enviar marcas
 */
Route::get('nueva_marca', [
    'as'   => 'email.store',
    'uses' => 'Backend\EmailController@add'
            ]);
Route::post('nueva_marca', [
    'as'   => 'email.store',
    'uses' => 'Backend\EmailController@store'
            ]);