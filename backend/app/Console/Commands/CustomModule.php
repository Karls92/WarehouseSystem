<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CustomModule extends Command
{
    private $pluralized_name;
    private $singular_name;
    private $pluralized_name_formatted;
    private $singular_name_formatted;
    private $pluralized_name_translated;
    private $singular_name_translated;
    private $pluralized_variable;
    private $singular_variable;
    private $gender_the;
    private $gender_this;
    private $gender_none;
    private $male_name;
    private $has_image;
    private $has_datatables;
    private $has_details;
    private $is_parent;
    private $wrong_translated;
    private $wrong_pluralized;
    private $plural_exceptions;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un módulo para gestionarlo básicamente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->plural_exceptions = array(
            'unit of measure' => 'units of measure',
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->pluralized_name = trim(strtolower($this->pluralize($this->argument('module'))));
        $this->pluralized_name_formatted = str_replace(' ','',ucwords($this->pluralized_name));
        $this->pluralized_variable = str_replace(' ','_',strtolower($this->pluralized_name));
        
        $this->singular_name = trim(strtolower($this->argument('module')));
        $this->singular_name_formatted = str_replace(' ','',ucwords($this->singular_name));
        $this->singular_variable = str_replace(' ','_',strtolower($this->singular_name));
        
        if(!$this->confirm('El nombre pluralizado es '.$this->pluralized_name.'? [y|n]'))
        {
            $this->wrong_pluralized = true;
    
            $this->pluralized_name = $this->ask('Escribe el plural manualmente');
        }
        else
        {
            $this->wrong_pluralized = false;
        }
        
        if(\Lang::has('messages.full.'.$this->singular_name.'.singular'))
        {
            $this->wrong_translated = false;
            
            $this->singular_name_translated = trans('messages.full.'.$this->singular_name.'.singular');
            $this->pluralized_name_translated = trans('messages.full.'.$this->singular_name.'.plural');
        }
        else
        {
            $this->wrong_translated = true;
    
            $this->singular_name_translated = strtolower(trim($this->ask('Escribe el singular traducido manualmente')));
            $this->pluralized_name_translated = strtolower(trim($this->ask('Escribe el plural traducido manualmente')));
        }
    
        $this->has_datatables = $this->confirm('Habilitar registros extensos? [y|n]');
        $this->has_details = $this->confirm('Habrán datos suficientes para mostrar detalles? [y|n]');
        $this->is_parent = $this->confirm('Dependerán de éste modelo? [y|n]');
        $this->male_name = $this->confirm('Es una palabra masculina? [y|n]');
        $this->has_image = $this->confirm('Habrá imagen? [y|n]');
        
        if($this->male_name === true)
        {
            $this->gender_the = 'el';
            $this->gender_this = 'éste';
            $this->gender_none = 'ningún';
        }
        else
        {
            $this->gender_the = 'la';
            $this->gender_this = 'ésta';
            $this->gender_none = 'ninguna';
        }
        
        $this->make_controller();
        $this->make_model();
        $this->make_migration();
        $this->make_seeder();
        $this->make_views();
        $this->make_copy_paste();
        
        if($this->has_image)
        {
            $this->make_dir_images();
        }
    }
    
    /**
     * Creando el algo
     */
    public function make_ALGO()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DEL MODELO');
        
        $controller_path = app_path('Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'Backend'.DIRECTORY_SEPARATOR);
        $name_controller = $this->pluralized_name_formatted.'Controller.php';
    
        $content = '';
        
        $this->write_file($controller_path,$name_controller,$content);
        $this->subtitle_format('CONTROLADOR CREADO CORRECTAMENTE');
    }
    
    /**
     * Creando la carpeta de imagenes
     */
    public function make_dir_images()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DE LA CARPETA DE LAS IMAGENES');
    
        $img_path = base_path('..'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR);
        $images_path = $img_path.$this->pluralized_variable.DIRECTORY_SEPARATOR;
        
        try
        {
            if(!file_exists($images_path))
            {
                mkdir($images_path);
            }
    
            $this->copy_file($img_path.'index.html',$images_path.'index.html');
            $this->copy_file($img_path.'default.png',$images_path.'default.png');
        }
        catch(\Exception $e)
        {
            $this->error('Algo ocurrió: '.$e->getMessage());die;
        }
        
        $this->subtitle_format('CARPETA DE IMAGENES CREADA CORRECTAMENTE');
    }
    
    /**
     * Creando las vistas
     */
    public function make_views()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DE LAS VISTAS');
        
        if(!file_exists(base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.$this->singular_variable.DIRECTORY_SEPARATOR)))
        {
            mkdir(base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.$this->singular_variable.DIRECTORY_SEPARATOR));
        }
        
        $this->make_view_index();
        $this->make_view_add();
        $this->make_view_edit();
        
        $this->subtitle_format('VISTAS CREADAS CORRECTAMENTE');
    }
    
    /**
     * Creando el index view
     */
    public function make_view_index()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DEL INDEX VIEW');
        
        $view_path = base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.$this->singular_variable.DIRECTORY_SEPARATOR);
        $name_view = 'index.blade.php';
        
        if($this->has_image === true)
        {
            $th_image = '
                            <th width="10%">Imagen</th>';
    
            $td_image = '
                                <td><img src="<?=img_dir(\''.$this->pluralized_variable.'/\'.$'.$this->singular_variable.'->image);?>" class="img-responsive img-thumbnail show_image_preview"></td>';
            
            $style_image1 = '#table_list td:first-child,';
            $style_image2 = '#table_list th:first-child, #table_list td:first-child,';
        }
        else
        {
            $th_image = '';
            $style_image1 = '';
            $style_image2 = '';
            $td_image = '';
        }
        
        if($this->has_datatables)
        {
            $per_page = 'parseInt(\'<?=$per_page;?>\')';
        }
        else
        {
            $per_page = '\'all\'';
        }
        
        if($this->has_details)
        {
            $th_details = '
                            <th width="45%">Detalles</th>';
            $td_details = '
                                <td>
                                    <b>Field: </b><?=$'.$this->singular_variable.'->field;?><br>
                                </td>';
            $style_details1 = '#table_list td:nth-child(2),';
            $style_details2 = '#table_list th:nth-child(2),#table_list td:nth-child(2)';
        }
        else
        {
            $th_details = '';
            $td_details = '';
            $style_details1 = '';
            $style_details2 = '';
        }
        
        if($style_image2 == '' && $style_details2 == '')
        {
            $style = '';
        }
        else
        {
            $style = $style_image2.$style_details2.'{
                display: none;
            }';
        }
        
        $content = '<?php /* @var App\Models\\'.$this->singular_name_formatted.' $'.$this->singular_variable.' */ ?>
