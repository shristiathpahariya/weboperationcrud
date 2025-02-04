# Photo Upload & Profile Management System

This project is a PHP-based web application that allows users to manage their profiles and upload photos with captions. Users can update their profile information, add a bio, and upload a "Photo of the Day" with a caption that reflects their mood. The project follows a CRUD (Create, Read, Update, Delete) approach for managing user-uploaded photos.

## Features

### User Profile Management
- Users can update their profile information (name, phone number, and profile picture).
- A bio can be added and edited directly from the profile header.

### Photo Upload System
- Users can upload images along with a caption to express their mood.
- Uploaded photos are displayed on the index page.
- Each photo is timestamped with the upload date and time.
- Users can edit the caption or delete their uploaded photos.

### Authentication
- User authentication is required to access profile and photo upload features.
- Logged-in users can log out, and unauthorized users are redirected to the login page.

## Technologies Used
- **Frontend:** HTML, CSS (Neumorphism design for a modern look)
- **Backend:** PHP, MySQL
- **Database:** MySQL for storing user profiles and uploaded photos
- **Storage:** `uploads/` directory for storing profile pictures and photos

## Installation

1. **Clone the repository:**
   ```sh
   git clone https://github.com/yourusername/photo-upload-app.git
   cd photo-upload-app
   ```

2. **Set up the database:**
   - Import the `database.sql` file into your MySQL database.
   - Update `db.php` with your database credentials.

3. **Run the application:**
   - Start a local server (e.g., XAMPP, MAMP, or a built-in PHP server):
     ```sh
     php -S localhost:8000
     ```
   - Open `http://localhost:8000` in your browser.

## Database Schema

### Users Table
| Column       | Type         | Description |
|-------------|-------------|-------------|
| id          | INT (PK)     | Unique user ID |
| first_name  | VARCHAR(255) | User's first name |
| last_name   | VARCHAR(255) | User's last name |
| email       | VARCHAR(255) | User email (unique) |
| phone       | VARCHAR(20)  | Contact number |
| profile_photo | VARCHAR(255) | Path to profile picture |
| bio         | TEXT         | User's bio |

### User Photos Table
| Column       | Type         | Description |
|-------------|-------------|-------------|
| id          | INT (PK)     | Unique photo ID |
| user_id     | INT (FK)     | ID of the user who uploaded the photo |
| caption     | TEXT         | User's caption for the photo |
| photo_path  | VARCHAR(255) | Path to the uploaded photo |
| created_at  | TIMESTAMP    | Timestamp of upload |

## Usage

1. **Register/Login**
   - Users need to sign up or log in to access the profile and photo upload features.

2. **Update Profile**
   - Navigate to the profile page to update personal details.
   - Click on the bio section to edit it directly.

3. **Upload a Photo**
   - Use the upload form on the index page to add an image with a caption.
   - The uploaded photo appears on the index page with a timestamp.

4. **Edit/Delete Photos**
   - Click on "Edit Caption" to update the text.
   - Click "Delete Photo" to remove an image from the gallery.

## Contributing

If you would like to contribute:
- Fork the repository
- Create a new branch (`git checkout -b feature-name`)
- Commit changes (`git commit -m 'Added new feature'`)
- Push to the branch (`git push origin feature-name`)
- Submit a pull request

## License
This project is open-source and available under the [MIT License](LICENSE).

