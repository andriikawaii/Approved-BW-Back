<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