@extends(\'backend.general.basic_list\')

@section(\'list_content\')
    <div class="col-md-12">
        <h4><?= ($'.$this->pluralized_variable.'_qty != 1) ? $'.$this->pluralized_variable.'_qty.\' '.camel_case_text($this->pluralized_name_translated).'\' : $'.$this->pluralized_variable.'_qty.\' '.camel_case_text($this->singular_name_translated).'\';?> en total <a href="<?=route(\''.$this->singular_variable.'.store\');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Agregar</p></a></h4>
        <br/>
        <?php
            if($'.$this->pluralized_variable.'_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>'.$th_image.'
                            <th width="30%">'.camel_case_text($this->singular_name_translated).'</th>'.$th_details.'
                            <th width="15%">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($'.$this->pluralized_variable.' as $'.$this->singular_variable.')
                        {
                            ?>
                            <tr>'.$td_image.'
                                <td>
                                    <?=$'.$this->singular_variable.'->name;?>
                                </td>'.$td_details.'
                                <td>
                                    <a href="<?=route(\''.$this->singular_variable.'.update\',[\'slug\' => $'.$this->singular_variable.'->slug]);?>" class="btn btn-primary" title="Editar '.$this->gender_this.' '.$this->singular_name_translated.'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$'.$this->singular_variable.'->id;?>" class="btn btn-danger confirmation_delete_modal" title="Borrar '.$this->gender_this.' '.$this->singular_name_translated.'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            }
            else
            {
                well(\'Sin '.camel_case_text($this->pluralized_name_translated).'\',\'No hay '.$this->gender_none.' '.$this->singular_name_translated.' en el sistema.\');
            }
        ?>
    </div><!-- /.col-md-12 -->
@endsection

@section(\'css_header\')
    <style>
        '.$style_image1.$style_details1.'#table_list td:last-child{
            text-align: center;
        }
        /* xs solamente: */
        @media (max-width: 768px){
            '.$style.'
        }
    </style>
@endsection

@section(\'js_footer\')
    <script>
        $(\'#text_delete_modal\').html(\''.camel_case_text($this->singular_name_translated).'\');
        var ajax_url_delete = \'<?=route(\''.$this->singular_variable.'.delete\',[\'id\' => \'\']);?>/\';

        var registers_qty = parseInt(\'<?=count($'.$this->pluralized_variable.');?>\');
        var per_page = '.$per_page.';
        var ajax_registers = \'<?=route(\''.$this->singular_variable.'.index\');?>\';
        var _token = \'<?=csrf_token();?>\';

        $(document).ready(function()
        {

        });
    </script>
@endsection
';
        
        $this->write_file($view_path,$name_view,$content);
        $this->subtitle_format('INDEX VIEW CREADO CORRECTAMENTE');
    }
    
    /**
     * Creando el index view
     */
    public function make_view_add()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DEL ADD VIEW');
        
        $view_path = base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.$this->singular_variable.DIRECTORY_SEPARATOR);
        $view_name = 'add.blade.php';
        
        if($this->has_image)
        {
            $img_form = ' enctype="multipart/form-data"';
            $img_input = '
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Imagen</label>
            <div class="col-sm-5">
                <input name="image" type="file" class="form-control" />
                <small class="text-muted">Formatos permitidos: png|jpg|jpeg.</small>
            </div>
        </div>';
        }
        else
        {
            $img_form = '';
            $img_input = '';
        }
        
        $content = '@extends(\'backend.general.form_basic\')

@section(\'form_content\')
    <form class="form-horizontal" method="POST"'.$img_form.' action="<?=route(\''.$this->singular_variable.'.store\');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">
        
        <div class="form-group">
            <label for="#formName" class="col-sm-2 control-label">'.camel_case_text($this->singular_name_translated).'</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="formName" value="<?=old(\'name\');?>" placeholder="¿'.camel_case_text($this->singular_name_translated).'?" required autocomplete="off" autofocus>
            </div>
        </div>'.$img_input.'
        
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Agregar</button>
            </div>
        </div>
    </form>
@endsection

@section(\'css_header\')
    <style>

    </style>
@endsection

@section(\'js_footer\')
    <script>
        $(document).ready(function()
        {
        
        });
    </script>
@endsection

';
        
        $this->write_file($view_path,$view_name,$content);
        $this->subtitle_format('ADD VIEW CREADO CORRECTAMENTE');
    }
    
    /**
     * Creando el index view
     */
    public function make_view_edit()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DEL EDIT VIEW');
        
        $view_path = base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.$this->singular_variable.DIRECTORY_SEPARATOR);
        $view_name = 'edit.blade.php';
        
        if($this->has_image)
        {
            $img_form = ' enctype="multipart/form-data"';
            $img_input = '
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Imagen</label>
            <div class="row col-sm-4">
                <div class="col-sm-4">
                    <img src="<?=img_dir(\''.$this->pluralized_variable.'/\'.$'.$this->singular_variable.'->image);?>" id="formImage" alt="" class="img-responsive img-thumbnail" />
                </div>
                <div class="col-sm-8">
                    <input type="file" name="image">
                    <p class="text-muted">Formatos permitidos: png|jpg|jpeg.</p>
                </div>
            </div>
        </div>';
        }
        else
        {
            $img_form = '';
            $img_input = '';
        }
        
        
        $content = '<?php /* @var App\Models\\'.$this->singular_name_formatted.' $'.$this->singular_variable.' */ ?>
@extends(\'backend.general.form_basic\')

@section(\'form_content\')
    <form class="form-horizontal" method="POST"'.$img_form.' action="<?=route(\''.$this->singular_variable.'.update\',[\'slug\' => $'.$this->singular_variable.'->slug]);?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">
        
        <div class="form-group">
            <label for="#formName" class="col-sm-2 control-label">'.camel_case_text($this->singular_name_translated).'</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="formName" value="<?=$'.$this->singular_variable.'->name;?>" placeholder="¿Qué cambios le harás al campo '.camel_case_text($this->singular_name_translated).'?" required autocomplete="off" autofocus>
            </div>
        </div>'.$img_input.'
        
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Guardar cambios</button>
            </div>
        </div>
    </form>
@endsection

@section(\'css_header\')
    <style>

    </style>
@endsection

@section(\'js_footer\')
    <script>
        $(document).ready(function()
        {
        
        });
    </script>
@endsection

';
        
        $this->write_file($view_path,$view_name,$content);
        $this->subtitle_format('EDIT VIEW CREADO CORRECTAMENTE');
    }
    
    /**
     * Creando el algo
     */
    public function make_seeder()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DEL SEEDER');
    
        $seeder_path = base_path('database'.DIRECTORY_SEPARATOR.'seeds'.DIRECTORY_SEPARATOR);
        $name_seeder = $this->singular_name_formatted.'Seeder.php';
        
        $content = '<?php

use Illuminate\Database\Seeder;

class '.$this->singular_name_formatted.'Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $'.$this->pluralized_variable.' = DB::table(\''.$this->pluralized_variable.'\')->get();
        
        if (!$'.$this->pluralized_variable.')
        {
            $false_'.$this->pluralized_variable.' = array();

            for($i = 1; $i <= 150; $i++)
            {
                array_push($false_'.$this->pluralized_variable.',array(
                    \'name\' => \''.$this->singular_name_translated.' #\'.$i,
                    \'slug\' => slug(\''.$this->singular_name_translated.' #\'.$i),
                    \'created_at\' => date(\'Y-m-d H:i:s\', time()),
                    \'updated_at\' => date(\'Y-m-d H:i:s\', time()),
                ));
            }

            DB::table(\''.$this->pluralized_variable.'\')->insert($false_'.$this->pluralized_variable.');
        }
    }
}';
        
        $this->write_file($seeder_path,$name_seeder,$content);
        $this->subtitle_format('SEEDER CREADO CORRECTAMENTE');
    }
    
    /**
     * Creando la migración
     */
    public function make_migration()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DE LA MIGRACIÓN');
        
        $migration_path = base_path('database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR);
        $name_migration = date('Y_m_d_His').'_create_'.$this->pluralized_variable.'_table.php';
        
        $content = '<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create'.$this->pluralized_name_formatted.'Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\''.$this->pluralized_variable.'\', function (Blueprint $table) {
            $table->increments(\'id\');
            $table->string(\'name\',100);
            $table->string(\'slug\',500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(\''.$this->pluralized_variable.'\');
    }
}';
        
        $this->write_file($migration_path,$name_migration,$content);
        $this->subtitle_format('MIGRACIÓN CREADA CORRECTAMENTE');
    }
    
    /**
     * Creando el copy/paste
     */
    public function make_copy_paste()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DEL COPY/PASTE');
        
        $copy_paste_path = base_path().DIRECTORY_SEPARATOR;
        $name_copy_paste = 'copy_paste.php';
        
        if($this->has_datatables)
        {
            $datatables_route = '
    Route::post(\'/\', [
        \'as\'   => \''.$this->singular_variable.'.index\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@ajax_'.$this->pluralized_variable.'\'
    ]);
';
        }
        else
        {
            $datatables_route = '';
        }
        
        /*if($this->is_parent)
        {
            $route_parent = 'Route::get(\'/\', [
        \'as\'   => \''.$this->singular_variable.'.index\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@index\'
    ]);
    Route::get(\'busqueda/{slug}\', [
        \'as\'   => \''.$this->singular_variable.'.search\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@index\'
    ])->where([\'slug\' => \'[0-9a-zA-Z_-]+\']);'.$datatables_route;
        }
        else
        {
            $route_parent = 'Route::get(\'/\', [
        \'as\'   => \''.$this->singular_variable.'.index\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@index\'
    ]);'.$datatables_route.'';
        }*/
        
        $content = '
-------------------------------------------------------------------------------------------------
#grupo de rutas de '.$this->pluralized_name_translated.'
Route::group([\'prefix\' => \''.str_replace(' ','_',$this->pluralized_name_translated).'\'], function ()
{
    Route::get(\'/\', [
        \'as\'   => \''.$this->singular_variable.'.index\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@index\'
    ]);'.$datatables_route.'
    Route::get(\'busqueda/{slug}\', [
        \'as\'   => \''.$this->singular_variable.'.search\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@index\'
    ])->where([\'slug\' => \'[0-9a-zA-Z_-]+\']);
    
    Route::get(\'agregar\', [
        \'as\'   => \''.$this->singular_variable.'.store\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@add\'
    ]);
    Route::post(\'agregar\', [
        \'as\'   => \''.$this->singular_variable.'.store\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@store\'
    ]);

    Route::get(\'editar/{slug}\', [
        \'as\'   => \''.$this->singular_variable.'.update\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@edit\'
    ])->where([\'slug\' => \'[0-9a-zA-Z_-]+\']);
    Route::post(\'editar/{slug}\', [
        \'as\'   => \''.$this->singular_variable.'.update\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@update\'
    ])->where([\'slug\' => \'[0-9a-zA-Z_-]+\']);

    Route::get(\'eliminar/{id}\', [
        \'as\'   => \''.$this->singular_variable.'.delete\',
        \'uses\' => \'Backend\\'.$this->pluralized_name_formatted.'Controller@delete\'
    ])->where([\'id\' => \'[0-9]+\']);
});

$config[\'lateral_elements\'][\''.$this->pluralized_variable.'\'] = array(
    \'url\'       => route(\''.$this->singular_variable.'.index\'),
    \'details\'   => array(
        \'icon\'     => \'fa fa-user\',
        \'level\'    => 3,
        \'database\' => array(
            \''.$this->pluralized_variable.'\' => \''.$this->pluralized_variable.'\', //en el site_config
        ),
    ),
);

$this->call('.$this->singular_name_formatted.'Seeder::class); // en DatabaseSeeder.php
';
        if($this->wrong_pluralized)
        {
            $content .= '
\''.$this->singular_name.'\' => \''.$this->pluralized_name.'\', // en el arreglo de excepciones de plurales
';
        }
    
        if($this->wrong_translated)
        {
            $content .= '
\''.$this->singular_name.'\' => [
    \'singular\' => \''.$this->singular_name_translated.'\',
    \'plural\' => \''.$this->pluralized_name_translated.'\',// en el arreglo de messages.php de lang
],
';
        }
        
        $content .= '
*** RECUERDA TERMINAR DE PREPARAR LA MIGRACION CON SU SEEDER, LAS RULES() DEL MODELO, TRADUCCIONES DE LOS CAMPOS DE VALIDACIÓN, Y EJECUTAR EL refresh_laravel ***

';
        
        $this->write_file($copy_paste_path,$name_copy_paste,$content);
        $this->subtitle_format('COPY/PASTE CREADO CORRECTAMENTE');
    }
    
    /**
     * Creando el modelo
     */
    public function make_model()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DEL MODELO');
    
        $model_path = app_path('Models'.DIRECTORY_SEPARATOR);
        $name_model = $this->singular_name_formatted.'.php';
    
        $content = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class '.$this->singular_name_formatted.' extends Model
{
    protected $table = \''.$this->pluralized_variable.'\';
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
    
        return [
            \'name\' => \'required|string|max:100\',
            \'slug\'  => \'max:500|unique:'.$this->pluralized_variable.',slug,\'.$id.\',id\' 
        ];
    }
    
    /**
     * Relaciones
     */
    
    /*#has many
    public function model_plural()
    {
        return $this->hasMany(\'App\Models\Model\',\''.$this->singular_variable.'_id\',\'id\');
    }
    
    #belongs to
    public function model_singular()
    {
        return $this->belongsTo(\'App\Models\Model\',\'model_id\',\'id\');
    }
    
    #many yo many
    public function model_plural()
    {
        return $this->belongsToMany(\'App\Models\Model\', \'alphabetical_singular_tables\', \''.$this->singular_variable.'_id\', \'model_id\')->withPivot(\'id\')->withTimestamps();
    }
    
    #has one
    public function model_singular()
    {
        return $this->hasOne(\'App\Models\Model\',\''.$this->singular_variable.'_id\',\'id\');
    }*/
    
    /**
     * Atributos virtuales
     */
    
    /*public function getAttributeNameAttribute()
    {
        return $this->field1.\' \'.$this->field2;
    }*/
}

';
    
        $this->write_file($model_path,$name_model,$content);
        $this->subtitle_format('MODELO CREADO CORRECTAMENTE');
    }
    
    /**
     * Creando el controlador
     */
    public function make_controller()
    {
        $this->title_format('INICIANDO PROCESO DE CREACIÓN DEL CONTROLADOR');
        
        $controller_path = app_path('Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'Backend'.DIRECTORY_SEPARATOR);
        $name_controller = $this->pluralized_name_formatted.'Controller.php';
    
        /*
         * Contenido del controlador
         */
        
        /*if($this->is_parent)
        {
            $parameter_parent = '$slug = null';
            $condition_parent = '
            
        if (is_null($slug))
        {
            $page_data[\''.$this->pluralized_variable.'\'] = '.$this->singular_name_formatted.'::limit($this->per_page)
                                          ->orderBy(\'created_at\', \'desc\')
                                          ->get();
                                          
            $page_data[\''.$this->pluralized_variable.'_qty\']     = '.$this->singular_name_formatted.'::count();
        }
        else
        {
            $page_data[\''.$this->pluralized_variable.'\'] = '.$this->singular_name_formatted.'::where(\'slug\',\'=\',$slug)
                                                                                                ->get();
                                          
            if(count($page_data[\''.$this->pluralized_variable.'\']) == 0)
            {
                flash(\'¡'.ucfirst($this->gender_the).' '.camel_case_text($this->singular_name_translated).' ha buscar no existe!\', \'danger\');
                
                return redirect(route(\''.$this->singular_variable.'.index\'));
            }
            
            $page_data[\''.$this->pluralized_variable.'_qty\'] = 1;
        }';
        }
        else
        {
            $parameter_parent = '';
            $condition_parent = '
        $page_data[\''.$this->pluralized_variable.'\'] = '.$this->singular_name_formatted.'::limit($this->per_page)
                                      ->orderBy(\'created_at\', \'desc\')
                                      ->get();
        $page_data[\''.$this->pluralized_variable.'_qty\']     = '.$this->singular_name_formatted.'::count();';
        }*/
        
        $content = '<?php
        
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\\'.$this->singular_name_formatted.';

class '.$this->pluralized_name_formatted.'Controller extends Controller
{
    private $icon;
    private $module;
    private $per_page;
    
    public function __construct()
    {
        $this->module   = \''.$this->pluralized_variable.'\';
        $this->icon     = site_config()[\'lateral_elements\'][$this->module][\'details\'][\'icon\'];
        $this->per_page = 100;
    }
    
    /**
     * Gestionar '.camel_case_text($this->pluralized_name_translated).'
     */
     
    #listar '.$this->pluralized_name_translated.'
    public function index($slug = null)
    {
        $page_data[\'breadcrumb\'] = array(
            array(
                \'name\' => \''.camel_case_text($this->pluralized_name_translated).'\',
                \'url\'  => route(\''.$this->singular_variable.'.index\'),
            ),
            array(
                \'name\' => \'Listado\',
            ),
        );
        
        $page_data[\'page_title\']    = \'Listado de '.camel_case_text($this->pluralized_name_translated).'\';
        $page_data[\'active_module\'] = $this->module;
        $page_data[\'active_submodule\'] = \''.$this->pluralized_variable.'\';
        $page_data[\'per_page\']      = $this->per_page;
        
        if (is_null($slug))
        {
            $page_data[\''.$this->pluralized_variable.'\'] = '.$this->singular_name_formatted.'::limit($this->per_page)
                                          ->orderBy(\'created_at\', \'desc\')
                                          ->get();
                                          
            $page_data[\''.$this->pluralized_variable.'_qty\']     = '.$this->singular_name_formatted.'::count();
        }
        else
        {
            $page_data[\''.$this->pluralized_variable.'\'] = '.$this->singular_name_formatted.'::where(\'slug\',\'=\',$slug)
                                                                                                ->get();
                                          
            if(count($page_data[\''.$this->pluralized_variable.'\']) == 0)
            {
                flash(\'¡'.ucfirst($this->gender_the).' '.camel_case_text($this->singular_name_translated).' ha buscar no existe!\', \'danger\');
                
                return redirect(route(\''.$this->singular_variable.'.index\'));
            }
            
            $page_data[\''.$this->pluralized_variable.'_qty\'] = 1;
        }
        
        return view(\'backend.'.$this->singular_variable.'.index\', $page_data);
    }';
        
        if($this->has_datatables)
        {
            $content .= '
    
    #ajax para cargar más '.$this->pluralized_name_translated.'
    public function ajax_'.$this->pluralized_variable.'(Request $request)
    {
        $data = array();
    
        $'.$this->pluralized_variable.' = '.$this->singular_name_formatted.'::offset($request->offset)->limit($this->per_page)->orderBy(\'created_at\',\'desc\')->get();
    
        foreach($'.$this->pluralized_variable.' as $'.$this->singular_variable.')
        {
            array_push($data,array(
                $'.$this->singular_variable.'->name,
            ));
        }
        
        die(json_encode($data));
    }';
        }
        
        $content .= '
        
    #agregar '.$this->singular_name_translated.'
    public function add()
    {
        $page_data[\'breadcrumb\'] = array(
            array(
                \'name\' => \''.camel_case_text($this->pluralized_name_translated).'\',
                \'url\'  => route(\''.$this->singular_variable.'.index\'),
            ),
            array(
                \'name\' => \'Agregar\',
            ),
        );
        
        $page_data[\'page_title\']    = \'Agregar '.camel_case_text($this->singular_name_translated).'\';
        $page_data[\'active_module\'] = $this->module;
        $page_data[\'active_submodule\'] = \''.$this->pluralized_variable.'\';
        $page_data[\'form_type\']     = \'success\';
        
        return view(\'backend.'.$this->singular_variable.'.add\', $page_data);
    }
    
    #guardar '.$this->singular_name_translated.'
    public function store(Request $request)
    {
        $'.$this->singular_variable.' = new '.$this->singular_name_formatted.'();
        
        #valores que podrían cambiar con el respectivo formato, y ademas son únicos.
        
        $this->validate($request, $'.$this->singular_variable.'->rules());
        
        $'.$this->singular_variable.'->name  = trim($request->name);
        $'.$this->singular_variable.'->field = trim($request->field);
        
        #creacion del slug
        $slug = slug($'.$this->singular_variable.'->name);
        $count = 2;
        
        while('.$this->singular_name_formatted.'::where(\'slug\',\'=\', $slug)->count() > 0)
        {
            $slug = slug($'.$this->singular_variable.'->name.\' \'.$count);
            $count++;
        }
        
        $'.$this->singular_variable.'->slug = $slug;
        
        if (secureSave($'.$this->singular_variable.'))
        {';
        
        if($this->has_image === true)
        {
            $content .= '
            $information_saved = true;
            
            if (!is_null($request->file(\'image\')))
            {
                if (permitted_extension($request->file(\'image\')->getClientOriginalExtension(), [\'png\', \'jpg\', \'jpeg\']))
                {
                    $new_image = $slug.\'.\'.strtolower($request->file(\'image\')->getClientOriginalExtension());
                    
                    if (resize_image($request->file(\'image\')->getRealPath(), \'img/'.$this->pluralized_variable.'\', $new_image, 400, 300))
                    {
                        $'.$this->singular_variable.'->image = $new_image;
                        
                        if (secureSave($'.$this->singular_variable.'))
                        {
                            $file_saved = true;
                        }
                        else
                        {
                            delete_file(\'img/'.$this->pluralized_variable.'\', $new_image);
                            
                            flash(\'¡Se ha guardado la información correctamente, pero ha ocurrido un error subiendo la imagen!\', \'warning\');
                            
                            $file_saved    = false;
                            $flash_message = true;
                        }
                    }
                }
                else
                {
                    flash(\'¡Se ha guardado la información correctamente, pero la imagen no tiene una extensión permitida!\', \'warning\');
                    
                    $file_saved    = false;
                    $flash_message = true;
                }
            }
        }
        else
        {
            $information_saved = false;
            $flash_message     = true;
            
            flash(\'¡Ha ocurrido un problema guardando la información!\', \'danger\');
        }
        
        if (isset($information_saved) && $information_saved || isset($file_saved) && $file_saved)
        {
            $data = array(
                \'activity\' => \'ha agregado '.$this->gender_the.' '.$this->singular_name_translated.' <a href="\'.route(\''.$this->singular_variable.'.update\', [\'slug\' => $'.$this->singular_variable.'->slug]).\'">\'.$'.$this->singular_variable.'->name.\'</a>\',
                \'icon\'     => $this->icon.\' bg-green\',
            );
            
            add_recent_activity($data);
            
            if (!isset($flash_message) || isset($file_saved) && $file_saved)
            {
                flash(\'¡Se ha guardado la información correctamente!\', \'success\');
            }
        }
        
        return redirect(route(\''.$this->singular_variable.'.index\'));
    }';
        }
        else
        {
            $content .= '
            $data = array(
                \'activity\' => \'ha agregado '.$this->gender_the.' '.$this->singular_name_translated.' <a href="\'.route(\''.$this->singular_variable.'.update\', [\'slug\' => $'.$this->singular_variable.'->slug]).\'">\'.$'.$this->singular_variable.'->name.\'</a>\',
                \'icon\'     => $this->icon.\' bg-green\',
            );
            
            add_recent_activity($data);
            
            flash(\'¡Se ha guardado la información correctamente!\', \'success\');
        }
        else
        {
            flash(\'¡Ha ocurrido un problema guardando la información!\', \'danger\');
        }
        
        return redirect(route(\''.$this->singular_variable.'.index\'));
    }';
        }
        
        if($this->has_image)
        {
            $image_condition = ' && (is_null($request->file(\'image\')) || count(array_diff_assoc($new_values, $old_values)) > 1)';
        }
        else
        {
            $image_condition = '';
        }
        
        $content .= '
    
    #editar '.$this->singular_name_translated.'
    public function edit($slug = null)
    {
        $page_data[\''.$this->singular_variable.'\'] = '.$this->singular_name_formatted.'::where(\'slug\',\'=\', $slug)->first();
        
        if(!is_null($page_data[\''.$this->singular_variable.'\']))
        {
            $page_data[\'breadcrumb\'] = array(
                array(
                    \'name\' => \''.camel_case_text($this->pluralized_name_translated).'\',
                    \'url\'  => route(\''.$this->singular_variable.'.index\'),
                ),
                array(
                    \'name\' => $page_data[\''.$this->singular_variable.'\']->name,
                    \'url\'  => route(\''.$this->singular_variable.'.update\',[\'slug\' => $page_data[\''.$this->singular_variable.'\']->slug]),
                ),
                array(
                    \'name\' => \'Editar\',
                ),
            );
    
            $page_data[\'page_title\']    = \'Editar '.camel_case_text($this->singular_name_translated).'\';
            $page_data[\'active_module\'] = $this->module;
            $page_data[\'active_submodule\'] = \''.$this->pluralized_variable.'\';
            $page_data[\'form_type\']     = \'primary\';
    
            return view(\'backend.'.$this->singular_variable.'.edit\', $page_data);
        }
        else
        {
            flash(\'¡No existe '.$this->gender_the.' '.$this->singular_name_translated.' a editar!\', \'danger\');
            
            return redirect(route(\''.$this->singular_variable.'.index\'));
        }
    }
    
    #actualizar '.$this->singular_name_translated.'
    public function update(Request $request, $slug = null)
    {
        $'.$this->singular_variable.' = '.$this->singular_name_formatted.'::where(\'slug\',\'=\', $slug)->first();
        
        if(!is_null($'.$this->singular_variable.'))
        {
            $old_values = $'.$this->singular_variable.'->attributesToArray();
            $new_values = $request->except(\'_token\');
            
            if (count(array_diff_assoc($new_values, $old_values)) > 0'.$image_condition.')
            {
                #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
        
                $this->validate($request, $'.$this->singular_variable.'->rules());
    
                $'.$this->singular_variable.'->name  = trim($request->name);
                $'.$this->singular_variable.'->field = trim($request->field);
                
                #creacion del slug
                $slug = slug($'.$this->singular_variable.'->name);
                $count = 2;
                
                while('.$this->singular_name_formatted.'::where(\'slug\',\'=\', $slug)->where(\'id\', \'!=\', $'.$this->singular_variable.'->id)->count() > 0)
                {
                    $slug = slug($'.$this->singular_variable.'->name.\' \'.$count);
                    $count++;
                }
                
                $'.$this->singular_variable.'->slug = $slug;
        
                if (secureSave($'.$this->singular_variable.'))
                {';
        
        if($this->has_image)
        {
            $content .= '
                    $information_saved = true;
                }
                else
                {
                    flash(\'¡Ha ocurrido un problema guardando la información!\', \'danger\');
            
                    $information_saved = false;
                    $flash_message     = true;
                }
            }
            else
            {
                flash(\'¡No se produjo ningún cambio!\', \'info\');
        
                $flash_message = true;
            }
    
            if (!is_null($request->file(\'image\')) && (isset($information_saved) && $information_saved || !isset($information_saved)))
            {
                if (permitted_extension($request->file(\'image\')->getClientOriginalExtension(), [\'png\', \'jpg\', \'jpeg\']))
                {
                    $new_image = $slug.\'.\'.strtolower($request->file(\'image\')->getClientOriginalExtension());
                    $old_image = $'.$this->singular_variable.'->image;
            
                    if (resize_image($request->file(\'image\')->getRealPath(), \'img/'.$this->pluralized_variable.'\', $new_image, 400, 300))
                    {
                        if ($new_image != $old_image)
                        {
                            $'.$this->singular_variable.'->image = $new_image;
                    
                            if (secureSave($'.$this->singular_variable.'))
                            {
                                delete_file(\'img/'.$this->pluralized_variable.'\', $old_image);
                        
                                $file_saved = true;
                            }
                            else
                            {
                                delete_file(\'img/'.$this->pluralized_variable.'\', $new_image);
                        
                                if (isset($information_saved) && $information_saved)
                                {
                                    flash(\'¡Se ha guardado la información correctamente, pero ha ocurrido un error subiendo la imagen!\', \'warning\');
                                }
                                else
                                {
                                    flash(\'¡Ha ocurrido un error subiendo la imagen!\', \'warning\');
                                }
                        
                                $file_saved    = false;
                                $flash_message = true;
                            }
                        }
                        else
                        {
                            $file_saved = true;
                        }
                    }
                }
                else
                {
                    if (isset($information_saved) && $information_saved)
                    {
                        flash(\'¡Se ha guardado la información correctamente, pero la imagen no tiene una extensión permitida!\', \'warning\');
                    }
                    else
                    {
                        flash(\'¡La imagen no tiene una extensión permitida!\', \'danger\');
                    }
            
                    $file_saved    = false;
                    $flash_message = true;
                }
            }
    
            if (isset($information_saved) && $information_saved || isset($file_saved) && $file_saved)
            {
                $data = array(
                    \'activity\' => \'ha editado '.$this->gender_the.' '.$this->singular_name_translated.' <a href="\'.route(\''.$this->singular_variable.'.update\',[\'slug\' => $'.$this->singular_variable.'->slug]).\'">\'.$'.$this->singular_variable.'->name.\'</a>\',
                    \'icon\'     => $this->icon.\' bg-blue\',
                );
        
                add_recent_activity($data);
        
                if (!isset($flash_message) || isset($file_saved) && $file_saved)
                {
                    flash(\'¡Se ha guardado la información correctamente!\', \'success\');
                }
            }
        }
        else
        {
            flash(\'¡No existe '.$this->gender_the.' '.$this->singular_name_translated.' a editar!\', \'danger\');
        }
        
        return redirect(route(\''.$this->singular_variable.'.index\'));
    }
    ';
        }
        else
        {
            $content .= '
                    $data = array(
                        \'activity\' => \'ha editado '.$this->gender_the.' '.$this->singular_name_translated.' <a href="\'.route(\''.$this->singular_variable.'.update\', [\'slug\' => $'.$this->singular_variable.'->slug]).\'">\'.$'.$this->singular_variable.'->name.\'</a>\',
                        \'icon\'     => $this->icon.\' bg-blue\',
                    );
                
                    add_recent_activity($data);
                
                    flash(\'¡Se ha guardado la información correctamente!\', \'success\');
                }
                else
                {
                    flash(\'¡Ha ocurrido un problema guardando la información!\', \'danger\');
                }
            }
            else
            {
                flash(\'¡No se produjo ningún cambio!\', \'info\');
            }
        }
        else
        {
            flash(\'¡No existe '.$this->gender_the.' '.$this->singular_name_translated.' a editar!\', \'danger\');
        }
        
        return redirect(route(\''.$this->singular_variable.'.index\'));
    }';
        }
        
        if($this->has_image === true)
        {
            $image_delete = '
                delete_file(\'img/'.$this->pluralized_variable.'\',$'.$this->singular_variable.'->image);
            ';
        }
        else
        {
            $image_delete = '';
        }
        
        $content .= '
        
    #eliminar '.$this->singular_name_translated.'
    public function delete($id = null)
    {
        $'.$this->singular_variable.' = '.$this->singular_name_formatted.'::find($id);
        
        if (!is_null($'.$this->singular_variable.'))
        {
            if (secureDelete($'.$this->singular_variable.'))
            {
                $data = array(
                    \'activity\' => \'ha eliminado '.$this->gender_the.' '.$this->singular_name_translated.' <a href="#">\'.$'.$this->singular_variable.'->name.\'</a>\',
                    \'icon\'     => $this->icon.\' bg-red\',
                );
                
                add_recent_activity($data);'.$image_delete.'
                
                flash(\'¡'.ucfirst($this->gender_the).' '.camel_case_text($this->singular_name_translated).' \'.$'.$this->singular_variable.'->name.\' ha sido exitosamente eliminado!\', \'success\');
            }
            else
            {
                flash(\'¡Ha ocurrido un error desconocido eliminando '.$this->gender_the.' '.$this->singular_name_translated.' \'.$'.$this->singular_variable.'->name.\'!\', \'danger\');
            }
        }
        else
        {
            flash(\'¡'.ucfirst($this->gender_the).' '.camel_case_text($this->singular_name_translated).' a eliminar no se encuentra en el sistema!\', \'danger\');
        }
        
        return redirect(route(\''.$this->singular_variable.'.index\'));
    }
}';
        
        $this->write_file($controller_path,$name_controller,$content);
        $this->subtitle_format('CONTROLADOR CREADO CORRECTAMENTE');
    }
    
    /**
     * Escribir un archivo
     */
    public function write_file($path,$file, $content = 'Contenido vacío')
    {
        try
        {
            $this->info('Intentando escribir en el archivo: '.$file);
            
            if(file_put_contents($path.$file,$content,FILE_APPEND | LOCK_EX) === false)
            {
                $this->error('No se pudo escribir en el archivo.');die;
            }
            else
            {
                $this->info('Se ha escrito correctamente en el archivo.');
            }
        }
        catch (\Exception $e)
        {
            $this->error('Algo ocurrió: '.$e->getMessage());die;
        }
    }
    
    /**
     * Escribir un titulo en la consola
     */
    public function title_format($text)
    {
        $this->info('
        ***************************************************
        ***************************************************
        '.strtoupper($text).'
        ***************************************************
        ***************************************************
        ');
    }
    
    /**
     * Escribir un subtitulo en la consola
     */
    public function subtitle_format($text)
    {
        $this->info('        *** '.strtoupper($text).' ***');
    }
    
    /**
     * Pluralizar texto
     */
    public function pluralize($word)
    {
        $last_letter = substr($word,-1);
        
        if(isset($this->plural_exceptions[$word]))
        {
            return $this->plural_exceptions[$word];
        }
        else
        {
            switch($last_letter)
            {
                case 'y':
                    return substr($word,0,-1).'ies';
                case 's':
                    return $word.'es';
                default:
                    return $word.'s';
            }
        }
    }
    
    /**
     * Crear o abrir archivo
     */
    public function open_file($path, $file, $type = 'el archivo')
    {
        #creando el archivo
        try
        {
            $this->info('Intentando abrir '.$type.': '.$file);
            return fopen($path.$file,'w+');
            $this->info('Archivo abierto correctamente.');
        }
        catch (\Exception $e)
        {
            $this->error('Algo ocurrió: '.$e->getMessage());die;
        }
    }
    
    /**
     * Cerrar archivo
     */
    public function close_file($file)
    {
        #cerrando el archivo
        try
        {
            $this->info('Cerrando archivo');
            fclose($file);
            $this->info('Archivo cerrado correctamente.');
        }
        catch (\Exception $e)
        {
            $this->error('Algo ocurrió: '.$e->getMessage());die;
        }
    }
    
    /**
     * Copiar archivo
     */
    
    public function copy_file($source, $dest)
    {
        if (is_dir($source))
        {
            $dir_handle = opendir($source);
            while ($file = readdir($dir_handle))
            {
                if ($file != "." && $file != "..")
                {
                    if (!file_exists($dest.DIRECTORY_SEPARATOR))
                    {
                        mkdir($dest.DIRECTORY_SEPARATOR);
                    }
                    
                    if (is_dir($source.DIRECTORY_SEPARATOR.$file))
                    {
                        $this->copy_file($source.DIRECTORY_SEPARATOR.$file, $dest.DIRECTORY_SEPARATOR.$file);
                    }
                    else
                    {
                        copy($source.DIRECTORY_SEPARATOR.$file, $dest.DIRECTORY_SEPARATOR.$file);
                    }
                }
            }
            closedir($dir_handle);
        }
        else
        {
            copy($source, $dest);
        }
    }
}
