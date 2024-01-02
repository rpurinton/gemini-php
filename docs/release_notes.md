## Release Notes for v0.1.1

### Updated Files
- `composer.json`: Version bump to `0.1.1`.

### Added Files
- `src/AccessTokenManager.php`: New file for managing access tokens.

### Modified Files
- `src/GeminiClient.php`: Refactored to use `AccessTokenManager` and added retry logic.
- `src/GeminiPrompt.php`: Improved validation and default value handling.
- `src/GeminiResponse.php`: Default value for `$response` parameter in constructor.
- `src/HTTPClient.php`: Switched from `file_get_contents` to `cURL` for HTTP POST requests.
- `src/Validate.php`: Added constants for valid regions, models, categories, thresholds, and property types.

### Notes
The changes include improvements to error handling, code maintainability, and the addition of new features for better access token management and API request retries.## Release Notes for v0.1.2

### Modified Files
- `example.php`: Changed method call from `setContent([])` to `resetContent()` for clearing the prompt.
- `src/GeminiPrompt.php`: Added `resetContent` method and `base_contents` property to reset the prompt to its base state.

### Notes
The changes include code refactoring for better clarity and maintainability.## Release Notes for v0.1.3

### Modified Files
- `composer.json`: Version bump to `0.1.3`.
- `composer.lock`: Updated content hash.
- `src/GeminiResponse.php`: Added error handling in the constructor.
- `tests/GeminiResponseTest.php`: Added test for simulated error response.

### Notes
The changes include dependency updates and improved error handling in response parsing.## Release Notes for v0.1.4

### Modified Files
- `composer.json`: Version bump to `0.1.4`.
- `composer.lock`: Updated content hash.
- `example.php`: Refactored to use JSON configuration files.
- `src/GeminiPrompt.php`: Code refactoring and added new methods.

### Added Files
- `example-client.json`: New example configuration for the client.
- `example-prompt.json`: New example configuration for the prompt.

### Notes
The changes include refactoring for better code organization, the addition of example configuration files, and updates to dependencies.