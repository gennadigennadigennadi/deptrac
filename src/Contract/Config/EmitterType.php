<?php

declare(strict_types=1);

namespace Qossmic\Deptrac\Contract\Config;

use Stringable;

enum EmitterType: string
{
    case CLASS_TOKEN = 'class';
    case CLASS_SUPERGLOBAL_TOKEN = 'class_superglobal';
    case FILE_TOKEN = 'file';
    case FUNCTION_TOKEN = 'function';
    case FUNCTION_CALL = 'function_call';
    case FUNCTION_SUPERGLOBAL_TOKEN = 'function_superglobal';
    case USE_TOKEN = 'use';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $type): string => $type->toString(),
            [
                self::CLASS_TOKEN,
                self::CLASS_SUPERGLOBAL_TOKEN,
                self::FILE_TOKEN,
                self::FUNCTION_TOKEN,
                self::FUNCTION_CALL,
                self::FUNCTION_SUPERGLOBAL_TOKEN,
                self::USE_TOKEN,
            ]
        );
    }

    public function toString(): string
    {
        return match ($this) {
            self::CLASS_TOKEN => 'class',
            self::CLASS_SUPERGLOBAL_TOKEN => 'class_superglobal',
            self::FILE_TOKEN => 'file',
            self::FUNCTION_TOKEN => 'function',
            self::FUNCTION_CALL => 'function_call',
            self::FUNCTION_SUPERGLOBAL_TOKEN => 'function_superglobal',
            self::USE_TOKEN => 'use',
        };
    }
}
