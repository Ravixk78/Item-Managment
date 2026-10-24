# Item Management System

A cloud-ready RESTful inventory web application built with a PHP backend and a responsive jQuery AJAX frontend client.

## Features

- **RESTful API Backend (`api.php`)**:
  - `GET /api.php`: Fetches all inventory items in JSON format (Status `200 OK`).
  - `POST /api.php`: Creates a new inventory item with payload `{ "name": "...", "description": "..." }` and returns the created record (Status `201 Created`).
  - Input validation and HTTP status error handling (`400 Bad Request`, `405 Method Not Allowed`).
- **Modern Responsive Frontend (`index.html`)**:
  - Dual Theme: Light and Dark Mode toggle with automatic user preference persistence.
  - Live Search & Filtering: Instant real-time search across item names and descriptions with keyword highlighting.
  - Metrics Dashboard: Real-time counter for total items, latest added item, and REST API connection status.
  - Asynchronous AJAX Client: Seamless CRUD operations without page reloads.

## Tech Stack

- **Frontend**: HTML5, Vanilla CSS3, jQuery 3.7.1, Google Fonts (Plus Jakarta Sans, JetBrains Mono)
- **Backend**: PHP 8.x (RESTful JSON API)
- **Storage**: Flat-file JSON database (`data.json`)

## Getting Started

1. Clone the repository:
   ```bash
   git clone https://github.com/Ravixk78/Item-Managment.git
   cd Item-Managment
   ```

2. Start the local PHP built-in server:
   ```bash
   php -S 127.0.0.1:8000
   ```
   *(Or place the project directory inside your XAMPP `htdocs` folder)*

3. Open your browser and navigate to:
   ```
   http://127.0.0.1:8000/index.html
   ```
