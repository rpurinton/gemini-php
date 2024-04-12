# Configuration

Configuring Gemini PHP is straightforward. You need to provide several key pieces of information that the library will use to authenticate and interact with the Gemini AI platform.

## Configuration Parameters

- `projectId`: Your Google Cloud Project ID.
- `regionName`: The Google Cloud region where your Vertex AI API is hosted.
- `credentialsPath`: The file path to your service account credentials JSON.
- `modelName`: The name of the AI model you wish to use, such as `gemini-pro` or `gemini-pro-vision`.
- `ignoreModelValidation`: Set to true to not be constricted to `gemini-pro` or `gemini-pro-vision`.
- `ignoreRegionValidation`: Set to true to not be constricted to hardcoded regions.

## Example Configuration

Here's an example of how to configure the `GeminiClient`:

```php
$config = [
    'projectId' => 'ai-project-123456',
    'regionName' => 'us-east4',
    'credentialsPath' => 'path-to-your-credentials.json',
    'modelName' => 'gemini-pro',
];

$client = new GeminiClient($config);
```

Ensure that the `credentialsPath` points to a valid JSON file containing your service account credentials. The `modelName` should correspond to the specific AI model you intend to use for generating content.
