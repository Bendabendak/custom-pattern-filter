# CustomPatternFilter

A modern PHP CLI tool to scan a file (or stream) using user-defined regex patterns, count matches, and output results in multiple formats.

---

## 🚀 Features

-   ✅ Filter text using multiple regex patterns
-   ✅ Stream from large files
-   ✅ Output in JSON, plain text, CSV, or Markdown
-   ✅ Log results to multiple files in different formats
-   ✅ Auto-detect format from log file extensions
-   ✅ CLI output formatter (`--format`)
-   ✅ Logging summary printed + saved to `log_summaries/`
-   ✅ `--summary=filename` to name the summary log
-   ✅ View the latest summary with `summary:latest`
-   ✅ Live progress output with `--debug`
-   ✅ Uses PHP 8+ features (readonly, generators, typed properties)

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

3. Run via:

```bash
php bin/custom-filter.php ...
```

or use:

```bash
./run.sh ...
```

---

## 📦 Usage

### ✅ Filter a file

```bash
php bin/custom-filter.php '/john/' '/@gmail\.com/' users.txt
```

### ✅ Output as JSON

```bash
--format=json
```

### ✅ Log results to multiple files

```bash
--log=output.json --log=output.md --log=output.csv
```

### ✅ Auto-detect format from extension

```bash
# Will log in markdown format
--log=report.md
```

### ✅ Write summary to a custom file

```bash
--summary=final-summary.txt
```

### ✅ View the latest summary

```bash
php bin/custom-filter.php summary:latest
```

---

## 📁 Project Structure

```
bin/             CLI entrypoint
src/             Core application files
src/CustomPatternFilter/Formatter/   Formatters for output (Text, JSON, CSV, Markdown)
src/CustomPatternFilter/Command/     Extra CLI commands like summary viewer
log_summaries/   Where summary logs are saved
tests/           PHPUnit tests
```

---

## 🧪 Testing

```bash
vendor/bin/phpunit
```

Includes tests for:

-   CLI command output
-   Invalid file handling
-   JSON format validation

---

## 👤 Author

-   **Name**: Benda Martin
-   **GitHub**: [github.com/Bendabendak](https://github.com/Bendabendak)
-   **License**: MIT

---
