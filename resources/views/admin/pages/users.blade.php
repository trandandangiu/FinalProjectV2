@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">


            <div class="clearfix"></div>

            <div class="row">
                <div class="x_panel">
                    <div class="x_content">
                        <div class="row">
                            @foreach ($users as $user)
                                <div class="col-md-4 col-sm-4  profile_details">
                                    <div class="well profile_view">
                                        <div class="col-sm-12">
                                            <h4 class="brief text-uppercase "><i>{{ optional($user->role)->name }}</i></h4>
                                            <div class="left col-md-7 col-sm-7">
                                                <h2>{{ $user->name }}</h2>
                                                <p><strong>Email: </strong> {{ $user->email }}
                                                </p>
                                                <ul class="list-unstyled">
                                                    <li><i class="fa fa-building"></i> Address:{{ $user->address }} </li>
                                                    <li><i class="fa fa-phone"></i> Phone :{{ $user->phone_number }} </li>
                                                </ul>
                                            </div>
                                            <div class="right col-md-5 col-sm-5 text-center">
                                                <img src="{{ asset('storage/' . ($user->avatar ?? 'uploads/users/default-avatar.png')) }}"
                                                    alt="" class="img-circle img-fluid">
                                            </div>
                                        </div>
                                        <div class="profile-bottom text-center">
                                            <div class="col-sm-12 emphasis">
                                                {{-- Upgrade/Downgrade --}}
                                                @if ($user->role->name == 'customer')
                                                    <button type="button" class="btn btn-primary btn-sm upgradeStaff"
                                                        data-user-id="{{ $user->id }}">
                                                        <i class="fa fa-user"></i> Nhân viên
                                                    </button>
                                                @elseif ($user->role->name == 'staff')
                                                    <button type="button" class="btn btn-info btn-sm downgradeCustomer"
                                                        data-user-id="{{ $user->id }}">
                                                        <i class="fa fa-user"></i> Khách hàng
                                                    </button>
                                                @endif

                                                {{-- Chặn / Bỏ chặn --}}
                                                @if ($user->status == 'banned')
                                                    <button type="button" class="btn btn-success btn-sm changeStatus"
                                                        data-user-id="{{ $user->id }}" data-status="active">
                                                        <i class=""></i> Bỏ chặn
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-warning btn-sm changeStatus"
                                                        data-user-id="{{ $user->id }}" data-status="banned">
                                                        <i class=""></i> Chặn
                                                    </button>
                                                @endif
                                                {{-- Xóa hẳn --}}
                                                <button type="button" class="btn btn-danger btn-sm changeStatus"
                                                    data-user-id="{{ $user->id }}" data-status="deleted">
                                                    <i class="fa fa-trash"></i> Xóa
                                                </button>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    @endsection
