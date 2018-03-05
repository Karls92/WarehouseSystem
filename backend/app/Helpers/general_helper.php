<?php
# ----------------------------------------------------------------------------------------
#
# Helper general to Laravel
# Versión 2.0
#
# Escrito por Athena el 14/12/2015
#
# ----------------------------------------------------------------------------------------

/*
 *
 * Funciones para los ASSETS
 *
 */

function img_dir($file = '')
{
    if(strpos(strtolower($file), 'default.png') === false && (!file_exists(assets_path('img/'.$file)) || !is_file(assets_path('img/'.$file))))
    {
        $file_parts = explode('/',$file);
    
        unset($file_parts[count($file_parts)-1]);
        
        $file = trim(implode("/", $file_parts),'/').'/default.png';
    }
    
    return public_path('assets/img/'.$file);
}

function css_dir($file = "", $tag = true)
{
    return ($tag ? '<link rel="stylesheet" type="text/css" href="' : '').public_path('assets/css/'.$file).($tag ? '" />' : '');
}

function js_dir($file = '', $tag = true)
{
    return ($tag ? '<script type="text/javascript" src="' : '').public_path('assets/js/'.$file).($tag ? '"></script>' : '');
}

function plugins_css_dir($file = '', $tag = true)
{
    return ($tag ? '<link rel="stylesheet" type="text/css" href="' : '').public_path('assets/plugins/'.$file).($tag ? '" />' : '');
}

function plugins_js_dir($file = '', $tag = true)
{
    return ($tag ? '<script type="text/javascript" src="' : '').public_path('assets/plugins/'.$file).($tag ? '"></script>' : '');
}

function reports_dir($file = '', $tag = true)
{
    return public_path('assets/reports/'.$file);
}

function assets_path($file = '')
{
    return base_path('../public/assets/'.$file);
}

/*
 *
 * Funciones para los ARCHIVOS
 *
 */

