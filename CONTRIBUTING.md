# Contributing to Statum PHP SDK

We welcome contributions! Please follow these guidelines:

## Coding Standards
- The SDK follows [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards.
- All code must include strict typing: `declare(strict_types=1);`.
- Use descriptive variable and method names.

## Branching Strategy
- `main` is the stable branch.
- Create feature branches: `feature/your-feature-name`.
- Create fix branches: `fix/issue-description`.

## Pull Request Process
1. Fork the repository.
2. Create your feature branch.
3. Ensure all tests pass: `composer test`.
4. Submit a PR with a clear description of changes.

## Testing
New features must include unit tests. We strive for high test coverage.
