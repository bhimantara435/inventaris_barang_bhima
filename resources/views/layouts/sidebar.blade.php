<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <h3>Inventaris</h3>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-item {{ $activeMenu == 'kategori' ? 'active' : '' }}">
                    <a href="{{ route('kategori.index') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Kategori</span>
                    </a>
                </li>
                <li class="sidebar-item {{ $activeMenu == 'barang' ? 'active' : '' }}">
                    <a href="{{ route('barang.index') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Barang</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

