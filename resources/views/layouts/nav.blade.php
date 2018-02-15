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
                <li class="dropdown {{ is_active_match('lists')}}">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                        Lists
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ is_active_route('lists')}}">
                            <a href="/lists">List Management</a>
                        </li>
                        <li class="{{ is_active_route('new-list')}}">
                            <a href="/list/create">Create a new list</a>
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