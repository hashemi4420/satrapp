@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manageBasket').classList.add('active');
        document.getElementById('newProduct').classList.add('active');
    </script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            <div class="card">
                                <div class="card-header">

                                </div>
                                <div class="card-body">
                                    <h4>لطفا تصاویر محصولات یا لیست خدمات خود را براساس <a class="collapsed" style="color: #0ad06e !important;" data-toggle="collapse" href="#collapseExample">راهنما</a> ارسال نمایید.</h4>
                                    <div class="collapse" id="collapseExample" style="">
                                        <p>
                                        <div class="card">
                                            <div class="card-body">
                                            <li>اگر تأمین کننده خدمات هستید "این فرم" را دانلود و پس از تکمیل فرم به همراه فایل تصویر لوگوی خود برای ما ارسال کنید.</li>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                            <li>درصورتی که ارائه دهنده کالا هستید:</li>
                                            <li>۱) ابتدا تصویر با کیفیت محصولات خود را آماده نمایید.</li>
                                            <li>۲) نام هر عکس را ویرایش نمایید و مشخصات محصول را جایگزین نمایید.</li>
                                            <li>مثال:   jpg.چای کیسه ای 20 عددی احمد</li>
                                            </div>
                                            <div class="card-body">
                                            <li>۳) تمام تصاویر و فرم تکمیل شده را بصورت فایل فشرده (zip) برای ما ارسال نمایید.</li>
                                            <li>۴) ظرف یک هفته، محصولات و خدمات به پنل اضافه می گردد و به شما اطلاع رسانی می شود.</li>
                                            </div>
                                        </div>
                                        </p>
                                        <form action="#" id="mydropzone">
                                            <div class="row">
                                                <div class="custom-file col-md-3">
                                                    <input type="file" class="custom-file-input" id="customFile">
                                                    <label class="custom-file-label" for="customFile">انتخاب فایل</label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
