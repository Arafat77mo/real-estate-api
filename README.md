# Real Estate API

A robust and scalable backend API for managing real estate properties. Built using Laravel, this API provides features to manage properties, media uploads, and advanced search functionality.

## Features

- **Property Management**: Create, update, delete, and view properties with detailed specifications.
- **Media Handling**: Upload and manage images and videos associated with properties.
- **Advanced Search**: Filter properties based on various criteria like location, price, size, etc.
- **Pagination**: Efficient pagination using `fastPaginate` for handling large datasets.
- **RESTful API**: Designed with REST principles for seamless integration with frontend applications.

## Prerequisites

- PHP >= 8.1
- Composer
- MySQL
- Laravel >= 10
- Node.js & npm (for asset compilation)

## Installation

1. Clone the repository:
   ```bash
   git clone git@github.com:Arafat77mo/real-estate-api.git
   ```

2. Navigate to the project directory:
   ```bash
   cd real-estate-api
   ```

3. Install dependencies:
   ```bash
   composer install
   npm install
   ```

4. Copy the `.env.example` file to `.env` and configure your environment variables:
   ```bash
   cp .env.example .env
   ```

5. Generate an application key:
   ```bash
   php artisan key:generate
   ```

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

### API Endpoints

- **Properties**:
    - `GET /api/properties`: List all properties.
    - `POST /api/properties`: Create a new property.
    - `GET /api/properties/{id}`: View a specific property.
    - `PUT /api/properties/{id}`: Update a property.
    - `DELETE /api/properties/{id}`: Delete a property.


### Search Functionality

Use the `/api/properties` endpoint with query parameters to filter results:
- `location`: Filter by location.
- `min_price` and `max_price`: Filter by price range.
- `bedrooms`: Filter by number of bedrooms.

Example:
```bash
GET /api/properties?q=test
```

## Media Uploads

- Media files are stored in the `storage/app/public` directory.
- Run the following command to create a symbolic link to the `public` directory:
  ```bash
  php artisan storage:link
  ```

## Development

### Running Tests

Run the PHPUnit tests to ensure the application is working as expected:
```bash
php artisan test
```

### Compiling Assets

Compile the frontend assets using Laravel Mix:
```bash
npm run dev
```
For production:
```bash
npm run build
```

## Contributing

1. Fork the repository.
2. Create a new branch for your feature:
   ```bash
   git checkout -b feature-name
   ```
3. Commit your changes:
   ```bash
   git commit -m "Add a meaningful commit message"
   ```
4. Push to the branch:
   ```bash
   git push origin feature-name
   ```
5. Create a pull request.

## License

This project is licensed under the MIT License. See the LICENSE file for details.

---

### Author

Developed by Muhammad Arafat.
