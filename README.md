# News Aggregator Project

This project is a news aggregator application built with Laravel for the backend and React for the frontend. It uses MySQL as the database and Docker for containerization.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Running the Project](#running-the-project)
  - [Using Docker Compose](#using-docker-compose)
  - [Manual Setup](#manual-setup)
- [Usage](#usage)
- [License](#license)

## Prerequisites

Before you begin, ensure you have the following installed:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/ewangclarkson/news-aggregator-app.git
   cd news-aggregator-app
   ```

2. Make sure you have the correct structure:

   ```
   news-aggregator/
   ├── docker-compose.yml
   ├── news-aggregator-api/
   │   ├── Dockerfile
   │   ├── entrypoint.sh
   │   └── ...
   └── news-aggregator-web/
       ├── Dockerfile
       └── ...
   ```

## Running the Project

### Using Docker Compose

To run the project using Docker Compose:

1. Build the Docker images and start the containers:

   ```bash
   docker-compose up --build
   ```

2. Access the application:
   - The backend (Laravel) will be available at `http://localhost:8000`
   - The frontend (React) will be available at `http://localhost:3000`

3. To stop the application, press `CTRL + C` in the terminal running Docker Compose or run:

   ```bash
   docker-compose down
   ```

### Manual Setup

If you prefer to run the project manually without Docker, follow these steps:

1. **Backend Setup (Laravel)**

   - Install Composer if you haven't already. Follow the instructions at [getcomposer.org](https://getcomposer.org/download/).
   
   - Navigate to the backend directory:

     ```bash
     cd news-aggregator-api
     ```

   - Install the dependencies:

     ```bash
     composer install
     ```

   - Set up your `.env` file with key env variables same as docker-compose.yml but with you mysql DB credentials:



   - Configure the database connection in your `.env` file.

   - Run the migrations and seed the database:

     ```bash
     php artisan migrate --seed
     ```
   - Set permissions if you previously ran the application through docker compose
       ```bash
        sudo chown -R www-data:www-data ./storage ./bootstrap/cache
        sudo chmod -R 775 ./storage ./bootstrap/cache
       ```
     www-data - should be your username as in your Terminal

   - Run necessary migrations
   ```bash
        php artisan migrate
        php artisan db:seed
        php artisan schedule:run >> /dev/null 2>&1 &
        php artisan fetch:articles
   ```
   - Start the Laravel server:

     ```bash
     php artisan serve 
     ```

2. **Frontend Setup (React)**

   - Navigate to the frontend directory:

     ```bash
     cd ../news-aggregator-web
     ```

   - Install the frontend dependencies:

     ```bash
     npm install
     ```

   - Start the React application:

     ```bash
     npm start
     ```

   - The frontend will be accessible at `http://localhost:3000`.

## Usage

- The application allows users to register, log in, and view aggregated news articles from various sources.
