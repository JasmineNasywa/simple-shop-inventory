<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<hr>

<h1>Laravel Simple Shop Inventory Starter-Kit</h1>

<p>This repository is a lightweight boilerplate (starter-kit) designed for Point of Sales (POS) and shop inventory management applications built with the Laravel framework. It focuses on providing a clean, baseline architecture for core functionalities, including basic sales/purchase logging and automated database stock deductions.</p>

<p>This template serves as a solid foundation to accelerate development for retail management applications, academic projects, or small-scale commercial minimum viable products (MVPs).</p>

<h2>Core Architecture & Concepts</h2>
<ul>
  <li><strong>Standard CRUD Operations:</strong> Pre-configured database schema and models for item master data, ready for further expansion.</li>
  <li><strong>Transaction Logic:</strong> Simple checkout flows integrated with real-time inventory adjustments.</li>
  <li><strong>Data Consistency:</strong> Optimized database interactions to ensure accurate stock quantities upon successful transaction commits.</li>
</ul>

<h2>Getting Started (Local Installation)</h2>

<p>Follow these steps sequentially to set up and run this project template in your local development environment:</p>

<h3>1. Clone the Repository</h3>
<p>Open your terminal or Command Prompt (CMD) and execute the following commands:</p>
<pre><code>git clone https://github.com/JasmineNasywa/simple-shop-inventory.git
cd simple-shop-inventory</code></pre>

<h3>2. Environment Configuration</h3>
<p>Duplicate the default environment template and configure your database credentials:</p>
<pre><code>cp .env.example .env</code></pre>
<p>Open the <code>.env</code> file and adjust the following database settings to match your local server configuration (MySQL/PostgreSQL):</p>
<pre><code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=</code></pre>

<h3>3. Install Dependencies & Generate Application Key</h3>
<p>Run Composer to install all framework package dependencies and generate the secure app key:</p>
<pre><code>composer install
php artisan key:generate</code></pre>

<h3>4. Database Migration & Seeding</h3>
<p>Create a new empty database via phpMyAdmin or your preferred DBMS tool matching the name in your <code>.env</code> file, then run:</p>
<pre><code>php artisan migrate --seed</code></pre>

<h3>5. Run the Application Server</h3>
<p>Start the local development server with the following artisan command:</p>
<pre><code>php artisan serve</code></pre>
<p>Once the server is up and running, open your web browser and navigate to: <strong>http://127.0.0.1:8000</strong></p>

<hr>

<h2>Contributing</h2>
<p>This boilerplate is open-source software. You are fully welcome to fork this repository, add custom features, optimize the codebase, or submit Pull Requests to enhance this starter-kit.</p>

<h2>License</h2>
<p>This project is open-source software and is free to use for educational, personal portfolio, or commercial purposes (MIT License style). You are fully permitted to use, modify, and deploy this template to run real-world shop businesses or commercial projects.</p>
