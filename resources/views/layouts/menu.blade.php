<li class="nav-item">
    <a href="{{ route('wareHouses.index') }}"
       class="nav-link {{ Request::is('wareHouses*') ? 'active' : '' }}">
        <p>Ware Houses</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('shops.index') }}"
       class="nav-link {{ Request::is('shops*') ? 'active' : '' }}">
        <p>Shops</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('categories.index') }}"
       class="nav-link {{ Request::is('categories*') ? 'active' : '' }}">
        <p>Categories</p>
    </a>
</li>
















<li class="nav-item">
    <a href="{{ route('brands.index') }}"
       class="nav-link {{ Request::is('brands*') ? 'active' : '' }}">
        <p>Brands</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('suppliers.index') }}"
       class="nav-link {{ Request::is('suppliers*') ? 'active' : '' }}">
        <p>Suppliers</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('menufactures.index') }}"
       class="nav-link {{ Request::is('menufactures*') ? 'active' : '' }}">
        <p>Menufactures</p>
    </a>
</li>




<li class="nav-item">
    <a href="{{ route('products.index') }}"
       class="nav-link {{ Request::is('products*') ? 'active' : '' }}">
        <p>Products</p>
    </a>
</li>


