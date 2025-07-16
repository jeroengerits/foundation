<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\AiService;
use Illuminate\Console\Command;

final class TestPrismCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prism:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Prism PHP integration';

    /**
     * Execute the console command.
     */
    public function handle(AiService $aiService): int
    {
        $this->info('Testing Prism PHP integration...');
        
        // Test 1: Basic text generation
        $this->info("\n1. Testing basic text generation:");
        try {
            $text = $aiService->generateText('Write a haiku about Laravel');
            $this->line($text);
            $this->info('✓ Text generation successful');
        } catch (\Exception $e) {
            $this->error('✗ Text generation failed: ' . $e->getMessage());
        }
        
        // Test 2: Text generation with system prompt
        $this->info("\n2. Testing text generation with system prompt:");
        try {
            $text = $aiService->generateText(
                'Explain MVC',
                'You are a Laravel expert who explains concepts concisely'
            );
            $this->line($text);
            $this->info('✓ Text generation with system prompt successful');
        } catch (\Exception $e) {
            $this->error('✗ Text generation with system prompt failed: ' . $e->getMessage());
        }
        
        // Test 3: Structured output
        $this->info("\n3. Testing structured output:");
        try {
            $schema = [
                'description' => 'A product review',
                'properties' => [
                    'product_name' => [
                        'type' => 'string',
                        'description' => 'Name of the product',
                        'required' => true,
                    ],
                    'rating' => [
                        'type' => 'integer',
                        'description' => 'Rating from 1 to 5',
                        'required' => true,
                    ],
                    'pros' => [
                        'type' => 'array',
                        'description' => 'List of pros',
                        'required' => true,
                    ],
                    'cons' => [
                        'type' => 'array',
                        'description' => 'List of cons',
                        'required' => true,
                    ],
                ],
            ];
            
            $data = $aiService->generateStructuredData(
                'Review the Laravel framework',
                $schema
            );
            $this->line(json_encode($data, JSON_PRETTY_PRINT));
            $this->info('✓ Structured output successful');
        } catch (\Exception $e) {
            $this->error('✗ Structured output failed: ' . $e->getMessage());
        }
        
        $this->info("\n✅ Prism PHP integration test completed!");
        
        return Command::SUCCESS;
    }
}