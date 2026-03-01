<?php

return [
    // Dashboard
    'dashboard.view',

    // Products
    'products.list', 'products.create', 'products.edit', 'products.status', 'products.delete',
    'products.combo.list', 'products.combo.create', 'products.combo.edit', 'products.combo.delete',

    // Orders
    'orders.list', 'orders.create', 'orders.edit', 'orders.status', 'orders.payment_status', 'orders.print', 'orders.send_courier', 'orders.delete',
    'orders.bulk.delete', 'orders.bulk.status', 'orders.bulk.print', 'orders.courier_export', 'orders.bulk.send_courier',

    // Customers
    'customers.list', 'customers.create', 'customers.edit', 'customers.status', 'customers.delete',

    // Suppliers
    'suppliers.list', 'suppliers.create', 'suppliers.edit', 'suppliers.status', 'suppliers.delete',

    // Purchases
    'purchases.list', 'purchases.create', 'purchases.edit', 'purchases.status', 'purchases.delete',

    // Visitors
    'visitors.list',
    'ip_address.list', 'ip_address.status', 'ip_address.delete',

    // Couriers & Shipping
    'couriers.list',
    'shipping_method.list', 'shipping_method.create', 'shipping_method.edit', 'shipping_method.status', 'shipping_method.delete',

    // Roles
    'roles.list', 'roles.create', 'roles.edit', 'roles.delete',

    // Staffs
    'staffs.list', 'staffs.create', 'staffs.edit', 'staffs.status', 'staffs.delete',

    // Settings
    'settings.general', 'settings.page', 'settings.courier_api', 'settings.attribute','settings.print',

    // Media
    'media.list', 'media.create', 'media.edit', 'media.status', 'media.delete',

    // Promotions & Banners
    'promotion_banner.list',

    // Sliders
    'sliders.list', 'sliders.create', 'sliders.edit', 'sliders.status', 'sliders.delete',

    // Categories
    'categories.list', 'categories.create', 'categories.edit', 'categories.delete', 'categories.status',

    // test
    'test.list', 'test.create', 'test.edit', 'test.status',
    'incomplete.order.list', 'incomplete.order.create', 'incomplete.order.delete',

    // Abandoned Cart
    'abandoned.cart.list', 'abandoned.order.create', 'abandoned.order.delete',

    // Device List
    'device.list',

    // Landing Page
    'landing.page.list', 'landing.page.create', 'landing.page.edit', 'landing.page.delete',

    // Review
    'review.list',

    // Newsletter
    'newsletter.list', 'newsletter.delete',

    // Theme
    'theme.list', 'theme.create', 'theme.delete', 'theme.status',

    // Accounts
    'accounts.list', 'accounts.create', 'accounts.edit', 'accounts.balance.add', 'accounts.delete',
    'accounts.transaction.list',

    // Expenses
    'expenses.list', 'expenses.create', 'expenses.edit', 'expenses.delete',
    'expenses.category.list', 'expenses.category.create', 'expenses.category.edit', 'expenses.category.delete',

    // Reports
    'reports.profit.loss', 'reports.account.transaction', 'reports.product.stock', 'reports.courier', 'reports.products',
];
