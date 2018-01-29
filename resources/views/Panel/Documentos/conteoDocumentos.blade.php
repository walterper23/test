<div class="js-inbox-nav d-none d-md-block">
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Menú</h3>
            <div class="block-options">
                <div class="dropdown">
                    <button type="button" class="btn-block-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="javascript:void(0)">
                            <i class="fa fa-fw fa-flask mr-5"></i>Filter
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)">
                            <i class="fa fa-fw fa-cogs mr-5"></i>Manage
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content">
            <ul class="nav nav-pills flex-column push">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-between @if($type=='news') {{ 'active' }} @endif" href="{{ url('panel/documentos/?type=news') }}">
                        <span><i class="fa fa-fw fa-inbox mr-5"></i> Nuevos</span>
                        <span class="badge badge-pill badge-secondary">{{ sizeof($nuevos) }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-between @if($type=='all') {{ 'active' }} @endif" href="{{ url('panel/documentos/?type=all') }}">
                        <span><i class="fa fa-fw fa-cubes mr-5"></i> Todos</span>
                        <span class="badge badge-pill badge-secondary">{{ sizeof($todos) }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-between @if($type=='important') {{ 'active' }} @endif" href="{{ url('panel/documentos/?type=important') }}">
                        <span><i class="fa fa-fw fa-star mr-5"></i> Importantes</span>
                        <span class="badge badge-pill badge-secondary">{{ sizeof($importantes) }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-between @if($type=='finished') {{ 'active' }} @endif" href="{{ url('panel/documentos/?type=finished') }}">
                        <span><i class="fa fa-fw fa-flag-checkered mr-5"></i> Finalizados</span>
                        <span class="badge badge-pill badge-secondary">{{ sizeof($finalizados) }}</span>
                    </a>
                </li>
                <!--li class="nav-item">
                    <hr class="my-5">
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)">
                        <i class="fa fa-fw fa-plus mr-5"></i> Create label
                    </a>
                </li-->
            </ul>
        </div>
    </div>
</div>