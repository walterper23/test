@extends('app.layoutMaster')

@section('title')
	{{ title('Inicio') }}
@endsection

@section('content')
<div class="row gutters-tiny invisible" data-toggle="appear">
    <!-- Row #1 -->
    <div class="col-6 col-xl-3">
        <a class="block block-link-rotate block-transparent text-right bg-primary-lighter" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-bag fa-3x text-primary"></i>
                </div>
                <div class="font-size-h3 font-w600 text-primary-darker" data-toggle="countTo" data-speed="1000" data-to="1500">0</div>
                <div class="font-size-sm font-w600 text-uppercase text-primary-dark">Sales</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a class="block block-link-rotate block-transparent text-right bg-primary-lighter" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-wallet fa-3x text-primary"></i>
                </div>
                <div class="font-size-h3 font-w600 text-primary-darker">$<span data-toggle="countTo" data-speed="1000" data-to="780">0</span></div>
                <div class="font-size-sm font-w600 text-uppercase text-primary-dark">Earnings</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a class="block block-link-rotate block-transparent text-right bg-primary-lighter" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-envelope-open fa-3x text-primary"></i>
                </div>
                <div class="font-size-h3 font-w600 text-primary-darker" data-toggle="countTo" data-speed="1000" data-to="15">0</div>
                <div class="font-size-sm font-w600 text-uppercase text-primary-dark">Messages</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a class="block block-link-rotate block-transparent text-right bg-primary-lighter" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-users fa-3x text-primary"></i>
                </div>
                <div class="font-size-h3 font-w600 text-primary-darker" data-toggle="countTo" data-speed="1000" data-to="4252">0</div>
                <div class="font-size-sm font-w600 text-uppercase text-primary-dark">Online</div>
            </div>
        </a>
    </div>
    <!-- END Row #1 -->
</div>
<div class="row gutters-tiny invisible" data-toggle="appear">
    <!-- Row #2 -->
    <div class="col-md-6">
        <div class="block block-themed block-mode-loading-refresh">
            <div class="block-header bg-primary">
                <h3 class="block-title">
                    Sales <small>This week</small>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                    <button type="button" class="btn-block-option">
                        <i class="si si-wrench"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="pull-all">
                    <!-- Lines Chart Container -->
                    <canvas class="js-chartjs-dashboard-lines"></canvas>
                </div>
            </div>
            <div class="block-content">
                <div class="row items-push">
                    <div class="col-6 col-sm-4 text-center text-sm-left">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">This Month</div>
                        <div class="font-size-h4 font-w600">720</div>
                        <div class="font-w600 text-success">
                            <i class="fa fa-caret-up"></i> +16%
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 text-center text-sm-left">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">This Week</div>
                        <div class="font-size-h4 font-w600">160</div>
                        <div class="font-w600 text-danger">
                            <i class="fa fa-caret-down"></i> -3%
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 text-center text-sm-left">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Average</div>
                        <div class="font-size-h4 font-w600">24.3</div>
                        <div class="font-w600 text-success">
                            <i class="fa fa-caret-up"></i> +9%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block block-themed block-mode-loading-refresh">
            <div class="block-header bg-primary">
                <h3 class="block-title">
                    Earnings <small>This week</small>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                    <button type="button" class="btn-block-option">
                        <i class="si si-wrench"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="pull-all">
                    <!-- Lines Chart Container -->
                    <canvas class="js-chartjs-dashboard-lines2"></canvas>
                </div>
            </div>
            <div class="block-content bg-white">
                <div class="row items-push">
                    <div class="col-6 col-sm-4 text-center text-sm-left">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">This Month</div>
                        <div class="font-size-h4 font-w600">$ 6,540</div>
                        <div class="font-w600 text-success">
                            <i class="fa fa-caret-up"></i> +4%
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 text-center text-sm-left">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">This Week</div>
                        <div class="font-size-h4 font-w600">$ 1,525</div>
                        <div class="font-w600 text-danger">
                            <i class="fa fa-caret-down"></i> -7%
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 text-center text-sm-left">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Balance</div>
                        <div class="font-size-h4 font-w600">$ 9,352</div>
                        <div class="font-w600 text-success">
                            <i class="fa fa-caret-up"></i> +35%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Row #2 -->
</div>
<div class="row gutters-tiny invisible" data-toggle="appear">
    <!-- Row #5 -->
    <div class="col-6 col-md-4 col-xl-2">
        <a class="block block-link-shadow text-center" href="be_pages_generic_inbox.html">
            <div class="block-content ribbon ribbon-bookmark ribbon-success ribbon-left">
                <div class="ribbon-box">15</div>
                <p class="mt-5">
                    <i class="si si-envelope-letter fa-3x"></i>
                </p>
                <p class="font-w600">Inbox</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <a class="block block-link-shadow text-center" href="be_pages_generic_profile.html">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-user fa-3x"></i>
                </p>
                <p class="font-w600">Profile</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <a class="block block-link-shadow text-center" href="be_pages_forum_categories.html">
            <div class="block-content ribbon ribbon-bookmark ribbon-primary ribbon-left">
                <div class="ribbon-box">3</div>
                <p class="mt-5">
                    <i class="si si-bubbles fa-3x"></i>
                </p>
                <p class="font-w600">Forum</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <a class="block block-link-shadow text-center" href="be_pages_generic_search.html">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-magnifier fa-3x"></i>
                </p>
                <p class="font-w600">Search</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <a class="block block-link-shadow text-center" href="be_comp_charts.html">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-bar-chart fa-3x"></i>
                </p>
                <p class="font-w600">Live Stats</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <a class="block block-link-shadow text-center" href="javascript:void(0)">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-settings fa-3x"></i>
                </p>
                <p class="font-w600">Settings</p>
            </div>
        </a>
    </div>
    <!-- END Row #5 -->
</div>
@endsection

@push('js-script')
{{ Html::script('js/plugins/chartjs/Chart.bundle.min.js') }}
{{ Html::script('js/pages/be_pages_dashboard.js') }}
@endpush