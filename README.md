<p align="center">
  <a href="https://boidcms.github.io" target="_blank">
    <img src="https://boidcms.github.io/_media/logo.svg" width="150" alt="BoidCMS">
  </a>
</p>
<p align="center">
  <a href="https://github.com/BoidCMS/BoidCMS/issues">
    <img alt="GitHub issues" src="https://img.shields.io/github/issues/BoidCMS/BoidCMS">
  </a>
  <a href="https://github.com/BoidCMS/BoidCMS/network">
    <img alt="GitHub forks" src="https://img.shields.io/github/forks/BoidCMS/BoidCMS">
  </a>
  <a href="https://github.com/BoidCMS/BoidCMS/stargazers">
    <img alt="GitHub stars" src="https://img.shields.io/github/stars/BoidCMS/BoidCMS">
  </a>
  <img alt="GitHub release (latest SemVer including pre-releases)" src="https://img.shields.io/github/v/release/BoidCMS/BoidCMS?include_prereleases">
  <img alt="GitHub" src="https://img.shields.io/github/license/BoidCMS/BoidCMS">
</p>

# BoidCMS

**BoidCMS** is a free and open-source flat-file CMS developed in **PHP**. It is designed for simple, fast websites but built with a focus on **modular architecture** and **backend extensibility** for developers. It uses JSON as it's primary data store, eliminating the need for relational databases like MySQL.

---

## Requirements

 - PHP 8+
 - Web server (Apache with `mod_rewrite` recommended)

---

## Installation

- Download the latest version on this repo.
- Extract the zip file.
- Upload the extracted content to your server or hosting.
- Follow the [admin page](https://boidcms.github.io/#/install?id=default-login-credentials) to configure your new website.

---

## Features

BoidCMS includes all standard CMS features, along with advanced modules engineered for systems governance and automation.

### Standard Features

  - One Step [Install](https://boidcms.github.io/#/install)
  - Simple and Easy to Navigate Admin Panel
  - Custom Admin URL, Page Permalink, Page Template
  - Blog Option, File Manager, and SEO Friendly
  - CSRF Authentication & GDPR Compliant

### Advanced Systems

These features showcase the platform's robust architecture and developer focus:
- **Custom REST API:** A core plugin provides full **CRUD (Create, Read, Update, Delete)** operations for site content and configuration, offering a clean service layer.
- **Dependency Resolution Engine:** Features an in-dashboard system that manages plugins and themes. It parses complex **JSON metadata** to automatically check and resolve all module dependencies before installation.
- **Systems Automation:** Includes a web-based **CRON task scheduler plugin** for managing asynchronous background jobs.
- **Code Governance:** The integrated Module Distribution System requires **Pull Requests (PRs) and code review** for all community packages to ensure system integrity.

---

## Support and Contribution

Ask questions, get support, and discuss BoidCMS on the [Discussions](https://github.com/BoidCMS/BoidCMS/discussions) page.

We welcome contributions! All code submissions are subject to the project's **code review** policy to maintain system stability.

---

## Origin and License

This project is a fork of [WonderCMS](https://www.wondercms.com).

BoidCMS is an open source software [licensed](https://boidcms.github.io/#/license) under the MIT License.
