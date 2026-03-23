@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">


            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12"></div>
                <div class="x_panel" style="height: auto;">
                    <div class="x_title">
                        <h2>Danh sách tất cả sản phẩm </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">

                                    <table id="datatable-buttons" class="table table-striped table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Hình ảnh</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Danh mục</th>
                                                <th>Slug</th>
                                                <th>Mô tả </th>
                                                <th>Số lượng </th>
                                                <th>Giá</th>
                                                <th>Đơn vị</th>
                                                <th>Trạng thái</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                                <td>
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                        class="image-product">


                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name }}</td>
                                                <td>{{ $product->slug }}</td>
                                                <td>{{ $product->description }}</td>
                                                <td>{{ $product->stock }}</td>
                                                <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>


                                                <td>{{ $product->unit }}</td>
                                                <td>{{ $product->status == 'in_stock' ? 'Còn hàng' : 'Hết hàng' }}</td>
                                                <td>
                                                    <a class="btn btn-app btn-update-product" data-bs-toggle="modal"
                                                        data-toggle="modal"
                                                        data-target="#exampleModal-{{ $product->id }}">
                                                        <i class="fa fa-edit"></i>
                                                        Chỉnh sửa
                                                    </a>
                                                </td>
                                                <td>
                                                    <a class="btn btn-app btn-delete-product"
                                                        data-id="{{ $product->id }}"><i class="fa fa-close">
                                                            Xóa
                                                        </i></a>
                                                </td>
                                                </tr>
                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal-{{ $product->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="productModalLabel">Chỉnh sửa
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="update-product" method="POST"
                                                                    class="form-horizontal form-label-left"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align"
                                                                            for="product-name">Tên sản phẩm <span
                                                                                class="required">*</span></label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <input type="text" id="product-name"
                                                                                name="name" required="required"
                                                                                class="form-control"
                                                                                value="{{ $product->name }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align"
                                                                            for="product-name">Danh mục <span
                                                                                class="required">*</span></label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <select name="category_id" id="category_id"
                                                                                class="form-control">
                                                                                <option value="">Chọn danh mục
                                                                                </option>
                                                                                @foreach ($categories as $category)
                                                                                    <option
                                                                                        value="{{ $category->id }}"{{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                                                        {{ $category->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align"
                                                                            for="product-description">Mô tả</label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <input type="text" id="product-description"
                                                                                name="description" required
                                                                                class="form-control"
                                                                                value="{{ $product->description }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align"
                                                                            for="product-price">Giá</label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <input type="number" id="product-price"
                                                                                name="price" required class="form-control"
                                                                                value="{{ rtrim(rtrim($product->price, '0'), '.') }}">

                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align"
                                                                            for="product-stock">Số lượng</label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <input type="number" id="product-stock"
                                                                                name="stock" required
                                                                                class="form-control"
                                                                                value="{{ $product->stock }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align"
                                                                            for="product-unit">Đơn vị</label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <input type="text" id="product-unit"
                                                                                name="unit" required
                                                                                class="form-control"
                                                                                value="{{ $product->unit }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align"
                                                                            for="products-images">
                                                                            Hình ảnh
                                                                        </label>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div id="image-preview-container-{{ $product->id }}"
                                                                                class="image-preview-container image-preview-listproduct"
                                                                                data-id="{{ $product->id }}">
                                                                                @foreach ($product->images as $image)
                                                                                    <img src="{{ asset('storage/' . $image->image) }}"
                                                                                        alt="Ảnh sản phẩm"
                                                                                        style="width: 80px; height: 80px; object-fit: cover; margin: 5px;">
                                                                                @endforeach
                                                                            </div>
                                                                            <label class="custom-file-upload"
                                                                                for="product-images-{{ $product->id }}">
                                                                                Chọn ảnh
                                                                            </label>

                                                                            <input type="file" name="images[]"
                                                                                id="product-images-{{ $product->id }}"
                                                                                class="product-images"
                                                                                data-id="{{ $product->id }}"
                                                                                accept="image/*" multiple required>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Quay lại</button>
                                                                <button type="button"
                                                                    class="btn btn-primary btn-update-submit-product"
                                                                    data-id="{{ $product->id }}">Chỉnh sửa</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endsection
