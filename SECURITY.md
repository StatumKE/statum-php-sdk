# Security Policy

## Supported Versions

Only the latest major version is supported for security updates.

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

If you discover a security vulnerability within this SDK, please send an e-mail to security@statum.co.ke. All security vulnerabilities will be promptly addressed.

### Credential Handling Rules
- NEVER check credentials into version control.
- Use environment variables or secure vault systems.
- The SDK never logs authentication headers or raw request bodies containing secrets.
