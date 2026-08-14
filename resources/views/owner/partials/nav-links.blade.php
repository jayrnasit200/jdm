@php
    $navLinks = [
        ['route' => 'owner.dashboard', 'match' => 'owner.dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard'],
        ['route' => 'weekreport', 'match' => 'weekreport', 'icon' => 'fa-calendar', 'label' => 'This Week'],
        ['route' => 'owner.sellers.index', 'match' => 'owner.sellers.*', 'icon' => 'fa-users', 'label' => 'Sellers'],
        ['route' => 'owner.shops.index', 'match' => 'owner.shops.*', 'icon' => 'fa-building', 'label' => 'Shops'],
        ['route' => 'owner.products.index', 'match' => 'owner.products.*', 'icon' => 'fa-cube', 'label' => 'Products', 'absolute' => false],
        ['route' => 'owner.reports.products', 'match' => 'owner.reports.products', 'icon' => 'fa-line-chart', 'label' => 'Sales Reports'],
        ['route' => 'reports.shop-balance', 'match' => 'reports.shop-balance', 'icon' => 'fa-building', 'label' => 'Shop Balances'],
    ];
@endphp

@foreach ($navLinks as $link)
    <a href="{{ isset($link['absolute']) ? route($link['route'], absolute: false) : route($link['route']) }}"
       class="nav-link {{ request()->routeIs($link['match']) ? 'active' : '' }}">
        <i class="fa {{ $link['icon'] }}"></i>
        <span>{{ $link['label'] }}</span>
    </a>
@endforeach

<a href="{{ url('/') }}" class="nav-link">
    <i class="fa fa-external-link"></i>
    <span>Back To Site</span>
</a>
