# Symfony Base Template

<p>
<!-- Version Badge -->
<img src="https://img.shields.io/badge/Version-0.5.0-blue" alt="Version 0.5.0">
<!-- License Badge -->
<img src="https://img.shields.io/badge/License-GPL--3.0--or--later-40adbc" alt="License GPL-3.0-or-later">
</p>

A highly opinionated, ready-to-use Symfony 7.3 project template with the Latte templating engine.

## Installation

```bash
composer create-project cloudbase/symfony-base my-project
```

## What's Included?

- Symfony 7.3
- Latte Templating Engine
- Doctrine ORM/DBAL
- NPM with Webpack
- TailwindCSS
- TypeScript
- SCSS

## Basic Usage

After installation, run `npm install` to install node dependencies. This has been excluded from the auto-setup script to
allow you to run this in your container or your own environment without requiring node/npm locally.

Update your projects `.env` file with your database credentials.