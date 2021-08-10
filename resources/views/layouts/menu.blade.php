
<li class="nav-item">
    <a href="{{ route('admin.wareHouses.index') }}"
       class="nav-link {{ Request::is('admin/wareHouses*') ? 'active' : '' }}">
        <p>Ware Houses</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('admin.shops.index') }}"
       class="nav-link {{ Request::is('admin/shops*') ? 'active' : '' }}">
        <p>Shops</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('admin.categories.index') }}"
       class="nav-link {{ Request::is('admin/categories*') ? 'active' : '' }}">
        <p>Categories</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('admin.menufactures.index') }}"
       class="nav-link {{ Request::is('admin/menufactures*') ? 'active' : '' }}">
        <p>Menufactures</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('admin.suppliers.index') }}"
       class="nav-link {{ Request::is('admin/suppliers*') ? 'active' : '' }}">
        <p>Suppliers</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('admin.brands.index') }}"
       class="nav-link {{ Request::is('admin/brands*') ? 'active' : '' }}">
        <p>Brands</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('admin.products.index') }}"
       class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}">
        <p>Products</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('admin.stocks.index') }}"
       class="nav-link {{ Request::is('admin/stocks*') ? 'active' : '' }}">
        <p>Stocks</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.users.index') }}"
       class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
        <p>Users</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.users.index') }}"
       class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
        <p>Shop Products</p>
    </a>
</li>
