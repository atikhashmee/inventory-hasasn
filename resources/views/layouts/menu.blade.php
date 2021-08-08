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




