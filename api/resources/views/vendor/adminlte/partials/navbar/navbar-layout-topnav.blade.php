<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand-md') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    <div class="{{ config('adminlte.classes_topnav_container', 'container') }}">

        {{-- Navbar toggler button --}}
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar collapsible menu --}}
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            {{-- Navbar left links --}}
            <div class="dropdown" style="margin-left: 5px;">
                <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="nav-icon fas fa-bars"></i> Menú
                </a>

                <div class="dropdown-menu" aria-labelledby="menuDropdown" style="width: 200px;">
                    <!-- Encabezado no clickeable -->
                    <h6 class="dropdown-header">MangaBakaShop</h6>
                    <div class="dropdown-divider"></div>

                    <a href="{{ route('autores.index') }}" class="dropdown-item">
                        <i class="fas fa-user"></i> Autores
                    </a>
                    <a href="{{ route('dibujantes.index') }}" class="dropdown-item">
                        <i class="fas fa-pencil-alt"></i> Dibujantes
                    </a>
                    <a href="{{ route('editoriales.index') }}" class="dropdown-item">
                        <i class="fas fa-building"></i> Editoriales
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-tags"></i> Categorías
                    </a>
                    <a href="{{ route('mangas.index') }}" class="dropdown-item">
                        <i class="fas fa-book"></i> Mangas
                    </a>
                    <a href="{{ route('tomos.index') }}" class="dropdown-item">
                        <i class="fas fa-book"></i> Tomos
                    </a>
                    <div class="dropdown-divider"></div>

                    <!-- Botón para cerrar sesión que abre el modal -->
                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-power-off"></i> Cerrar sesión
                    </a>
                </div>
            </div>
        </div>

        {{-- Navbar right links --}}
        <ul class="navbar-nav ml-auto order-1 order-md-3 navbar-no-expand">
            @yield('content_top_nav_right')
            @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')
        </ul>
    </div>
</nav>

<!-- Modal para confirmar cierre de sesión -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirmar cierre de sesión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas cerrar sesión?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary">Cerrar sesión</button>
        </form>
      </div>
    </div>
  </div>
</div>
