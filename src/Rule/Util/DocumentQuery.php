<?php

namespace DTL\GherkinLint\Rule\Util;

use Cucumber\Messages\FeatureChild;
use Cucumber\Messages\GherkinDocument;

class DocumentQuery
{
    public static function countScenarios(GherkinDocument $document): int
    {
        $count = 0;
        foreach ($document->feature?->children ?? [] as $featureChild) {
            if (!$featureChild instanceof FeatureChild) {
                continue;
            }

            if ($featureChild->scenario) {
                $count++;
            }
        }

        return $count;
    }

    public static function countBackgrounds(GherkinDocument $document): int
    {
        $count = 0;
        foreach ($document->feature?->children ?? [] as $featureChild) {
            if (!$featureChild instanceof FeatureChild) {
                continue;
            }

            if ($featureChild->background) {
                $count++;
            }
        }

        return $count;
    }
}
