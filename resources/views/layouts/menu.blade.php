


<!-- @sdfdfif( Auth::user()->role == 'client' )-->





<li class="nav-item">
    <a href="{{ route('home').'/order/create' }}" class="nav-link {{ (\Request::getPathInfo() == '/account/order/creat')? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Створення замовлень</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('home').'/order' }}" class="nav-link {{ (\Request::getPathInfo() == '/account/order')? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Історія замовлень</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('home').'/prices' }}" class="nav-link {{ (\Request::getPathInfo() == '/account/prices')? 'active' : '' }}">
        <p>Прайс по тз </p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('home').'/typeoforder' }}" class="nav-link {{ (\Request::getPathInfo() == '/account/typeoforder')? 'active' : '' }}">
        <p>Типи замовлень </p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('home').'/priceoforder' }}" class="nav-link {{ (\Request::getPathInfo() == '/account/priceoforder')? 'active' : '' }}">
        <p>Прайс</p>
    </a>
</li>



@if (Auth::user()->role == 'admin')

    <li  class="nav-item">
        <a href="{{ route('home').'/user/index' }}" class="nav-link {{ (\Request::getPathInfo() == '/account/user/index')? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Користувачі</p>
        </a>
    </li>

@endif


