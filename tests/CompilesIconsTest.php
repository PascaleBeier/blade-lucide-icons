<?php

declare(strict_types=1);

namespace Tests;

use Technikermathe\LucideIcons\BladeLucideIconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Orchestra\Testbench\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CompilesIconsTest extends TestCase
{
    use MatchesSnapshots;

    public function test_it_compiles_a_single_anonymous_component(): void
    {
        $result = svg('lucide-activity')->toHtml();
        $this->assertMatchesXmlSnapshot($result);
    }

    public function test_it_can_add_classes_to_icons(): void
    {
        $result = svg('lucide-bell', 'w-6 h-6 text-gray-500')->toHtml();
        $this->assertMatchesXmlSnapshot($result);
    }

    public function test_it_can_add_styles_to_icons(): void
    {
        $result = svg('lucide-bell', ['style' => 'color: #555'])->toHtml();
        $this->assertMatchesXmlSnapshot($result);
    }

    protected function getPackageProviders($app)
    {
        return [
            BladeIconsServiceProvider::class,
            BladeLucideIconsServiceProvider::class,
        ];
    }
}
