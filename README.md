# Symfony Base Template

<p>
<!-- Version Badge -->
<img src="https://img.shields.io/badge/Version-0.5.0-blue" alt="Version 0.5.0">
<!-- License Badge -->
<img src="https://img.shields.io/badge/License-GPL--3.0--or--later-40adbc" alt="License GPL-3.0-or-later">
</p>

A _highly opinionated_, ready-to-use Symfony 7.3 project template with the Latte templating engine.

## Installation

```bash
composer create-project cloudbase/symfony-base my-project
```

## What's Included?

- Symfony 7.3
- Latte Templating Engine
- Full Authentication System: Login, Registration, Password Reset and Email Verification
- Doctrine ORM/DBAL
- NPM with Webpack
- TailwindCSS
- TypeScript
- SCSS

## Basic Usage

After installation, run `npm install` to install node dependencies. This has been excluded from the auto-setup script to
allow you to run this in your container or your own environment without requiring node/npm locally.

Update your projects `.env` file with your database credentials and run the SQL manually (`data/setup.sql`).

## Customising

This is just a base project with some helpful features baked in. Modify anything and everything to suit your needs.

Your app homepage route is defined in `src/Application/IndexController.php` and the template is located 
at `templates/application/index.latte` - remove the default content and replace it with your own.

All the auth code has been included and can be found in the `src/Auth` directory. Out of the box this includes:

- Login
- Registration
- Password Reset
- Email Verification

Again, feel free to modify any part of this to suit your needs.