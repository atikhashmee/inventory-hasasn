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
    <a href="{{ route('products.index') }}"
       class="nav-link {{ Request::is('products*') ? 'active' : '' }}">
        <p>Products</p>
    </a>
</li>


