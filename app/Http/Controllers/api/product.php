<?php

namespace App\Http\Controllers\api;

use App\Contact;
use App\Customer;
use App\RateCustomerToReseller;
use App\Report;
use App\Sign;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class product extends Controller
{
    public function home(Request $request)
    {
        $where = "WHERE 1 = 1";
        if($request->state != null && $request->state != ""){
            $where .= " AND locations.state_id = ".$request->state;
        }
        if($request->city != null && $request->city != ""){
            $where .= " AND locations.city_id = ".$request->city;
        }
        if($request->district != null && $request->district != ""){
            $where .= " AND locations.district_id = ".$request->district;
        }

        $data = DB::select(DB::raw("SELECT
	COUNT(article_homePage.articleId) AS countBrand,
	articleId,
	serviceId,
	color,
	users,
	title 
FROM
	(
	SELECT
		COUNT( product.id ) AS countBrand, 
		(
			SELECT COUNT(users.id) 
			FROM users 
			INNER JOIN 
			(SELECT locations.user_id FROM locations ".$where." GROUP BY locations.user_id) AS location ON users.id = location.user_id 
			INNER JOIN 
			(SELECT article_providers.user_id FROM article_providers WHERE article_providers.active = 1 
				GROUP BY article_providers.user_id) AS article_provider ON users.id = article_provider.user_id 
			WHERE users.workFieldArticle = article.id AND users.active = 1 AND users.userRole = 3
		) AS users,
		article.id AS articleId,
		NULL AS serviceId,
		article.color,
		article.title AS title,
		brand.title AS brand,
		brand.id AS brandId,
		brand.url_avatar AS brandPic 
	FROM
		article_creators AS product
		INNER JOIN article_areas AS article ON product.area_id = article.id
		INNER JOIN article_brands AS brand ON product.brand_id = brand.id 
		WHERE product.active = 1
	GROUP BY
		product.brand_id 
	) AS article_homePage 
GROUP BY
	article_homePage.articleId UNION
SELECT
	COUNT(service_homePage.serviceId) AS countBrand,
	articleId,
	serviceId,
	color,
	users,
	title 
FROM
	(
	SELECT
		COUNT( product.id ) AS countBrand,
		(
			SELECT COUNT(users.id) 
			FROM users 
			INNER JOIN 
			(SELECT locations.user_id FROM locations ".$where." GROUP BY locations.user_id) AS location ON users.id = location.user_id 
			INNER JOIN 
			(SELECT service_providers.user_id FROM service_providers WHERE service_providers.active = 1 
				GROUP BY service_providers.user_id) AS service_provider ON users.id = service_provider.user_id 
			WHERE users.workFieldService = service_areas.id AND users.active = 1 AND users.userRole = 3
		) AS users, 
		NULL AS articleId,
		service_areas.id AS serviceId,
		service_areas.color,
		service_areas.title AS title,
		brand.title AS brand,
		brand.id AS brandId,
		brand.url_avatar AS brandPic 
	FROM
		service_creators AS product
		INNER JOIN service_areas ON product.area_id = service_areas.`id`
		INNER JOIN service_brands AS brand ON product.brand_id = brand.id 
		WHERE product.active = 1
	GROUP BY
		product.brand_id 
	) service_homePage 
GROUP BY
	service_homePage.serviceId"));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function article(Request $request)
    {
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;
        $data = DB::select(DB::raw("SELECT brand,brandId,brandPic FROM (SELECT
	COUNT( product.id ) AS countBrand,
	article.id AS articleId,
	NULL AS serviceId,
	CONCAT( 'محصولات', ' ', article.title ) AS title,
	brand.title AS brand,
	brand.id AS brandId,
	brand.url_avatar AS brandPic
FROM
	article_creators AS product
	INNER JOIN article_areas AS article ON product.area_id = article.id 
	INNER JOIN article_brands AS brand ON product.brand_id = brand.id 
	WHERE product.active = 1
GROUP BY
	product.brand_id ) AS article_homePage WHERE articleId = ".$request->id." LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function service(Request $request)
    {
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;
        $data = DB::select(DB::raw("SELECT brand,brandId,brandPic FROM (SELECT
	COUNT( product.id ) AS countBrand,
	'' AS articleId,
	service_areas.id AS serviceId,
	CONCAT( 'خدمات', ' ', service_areas.title ) AS title,
	brand.title AS brand,
	brand.id AS brandId,
	brand.url_avatar AS brandPic 
FROM
	service_creators AS product
	INNER JOIN service_areas ON product.area_id = service_areas.`id`
	INNER JOIN service_brands AS brand ON product.brand_id = brand.id 
	WHERE product.active = 1
GROUP BY
	product.brand_id ) AS service_homePage WHERE serviceId = ".$request->id." LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function articleBrandHeader(Request $request)
    {
        $data = DB::select(DB::raw("SELECT groupId,groupTitle,brandPic FROM (SELECT 
	groups.id AS groupId,
	groups.title AS groupTitle,
	brand.id AS brandId,
	brand.url_avatar AS brandPic 
FROM article_creators 
INNER JOIN article_categories AS groups ON article_creators.category_id = groups.id
INNER JOIN article_brands AS brand ON article_creators.brand_id = brand.id 
WHERE article_creators.active = 1) AS brandarticleheader 
WHERE brandId = ".$request->id." 
GROUP BY groupId"));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function articleBrandBody(Request $request)
    {
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;
        $data = DB::select(DB::raw("SELECT productPic,title,articleId,brandPic FROM 
(SELECT
	product.id AS articleId,
	product.title AS title,
	product.url_avatar AS productPic,
	product.category_id AS groupId,
	groups.title AS groupTitle,
	product.brand_id AS brandId,
	brand.url_avatar AS brandPic
	
FROM article_creators AS product
INNER JOIN article_areas AS article ON product.area_id = article.id 
INNER JOIN article_brands AS brand ON product.brand_id = brand.id 
INNER JOIN article_categories AS groups ON product.category_id = groups.id 
WHERE product.active = 1) AS brandarticlepage 
WHERE groupId = ".$request->group." AND brandId = ".$request->id." LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function serviceBrandHeader(Request $request)
    {
        $data = DB::select(DB::raw("SELECT groupId,groupTitle,brandPic FROM (SELECT
	article.id AS articleId,
	product.title AS title,
	product.url_avatar AS productPic,
	groups.id AS groupId,
	groups.title AS groupTitle,
	brand.id AS brandId,
	brand.url_avatar AS brandPic
	FROM service_creators AS product
	INNER JOIN service_areas AS article ON product.area_id = article.id 
	INNER JOIN service_brands AS brand ON product.brand_id = brand.id 
	INNER JOIN service_categories AS groups ON product.category_id = groups.id 
	WHERE product.active = 1) AS brandserviceheader 
WHERE brandId = ".$request->id."
GROUP BY groupId"));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function serviceBrandBody(Request $request)
    {
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;
        $data = DB::select(DB::raw("SELECT productPic,title,articleId,brandPic FROM 
(	SELECT
		product.id AS articleId,
		product.title AS title,
		product.url_avatar AS productPic,
		product.category_id AS groupId,
		groups.title AS groupTitle,
		product.brand_id AS brandId,
		brand.url_avatar AS brandPic
	
	FROM service_creators AS product
	INNER JOIN service_areas AS article ON product.area_id = article.id 
	INNER JOIN service_brands AS brand ON product.brand_id = brand.id 
	INNER JOIN service_categories AS groups ON product.category_id = groups.id 
	WHERE product.active = 1) AS brandservicepage 
WHERE groupId = ".$request->group." AND brandId = ".$request->id." LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function articlePage(Request $request){
        $data = DB::select(DB::raw("SELECT id,title,url_avatar FROM article_creators WHERE active = 1 AND id = ".$request->id));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function servicePage(Request $request){
        $data = DB::select(DB::raw("SELECT id,title,url_avatar FROM service_creators WHERE active = 1 AND id = ".$request->id));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function reseller(Request $request){
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;
        $time = strtotime("-2 days");

        $where = "";
        if($request->state != null && $request->state != ""){
            $where = " AND locations.state_id = ".$request->state;
        }
        if($request->city != null && $request->city != ""){
            $where = " AND locations.city_id = ".$request->city;
        }
        if($request->district != null && $request->district != ""){
            $where = " AND locations.district_id = ".$request->district;
        }

        $join = '';

        if($request->level == 'all'){
            $join = "LEFT JOIN upgrade_logos ON users.id = upgrade_logos.user_id AND upgrade_logos.page = 'allReseller' AND upgrade_logos.timestamp > ".$time."\n";
        } else {
            $join = "LEFT JOIN upgrade_logos ON users.id = upgrade_logos.user_id AND upgrade_logos.page = 'reseller' AND upgrade_logos.timestamp > ".$time."\n";
        }

        if($request->articleId != null && $request->articleId != ''){
            $join .= 'inner JOIN article_providers ON users.id = article_providers.user_id
LEFT JOIN locations ON users.id = locations.user_id ';
            $where .= ' AND article_providers.active = 1';
        } else if($request->serviceId != null && $request->serviceId != ''){
            $join .= 'inner JOIN service_providers ON users.id = service_providers.user_id
LEFT JOIN locations ON users.id = locations.user_id ';
            $where .= ' AND service_providers.active = 1';
        }

        $data = DB::select(DB::raw("SELECT users.id,users.url_avatar FROM users 
".$join."
WHERE users.userRole = 3 AND users.active = 1 ".$where."
AND users.workFieldArticle = '".$request->articleId."' OR users.workFieldService = '".$request->serviceId."' 
GROUP BY users.id 
ORDER BY upgrade_logos.updated_at DESC, users.id DESC 
LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function freeCall(Request $request){
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;
        $time = strtotime("-2 days");

        $where = "";
        if($request->state != null && $request->state != ""){
            $where = " AND locations.state_id = ".$request->state;
        }
        if($request->city != null && $request->city != ""){
            $where = " AND locations.city_id = ".$request->city;
        }
        if($request->district != null && $request->district != ""){
            $where = " AND locations.district_id = ".$request->district;
        }

        $join = '';
        if($request->level == 'all'){
            $join = "LEFT JOIN upgrade_logos ON users.id = upgrade_logos.user_id AND upgrade_logos.page = 'allFreeCall' AND upgrade_logos.timestamp > ".$time."\n";
        } else {
            $join = "LEFT JOIN upgrade_logos ON users.id = upgrade_logos.user_id AND upgrade_logos.page = 'freeCall' AND upgrade_logos.timestamp > ".$time."\n";
        }

        if($request->articleId != null && $request->articleId != ''){
            $join = 'inner JOIN article_providers ON users.id = article_providers.user_id
LEFT JOIN locations ON users.id = locations.user_id ';
            $where .= ' AND article_providers.active = 1';
        } else if($request->serviceId != null && $request->serviceId != ''){
            $join = 'inner JOIN service_providers ON users.id = service_providers.user_id
LEFT JOIN locations ON users.id = locations.user_id ';
            $where .= ' AND service_providers.active = 1';
        }

        $data = DB::select(DB::raw("SELECT
	users.id,
	users.url_avatar
FROM active_free_calls
INNER JOIN users ON active_free_calls.user_id = users.id
".$join."
WHERE users.userRole = 3 AND users.active = 1 AND active_free_calls.active = 1 AND active_free_calls.pey > 15000 ".$where."
			AND users.workFieldArticle = '".$request->articleId."' OR users.workFieldService = '".$request->serviceId."' 
			GROUP BY users.id 
			ORDER BY upgrade_logos.updated_at DESC, users.id DESC 
			LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function sagest(Request $request){
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;
        $time = strtotime("-2 days");

        $where = "";
        if($request->state != null && $request->state != ""){
            $where = " AND locations.state_id = ".$request->state;
        }
        if($request->city != null && $request->city != ""){
            $where = " AND locations.city_id = ".$request->city;
        }
        if($request->district != null && $request->district != ""){
            $where = " AND locations.district_id = ".$request->district;
        }

        $join = '';
        if($request->level == 'all'){
            $join = "LEFT JOIN upgrade_logos ON users.id = upgrade_logos.user_id AND upgrade_logos.page = 'allSagest' AND upgrade_logos.timestamp > ".$time."\n";
        } else {
            $join = "LEFT JOIN upgrade_logos ON users.id = upgrade_logos.user_id AND upgrade_logos.page = 'sagest' AND upgrade_logos.timestamp > ".$time."\n";
        }

        if($request->articleId != null && $request->articleId != ''){
            $join = 'inner JOIN article_providers ON users.id = article_providers.user_id
LEFT JOIN locations ON users.id = locations.user_id ';
            $where .= ' AND article_providers.active = 1';
        } else if($request->serviceId != null && $request->serviceId != ''){
            $join = 'inner JOIN service_providers ON users.id = service_providers.user_id
LEFT JOIN locations ON users.id = locations.user_id ';
            $where .= ' AND service_providers.active = 1';
        }

        $data = DB::select(DB::raw("SELECT
	users.id,
	users.url_avatar
FROM upgrade_logos
INNER JOIN users ON upgrade_logos.user_id = users.id 
".$join." 
WHERE users.userRole = 3 AND users.active = 1 AND upgrade_logos.active = 1 AND upgrade_logos.page = 'sagest' ".$where."
			AND users.workFieldArticle = '".$request->articleId."' OR users.workFieldService = '".$request->serviceId."'  
			GROUP BY users.id 
			ORDER BY upgrade_logos.updated_at DESC, users.id DESC  
			LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function headerReseller(Request $request){

        $sign = Sign::where('user_id', '=', $request->id)->where('customer_id', '=', $request->customer)->get();
        $rate = RateCustomerToReseller::where('user_id', '=', $request->id)->where('customer_id', '=', $request->customer)->get();

        $data = null;

        if($sign->count() == 1 && $rate->count() == 1){
            $data = DB::select(DB::raw("SELECT
	users.title_company AS title,
	users.url_avatar AS avatar,
	users.about AS aboutUs,
	users.numberFree AS tamasRayegan,
	users.numberPey AS tamasAdi,
	users.saateKariFrom AS saateKariFrom,
	users.saateKariTo AS saateKariTo,
	format((users.stars / users.countStar),1) AS stars,
	format(rate_customer_to_resellers.stars,1) AS previousPoint,
	IF(IFNULL(signs.id,NULL) IS NULL,0,1) AS shown
FROM users 
INNER JOIN rate_customer_to_resellers ON rate_customer_to_resellers.user_id = users.id
INNER JOIN signs ON signs.user_id = users.id
WHERE users.userRole = 3 AND users.active = 1 AND users.id = ".$request->id." 
AND rate_customer_to_resellers.customer_id = ".$request->customer."
AND signs.customer_id = ".$request->customer));
        } else if ($rate->count() == 1){
            $data = DB::select(DB::raw("SELECT
	users.title_company AS title,
	users.url_avatar AS avatar,
	users.about AS aboutUs,
	users.numberFree AS tamasRayegan,
	users.numberPey AS tamasAdi,
	users.saateKariFrom AS saateKariFrom,
	users.saateKariTo AS saateKariTo,
	format((users.stars / users.countStar),1) AS stars,
	format(rate_customer_to_resellers.stars,1) AS previousPoint,
	0 AS shown
FROM users 
INNER JOIN rate_customer_to_resellers ON rate_customer_to_resellers.user_id = users.id
WHERE users.userRole = 3 AND users.active = 1 AND users.id = ".$request->id." 
AND rate_customer_to_resellers.customer_id = ".$request->customer));
        } else if ($sign->count() == 1){
            $data = DB::select(DB::raw("SELECT
	users.title_company AS title,
	users.url_avatar AS avatar,
	users.about AS aboutUs,
	users.numberFree AS tamasRayegan,
	users.numberPey AS tamasAdi,
	users.saateKariFrom AS saateKariFrom,
	users.saateKariTo AS saateKariTo,
	format((users.stars / users.countStar),1) AS stars,
	NULL AS previousPoint,
	IF(IFNULL(signs.id,NULL) IS NULL,0,1) AS shown
FROM users 
INNER JOIN signs ON signs.user_id = users.id
WHERE users.userRole = 3 AND users.active = 1 AND users.id = ".$request->id." 
AND signs.customer_id = ".$request->customer));
        } else {
            $data = DB::select(DB::raw("SELECT
	title_company AS title,
	url_avatar AS avatar,
	about AS aboutUs,
	numberFree AS tamasRayegan,
	numberPey AS tamasAdi,
	saateKariFrom AS saateKariFrom,
	saateKariTo AS saateKariTo,
	format((stars / countStar),1) AS stars,
	NULL AS previousPoint,
	0 AS shown
FROM users WHERE userRole = 3 AND active = 1 AND id = ".$request->id));
        }
        $pp = "";


        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function collectionResellerArticle(Request $request){
        $data = DB::select(DB::raw("SELECT
	article_creators.category_id AS id,
	article_categories.title
FROM users 
RIGHT JOIN article_providers ON users.id = article_providers.user_id
RIGHT JOIN article_creators ON article_creators.id = article_providers.article_id
RIGHT JOIN article_categories ON article_categories.id = article_creators.category_id
WHERE users.active = 1 AND users.userRole = 3 AND article_providers.active = 1 AND users.id = ". $request->id."
GROUP BY article_creators.category_id"));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function collectionResellerService(Request $request){
        $data = DB::select(DB::raw("SELECT
	service_creators.category_id AS id,
	service_categories.title
FROM users 
RIGHT JOIN service_providers ON users.id = service_providers.user_id
RIGHT JOIN service_creators ON service_creators.id = service_providers.service_id
RIGHT JOIN service_categories ON service_categories.id = service_creators.category_id
WHERE users.active = 1 AND users.userRole = 3 AND service_providers.active = 1 AND users.id = ".$request->id."
GROUP BY service_creators.category_id"));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function productResellerArticle(Request $request){
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;
        $data = DB::select(DB::raw("SELECT
	article_providers.id,
	article_creators.title,
	article_creators.url_avatar AS avatar,
	article_providers.price,
	article_providers.atless,
	article_providers.`status` AS takhfif
FROM users 
RIGHT JOIN article_providers ON users.id = article_providers.user_id
RIGHT JOIN article_creators ON article_creators.id = article_providers.article_id
RIGHT JOIN article_categories ON article_categories.id = article_creators.category_id
WHERE users.active = 1 AND users.userRole = 3 AND article_providers.active = 1 
AND users.id = ".$request->userId." AND article_creators.category_id = ".$request->groupId." LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function productResellerService(Request $request){
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;
        $data = DB::select(DB::raw("SELECT
	service_providers.id,
	service_creators.url_avatar AS avatar,
	service_creators.title,
	service_providers.price,
	service_providers.atless,
	service_providers.`status` AS takhfif
FROM users 
RIGHT JOIN service_providers ON users.id = service_providers.user_id
RIGHT JOIN service_creators ON service_creators.id = service_providers.service_id
RIGHT JOIN service_categories ON service_categories.id = service_creators.category_id
WHERE users.active = 1 AND users.userRole = 3 AND service_providers.active = 1 
AND users.id = ".$request->userId." AND service_creators.category_id = ".$request->groupId." LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function starRate(Request $request){

        try{
                $customerRate = RateCustomerToReseller::where('user_id', '=', trim($request->userId))
                ->where('customer_id', '=', trim($request->customerId))
                ->get();

            $rate = 0;

            if($customerRate->count() == 1){

                foreach ($customerRate as $item){
                    $rate = $item->stars;

                    $item->stars = $request->rate;
                    $item->save();
                }

            } else {
                RateCustomerToReseller::create([
                    'user_id' => trim($request->userId),
                    'customer_id' => trim($request->customerId),
                    'stars' => trim($request->rate),
                ]);
            }

            $user = User::find($request->userId);

            $point = $user->stars;
            $countPoint = $user->countStar;

            $point = $point - $rate;

            $point = $point + $request->rate;

            if($customerRate->count() == 0) {
                $countPoint = $countPoint + 1;
            }

            $user->stars = $point;
            $user->countStar = $countPoint;

            $user->save();

            $response = [
                'success' => true,
                'data' => 'امتیاز شما با موفقیت ثبت گردید.',
                'message' => 'successfully.'
            ];
            return response()->json($response, 200);
        } catch (Exception $e){
            $response = [
                'success' => false,
                'data' => 'Error',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }
    }

    public function register(Request $request){

        try{

            $user = Customer::where('phone', '=', trim($request->phone))->get();

            if($user->count() == 0){
                $user = Customer::create([
                    'phone' => trim(trim($request->phone)),
                    'active' => 1,
                    'state_id' => 8,
                    'cash' => '0',
                ]);
            }

            $data = DB::select(DB::raw("SELECT id,name,family,email,state_id,city_id,district_id,active FROM customers WHERE phone = '".trim($request->phone)."'"));

            if (is_null($data)) {
                $response = [
                    'success' => false,
                    'data' => 'Empty',
                    'message' => 'not found.'
                ];
                return response()->json($response, 404);
            }


            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'successfully.'
            ];

            return response()->json($response, 200);

        } catch (Exception $e){
            $response = [
                'success' => false,
                'data' => 'Error',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }
    }

    //نشان کردن
    public function sign(Request $request){

        try{
            if($request->active == 1){
                $sign = Sign::where('customer_id', '=', trim($request->customer_id))->where('user_id', '=', trim($request->user_id))->get();

                if($sign->count() == 0){
                    Sign::create([
                        'user_id' => trim(trim($request->user_id)),
                        'customer_id' => trim(trim($request->customer_id)),
                    ]);

                    $response = [
                        'success' => true,
                        'data' => 'با موفقیت نشان شد.',
                        'message' => 'successfully.'
                    ];
                    return response()->json($response, 200);
                }
            } else {
                $sign = Sign::where('customer_id', '=', trim($request->customer_id))->where('user_id', '=', trim($request->user_id))->get();
                foreach ($sign as $item){
                    $item->delete();
                }

                $response = [
                    'success' => true,
                    'data' => 'از لیست نشان شما حذف شد.',
                    'message' => 'successfully.'
                ];
                return response()->json($response, 200);
            }
        } catch (Exception $e){
            $response = [
                'success' => false,
                'data' => 'Error',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }
    }
    //نشان شده ها
    public function shown(Request $request){
        $start = 10 * ($request->page - 1);
        $end = 10 * $request->page;

        $where = "";
        if($request->state != null && $request->state != ""){
            $where = " AND locations.state_id = ".$request->state;
        }
        if($request->city != null && $request->city != ""){
            $where = " AND locations.city_id = ".$request->city;
        }
        if($request->district != null && $request->district != ""){
            $where = " AND locations.district_id = ".$request->district;
        }

        $data = DB::select(DB::raw("SELECT
 users.url_avatar,
 users.id,
 format((users.stars / users.countStar),1) AS stars,
 IF(IFNULL(users.workFieldArticle,'') <> '',users.workFieldArticle,users.workFieldService) AS areaId,
 IF(IFNULL(users.workFieldArticle,'') <> '',article_areas.title,service_areas.title) AS areatitle
FROM signs
INNER JOIN users ON signs.user_id = users.id 
LEFT JOIN locations ON users.id = locations.user_id 
LEFT JOIN article_areas ON users.workFieldArticle = article_areas.id 
LEFT JOIN service_areas ON users.workFieldService = service_areas.id 
WHERE signs.customer_id = '".$request->customer_id."' ".$where." GROUP BY signs.user_id LIMIT ".$start.",".$end));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function information(Request $request){
        $user = Customer::find($request->id);

        $user->name = $request->name;
        $user->family = $request->family;
        $user->email = $request->email;

        if($request->active == 1){
            $user->active = $request->active;
        }else {
            $user->active = 0;
        }

        if($request->state_id != null && $request->state_id != ""){
            $user->state_id = $request->state_id;
        } else {
            $user->state_id = null;
        }

        if($request->city_id != null && $request->city_id != ""){
            $user->city_id = $request->city_id;
        } else {
            $user->city_id = null;
        }

        if($request->district_id != null && $request->district_id != ""){
            $user->district_id = $request->district_id;
        } else {
            $user->district_id = null;
        }

        $user->save();

        $response = [
            'success' => true,
            'data' => "ویرایش اطلاعات با موفقیت انجام شد.",
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function state(){
        $data = DB::select(DB::raw("SELECT id,title FROM states"));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function city(Request $request){
        $data = DB::select(DB::raw("SELECT id,state_id,title FROM cities WHERE state_id = ".$request->state));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function district(Request $request){
        $data = DB::select(DB::raw("SELECT id,state_id,city_id,title FROM districts WHERE city_id = ".$request->city));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function reportQuestion(){
        $data = DB::select(DB::raw("SELECT id,title FROM question_reports"));

        if (is_null($data)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function report(Request $request){

        Report::create([
            'question_id' => trim($request->reportId),
            'customer_id' => trim($request->customerId),
            'user_id' => trim($request->userId),
        ]);

        $response = [
            'success' => true,
            'data' => "گزارش شما با موفقیت ثبت گردید",
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function contact(Request $request){

        Contact::create([
            'customer_id' => trim($request->customerId),
            'email' => trim($request->email),
            'title' => trim($request->title),
            'message' => trim($request->message),
            'date' => trim($request->date),
        ]);

        $response = [
            'success' => true,
            'data' => "پیام شما با موفقیت ثبت گردید",
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function search(Request $request){

        $where = "";
        if($request->state != null && $request->state != ""){
            $where = " AND locations.state_id = ".$request->state;
        }
        if($request->city != null && $request->city != ""){
            $where = " AND locations.city_id = ".$request->city;
        }
        if($request->district != null && $request->district != "") {
            $where = " AND locations.district_id = " . $request->district;
        }


            $data = DB::select(DB::raw("SELECT * FROM (
SELECT
	IF(IFNULL(article_creators.area_id,'') <> '', COUNT(DISTINCT article_providers.id), NULL) AS tedad,
	IF(IFNULL(article_creators.area_id,'') <> '', article_creators.area_id, NULL) AS id,
	IF(IFNULL(article_creators.area_id,'') <> '', article_areas.title, NULL) AS title,
	IF(IFNULL(article_creators.area_id,'') <> '', 1, NULL) AS article,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS service,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS articleBrand,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS serviceBrand,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS brand,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS reseller
FROM article_providers
INNER JOIN article_creators ON article_creators.id = article_providers.article_id
INNER JOIN article_areas ON article_areas.id = article_creators.area_id
LEFT JOIN locations ON article_providers.user_id = locations.user_id
WHERE UPPER(article_creators.title) LIKE '%".strtoupper($request->title)."%' AND article_providers.active = 1 ".$where."
GROUP BY article_creators.area_id

UNION

SELECT
	IF(IFNULL(service_creators.area_id,'') <> '', COUNT(DISTINCT service_providers.id), NULL) AS tedad,
	IF(IFNULL(service_creators.area_id,'') <> '', service_creators.area_id, NULL) AS id,
	IF(IFNULL(service_creators.area_id,'') <> '', service_areas.title, NULL) AS title,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS article,
	IF(IFNULL(service_creators.area_id,'') <> '', 1, NULL) AS service,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS articleBrand,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS serviceBrand,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS brand,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS reseller
FROM service_providers
INNER JOIN service_creators ON service_creators.id = service_providers.service_id
INNER JOIN service_areas ON service_areas.id = service_creators.area_id
LEFT JOIN locations ON service_providers.user_id = locations.user_id
WHERE UPPER(service_creators.title) LIKE '%".strtoupper($request->title)."%' AND service_providers.active = 1 ".$where."
GROUP BY service_creators.area_id

UNION

SELECT
	IF(IFNULL(article_creators.id,'') <> '', COUNT(DISTINCT article_creators.id), NULL) AS tedad,
	IF(IFNULL(article_creators.id,'') <> '', NULL, NULL) AS id,
	IF(IFNULL(article_creators.id,'') <> '', 'برند محصولات', NULL) AS title,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS article,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS service,
	IF(IFNULL(article_creators.id,'') <> '', 1, NULL) AS articleBrand,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS serviceBrand,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS brand,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS reseller
FROM article_creators
INNER JOIN article_brands ON article_brands.id = article_creators.brand_id
WHERE UPPER(article_creators.title) LIKE '%".strtoupper($request->title)."%'

UNION

SELECT
	IF(IFNULL(service_creators.id,'') <> '', COUNT(DISTINCT service_creators.id), NULL) AS tedad,
	IF(IFNULL(service_creators.id,'') <> '', NULL, NULL) AS id,
	IF(IFNULL(service_creators.id,'') <> '', 'ببرند خدمات', NULL) AS title,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS article,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS service,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS articleBrand,
	IF(IFNULL(service_creators.id,'') <> '', 1, NULL) AS serviceBrand,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS brand,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS reseller
FROM service_creators
INNER JOIN service_brands ON service_brands.id = service_creators.brand_id
WHERE UPPER(service_creators.title) LIKE '%".strtoupper($request->title)."%'

UNION

SELECT
	IF(IFNULL(article_creators.id,'') <> '', COUNT(DISTINCT article_creators.brand_id), NULL) AS tedad,
	IF(IFNULL(article_creators.id,'') <> '', NULL, NULL) AS id,
	IF(IFNULL(article_creators.id,'') <> '', 'برندها', NULL) AS title,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS article,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS service,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS articleBrand,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS serviceBrand,
	IF(IFNULL(article_creators.id,'') <> '', 1, NULL) AS brand,
	IF(IFNULL(article_creators.id,'') <> '', 0, NULL) AS reseller
FROM article_creators
INNER JOIN article_brands ON article_brands.id = article_creators.brand_id
WHERE UPPER(article_brands.title) LIKE '%".strtoupper($request->title)."%'

UNION

SELECT
	IF(IFNULL(service_creators.id,'') <> '', COUNT(DISTINCT service_creators.brand_id), NULL) AS tedad,
	IF(IFNULL(service_creators.id,'') <> '', NULL, NULL) AS id,
	IF(IFNULL(service_creators.id,'') <> '', 'برندها', NULL) AS title,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS article,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS service,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS articleBrand,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS serviceBrand,
	IF(IFNULL(service_creators.id,'') <> '', 1, NULL) AS brand,
	IF(IFNULL(service_creators.id,'') <> '', 0, NULL) AS reseller
FROM service_creators
INNER JOIN service_brands ON service_brands.id = service_creators.brand_id
WHERE UPPER(service_brands.title) LIKE '%".strtoupper($request->title)."%'

UNION

SELECT
	IF(IFNULL(article_creators.area_id,'') <> '', COUNT(DISTINCT article_providers.user_id), NULL) AS tedad,
	IF(IFNULL(article_creators.area_id,'') <> '', article_creators.area_id, NULL) AS id,
	IF(IFNULL(article_creators.area_id,'') <> '', article_areas.title, NULL) AS title,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS article,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS service,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS articleBrand,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS serviceBrand,
	IF(IFNULL(article_creators.area_id,'') <> '', 0, NULL) AS brand,
	IF(IFNULL(article_creators.area_id,'') <> '', 1, NULL) AS reseller
FROM article_providers
INNER JOIN article_creators ON article_creators.id = article_providers.article_id
INNER JOIN article_areas ON article_areas.id = article_creators.area_id
INNER JOIN users ON users.id = article_providers.user_id
LEFT JOIN locations ON article_providers.user_id = locations.user_id
WHERE UPPER(users.title_company) LIKE '%".strtoupper($request->title)."%' AND article_providers.active = 1 AND users.active = 1 ".$where."
GROUP BY article_creators.area_id

UNION

SELECT
	IF(IFNULL(service_creators.area_id,'') <> '', COUNT(DISTINCT service_providers.user_id), NULL) AS tedad,
	IF(IFNULL(service_creators.area_id,'') <> '', service_creators.area_id, NULL) AS id,
	IF(IFNULL(service_creators.area_id,'') <> '', service_areas.title, NULL) AS title,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS article,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS service,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS articleBrand,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS serviceBrand,
	IF(IFNULL(service_creators.area_id,'') <> '', 0, NULL) AS brand,
	IF(IFNULL(service_creators.area_id,'') <> '', 1, NULL) AS reseller
FROM service_providers
INNER JOIN service_creators ON service_creators.id = service_providers.service_id
INNER JOIN service_areas ON service_areas.id = service_creators.area_id
INNER JOIN users ON users.id = service_providers.user_id
LEFT JOIN locations ON service_providers.user_id = locations.user_id
WHERE UPPER(users.title_company) LIKE '%".strtoupper($request->title)."%' AND service_providers.active = 1 AND users.active = 1 ".$where."
GROUP BY service_creators.area_id

) AS result
WHERE result.tedad IS NOT NULL"));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function searchResultArticle(Request $request){
        $where = "";
        if($request->state != null && $request->state != ""){
            $where = " AND locations.state_id = ".$request->state;
        }
        if($request->city != null && $request->city != ""){
            $where = " AND locations.city_id = ".$request->city;
        }
        if($request->district != null && $request->district != "") {
            $where = " AND locations.district_id = " . $request->district;
        }

        $data = DB::select(DB::raw("SELECT
	article_creators.id AS id,
	article_creators.title AS title,
	article_creators.url_avatar AS avatar,
	users.id AS userId,
	users.url_avatar AS userAvatar
FROM article_providers
INNER JOIN users ON users.id = article_providers.user_id
INNER JOIN article_creators ON article_creators.id = article_providers.article_id
INNER JOIN article_areas ON article_areas.id = article_creators.area_id
LEFT JOIN locations ON users.id = locations.user_id
WHERE UPPER(article_creators.title) LIKE '%".strtoupper($request->title)."%' 
        AND article_creators.area_id = ".$request->areaId." 
        AND article_providers.active = 1 AND users.active = 1 ".$where."
GROUP BY article_creators.id"));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function searchResultService(Request $request){
        $where = "";
        if($request->state != null && $request->state != ""){
            $where = " AND locations.state_id = ".$request->state;
        }
        if($request->city != null && $request->city != ""){
            $where = " AND locations.city_id = ".$request->city;
        }
        if($request->district != null && $request->district != "") {
            $where = " AND locations.district_id = " . $request->district;
        }

        $data = DB::select(DB::raw("SELECT
	service_creators.id AS id,
	service_creators.title AS title,
	service_creators.url_avatar AS avatar,
	users.id AS userId,
	users.url_avatar AS userAvatar
FROM service_providers
INNER JOIN users ON users.id = service_providers.user_id
INNER JOIN service_creators ON service_creators.id = service_providers.service_id
INNER JOIN service_areas ON service_areas.id = service_creators.area_id
LEFT JOIN locations ON users.id = locations.user_id
WHERE UPPER(service_creators.title) LIKE '%".strtoupper($request->title)."%' 
        AND service_creators.area_id = ".$request->areaId." 
        AND service_providers.active = 1 AND users.active = 1 ".$where."
GROUP BY service_creators.id"));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function searchResultArticleBrand(Request $request){
        $data = DB::select(DB::raw("SELECT
	article_brands.id AS idBrand,
	article_brands.title AS titleBrand,
	article_brands.url_avatar AS avatarBrand,
	
	article_creators.id AS id,
	article_creators.title AS title,
	article_creators.url_avatar AS avatar
FROM article_brands
INNER JOIN article_creators ON article_creators.brand_id = article_brands.id
WHERE UPPER(article_creators.title) LIKE '%".strtoupper($request->title)."%'"));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function searchResultServiceBrand(Request $request){
        $data = DB::select(DB::raw("SELECT
	service_brands.id AS idBrand,
	service_brands.title AS titleBrand,
	service_brands.url_avatar AS avatarBrand,
	
	service_creators.id AS id,
	service_creators.title AS title,
	service_creators.url_avatar AS avatar
FROM service_brands
INNER JOIN service_creators ON service_creators.brand_id = service_brands.id
WHERE UPPER(service_creators.title) LIKE '%".strtoupper($request->title)."%'"));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function searchResultBrand(Request $request){
        $data = DB::select(DB::raw("SELECT * FROM (
SELECT
	service_brands.id AS idBrand,
	service_brands.title AS titleBrand,
	service_brands.url_avatar AS avatarBrand,
	
	service_creators.id AS id,
	service_creators.title AS title,
	service_creators.url_avatar AS avatar
FROM service_brands
INNER JOIN service_creators ON service_creators.brand_id = service_brands.id
WHERE UPPER(service_brands.title) LIKE '%".strtoupper($request->title)."%'

UNION

SELECT
	article_brands.id AS idBrand,
	article_brands.title AS titleBrand,
	article_brands.url_avatar AS avatarBrand,
	
	article_creators.id AS id,
	article_creators.title AS title,
	article_creators.url_avatar AS avatar
FROM article_brands
INNER JOIN article_creators ON article_creators.brand_id = article_brands.id
WHERE UPPER(article_brands.title) LIKE '%".strtoupper($request->title)."%'
) AS result
WHERE result.idBrand IS NOT NULL"));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }

    public function searchResultReseller(Request $request){
        $where = "";
        if($request->state != null && $request->state != ""){
            $where = " AND locations.state_id = ".$request->state;
        }
        if($request->city != null && $request->city != ""){
            $where = " AND locations.city_id = ".$request->city;
        }
        if($request->district != null && $request->district != "") {
            $where = " AND locations.district_id = " . $request->district;
        }

        $data = DB::select(DB::raw("SELECT * FROM (
SELECT
	IF(IFNULL(article_creators.id,'') <> '', article_creators.id, NULL) AS id,
	IF(IFNULL(article_creators.id,'') <> '', article_creators.title, NULL) AS title,
	IF(IFNULL(article_creators.id,'') <> '', article_creators.url_avatar, NULL) AS avatar,
	IF(IFNULL(article_creators.id,'') <> '', article_providers.price, NULL) AS price,
	IF(IFNULL(article_creators.id,'') <> '', article_providers.atless, NULL) AS atless,
	IF(IFNULL(article_creators.id,'') <> '', article_providers.`status`, NULL) AS darsad,
	IF(IFNULL(article_creators.id,'') <> '', users.id, NULL) AS idReseller,
	IF(IFNULL(article_creators.id,'') <> '', users.title_company, NULL) AS titleReseller,
	IF(IFNULL(article_creators.id,'') <> '', users.url_avatar, NULL) AS avatarReseller
FROM article_providers
INNER JOIN article_creators ON article_creators.id = article_providers.article_id
INNER JOIN users ON users.id = article_providers.user_id
LEFT JOIN locations ON article_providers.user_id = locations.user_id
WHERE UPPER(users.title_company) LIKE '%".strtoupper($request->title)."%' AND article_providers.active = 1 AND users.active = 1 ".$where."
GROUP BY article_providers.id

UNION

SELECT
	IF(IFNULL(service_creators.id,'') <> '', service_creators.id, NULL) AS id,
	IF(IFNULL(service_creators.id,'') <> '', service_creators.title, NULL) AS title,
	IF(IFNULL(service_creators.id,'') <> '', service_creators.url_avatar, NULL) AS avatar,
	IF(IFNULL(service_creators.id,'') <> '', service_providers.price, NULL) AS price,
	IF(IFNULL(service_creators.id,'') <> '', service_providers.atless, NULL) AS atless,
	IF(IFNULL(service_creators.id,'') <> '', service_providers.`status`, NULL) AS darsad,
	IF(IFNULL(service_creators.id,'') <> '', users.id, NULL) AS idReseller,
	IF(IFNULL(service_creators.id,'') <> '', users.title_company, NULL) AS titleReseller,
	IF(IFNULL(service_creators.id,'') <> '', users.url_avatar, NULL) AS avatarReseller
FROM service_providers
INNER JOIN service_creators ON service_creators.id = service_providers.service_id
INNER JOIN users ON users.id = service_providers.user_id
LEFT JOIN locations ON service_providers.user_id = locations.user_id
WHERE UPPER(users.title_company) LIKE '%".strtoupper($request->title)."%' AND service_providers.active = 1 AND users.active = 1 ".$where."
GROUP BY service_providers.id
) AS result
WHERE result.id IS NOT NULL"));

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'successfully.'
        ];

        return response()->json($response, 200);
    }
}
