# Pdf/ePub Viewer – Laravel

## Prerequisites

-   PHP
-   [Composer](https://getcomposer.org/download/)
-   [Laravel Installer](https://laravel.com/docs/8.x#the-laravel-installer)

You can install PHP via [XAMPP](https://www.mamp.info/en/mac/), [MAMP](https://www.apachefriends.org/index.html) or [Homebrew](https://formulae.brew.sh/formula/php).

## Getting Started

1. Clone the repo:

```bash
git clone https://github.com/nikolozi0525/PdfViewer.git

cd PdfViewer
```

2. Run `composer install` on your terminal.

3. Copy `.env.example` file to `.env` on the root folder.

-   For Windows, type `copy .env.example .env`
-   For Ubuntu, type `cp .env.example .env`

4. Generate your application encryption key using `php artisan key:generate`.

5. Copy & paste Pdfviewer as a dependency:
   Copy pdfviewer plugin and paste it into `/public/assets/` folder.

Make sure your `/public/assets/pdfviewer/` folder contains the file `pdfviewer.js` and a `pdfviewer-lib` directory with library assets.

## Running the Project

We are ready to launch the app! 🎉

```bash
php artisan serve
```

You can now open http://localhost:8000/ in your browser and enjoy!
