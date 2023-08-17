# Upgrade Guide

## 3.0 to 4.0

- Removed `zenstruck_redirect.model_manager_name` config
- `NotFound::$timestamp` changed to `\DateTimeImmutable`
- `Redirect::$lastAccessed` changed to `\DateTimeImmutable`
- Removed `NotFoundManager`
- Removed `RedirectManager`
