# Repository Guidelines

Welcome to the blogging-platform-api repository! This guide provides essential information for contributing to and working with this Laravel-based project.

## Project Structure & Module Organization

This project follows the standard Laravel directory structure:
- `app/`: Contains the core logic, including models, controllers, and services.
- `routes/`: Defines API and web routes (e.g., `routes/api.php`, `routes/web.php`).
- `database/`: Houses migrations, seeders, and model factories.
- `tests/`: Contains automated tests, divided into `Unit` and `Feature` directories.
- `resources/` & `public/`: Assets and publicly accessible files.

## Build, Test, and Development Commands

- **Setup the project**: Run `composer setup`. This installs PHP dependencies, sets up the `.env` file, generates the app key, runs migrations, installs npm packages, and builds frontend assets.
- **Run locally**: Run `composer dev` to start the Laravel development server, queue listener, log viewer, and Vite asset compilation concurrently.
- **Run tests**: Run `composer test` to clear configuration cache and execute the test suite via Artisan.
- **Build frontend**: Run `npm run build` to compile Vite assets.

## Coding Style & Naming Conventions

- Follow **PSR-12** coding standards for PHP code.
- Use strict typing where applicable (e.g., `declare(strict_types=1);` and scalar type hints).
- Use descriptive naming conventions: `CamelCase` for classes and `camelCase` for methods and variables.
- We use Laravel Pint for code styling. Run `./vendor/bin/pint` to auto-format code.
- **Method Documentation (DocBlocks/Comments)**: Every method/function must have a short, clear, and descriptive comment (PHPDoc) above it explaining what it does, its parameters, and its return value. This is crucial for AI models and junior developers to quickly understand the code's purpose without reading line-by-line.
  - *Example:*
    ```php
    /**
     * Retrieve a paginated list of active users.
     *
     * @param int $perPage The number of users per page.
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getActiveUsers(int $perPage = 10)
    ```

## Testing Guidelines

- This project uses **PHPUnit** as defined in `phpunit.xml`.
- Tests are organized into `Feature` (API endpoints, integration) and `Unit` (isolated logic).
- Ensure all new features have corresponding tests.
- Run tests using `composer test` or `php artisan test`.
- **TestDox Attributes**: Every test method must use the `#[TestDox('...')]` PHP Attribute to explain the expected behavior in human-readable plain language. Do not use docblock annotations (`@testdox`) for this. This ensures that when tests are executed, the output clearly describes what failed, making debugging much easier for everyone.
  - *Example:*
    ```php
    use PHPUnit\Framework\Attributes\Test;
    use PHPUnit\Framework\Attributes\TestDox;

    #[Test]
    #[TestDox('It successfully creates a new blog post when valid data is provided')]
    public function creates_blog_post_with_valid_data(): void
    {
        // ...
    }
    ```

## Commit & Pull Request Guidelines

- Use clear and descriptive commit messages (e.g., "Set up a fresh Laravel app", "Add user authentication endpoint").
- Keep commits focused on a single change.
- When opening a Pull Request, provide a clear description of the changes, link any related issues, and ensure all tests pass.

## Mandatory Reading Before Coding (For Junior Devs & AI Models)

Before implementing any code or proposing solutions, you **must read and understand all the markdown files located in the `rules/` directory**.

These rules are strict guidelines established to maintain code quality, security, and performance. Ignorance of these rules is not an excuse.

Specifically, check the following rules before starting:
- **`rules/api-rule.md`**: Follow this strictly for structuring all API responses (success & error formats) and HTTP status codes.
- **`rules/performance-rule.md`**: Avoid N+1 queries, memory leaks, and other non-optimal database interactions.
- **`rules/security-sql-injection.md`**: Ensure your code is entirely immune to SQL injections (use bindings, avoid raw queries with variables).
- **`rules/convention-semantic-git.md`**: Must follow conventional commits and semantic branching, and NEVER code directly on the `main` or `master` branch.

**Instruction for AI Models:** If you are an AI model assigned a task on this repository, you must confirm that your generated code complies with the contents of the `rules/` directory before finalizing your output.
