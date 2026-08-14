<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use ParseError;
use Tests\TestCase;

class RatingFormBladeCompilationTest extends TestCase
{
    public function test_rating_form_compiles_to_valid_php(): void
    {
        $source = file_get_contents(resource_path('views/livewire/customer/rating/rating-form.blade.php'));

        $this->assertIsString($source);

        $compiled = Blade::compileString($source);

        try {
            eval('if (false) { ?>'.$compiled.'<?php }');
        } catch (ParseError $exception) {
            $this->fail('Das Bewertungsformular kompiliert zu ungueltigem PHP: '.$exception->getMessage());
        }

        $this->addToAssertionCount(1);
    }
}
