# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.2] - 2026-01-18

### Added
- Official support for PHP 8.1.
- Expanded CI test matrix to include PHP 8.1 through 8.5.

## [1.0.1] - 2026-01-18

### Added
- Input validation for phone numbers and airtime amounts.
- Support for optional `+` prefix in phone numbers (e.g., `2547...` and `+2547...`).
- Airtime amount range validation (5 - 10,000 KES).
- `ValidationException` for handling 422 API responses with structured errors.
- Configurable timeout and base URL support in `StatumConfig`.
- Improved testability with `ClientInterface` support in `HttpClient`.
- Enhanced test coverage (16 tests covering success and error scenarios).
- Expanded documentation with better examples and links to dashboard and docs.
- Test support for PHP 8.2 up to 8.5 in CI.

### Changed
- Refactored `HttpClient` for better error mapping and testability.
- Updated `README.md` with comprehensive usage examples and error handling.

## [1.0.0] - 2026-01-18

### Added
- Initial release of the Statum PHP SDK.
- Support for Airtime API.
- Support for SMS API.
- Support for Account Details API.
- Strongly-typed DTOs for all responses.
- HTTP Basic Authentication.
- PSR-12 and PSR-4 compliance.
