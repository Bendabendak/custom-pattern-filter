# CustomPatternFilter

A modern PHP CLI tool to scan a file using user-defined regex patterns, count matches, and output results in multiple formats.

---

## 🚀 Features

-   ✅ Filter text using multiple regex patterns
-   ✅ Output in JSON or plain text
-   ✅ Log results to file
-   ✅ Live progress updates (with `--debug`)
-   ✅ Supports PHP 8.4+ with modern features: generators, callables, enums, readonly DTOs

---

## ⚙️ Requirements

-   PHP 8.4 or higher
-   Composer

---

## 💪 Setup

1. Clone the repo:

```bash
git clone https://github.com/Bendabendak/custom-pattern-filter.git
cd custom-pattern-filter
```

2. Install dependencies:

```bash
composer install
```

3. Make the CLI executable (optional):

```bash
chmod +x bin/custom-filter.php
```

---

## 📦 Usage

### ✅ Basic file filtering

```bash
php bin/custom-filter.php 'patterns'... file
```

Example:

```bash
php bin/custom-filter.php '/@gmail\.com/' '/^john/' users.txt
```

### ✅ Debug mode (simulate slow processing)

```bash
php bin/custom-filter.php '/error/' log.txt --debug
```

### ✅ JSON output

```bash
php bin/custom-filter.php '/@gmail\.com/' '/john/' users.txt --format=json
```

### ✅ Log output to file

```bash
php bin/custom-filter.php '/fail/' '/warn/' users.txt --format=json --log=results.json
```

---

## 🧪 Testing

Run unit and CLI tests using PHPUnit:

```bash
vendor/bin/phpunit
```

---

## 🎯 Commands Summary

| Command         | Description                           |
| --------------- | ------------------------------------- |
| `--debug`       | Simulate slow line-by-line reading    |
| `--format=json` | Output as raw JSON (machine-readable) |
| `--log=path`    | Append output to a file               |

---

## Code Formatting

Run PHP-CS-Fixer to apply headers and formatting:

```bash
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix
```

✅ Automatically runs before each commit via a Git pre-commit hook.

---

## 🔧 run.sh Script

You can use the included `run.sh` script to automatically:

-   Ensure PHP 8.4+ is selected (via `update-alternatives`)
-   Run `composer install` if `vendor/` is missing
-   Execute the CLI entrypoint with the right PHP version

### 🔄 Run with:

```bash
./run.sh filter '/pattern/' file.txt
```

If `run.sh` does not work on your system, you can run the main CLI entrypoint manually:

```bash
php bin/custom-filter.php '/pattern/' file.txt
```

> Make sure you have PHP 8.4+ installed and available through `update-alternatives`.

---

## 👤 Author

-   **Name**: Benda Martin
-   **GitHub**: [github.com/Bendabendak](https://github.com/Bendabendak)
-   **License**: MIT

---

## 📁 Project Structure

```
bin/        → CLI entrypoint
src/        → Application source code
tests/      → Unit and functional tests
run.sh      → Portable CLI wrapper with PHP auto-detection
```

---

## 📌 Example JSON Output

```json
{
    "lines_read": 2500,
    "patterns": {
        "/@gmail\\.com/": 1234,
        "/^john/": 312
    },
    "input_source": "users.txt",
    "command": "filter",
    "arguments": {
        "patterns": ["/@gmail\\.com/", "/^john/"],
        "stdin": false,
        "debug": false,
        "format": "json"
    },
    "timestamp": "2025-04-09T19:05:03+00:00"
}
```

---

Enjoy blazing-fast filtering with modern PHP! 🔥
