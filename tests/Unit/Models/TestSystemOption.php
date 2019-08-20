<?php


namespace Tests\Unit\Models;

use App\Models\SystemOption;
use Tests\TestCase;

class TestSystemOption extends TestCase
{
    public function testCasting()
    {
        // Should have a default type of string
        $option = new SystemOption([
            'key'   => 'foo',
            'value' => 'bar'
        ]);

        $this->assertSame('bar', $option->value);

        // Should cast to a boolean
        $option = new SystemOption([
            'key'   => 'foo',
            'value' => 1,
            'type'  => 'boolean'
        ]);

        $this->assertTrue($option->value);

        // Should cast to array from json
        $json = ['test' => 'baz'];

        $option = new SystemOption([
            'key'   => 'foo',
            'value' => $json,
            'type'  => 'json'
        ]);

        $this->assertSame($json, $option->value);
    }
}
