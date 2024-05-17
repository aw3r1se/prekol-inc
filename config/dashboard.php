<?php

return [
    'navigation' => [
        [
            'route' => 'dashboard.show',
            'label' => 'Dashboard',
        ],
        [
            'route' => 'dashboard.products.index',
            'label' => 'Products',
        ],
        [
            'route' => 'dashboard.orders.index',
            'label' => 'Orders',
        ],
        [
            'route' => 'dashboard.users.index',
            'label' => 'Users',
        ],
    ],
    'entities' => [
        'products' => [
            'name' => 'products',
            'label' => 'Products',
            'singular' => 'Product',
            'primaryKey' => 'uuid',
        ],
        'orders' => [
            'name' => 'orders',
            'label' => 'Orders',
            'singular' => 'Order',
            'primaryKey' => 'uuid',
        ],
        'users' => [
            'name' => 'users',
            'label' => 'Users',
            'singular' => 'User',
            'primaryKey' => 'uuid',
        ],
    ],
];
