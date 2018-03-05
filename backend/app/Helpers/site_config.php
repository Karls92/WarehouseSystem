<?php
/**
 * Created by Carmen Bravo.
 * Date: 12-Dic-17
 * Time: 10:30 AM
 */

function site_config()
{
    $config["site_name"]  = "Name of the WebSite or the name of the Company.";
    $config["created_at"] = "2018";
    
    $config["maker"]       = "Carmen Bravo";
    $config["maker_link"]  = "http://systemsinc.000webhost.com/";
    $config['false_maker'] = 'Name of the company';
    
    $config["panel_name"]            = 'Warehouse Manage System';
    $config["minimalist_panel_name"] = '<b>W</b>18';
    $config["version"]               = '';
    
    $config["months"] = array(
        '01' => 'Jan',
        '02' => 'Feb',
        '03' => 'Mar',
        '04' => 'Apr',
        '05' => 'May',
        '06' => 'Jun',
        '07' => 'Jul',
        '08' => 'Aug',
        '09' => 'Sep',
        '10' => 'Oct',
        '11' => 'Nov',
        '12' => 'Dic',
    );
    
    $config["days"] = array(
        '1' => 'Mon',
        '2' => 'Tiu',
        '3' => 'Wend',
        '4' => 'Thr',
        '5' => 'Fry',
        '6' => 'Sat',
        '7' => 'Sun',
    );
    
    $config["backgrounds"] = array(
        'bg-red',
        'bg-yellow',
        'bg-blue',
        'bg-olive',
        'bg-teal',
        'bg-navy',
        'bg-purple',
        'bg-gray',
        'bg-green',
        'bg-fuchsia',
        'bg-orange',
        'bg-black',
        'bg-lime',
    );
    
    /*
     *
     * Elemento lateral: Configuracion del sitio.
     *
     */
    
    $config["site_config"] = array(
        'logo'                 => array('url' => route('settings.logo')),
        'administrative_panel' => array('url' => route('settings.panel')),
        //'social_networks'      => array('url' => route('settings.social_networks')),
        //'contact'              => array('url' => route('settings.contact')),
        //'slide'                => array('translate' => 'Slide', 'url' => 'admin/configuracion/slide'),
        //'IDs de Estadísticas'     => 'admin/configuracion/idsestadisticas',
    );
    
    $config["lateral_elements"] = array();
    
    /*$config['lateral_elements']['visits'] = array(
        'visits' => array('url' => route('visits.index')),
        'referrers' => array('url' => route('visits.referrers')),
        'summary'       => array('url' => route('visits.summary')),
        'details'   => array(
            'icon'        => 'fa fa-eye',
            'level' => 3,
            'database'     => array(
                'visits' => 'visits',
                'summary'   => 'NO',
                'referrers' => 'NO'
            ),
        ),
    );*/
    
    $config['lateral_elements']['main_operations'] = array(
        'entry_orders' => array('url' => route('entry_order.index')),
        'out_orders' => array('url' => route('out_order.index')),
        'devolution_orders' => array('url' => route('devolution_order.index')),
        'details' => array(
            'icon'     => 'fa fa-cubes',
            'level'    => 3,
            'database' => array(
                'entry_orders' => array(
                    'table_name' => 'orders',
                    'where'      => array('type = entry'),
                ),
                'out_orders' => array(
                    'table_name' => 'orders',
                    'where'      => array('type = out'),
                ),
                'devolution_orders' => array(
                    'table_name' => 'orders',
                    'where'      => array('type = devolution'),
                ),
            ),
        ),
    );
    
    $config['lateral_elements']['reports'] = array(
        'reports'  => array('url' => route('report.reports')),
        'orders' => array('url' => route('report.orders')),
        'inventory'           => array('url' => route('report.inventory')),
        'details'          => array(
            'icon'     => 'fa fa-archive',
            'level'    => 3,
            'database' => array(
                'reports' => 'NO',
                'orders'  => 'NO',
                'inventory'           => 'NO',
            ),
        ),
    );
    
    $config['lateral_elements']['products'] = array(
        'url'     => route('product.index'),
        'details' => array(
            'icon'     => 'fa fa-barcode',
            'level'    => 0,
            'database' => array(
                'products' => 'products',
            ),
        ),
    );
    
    $config['lateral_elements']['clients'] = array(
        'url'     => route('client.index'),
        'details' => array(
            'icon'     => 'fa fa-group',
            'level'    => 0,
            'database' => array(
                'clients' => 'clients',
            ),
        ),
    );
    
    /*$config['lateral_elements']['users'] = array(
        'url'       => route('users.index'),
        'details'   => array(
            'icon'     => 'fa fa-group',
            'level'    => 0,
            'database' => array(
                'users' => 'users',//modelo a utilizar
            ),
        ),
    );*/
    
    $config['lateral_elements']['products_configuration'] = array(
        'classifications'  => array('url' => route('classification.index')),
        'units_of_measure' => array('url' => route('unit_of_measure.index')),
        'brands'           => array('url' => route('brand.index')),
        'models'           => array('url' => route('model.index')),
        'details'          => array(
            'icon'     => 'fa fa-product-hunt',
            'level'    => 0,
            'database' => array(
                'units_of_measure' => 'units_of_measure',
                'classifications'  => 'classifications',
                'brands' => array(
                    'table_name' => 'brands',
                    'where'      => array('id != 1'),
                ),
                'models' => array(
                    'table_name' => 'models',
                    'where'      => array('id != 1'),
                ),
            ),
        ),
    );
    
    $config['lateral_elements']['locations'] = array(
        'states'  => array('url' => route('state.index')),
        'cities'  => array('url' => route('city.index')),
        'details' => array(
            'icon'     => 'fa fa-map-marker',
            'level'    => 0,
            'database' => array(
                'states' => 'states',
                'cities' => 'cities',
            ),
        ),
    );
    
    return $config;
}
