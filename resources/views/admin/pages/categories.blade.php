@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách tất cả danh mục </h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12"></div>
                <div class="x_panel" style="height: auto;">
                    <div class="x_title">
                        <h2>Danh sách tất cả danh mục </h2>
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
                                    <p class="text-muted font-13 m-b-30">
                                        Trang quản lý danh mục
                                    </p>
                                    <table id="datatable-buttons" class="table table-striped table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Hình ảnh</th>
                                                <th>Tên danh mục</th>
                                                <th>Slug</th>
                                                <th>Mô tả </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr id="category-row-{{ $category->id }}">
                                                    <td>
                                                        <img src="{{ asset('storage/' . $category->image) }}"
                                                            alt="{{ $category->name }}" class = "image-category">
                                                    </td>
                                                    <td>{{ $category->name }}</td>
                                                    <td>{{ $category->slug }}</td>
                                                    <td>{{ $category->description }}</td>
                                                    <td>
                                                        <a class="btn btn-app btn-update-category" data-bs-toggle="modal"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal-{{ $category->id }}">
                                                            <i class="fa fa-edit"></i>
                                                            Chỉnh sửa
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-app btn-delete-category" data-id="{{ $category->id }}"><i class="fa fa-close">
                                                                Xóa
                                                            </i></a>
                                                    </td>
                                                </tr>
                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal-{{ $category->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="categoryModalLabel">Chỉnh sửa
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="update-category" method="POST"
                                                                    class="form-horizontal form-label-left"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align"
                                                                            for="category-name">Tên Danh
                                                                            mục <span class="required">*</span></label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <input type="text" id="category-name"
                                                                                name="name" required="required"
                                                                                class="form-control"
                                                                                value="{{ $category->name }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align"
                                                                            for="category-description">Mô tả</label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <input type="text" id="category-description"
                                                                                name="description" class="form-control"
                                                                                value="{{ $category->description }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label
                                                                            class="col-form-label col-md-3 col-sm-3 label-align">
                                                                            Hình ảnh
                                                                        </label>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <img src="{{ asset('storage/' . $category->image) }}"
                                                                                alt="{{ $category->name }}"
                                                                                id="image-preview-{{ $category->id }}"
                                                                                class="image-preview">

                                                                            <label class="custom-file-upload"
                                                                                for="category-image-{{ $category->id }}">
                                                                                Chọn ảnh
                                                                            </label>

                                                                            <input type="file" name="image"
                                                                                id="category-image-{{ $category->id }}"
                                                                                class="category-image"
                                                                                data-id="{{ $category->id }}"
                                                                                accept="image/*">
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Quay lại</button>
                                                                <button type="button"
                                                                    class="btn btn-primary btn-update-submit-category"
                                                                    data-id="{{ $category->id }}">Chỉnh sửa</button>
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
