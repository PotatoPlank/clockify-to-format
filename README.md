# clockify-to-format
Converting Clockify CSV exports to another format

## Requirements
- PHP 8.1

## Installation
```
composer install
```

## Usage
```
php sample.php <input_file> [output_file]
```
Omitting the output file in the sample script will save the XLSX file to the same filename as the input file, but with the extension `.xlsx` instead of `.csv`.