# Installation

## Prerequisites

Before installing Gemini PHP, ensure you have the following:

- PHP 8.2 or higher
- Composer
- A Google Cloud Project with the Vertex AI API enabled
- A service account with the necessary permissions

## Installing Gemini PHP

To install Gemini PHP, run the following command in your project directory:

```bash
composer require rpurinton/gemini-php
```

This will download the library and its dependencies into your project's `vendor` directory.

## Configuration

After installation, you will need to configure Gemini PHP with your Google Cloud Project details and service account credentials. Create a configuration array with the following keys:

- `projectId`: Your Google Cloud Project ID
- `regionName`: The region where your Vertex AI API is hosted
- `credentialsPath`: The file path to your service account credentials JSON
- `modelName`: The name of the AI model you wish to use (e.g., `gemini-pro`)

Pass this configuration array to the `GeminiClient` constructor when initializing the client in your application.