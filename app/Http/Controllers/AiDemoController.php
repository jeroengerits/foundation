<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AiService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AiDemoController extends Controller
{
    public function __construct(
        private readonly AiService $aiService
    ) {}

    /**
     * Generate text based on a prompt
     */
    public function generateText(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => 'required|string|max:1000',
            'system_prompt' => 'nullable|string|max:500',
        ]);

        try {
            $text = $this->aiService->generateText(
                $validated['prompt'],
                $validated['system_prompt'] ?? null
            );

            return response()->json([
                'success' => true,
                'data' => ['text' => $text],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate text: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate structured data
     */
    public function generateStructuredData(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => 'required|string|max:1000',
            'schema' => 'required|array',
        ]);

        try {
            $data = $this->aiService->generateStructuredData(
                $validated['prompt'],
                $validated['schema']
            );

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate structured data: '.$e->getMessage(),
            ], 500);
        }
    }
}
