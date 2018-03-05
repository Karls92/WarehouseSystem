<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Visit
 *
 * @property int $id
 * @property string $ip
 * @property string $country
 * @property string $browser
 * @property string $so
 * @property string $referrer
 * @property string $referrer_link
 * @property string $date
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Visit whereBrowser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Visit whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Visit whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Visit whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Visit whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Visit whereReferrer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Visit whereReferrerLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Visit whereSo($value)
 */
	class Visit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Client
 *
 * @property int $id
 * @property string $client_code
 * @property string $name
 * @property string $document
 * @property string $address
 * @property int $city_id
 * @property string $phone_1
 * @property string $phone_2
 * @property string $email
 * @property string $description
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\City $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereClientCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereDocument($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client wherePhone1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client wherePhone2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereUpdatedAt($value)
 */
	class Client extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Classification
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Classification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Classification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Classification whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Classification whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Classification whereUpdatedAt($value)
 */
	class Classification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\State
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @method static \Illuminate\Database\Query\Builder|\App\Models\State whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\State whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\State whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\State whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\State whereUpdatedAt($value)
 */
	class State extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $brand_id
 * @property int $model_id
 * @property int $classification_id
 * @property int $uom_id
 * @property string $product_code
 * @property string $name
 * @property string $description
 * @property string $observation
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Brand $brand
 * @property-read \App\Models\Classification $classification
 * @property-read \App\Models\ProductModel $model
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read \App\Models\UnitOfMeasure $unit_of_measure
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereBrandId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereClassificationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereModelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereObservation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereProductCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereUomId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PanelConfig
 *
 * @property int $id
 * @property int $user_id
 * @property string $theme_color
 * @property string $screen
 * @property string $breadcrumb
 * @property string $box_design
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PanelConfig whereBoxDesign($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PanelConfig whereBreadcrumb($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PanelConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PanelConfig whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PanelConfig whereScreen($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PanelConfig whereThemeColor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PanelConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PanelConfig whereUserId($value)
 */
	class PanelConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SiteConfig
 *
 * @property int $id
 * @property string $page_description
 * @property string $map_height
 * @property string $map_zoom
 * @property string $map_color
 * @property string $map_latitude
 * @property string $map_longitude
 * @property string $social_facebook
 * @property string $social_twitter
 * @property string $social_instagram
 * @property string $social_youtube
 * @property string $social_google_plus
 * @property string $social_mercado_libre
 * @property string $api_id_google_analytics
 * @property string $api_id_facebook
 * @property string $email
 * @property string $password_email
 * @property string $smtp_host
 * @property string $smtp_port
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereApiIdFacebook($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereApiIdGoogleAnalytics($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereMapColor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereMapHeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereMapLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereMapLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereMapZoom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig wherePageDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig wherePasswordEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereSmtpHost($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereSmtpPort($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereSocialFacebook($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereSocialGooglePlus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereSocialInstagram($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereSocialMercadoLibre($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereSocialTwitter($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereSocialYoutube($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfig whereUpdatedAt($value)
 */
	class SiteConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Brand
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductModel[] $product_models
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Brand whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Brand whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Brand whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Brand whereUpdatedAt($value)
 */
	class Brand extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $client_id
 * @property int $out_order_id
 * @property string $code
 * @property string $received_by
 * @property string $delivered_by
 * @property string $type
 * @property string $is_processed
 * @property string $date
 * @property string $description
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereDeliveredBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereIsProcessed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereOutOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereReceivedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereUpdatedAt($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $image
 * @property string $email
 * @property string $password
 * @property string $type
 * @property string $level
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read mixed $full_name
 * @property-read \App\Models\PanelConfig $panel_config
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductModel
 *
 * @property int $id
 * @property string $name
 * @property int $brand_id
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Brand $brand
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductModel whereBrandId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductModel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductModel whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductModel whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductModel whereUpdatedAt($value)
 */
	class ProductModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property int $state_id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Client[] $clients
 * @property-read \App\Models\State $state
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereStateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UnitOfMeasure
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UnitOfMeasure whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UnitOfMeasure whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UnitOfMeasure whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UnitOfMeasure whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UnitOfMeasure whereUpdatedAt($value)
 */
	class UnitOfMeasure extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RecentActivity
 *
 * @property int $id
 * @property string $user
 * @property string $activity
 * @property string $icon
 * @property string $date
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RecentActivity whereActivity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RecentActivity whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RecentActivity whereIcon($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RecentActivity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RecentActivity whereUser($value)
 */
	class RecentActivity extends \Eloquent {}
}

