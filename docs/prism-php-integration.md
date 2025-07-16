# Prism PHP Integration

This Laravel application now includes [Prism PHP](https://prismphp.com/), a powerful package for integrating Large Language Models (LLMs) into your application.

## Features

Prism PHP provides:
- **Multi-Provider Support**: Seamlessly switch between OpenAI, Anthropic, Ollama, Mistral, Groq, and more
- **Text Generation**: Generate AI-powered text with simple, expressive syntax
- **Structured Output**: Transform AI responses into strongly-typed data with schema validation
- **Multi-Modal Input**: Support for images, documents, audio, and video
- **Tool Integration**: Extend AI capabilities with custom tools and external APIs
- **Streaming Support**: Real-time response streaming
- **Testing Utilities**: Comprehensive testing support with response faking

## Configuration

1. Copy the environment variables from `.env.example` to your `.env` file
2. Add your API keys for the providers you want to use:
   - `ANTHROPIC_API_KEY` for Claude models
   - `OPENAI_API_KEY` for GPT models
   - `GEMINI_API_KEY` for Google's Gemini
   - etc.

**Important**: You must have at least one provider's API key configured to use Prism PHP. Without an API key, you'll get authentication errors.

## Usage Examples

### Basic Text Generation

```php
use App\Services\AiService;

$aiService = app(AiService::class);
$response = $aiService->generateText('Write a haiku about Laravel');
```

### With System Prompt

```php
$response = $aiService->generateText(
    'Explain quantum computing',
    'You are a physics professor who explains complex topics simply'
);
```

### API Endpoints

The application includes demo endpoints for testing:

- `POST /ai/generate-text` - Generate text from a prompt
- `POST /ai/generate-structured` - Generate structured data with schema validation

Example request:
```json
{
    "prompt": "Generate a product description",
    "system_prompt": "You are a creative copywriter"
}
```

## Service Architecture

The integration includes:
- `App\Services\AiService` - Main service for AI operations
- `App\Http\Controllers\AiDemoController` - Demo controller with examples
- Configuration in `config/prism.php`

## Testing

To test the integration:

1. Ensure you have valid API keys in your `.env` file
2. Run the test command: `php artisan prism:test`
3. Or use the API endpoints with your preferred HTTP client

The test command will verify:
- Basic text generation
- Text generation with system prompts
- Structured data output

## Resources

- [Prism PHP Documentation](https://prismphp.com/)
- [GitHub Repository](https://github.com/prism-php/prism)
- [Laravel Documentation](https://laravel.com/docs)