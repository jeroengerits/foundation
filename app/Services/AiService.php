<?php

declare(strict_types=1);

namespace App\Services;

use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Schema\ArraySchema;
use Prism\Prism\Schema\BooleanSchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;

final class AiService
{
    private const DEFAULT_PROVIDER = Provider::Anthropic;
    private const DEFAULT_MODEL = 'claude-3-5-sonnet-20241022';

    /**
     * Generate text using AI
     */
    public function generateText(string $prompt, ?string $systemPrompt = null): string
    {
        $prism = Prism::text()
            ->using(self::DEFAULT_PROVIDER, self::DEFAULT_MODEL);

        if ($systemPrompt !== null) {
            $prism = $prism->withSystemPrompt($systemPrompt);
        }

        $response = $prism
            ->withPrompt($prompt)
            ->generate();

        return $response->text;
    }

    /**
     * Generate structured data using AI
     */
    public function generateStructuredData(string $prompt, array $schemaDefinition): array
    {
        $objectSchema = $this->buildSchema($schemaDefinition);

        $response = Prism::structured()
            ->using(self::DEFAULT_PROVIDER, self::DEFAULT_MODEL)
            ->withSchema($objectSchema)
            ->withPrompt($prompt)
            ->asStructured();

        return $response->structured;
    }

    /**
     * Analyze an image using AI
     */
    public function analyzeImage(string $imagePath, string $prompt): string
    {
        $response = Prism::text()
            ->using(self::DEFAULT_PROVIDER, self::DEFAULT_MODEL)
            ->withPrompt($prompt)
            ->withImages($imagePath)
            ->generate();

        return $response->text;
    }

    /**
     * Build a Prism schema from array definition
     */
    private function buildSchema(array $schemaDefinition): ObjectSchema
    {
        $properties = [];
        $requiredFields = [];

        foreach ($schemaDefinition['properties'] as $name => $property) {
            $type = $property['type'] ?? 'string';
            $description = $property['description'] ?? '';

            $schema = match ($type) {
                'string' => new StringSchema($name, $description),
                'integer', 'int', 'number' => new NumberSchema($name, $description),
                'boolean', 'bool' => new BooleanSchema($name, $description),
                'array' => new ArraySchema($name, $description),
                default => new StringSchema($name, $description),
            };

            $properties[] = $schema;

            if ($property['required'] ?? false) {
                $requiredFields[] = $name;
            }
        }

        return new ObjectSchema(
            name: 'response',
            description: $schemaDefinition['description'] ?? 'Structured response',
            properties: $properties,
            requiredFields: $requiredFields
        );
    }
}