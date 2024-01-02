## Release Notes for v0.1.1

- Updated `composer.json` to include new dependencies and set version to `0.1.1`.
- Added `AccessTokenManager.php` for managing access tokens.
- Refactored `GeminiClient.php` to use `AccessTokenManager` and added retry logic for API requests.
- Updated `GeminiPrompt.php` to include token count functionality and improved validation.
- Modified `GeminiResponse.php` to handle response parsing with improved error handling.
- Replaced `file_get_contents` with `cURL` in `HTTPClient.php` for better error handling and performance.
- Enhanced validation logic in `Validate.php` to include more comprehensive checks and error messages.
