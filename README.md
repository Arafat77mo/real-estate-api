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

- **Users and Roles && Permission**: 
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

تحرير

### Explanation of the Changes:
- **Users and Roles**: Added endpoints for user management (create, update, delete users), role assignment, and role-based access control.
- **Notifications**: Included functionality to send and retrieve notifications for users.
- **Jobs**: Integrated Laravel's background job functionality to handle time-consuming tasks efficiently.

This updated README includes all necessary features for user management, role handling, no
