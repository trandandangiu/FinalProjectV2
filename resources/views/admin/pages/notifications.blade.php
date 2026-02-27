@extends('layouts.admin')

@section('title', 'Quản lý thông báo')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Thong bao</h3>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Inbox Design <small>User Mail</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-9 col-sm-9 ">
                                    <div class="" role="tabpanel" data-example-id="togglable-tabs">

                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane active " aria-labelledby="home-tab">


                                                <ul class="messages">
                                                    @foreach ($notifications as $notification)
                                                        <li>
                                                            <img src="{{ asset("assets/admin/images/bell.png") }}" class="avatar"
                                                                alt="Avatar">
                                                            <div class="message_date">
                                                                <h2 class="date text-info">
                                                                    {{ $notification->created_at->format('d') }}</h2>
                                                                <p class="month">
                                                                    {{ $notification->created_at->format('M Y') }}</p>
                                                                    
                                                            </div>
                                                            <div class="message_wrapper">
                                                               <a href="{{ "/admin" . $notification->link }}"> <h4 class="heading">{{ $notification->title }}</h4>
                                                                <blockquote class="message">{{ Str::limit($notification->message, 10) }}</blockquote></a>
                                                                <br /> 
                                                                </blockquote>
                                                                <br />
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>


                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- /page content -->
@endsection
