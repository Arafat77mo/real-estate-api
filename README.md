# Real Estate API

A robust and scalable backend API for managing real estate properties. Built using Laravel, this API provides features to manage properties, media uploads, users, roles, notifications, and jobs for background processing.

## Features

- **Property Management**: 
   - Create, update, delete, and view properties with detailed specifications such as location, price, size, etc.
   - CRUD operations for managing property details.
   
- **Media Handling**: 
   - Upload and manage images and videos associated with properties, such as property images, cover photos, and videos.
   
- **Advanced Search**: 
   - Filter properties based on various criteria such as location, price, size, and number of bedrooms.

- **Pagination**: 
   - Efficient pagination using `fastPaginate` for handling large datasets without compromising performance.

- **Users and Roles**: 
   - Manage users with specific roles like `owner`, `user`, etc. 
   - Assign roles to users and manage permissions for different types of users.

- **Notifications**: 
   - Send notifications to users, such as alerts on new properties, updates, or relevant actions.

- **Jobs**: 
   - Use Laravel's queue system to handle background jobs like sending emails, processing files, and other tasks.

- **RESTful API**: 
   - Designed with REST principles for seamless integration with frontend applications and external services.

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
Navigate to the project directory:

bash
نسخ
تحرير
cd real-estate-api
Install dependencies:

bash
نسخ
تحرير
composer install
npm install
Copy the .env.example file to .env and configure your environment variables (database, mail, etc.):

bash
نسخ
تحرير
cp .env.example .env
Generate an application key:

bash
نسخ
تحرير
php artisan key:generate
Run database migrations:

bash
نسخ
تحرير
php artisan migrate
Run the database seeder (optional, for sample data):

bash
نسخ
تحرير
php artisan db:seed
Start the development server:

bash
نسخ
تحرير
php artisan serve
For production, configure your .env file and ensure you deploy assets with:

bash
نسخ
تحرير
npm run production
Usage
API Endpoints
Properties
GET /api/properties: List all properties with optional filters.
POST /api/properties: Create a new property.
GET /api/properties/{id}: View a specific property by ID.
PUT /api/properties/{id}: Update a property.
DELETE /api/properties/{id}: Delete a property.
Example Request to List Properties:
bash
نسخ
تحرير
GET /api/properties
Example Request to Create a Property:
bash
نسخ
تحرير
POST /api/properties
Content-Type: application/json
Body:
{
    "name": "Luxury Villa",
    "location": "Downtown",
    "price": 500000,
    "size": 3500,
    "bedrooms": 4,
    "bathrooms": 3,
    "description": "A beautiful luxury villa with a pool.",
    "media": [file1, file2] // optional media files
}
Example Request to View a Specific Property:
bash
نسخ
تحرير
GET /api/properties/{id}
Users and Roles
Users can be created, updated, and assigned specific roles like owner, user.
Roles define user permissions and access levels in the system.
API Endpoints for Users:
POST /api/users: Create a new user.
GET /api/users: List all users.
GET /api/users/{id}: View a specific user.
PUT /api/users/{id}: Update a user.
DELETE /api/users/{id}: Delete a user.
Example Request to Create a User:
bash
نسخ
تحرير
POST /api/users
Content-Type: application/json
Body:
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "role": "user" // Role can be owner or user
}
Assigning Roles to Users:
You can assign roles to users using the role field while creating or updating a user.

Example Request to Update a User’s Role:
bash
نسخ
تحرير
PUT /api/users/{id}
Content-Type: application/json
Body:
{
    "role": "owner"
}
Notifications
The API supports notifications, allowing you to notify users about important events, such as new properties or system alerts.

Send a Notification: Trigger notifications to users using Laravel's built-in notification system.
Retrieve Notifications: Users can check their notifications.
Example Request to Send a Notification:
bash
نسخ
تحرير
POST /api/notifications
Content-Type: application/json
Body:
{
    "user_id": 1,
    "message": "New property available in Downtown!"
}
Example Request to Get Notifications:
bash
نسخ
تحرير
GET /api/notifications/{user_id}
Jobs
Jobs are used for handling background tasks such as sending emails, processing data, or other time-consuming operations. Laravel’s built-in queue system allows you to offload tasks efficiently.

Create Jobs: Jobs can be created to handle tasks such as sending emails for new property listings or processing media files.
Queue System: Use Laravel's queue to dispatch jobs and handle them asynchronously.
Example of Dispatching a Job:
bash
نسخ
تحرير
php artisan queue:work
Search Functionality
Use the /api/properties endpoint with query parameters to filter results:

location: Filter by location.
min_price and max_price: Filter by price range.
bedrooms: Filter by number of bedrooms.
size: Filter by property size (square footage).
Example Search Query:
bash
نسخ
تحرير
GET /api/properties?location=Downtown&min_price=200000&max_price=700000
Media Uploads
Media files (images, videos) are stored in the storage/app/public directory.

To make media publicly accessible, run the following command to create a symbolic link:

bash
نسخ
تحرير
php artisan storage:link
When uploading media, you can attach images and videos to a property:

Image uploads: Can be used for property images or gallery images.
Video uploads: Can be used for property walkthrough videos.
Example Request to Upload Media:
bash
نسخ
تحرير
POST /api/properties/{id}/media
Content-Type: multipart/form-data
Body:
{
    "media": [file1, file2]
}
Development
Running Tests
Run PHPUnit tests to ensure the application is working as expected:

bash
نسخ
تحرير
php artisan test
Compiling Assets
To compile the frontend assets using Laravel Mix:

bash
نسخ
تحرير
npm run dev
For production:

bash
نسخ
تحرير
npm run build
Contributing
Fork the repository.
Create a new branch for your feature:
bash
نسخ
تحرير
git checkout -b feature-name
Commit your changes:
bash
نسخ
تحرير
git commit -m "Add a meaningful commit message"
Push to the branch:
bash
نسخ
تحرير
git push origin feature-name
Create a pull request to the main repository.
License
This project is licensed under the MIT License. See the LICENSE file for details.

Author
Developed by Muhammad Arafat.

markdown
نسخ
تحرير

### Explanation of the Changes:
- **Users and Roles**: Added endpoints for user management (create, update, delete users), role assignment, and role-based access control.
- **Notifications**: Included functionality to send and retrieve notifications for users.
- **Jobs**: Integrated Laravel's background job functionality to handle time-consuming tasks efficiently.

This updated README includes all necessary features for user management, role handling, no
