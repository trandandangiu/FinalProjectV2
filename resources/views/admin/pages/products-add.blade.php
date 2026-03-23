@extends('layouts.admin')

@section('title', 'Thêm sản phẩm ')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
    

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel disable-panel">
                        <div class="x_title">
                            <h2>Thêm sản phẩm mới</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <form action ="{{ route('admin.products.store') }}" id="add-product" method="POST"
                                class="form-horizontal form-label-left" enctype="multipart/form-data">
                                @csrf
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-name">Tên sản
                                        phẩm <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" id="product-name" name="name" required="required"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-name">Danh mục
                                        <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select id="product-category" name="category_id" required="required"
                                            class = "form-control">
                                            <option value =""> Chọn danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value ="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-description">Mô
                                        tả</label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" id="product-description" name="description"
                                            class="form-control">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-price">Giá
                                        <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="number" id="product-price" name="price" required="required"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-stock">Số lượng
                                        <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="number" id="product-stock" name="stock" required="required"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-unit">Đơn vị
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" id="product-unit" name="unit" required="required"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-images">Hình
                                        ảnh</label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <label class="custom-file-upload" for="product-images">Chọn ảnh</label>
                                        <input type="file" name="images" id="product-images" accept="image/*" multiple
                                            required style="display: none">
                                        <div id="images-preview-container"></div>
                                    </div>
                                </div>


                                <div class="ln_solid"></div>


                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3">
                                        <button class="btn btn-primary btn_reset" type="reset">Reset</button>
                                        <button type="submit" class="btn btn-success">Thêm sản phẩm</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
     
@endsection
