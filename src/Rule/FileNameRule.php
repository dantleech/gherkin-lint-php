<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\GherkinDocument;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use Generator;
use RuntimeException;
use Symfony\Component\Filesystem\Path;

class FileNameRule implements Rule
{
    private const PASCAL_CASE = '{^[A-Z0-9][a-z0-9]+([A-Z0-9][a-z0-9]*)*$}';

    public function analyse(GherkinDocument $document, RuleConfig $config): Generator
    {
        assert($config instanceof FileNameConfig);

        $path = $document->uri;
        if (null === $path) {
            return;
        }
        $path = Path::getFilenameWithoutExtension($path);

        $valid = match ($config->style) {
            FileNameConfig::PASCAL_CASE => $this->match(self::PASCAL_CASE, $path),
            default => throw new RuntimeException(sprintf(
                'Invalid filename style "%s"', $config->style
            )),
        };

        if ($valid) {
            return;
        }

        yield new FeatureDiagnostic(
            Range::fromInts(1,1,1,1),
            FeatureDiagnosticSeverity::WARNING,
            sprintf('Filename "%s" should be "%s"', $document->uri, $config->style)
        );
    }

    private function match(string $pattern, string $filename): bool
    {
        return (bool)preg_match($pattern, $filename);
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'file-name',
            'Filenames must conform to the specified stype',
            FileNameConfig::class,
        );
    }
}
