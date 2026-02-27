@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách tất cả đơn hàng </h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12"></div>
                <div class="x_panel" style="height: auto;">
                    <div class="x_title">
                        <h2>Danh sách tất cả đơn hàng </h2>
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
                                        Trang quản lý đơn hàng
                                    </p>
                                    <table id="datatable-buttons" class="table table-striped table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tài khoản</th>
                                                <th>Thông tin người đặt</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái đơn hàng</th>
                                                <th>Trạng thái thanh toán</th>
                                                <th>Chi tiết đơn hàng</th>
                                                <th>Hành động</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->user->name }}</td>
                                                    <th>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#orderAddressShippingModal-{{ $order->id }}">{{ $order->shippingAddress->address }}</a>
                                                    </th>
                                                    <td>{{ number_format($order->total_price, 0, ',', '.') }} VND</td>
                                                    <td class="order-status">
                                                        @if ($order->status == 'pending')
                                                            <span class="badge bg-warning ">Chờ xử lý</span>
                                                        @elseif ($order->status == 'processing')
                                                            <span class="badge bg-info ">Đang giao</span>
                                                        @elseif ($order->status == 'completed')
                                                            <span class="badge bg-success ">Đã hoàn thành </span>
                                                        @elseif ($order->status == 'canceled')
                                                            <span class="badge bg-danger ">Đã hủy</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->payment->status == 'pending')
                                                            <span class="badge bg-warning ">Chưa thanh toán</span>
                                                        @else
                                                            <span class="badge bg-success ">Đã thanh toán</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#orderItemsModal-{{ $order->id }}">Xem</button>
                                                    </td>
                                                    <td>
                                                        <!-- Split button -->
                                                        <div class="btn-group">
                                                              @csrf
                                                            <button type="button"
                                                                class="btn btn-danger dropdown-toggle dropdown-toggle-split"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @if ($order->status == 'pending')
                                                                    <a class="dropdown-item confirm-order"
                                                                        href="javascript:void(0)"
                                                                        data-id="{{ $order->id }}">Xác nhận</a>
                                                                @endif
                                                                <a class="dropdown-item" target ="_blank" href="{{ route('admin.orders-detail',['id'=>$order->id]) }}">Xem
                                                                    chi tiết</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @foreach ($orders as $order)
                                                <!-- Modal Address -->
                                        <div class="modal fade" id="orderAddressShippingModal-{{ $order->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="orderAddressShippingModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="orderAddressShippingModalLabel">Địa chỉ giao hàng
                                                        </h5>
                                                        <button type="button" class="btn-close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                   <p>Người nhận: {{ $order->shippingAddress->full_name }}</p>
                                                   <p>Số điện thoại: {{ $order->shippingAddress->phone }}</p>
                                                   <p>Địa chỉ: {{ $order->shippingAddress->address }}</p>
                                                   <p>Thành phố: {{ $order->shippingAddress->city }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="orderItemsModal-{{ $order->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="orderItemsModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="orderItemsModalLabel">Chi tiết đơn hàng
                                                        </h5>
                                                        <button type="button" class="btn-close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Sản phẩm</th>
                                                                    <th>Số lượng</th>
                                                                    <th>Giá</th>
                                                                    <th>Thành tiền</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $index = 1; @endphp
                                                                @foreach ($order->orderItems as $item)
                                                                    <tr>
                                                                        <td>{{ $index++ }}</td>
                                                                        <td>{{ $item->product->name }}</td>
                                                                        <td>{{ $item->quantity }}</td>
                                                                        <td>{{ number_format($item->price, 0, ',', '.') }}
                                                                            VND</td>
                                                                        <td>{{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                                                            VND</td>
                                                                    </tr>
                                                                @endforeach


                                                            </tbody>
                                                        </table>
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
            </div>
        @endsection
