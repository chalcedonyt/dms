<style>
    .dropdown:hover .dropdown-menu {
        display: block;
    }
</style>
<div class="bs-component">
    <div class="navbar navbar-default">
        <div class="navbar-header">
            <a class="navbar-brand" href="/home">LindyKL DMS</a>
        </div>
        <div class="navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown {{ is_active_match('list')}}">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                        Lists
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ is_active_route('lists')}}">
                            <a href="/lists">List Management</a>
                        </li>
                        <li class="{{ is_active_route('list-create')}}">
                            <a href="/list/create">Create a new list</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown {{ is_active_match('voucher')}}">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                        Vouchers
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ is_active_route('vouchers')}}">
                            <a href="/vouchers">Voucher Management</a>
                        </li>
                        <li class="{{ is_active_route('voucher-create')}}">
                            <a href="/voucher/create">Create a new voucher</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown {{ is_active_match('admin')}}">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                        Admins
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ is_active_route('vouchers')}}">
                            <a href="/admins">Admin Management</a>
                        </li>
                        <li class="{{ is_active_route('voucher-create')}}">
                            <a href="/admin/create">Create a new admin</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <img height="40" class="img-rounded" src="{{Auth::user()->avatar_url}}" />
                </li>
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                        {{Auth::user()->name}}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/logout">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>