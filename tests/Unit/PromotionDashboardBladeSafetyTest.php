<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class PromotionDashboardBladeSafetyTest extends TestCase
{
    public function test_test_tickets_do_not_require_a_participation_in_the_profile(): void
    {
        $source = file_get_contents(resource_path('views/livewire/dashboard.blade.php'));

        $this->assertIsString($source);
        $this->assertStringContainsString('$ticket->participation?->public_id', $source);
        $this->assertStringNotContainsString('$ticket->participation->public_id', $source);
        $this->assertStringContainsString("'TEST-'.strtoupper(substr((string) \$ticket->public_id, 0, 8))", $source);
        $this->assertStringContainsString('Dieser Probelauf hat keine reguläre Teilnahme-ID', $source);
        $this->assertNotSame('', Blade::compileString($source));
    }
}
