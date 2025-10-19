# Publishing Trysail PHP SDK to Packagist

This guide will help you publish the Trysail PHP SDK to Packagist.

## Prerequisites

1. A GitHub account
2. A Packagist account (sign up at https://packagist.org)
3. The repository must be public on GitHub
4. Composer installed locally

## Step 1: Prepare the Repository

Ensure your repository has:
- [x] `composer.json` with proper package name and metadata
- [x] `README.md` with installation and usage instructions
- [x] `LICENSE` file (MIT)
- [x] `CHANGELOG.md` for version history
- [x] `.gitignore` to exclude unnecessary files

## Step 2: Push to GitHub

1. Create a repository on GitHub (e.g., `trysail/php-sdk`)
2. Push your code:

```bash
cd sdk/php
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/YOUR_USERNAME/php-sdk.git
git push -u origin main
```

## Step 3: Tag a Release

Create a version tag for your first release:

```bash
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

## Step 4: Submit to Packagist

1. Go to https://packagist.org
2. Log in with your account
3. Click "Submit" in the top menu
4. Enter your GitHub repository URL: `https://github.com/YOUR_USERNAME/php-sdk`
5. Click "Check" to validate
6. Click "Submit" to add the package

## Step 5: Set Up Auto-Update

To automatically update Packagist when you push to GitHub:

1. Go to your Packagist package page
2. Click "Settings" (if you're the owner)
3. Copy the API token URL
4. Go to your GitHub repository settings
5. Navigate to Webhooks → Add webhook
6. Paste the Packagist API URL
7. Set Content type to `application/json`
8. Click "Add webhook"

Alternatively, install the Packagist GitHub Service:
1. Go to your repository Settings → Integrations & services
2. Add the "Packagist" service
3. Enter your Packagist username and API token

## Step 6: Verify Installation

Test that your package can be installed:

```bash
composer require trysail/sdk
```

## Updating the Package

When you want to release a new version:

1. Update `CHANGELOG.md` with changes
2. Commit your changes:
   ```bash
   git add .
   git commit -m "Release v1.1.0"
   ```
3. Create a new tag:
   ```bash
   git tag -a v1.1.0 -m "Release version 1.1.0"
   git push origin v1.1.0
   ```
4. Packagist will auto-update (if webhook is configured)

## Version Naming Convention

Follow Semantic Versioning (semver):
- `v1.0.0` - Major release (breaking changes)
- `v1.1.0` - Minor release (new features, backwards compatible)
- `v1.0.1` - Patch release (bug fixes)

## Composer.json Package Name

The package name in `composer.json` must be:
```json
{
  "name": "trysail/sdk"
}
```

This will be available as: `composer require trysail/sdk`

## Common Issues

### Package not found
- Ensure your repository is public
- Check that the repository URL is correct on Packagist
- Wait a few minutes after submission

### Composer can't resolve dependencies
- Ensure all dependencies in `composer.json` exist
- Check version constraints are valid
- Run `composer validate` locally

### Auto-update not working
- Verify the webhook is configured correctly
- Check webhook delivery in GitHub settings
- Ensure Packagist API token is valid

## Best Practices

1. **Always tag releases**: Use git tags for versions
2. **Keep CHANGELOG.md updated**: Document all changes
3. **Follow PSR standards**: Use PSR-4 autoloading, PSR-12 coding style
4. **Write tests**: Include PHPUnit tests
5. **Use semantic versioning**: Follow semver strictly
6. **Document breaking changes**: Clearly mark in CHANGELOG

## Resources

- Packagist: https://packagist.org
- Composer documentation: https://getcomposer.org/doc/
- Semantic Versioning: https://semver.org
- PSR Standards: https://www.php-fig.org/psr/