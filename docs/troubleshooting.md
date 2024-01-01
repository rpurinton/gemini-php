# Troubleshooting

Encountering issues while using Gemini PHP? Here are some common problems and their solutions.

## Common Issues

- **Authentication Errors**: Ensure your `credentialsPath` is correct and that the service account JSON file has the necessary permissions.
- **API Communication Failures**: Check your network connection and verify that the Gemini API is accessible from your server.
- **Invalid Configuration**: Double-check the values in your configuration array, especially the `projectId`, `regionName`, and `modelName`.
- **Response Parsing Errors**: Make sure the response from the API is valid JSON and that it matches the expected format.

## Debugging Tips

- **Check Logs**: Review the error logs for detailed information about any exceptions or errors.
- **Update Dependencies**: Run `composer update` to ensure all dependencies are up to date.
- **Review Documentation**: Refer back to the documentation to ensure you're using the library correctly.

If you continue to experience issues, consider reaching out to the Gemini PHP community or filing an issue on the project's GitHub repository.