<?php //declare(strict_types=1);
//
//namespace SwagExamplePlugin\Service;
//
//class ExampleService implements ExampleServiceInterface
//{
//
//    public function filter(string $word): string
//    {
//        return str_replace('foo', '2', $word);
//    }
//}

declare(strict_types=1);

namespace SwagExamplePlugin\Service;

class ExampleService implements ExampleServiceInterface
{
    public function filter(string $word): string
    {
        return str_replace('foo', '2', $word); // Base replace
    }
}
