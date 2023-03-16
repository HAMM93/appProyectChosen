<?php

namespace Tests\Unit;

use App\Helpers\ImageHelper;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ValidateImageTest extends TestCase
{
    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function validateImageFromBase64()
    {
        $base64 = file_get_contents(__DIR__ . '/../Mock/fileBase64');

        $result = ImageHelper::validateImageBase64($base64);

        $this->assertTrue($result);
    }
}
