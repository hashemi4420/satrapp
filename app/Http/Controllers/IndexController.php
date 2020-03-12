<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {

        return view('manage.index');
    }

    public function loadData()
    {
        $view = '<div class="row">';

        if(Auth::user()->userRole == 1){
            $select = "SELECT COUNT(*) AS count FROM article_brands";
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/articleBrand">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h5 class="card-title" style="position: absolute;">برند کالا:</h5><h5 style="text-align: left;">' . $d->count . ' مورد</h5>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';


            $select = "SELECT COUNT(*) AS count FROM service_brands";
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/serviceBrand">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h5 class="card-title" style="position: absolute;">برند خدمات:</h5><h5 style="text-align: left;">' . $d->count . ' مورد</h5>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';


            $select = 'SELECT COUNT(*) AS count FROM article_creators WHERE accept = "تایید شده"';
//            $select = 'SELECT COUNT(*) AS count FROM article_creators WHERE 1 = 1';
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/createArticle">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h5 class="card-title" style="position: absolute;">کالاهای ثبت شده:</h5><h5 style="text-align: left;">' . $d->count . ' مورد</h5>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';


            $select = 'SELECT COUNT(*) AS count FROM service_creators WHERE accept = "تایید شده"';
//            $select = 'SELECT COUNT(*) AS count FROM service_creators WHERE 1 = 1';
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/createService">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h5 class="card-title" style="position: absolute;">خدمات ثبت شده:</h5><h5 style="text-align: left;">' . $d->count . ' مورد</h5>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';




            $select = 'SELECT COUNT(*) AS count FROM users WHERE userRole = 3';
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/createReseller">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h4 class="card-title" style="position: absolute;">تامین کنندگان:</h4><h4 style="text-align: left;">' . $d->count . ' عدد</h4>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';


            $select = 'SELECT COUNT(*) AS count FROM users WHERE userRole = 2';
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/users">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h4 class="card-title" style="position: absolute;">تولیدکنندگان محتوا:</h4><h4 style="text-align: left;">' . $d->count . ' عدد</h4>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';

            $select = 'SELECT COUNT(*) AS count FROM customers';
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/appUser">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h4 class="card-title" style="position: absolute;">کاربران اپ:</h4><h4 style="text-align: left;">' . $d->count . ' عدد</h4>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';

            $select = 'SELECT COUNT(*) AS count FROM customers WHERE active = 1';
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/appUser">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h4 class="card-title" style="position: absolute;">کاربران فعال:</h4><h4 style="text-align: left;">' . $d->count . ' عدد</h4>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';
        }

        if(Auth::user()->userRole == 2){
            $select = 'SELECT COUNT(*) AS count FROM service_creators WHERE accept = "تایید شده" AND brand_id =' .Auth::user()->articleBrand_id;
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/createArticle">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h4 class="card-title" style="position: absolute;">کالاهای ثبت شده:</h4><h4 style="text-align: left;">' . $d->count . ' مورد</h4>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';


            $select = 'SELECT COUNT(*) AS count FROM service_creators WHERE accept = "تایید شده" AND brand_id =' .Auth::user()->serviceBrand_id;
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/createService">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
            if($count != null) {
                foreach ($count as $d) {
                    $view .= '<h4 class="card-title" style="position: absolute;">خدمات ثبت شده:</h4><h4 style="text-align: left;">' . $d->count . ' مورد</h4>';
                }
            }
            $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';
        }

        if(Auth::user()->userRole == 3){
            if(Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != ""){
                $select = 'SELECT COUNT(*) AS count FROM article_providers WHERE active = 1 AND user_id =' .Auth::user()->id;
                $count = DB::select(DB::raw($select));
                $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/basketList">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
                if($count != null) {
                    foreach ($count as $d) {
                        $view .= '<h4 class="card-title" style="position: absolute;">خدمات و محصولات فعلی:</h4><h4 style="text-align: left;">' . $d->count . ' مورد</h4>';
                    }
                }
                $view .='</div>
                              </div>
                              </a>
                            </div>
                          </div>';
            }

            if(Auth::user()->workFieldService != null && Auth::user()->workFieldService != ""){
                $select = 'SELECT COUNT(*) AS count FROM service_providers WHERE active = 1 AND user_id =' .Auth::user()->id;
                $count = DB::select(DB::raw($select));
                $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/basketList">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
                                if($count != null) {
                                    foreach ($count as $d) {
                                        $view .= '<h4 class="card-title" style="position: absolute;">خدمات و محصولات فعلی:</h4><h4 style="text-align: left;">' . $d->count . ' مورد</h4>';
                                    }
                                }
                                $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';
            }

            $select = 'SELECT IFNULL(SUM(pey), 0) AS count FROM upgrade_logos WHERE user_id = ' .Auth::user()->id;
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/upLogo">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
                                if($count != null) {
                                    foreach ($count as $d) {
                                        $view .= '<h4 class="card-title" style="position: absolute;">اعتبار جهت ارتقا لوگو:</h4><h4 style="text-align: left;">' . $d->count . ' تومان</h4>';
                                    }
                                }
                                $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';

            $select = 'SELECT IFNULL(SUM(pey), 0) AS count FROM active_free_calls WHERE user_id = ' .Auth::user()->id;
            $count = DB::select(DB::raw($select));
            $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                            <a href="/manage/freeCall">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">';
                                if($count != null) {
                                    foreach ($count as $d) {
                                        $view .= '<h4 class="card-title" style="position: absolute;">اعتبار تماس رایگان:</h4><h4 style="text-align: left;">' . $d->count . ' تومان</h4>';
                                    }
                                }
                                $view .= '</div>
                              </div>
                              </a>
                            </div>
                          </div>';

            $view .= '<div class="col-xl-6 col-lg-6">
                            <div class="card l-bg-cyan-dark">
                              <div class="card-statistic-3" style="padding: 30px;">
                                <div class="card-content">
                                    <h4 class="card-title" style="position: absolute;">تعداد بازدید کنندگان امروز:</h4><h4 style="text-align: left;">0</h4>
                                </div>
                              </div>
                            </div>
                          </div>';
        }

        $view .= '</div>';

        return $view;
    }
}
