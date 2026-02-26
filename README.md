# Quizz-ly - Quiz Platform 🎓

## About the Project
The Quizz-ly project was developed as part of the Programming Workshops (WPRG) course during my 2nd semester of studies. The application is a fully functional web platform that allows users to take existing quizzes and create their own question sets.

This project (graded at 73%) represents a significant milestone in my early web development journey. The primary goal was to practically apply PHP for backend logic, session-based authentication, and file system management. Instead of utilizing a standard relational database, the application stores user data, scores, and quiz content within a system of structured text files.

## Features
* **Taking Quizzes**: Users can test their knowledge across various categories, including Pop Culture, Sports, Geography, and Automotive.
* **Various Question Types**: The system supports text input, single-choice, multiple-choice, and image-based guessing (image guess).
* **User Management**: A complete login and registration system (passwords are securely hashed using the `password_hash()` function), along with the ability to upload custom profile pictures.
* **Score Tracking**: Each user profile monitors the statistics of correct answers and calculates the success rate for individual categories. There is also an option to export achievements to a text file.
* **Quiz Creator**: Logged-in users can dynamically add their own quizzes by structuring questions, inputting answer options, and uploading images from their local drives.

## Technologies Used
* **Backend**: PHP (session management, POST/GET request handling, file I/O operations, server-side file upload verification).
* **Frontend**: HTML5, CSS3.
* **Database**: The project utilizes `.txt` files as a lightweight database to store application data and state (e.g., configurations for quiz tables and users).

## How to Run the Project Locally
1. Clone this repository to your local machine.
2. Move the downloaded folder to the appropriate directory of your PHP-supporting server (e.g., `htdocs` in XAMPP or `www` in WAMP).
3. **Important:** Ensure your local server has write permissions (read/write) for the `wyniki/`, `QUIZY/`, and `zdjeciaProfilowe/` directories, as the application dynamically generates and saves new files there.
4. Start your local development server and navigate to: `http://localhost/your_project_folder_name/logowanie.php` in your browser.

## Retrospective & Future Development
I consider this project an important educational stepping stone. If I were to rebuild this application today with my current knowledge, I would implement the following improvements:
* **Database Migration**: Replacing text file operations with a fully-fledged relational database (e.g., MySQL or PostgreSQL).
* **Architecture**: Refactoring the code using the MVC (Model-View-Controller) design pattern to better separate business logic from the presentation layer.
* **Security**: Implementing advanced security measures against SQL Injection (upon adding a database) and XSS attacks through stricter validation and sanitization of form inputs.
