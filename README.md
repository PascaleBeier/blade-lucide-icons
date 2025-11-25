# Blade Lucide Icons

<a href="https://github.com/technkermathe/blade-lucide-icons/actions?query=workflow%3ATests">
    <img src="https://github.com/technikermathe/blade-lucide-icons/workflows/Tests/badge.svg" alt="Tests">
</a>
<a href="https://packagist.org/packages/technikermthe/blade-lucide-icons">
    <img src="https://img.shields.io/packagist/v/technikermathe/blade-lucide-icons" alt="Latest Stable Version">
</a>
<a href="https://packagist.org/packages/technikermathe/blade-lucide-icons">
    <img src="https://img.shields.io/packagist/dt/technikermathe/blade-lucide-icons" alt="Total Downloads">
</a>

A package to easily make use of [Lucide](https://github.com/lucide-icons/lucide) Icons in your Laravel Blade views.

For a full list of available icons see [the SVG directory](resources/svg) or preview them at [lucide.dev](https://lucide.dev/).

## Requirements

- PHP 8.1 or higher
- Laravel 9.0 or higher

## Compatibility Matrix

| Laravel  | PHP 8.1 | PHP 8.2 | PHP 8.3 | PHP 8.4 | PHP 8.5 |
|----------|--------|--------|--------|--------|--------|
| 9.x      | ✅      | ✅      | ⚠️      | ❌      | ❌      |
| 10.x     | ✅      | ✅      | ✅      | ⚠️      | ⚠️      |
| 11.x     | ⚠️      | ✅      | ✅      | ✅      | ✅      |
| 12.x     | ❌      | ✅      | ✅      | ✅      | ✅      |

✅ Supported  
⚠️ Partially supported / Requires testing  
❌ Not tested / incompatible

## Installation

```bash
composer require technikermathe/blade-lucide-icons
```

## Blade Icons

Blade LucideIcons uses Blade Icons under the hood. Please refer to [the Blade Icons readme](https://github.com/blade-ui-kit/blade-icons) for additional functionality. We also recommend to [enable icon caching](https://github.com/blade-ui-kit/blade-icons#caching) with this library.

## Configuration

Blade LucideIcons also offers the ability to use features from Blade Icons like default classes, default attributes, etc. If you'd like to configure these, publish the `blade-lucide-icons.php` config file:

```bash
php artisan vendor:publish --tag=blade-lucide-icons-config
```

## Usage

Icons can be used as self-closing Blade components which will be compiled to SVG icons:

```blade
<x-lucide-activity />
```

You can also pass classes to your icon components:

```blade
<x-lucide-album class="w-6 h-6 text-gray-500"/>
```

This is how you can change the stroke width:

```blade
<x-lucide-server stroke-width="1"/>
```

And even use inline styles:

```blade
<x-lucide-anchor style="color: #555"/>
```

The solid icons can be referenced like this:

```blade
<x-lucide-bike />
```

### Raw SVG Icons

If you want to use the raw SVG icons as assets, you can publish them using:

```bash
php artisan vendor:publish --tag=blade-lucide-icons --force
```

Then use them in your views like:

```blade
<img src="{{ asset('vendor/blade-lucide-icons/cloud-moon.svg') }}" width="10" height="10"/>
```

## Use in Filament

For integration with [Filament](https://filamentphp.com/), check out the dedicated [filament-lucide-icons](https://github.com/CodeWithDennis/filament-lucide-icons) package by [Dennis Koch](https://github.com/CodeWithDennis).

## Changelog

Check out the [CHANGELOG](CHANGELOG.md) in this repository for all the recent changes.

## Maintainers

This is a [fork of the initial work of Dan Pock](https://github.com/mallardduck/blade-lucide-icons).

## License

Blade LucideIcons is open-sourced software licensed under [the MIT license](LICENSE.md).
