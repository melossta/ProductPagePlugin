<?php declare(strict_types=1);

namespace SwagExamplePlugin\Service;

class ExampleServiceDecorator implements ExampleServiceInterface
{
    private ExampleServiceInterface $originalService;

    public function __construct(ExampleServiceInterface $exampleService)
    {
        $this->originalService = $exampleService;
    }

    public function filter(string $word): string
    {
        $word = $this->originalService->filter($word);
        return str_replace('2', '13', $word); // Replace 2 with 3 after base replacement
    }
}
