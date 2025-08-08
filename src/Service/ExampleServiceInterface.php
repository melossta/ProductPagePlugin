<?php declare(strict_types=1);

namespace SwagExamplePlugin\Service;

interface ExampleServiceInterface
{
    public function filter(string $word): string;
}