function permitted_extension($extension,$permitted_extensions)
{
    if (in_array(strtolower($extension), $permitted_extensions))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function delete_file($file_path,$file_name)
{
    $file_path = trim($file_path,'/').'/';
    
    if(!in_array($file_name,['default.png','index.blade.php','index.html']) && file_exists(assets_path($file_path.$file_name)))
    {
        try
        {
            return unlink(assets_path($file_path.$file_name));
        }
        catch(Exception $e)
        {
            return false;
        }
    }
    else
    {
        return true;
    }
}

function save_file($file,$permitted_extensions,$file_path,$file_name,$file_reference = 'la imagen')
{
    if (!is_null($file))
    {
        if (permitted_extension($file->getClientOriginalExtension(),$permitted_extensions))
        {
            $file_path = assets_path($file_path.'/');
            
            if ($file->move($file_path,$file_name.'.'.strtolower($file->getClientOriginalExtension())))
            {
                return true;
            }
            else
            {
                flash('¡It could not save '.$file_reference.'!', 'danger');
                
                return false;
            }
        }
        else
        {
            flash('¡'.ucfirst($file_reference).' does not an allowed format!', 'danger');
            
            return false;
        }
    }
    else
    {
        flash('¡'.ucfirst($file_reference).' did not upload into the system!', 'danger');
        
        return false;
    }
}

function resize_image($image, $file_path, $file_name, $width, $height = null)
{
    if(is_null($height))
    {
        $new_image = Intervention\Image\ImageManagerStatic::make($image)->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
    else
    {
        $new_image = Intervention\Image\ImageManagerStatic::make($image)->resize($width, $height);
    }
    
    $file_path = assets_path($file_path.'/');
    
    if($new_image->save($file_path.$file_name))
    {
        return true;
    }
    else
    {
        flash('¡It could not save the image!', 'danger');
        
        return false;
    }
}

function obtener_imagen($imagen, $elemento)
{
    if ($imagen != 'no_img.png')
    {
        $filename  = explode('.', $imagen);
        $imagen    = $filename[0];
        $extension = $filename[1];
        
        return $imagen.$elemento.'.'.$extension;
    }
    else
    {
        return 'no_img.png';
    }
    
}

function obtener_extension($file)
{
    $extension = explode('.', $file);
    $extension = $extension[count($extension) - 1];
    
    return strtolower($extension);
}

function obtener_carpeta($extension)
{
    if ($extension == 'doc' || $extension == 'docx')
    {
        $carpeta = 'word';
    }
    if ($extension == 'rar' || $extension == 'zip')
    {
        $carpeta = 'comprimido';
    }
    if ($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv')
    {
        $carpeta = 'excel';
    }
    if ($extension == 'pptx')
    {
        $carpeta = 'diapositiva';
    }
    if ($extension == 'txt')
    {
        $carpeta = 'txt';
    }
    if ($extension == 'pdf')
    {
        $carpeta = 'pdf';
    }
    if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif')
    {
        $carpeta = 'imagen';
    }
    
    return $carpeta;
}


function gota_de_agua($imagen, $texto = null, $size = '24')
{
    $ci =& get_instance();
    
    $ci->image_lib->clear();
    
    if (is_null($texto))
    {
        $texto = config_item('nombre_sitio');
    }
    
    $config = array(
        'source_image'       => $imagen,
        'quality'            => 100,
        'wm_text'            => $texto,
        'wm_type'            => 'text',
        'wm_font_path'       => 'assets/fonts/gunplay.ttf',
        'wm_font_size'       => $size,
        'wm_font_color'      => 'ffffff',
        'wm_vrt_alignment'   => 'bottom',
        'wm_hor_alignment'   => 'left',
        'wm_padding'         => '5',
        'wm_shadow_color'    => '00AAAA',
        'wm_shadow_distance' => 1,
    );
    
    $ci->image_lib->initialize($config);
    
    if ($ci->image_lib->watermark())
    {
        return true;
    }
    else
    {
        return false;
    }
}

/*
 *
 * Funciones para la INTERFAZ
 *
 */

function add_recent_activity($data)
{
    $activity = new App\Models\RecentActivity();
    
    $activity->user     = '<a href="'.route('profile.details', ['username' => \Auth::user()->username]).'">'.\Auth::user()->full_name.'</a>';
    $activity->activity = $data['activity'];
    $activity->icon     = $data['icon'];
    $activity->date     = date('Y-m-d H:i:s', time());
    
    return secureSave($activity);
}

function show_alert($type = 'primary', $title = null, $message = 'Por favor escriba un mensaje', $dismiss = true)
{
    $icon = array(
        'success' => 'fa-check',
        'warning' => 'fa-warning',
        'info'    => 'fa-info',
        'danger'  => 'fa-ban',
    );
    ?>
    <div class="callout callout-<?= $type; ?> <?= ($dismiss) ? 'alert alert-dismissable' : ''; ?>">
        <?php
        if ($dismiss)
        {
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?php
        }
        
        if (!is_null($title))
        {
            ?><h4><i class="icon fa <?= $icon[$type]; ?>"></i> <?= $title; ?></h4><?php
        }
        
        if (is_array($message))
        {
            foreach ($message as $text)
            {
                ?>
                <li><?= $text ?></li>
                <?php
            }
        }
        else
        {
            ?>
            <p><?= $message; ?></p>
            <?php
        }
        ?>
    </div>
    <?php
}

function well($title, $message)
{
    ?>
    <div class="well well-lg well-flat">
        <h3><?=$title;?></h3>
        <p class="text-muted"><?= $message; ?></p>
    </div>
    <?php
}

/*
 *
 * Funcion para ENCRIPTACION
 *
 */

function encriptacion($input)
{
    return md5(md5($input).'creative');
}

/*
 *
 * Funciones para los CAPTCHAS
 *
 */

function generar_captcha()
{
    $col = array(
        0 => array(176, 204, 63),
        1 => array(255, 77, 77),
        2 => array(76, 126, 204),
        3 => array(54, 204, 199),
        4 => array(204, 125, 204),
        5 => array(204, 157, 106),
        6 => array(164, 204, 174),
        7 => array(61, 181, 204),
    );
    $al  = mt_rand(0, 7);
    
    $captcha = array(
        'word'        => mt_rand(1, 1000),
        'img_path'    => './incoming/',
        'img_url'     => url().'/'.'incoming/',
        'font_path'   => substr(APPPATH, 0, -12).'assets/fonts/gunplay.ttf',
        'img_width'   => '100',
        'img_height'  => '50',
        'expiration'  => 10,
        'word_length' => 8,
        'font_size'   => 16,
        'pool'        => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        
        'colors' => array(
            'background' => array($col[$al][0], $col[$al][1], $col[$al][2]),
            'border'     => array(100, 100, 100),
            'text'       => array(255, 255, 255),
            'grid'       => array(0, 0, 0),
        ),
    );
    
    return create_captcha($captcha);
}

/**
 *
 * Funciones para manejar base de datos
 */
function enable_query_log()
{
    \DB::enableQueryLog();
}

function last_query()
{
    $queries = \DB::getQueryLog();
    
    $last_query = end($queries);
    
    foreach ($last_query['bindings'] as $val) {
        $last_query['query'] = preg_replace('/\?/', "'{$val}'", $last_query['query'], 1);
    }
    
    dd($last_query['query']);
}

function getDBConfig($config = 'prefix')
{
    return \DB::connection()->getConfig($config);
}

function getRealQuery($query)
{
    $real_query = $query->toSql();
    
    foreach ($query->getBindings() as $val) {
        $real_query = preg_replace('/\?/', "'{$val}'", $real_query, 1);
    }
    
    return $real_query;
}

function secureSave($model)
{
    try
    {
        $model->save();
        
        return true;
    }
    catch(Exception $e)
    {
        return false;
    }
}

function secureDelete($model)
{
    try
    {
        $model->delete();
        
        return true;
    }
    catch(Exception $e)
    {
        return false;
    }
}
/*
 *
 * Funciones para manejar TEXTOS
 *
 */



function only_text($text)
{
    return remove_unnecessary_spaces(preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\']/', ' ', $text));
}

function mb_ucfirst($string, $encoding = 'utf8')
{
    $strlen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}

function camel_case_text($text, $min_qty_char = 4)
{
    $text = strtolower($text);
    $text = trim($text);
    
    $words  = explode(' ', $text);
    $final_text = '';
    
    foreach ($words as $word)
    {
        if (strlen($word) >= $min_qty_char)
        {
            $final_text = $final_text.' '.mb_ucfirst($word);
        }
        else
        {
            if($word == 'd\'')
            {
                $final_text = $final_text.' D\'';
            }
            else
            {
                $final_text = $final_text.' '.$word;
            }
        }
    }
    
    return trim($final_text);
}

function slug($text)
{
    $text = remove_unnecessary_spaces(strtolower($text));
    
    $characters = array(
        'á' => 'a',
        'é' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ú' => 'u',
        'ñ' => 'n',
        'ç' => 'c',
        'ø' => 'o',
        '$' => 's',
        '&' => 'y',
        'ÿ' => 'y',
        'ä' => 'a',
        'ë' => 'e',
        'ö' => 'o',
        'ü' => 'u',
        'â' => 'a',
        'ê' => 'e',
        'ô' => 'o',
        'û' => 'u',
        'à' => 'a',
        'è' => 'e',
        'ò' => 'o',
        'ù' => 'u',
        'å' => 'a',
    );
    
    $text = strtr($text, $characters);// toda coincidencia de caracteres en el texto, sera cambiado.
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);// cualquier cosa que no sea letra, numero o guion..se cambiara por -
    $text = preg_replace('/-+/', '-', $text);//muchos guiones juntos, cambiarlos por uno solo
    $text = trim($text, '-');// se quita cualquier guion al inicio o final del texto
    
    return $text;
}

function cortar_parrafo($texto, $cantidad_de_caracteres)
{
    return html_entity_decode(remove_unnecessary_spaces(trim(str_replace(array(
                                                                                "\r\n",
                                                                                "\r",
                                                                                "\n",
                                                                                "\t",
                                                                                "<br>",
                                                                                "<br/>",
                                                                                "<hr>",
                                                                                "<hr/>",
                                                                                PHP_EOL,
                                                                            ), " ", mb_substr(strip_tags($texto), 0, $cantidad_de_caracteres, 'UTF-8')))));
}

function remove_unnecessary_spaces($texto)
{
    return trim(preg_replace('/\s+/', ' ', str_replace('&nbsp;', ' ', $texto)));
}

function quitar_caracteres_especiales($texto)
{
    $texto = str_replace("á", "a", $texto);
    $texto = str_replace("é", "e", $texto);
    $texto = str_replace("í", "i", $texto);
    $texto = str_replace("ó", "o", $texto);
    $texto = str_replace("ú", "u", $texto);
    $texto = str_replace("Á", "A", $texto);
    $texto = str_replace("É", "E", $texto);
    $texto = str_replace("Í", "I", $texto);
    $texto = str_replace("Ó", "O", $texto);
    $texto = str_replace("Ú", "U", $texto);
    $texto = str_replace("ñ", "n", $texto);
    $texto = str_replace("Ñ", "N", $texto);
    
    return $texto;
}

function strip_tags_content($text, $tags = '', $invert = false) // quita no solo la etiqueta html sino tambien el contenido dentro de ella
{
    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    
    $tags = array_unique($tags[1]);
    
    if (is_array($tags) AND count($tags) > 0)
    {
        if ($invert == false)
        {
            return preg_replace('@<(?!(?:'.implode('|', $tags).')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        else
        {
            return preg_replace('@<('.implode('|', $tags).')\b.*?>.*?</\1>@si', '', $text);
        }
    }
    elseif ($invert == false)
    {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
    
    return $text;
}

/*
 *
 * Funciones para las FECHAS
 *
 */

function custom_date_format($date = null, $day = true, $month = true, $year = true, $separator = '/')
{
    if (is_null($date))
    {
        $date = date('Y-m-d');
    }
    else
    {
        $date = explode(' ',$date)[0];
    }

    $months = site_config()['months'];
    
    $date_parts = explode('-', $date);
    
    $formatted_date = '';
    
    if ($day)
    {
        $formatted_date .= $date_parts[2].$separator;
    }
    if ($month)
    {
        $formatted_date .= $months[$date_parts[1]].$separator;
    }
    if ($year)
    {
        $formatted_date .= $date_parts[0];
    }
    
    return $formatted_date;
}

function fecha_larga($fecha = null) // Lunes, 04 de Enero del 2016 a las 10:57 am
{
    if (is_null($fecha))
    {
        $fecha = time();
    }
    $fecha = date('N/d/m/Y/g/i/a', $fecha);
    $fecha = explode('/', $fecha);
    
    return config_item('dias')[$fecha[0]].", ".$fecha[1]." de ".config_item('meses')[$fecha[2]]." del ".$fecha[3]." a las ".$fecha[4].":".$fecha[5]." ".$fecha[6];
}

function fecha_corta($fecha = null) // 04 Enero 2016
{
    if (is_null($fecha))
    {
        $fecha = time();
    }
    $fecha = date('d/m/Y', $fecha);
    $fecha = explode('/', $fecha);
    
    return $fecha[0]." ".config_item('meses')[$fecha[1]]." ".$fecha[2];
}

function reverse_date($date,$normal = true,$separator = '-') // 04 Enero 2016
{
    if(!$normal)
    {
        $date_day = explode(' ',$date)[0];
        $date_parts = explode($separator, $date_day);
        
        $year = $date_parts[0];
        $month = (array_search($date_parts[1],site_config()['months'])) ? array_search($date_parts[1],site_config()['months']) : $date_parts[1];
        $day = $date_parts[2];
    
        $date_format = $year.'-'.$month.'-'.$day;
        
        if(isset(explode(' ',$date)[1]))
        {
            $date_format .= ' '.explode(' ',$date)[1];
        }
    }
    else
    {
        $date_format = $date;
    }
    
    return strtotime($date_format);
}

/*
 *
 * Funciones para Plugins Externos
 *
 */

function google_analytics($id)
{
    return "
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', '".$id."', 'auto');
          ga('send', 'pageview');
        </script>
    ";
}

function url_actual()
{
    return 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
}

function iniciar_facebook($app_id = null)
{
    if (!is_null($app_id))
    {
        $app_id = '&appId='.$app_id;
    }
    
    return "
        <div id='fb-root'></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = '//connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.6".$app_id."';
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    ";
}

function metatags_facebook($app_id = '966242223397117', $sitio, $titulo, $contenido, $imagen, $imagen_ancho, $imagen_alto, $tipo_pagina = 'article')
{
    $mimetypes = array(
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
    );
    
    return '
        <meta property="fb:app_id"        content="'.$app_id.'" />
        <meta property="og:url"           content="'.$sitio.'" />
    	<meta property="og:type"          content="'.$tipo_pagina.'" />
        <meta property="og:site_name"     content="'.config_item('nombre_sitio').'" />
    	<meta property="og:title"         content="'.str_replace(array("'", '"'), '*', $titulo).'" />
    	<meta property="og:description"   content="'.str_replace(array(
                                                                                                                                                                                                                                                                                                                                                                                                                                                            "'",
                                                                                                                                                                                                                                                                                                                                                                                                                                                            '"',
                                                                                                                                                                                                                                                                                                                                                                                                                                                        ), '*', cortar_parrafo($contenido, 350).'...').'" />
    	<meta property="og:image"         content="'.$imagen.'" />
        <meta property="og:image:type"    content="'.$mimetypes[obtener_extension($imagen)].'" />
        <meta property="og:image:width"   content="'.$imagen_ancho.'" />
        <meta property="og:image:height"  content="'.$imagen_alto.'" />

        <link href="'.$imagen.'" rel="image_src" />
    ';
}


function comentarios_facebook($sitio, $max_comentarios = 3, $width = '100%')
{
    return '
        <div class="fb-comments" data-href="'.$sitio.'" data-numposts="'.$max_comentarios.'" data-width="'.$width.'"></div>
    ';
}

function compartir_facebook($sitio, $layout = 'button')
{
    return '
        <div class="fb-share-button" data-href="'.$sitio.'" data-layout="'.$layout.'"></div>
    ';
}

function megusta_facebook($sitio, $layout = 'button')
{
    return '
        <div class="fb-like" data-href="'.$sitio.'" data-layout="'.$layout.'" data-action="like" data-show-faces="true"></div>
    ';
}

function pagina_facebook($sitio, $mostrar_post = 'true', $tabs = null, $cara_de_amigos = 'true', $ocultar_portada = 'false', $small_header = 'false')
{
    if (!is_null($tabs))
    {
        $tabs = 'data-tabs="'.$tabs.'"';
    }
    
    return '
        <div class="fb-page" data-href="'.$sitio.'" data-small-header="'.$small_header.'" data-adapt-container-width="true" data-hide-cover="'.$ocultar_portada.'" data-show-facepile="'.$cara_de_amigos.'" data-show-posts="'.$mostrar_post.'" '.$tabs.'></div>
    ';
}

function iniciar_twitter()
{
    return "
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    ";
}

function compartir_twitter($sitio, $contenido, $usuario, $hashtag)
{
    if (!is_null($hashtag))
    {
        $hashtag = 'data-hashtags="'.$hashtag.'"';
    }
    
    $contenido = str_replace(array("'", '"'), '*', trim(mb_substr($contenido, 0, 70, 'UTF-8')).'...');
    
    return '
    <a href="https://twitter.com/share" class="twitter-share-button"{count} data-url="'.$sitio.'" data-text="'.$contenido.'" data-via="'.$usuario.'" '.$hashtag.'>Tweet</a>
    ';
}