
@if (auth()->user()->role == 'admin')
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
        <p>Manufactures</p>
    </a>
  </li>

  <li class="nav-item">
  <a href="{{ route('admin.units.index') }}"
    class="nav-link {{ Request::is('admin/units*') ? 'active' : '' }}">
      <p>Units</p>
  </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('admin.brands.index') }}"
      class="nav-link {{ Request::is('admin/brands*') ? 'active' : '' }}">
        <p>Brands</p>
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('admin.suppliers.index') }}"
      class="nav-link {{ Request::is('admin/suppliers*') ? 'active' : '' }}">
        <p>Suppliers</p>
    </a>
  </li>
@endif

@if (auth()->user()->role == 'staff' || auth()->user()->role == 'admin')
  <li class="nav-item">
    <a href="{{ route('admin.products.index') }}"
      class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}">
        <p>Products</p>
    </a>
  </li>

  <li class="nav-item">
      <a href="{{ route('admin.stocks.index') }}"
        class="nav-link {{ Request::is('admin/stocks*') ? 'active' : '' }}">
          <p>Purchase</p>
      </a>
  </li>
@endif

@if (auth()->user()->role == 'admin')
  <li class="nav-item">
      <a href="{{ route('admin.shop_products.index') }}"
        class="nav-link {{ Request::is('admin/shop_products*') ? 'active' : '' }}">
          <p>Product Distribution</p>
      </a>
  </li>
@endif

@if (auth()->user()->role == 'staff' || auth()->user()->role == 'admin')
<li class="nav-item">
    <a href="#" class="nav-link">
      <p>
        Sales
        <i class="fas fa-angle-left right"></i>
      </p>
    </a>
    <ul class="nav nav-treeview" style="display: none;">
      <li class="nav-item">
        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ Request::is('admin/orders') ? 'active' : '' }}">
          <p>Sales Lists</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.order.draft.index') }}" class="nav-link {{ Request::is('admin/orders') ? 'active' : '' }}">
          <p>Drafts</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.orders.create') }}" class="nav-link {{ Request::is('admin/orders/create') ? 'active' : '' }}">
          <p>New Sale</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.order.return') }}" class="nav-link {{ Request::is('admin/order/sell-return') ? 'active' : '' }}">
          <p>Sale Return</p>
        </a>
      </li>
      @if (env('DEMO_SHOW'))
      <li class="nav-item">
        <a href="{{ route('admin.report.productWiseSaleHistory') }}" class="nav-link {{ Request::is('admin/report/product-sale-history') ? 'active' : '' }}">
          <p>Product Wise Sale history</p>
        </a>
      </li>
      @endif
    </ul>
  </li>
  @endif

  @if (auth()->user()->role == 'admin')
  <li class="nav-item">
      <a href="{{ route('admin.users.index') }}"
        class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
          <p>Users</p>
      </a>
  </li>
  @endif

  <li class="nav-item">
      <a href="{{ route('admin.customers.index') }}"
        class="nav-link {{ Request::is('admin/customers*') ? 'active' : '' }}">
          <p>Customers</p>
      </a>
  </li>
  <li class="nav-item">
      <a href="{{ route('admin.transactions.index') }}"
        class="nav-link {{ Request::is('admin/transactions*') ? 'active' : '' }}">
          <p>Customer&nbsp;Transaction</p>
      </a>
  </li>

  <li class="nav-item">
      <a href="{{ route('admin.challans.index') }}"
        class="nav-link {{ Request::is('admin/challans*') ? 'active' : '' }}">
          <p>Challans</p>
      </a>
  </li>



  <li class="nav-item">
      <a href="{{ route('admin.quotations.index') }}"
        class="nav-link {{ Request::is('admin/quotations*') ? 'active' : '' }}">
          <p>Quotations</p>
      </a>
  </li>
  @if (auth()->user()->role == 'admin')
    <li class="nav-item">
        <a href="{{ route('admin.report.sells') }}"
          class="nav-link">
            <p>Reports</p>
        </a>
    </li>
  @endif


