# TV Schedule with PHP and MySQL

This project, TV Schedule, is a database application created for a university course on databases. It utilizes PHP and MySQL to manage a television schedule with various channels, shows, performers, and episodes.

## Database Schema

The database schema consists of the following tables:

- **Admin**: Stores administrator information including name, password, email, and last login timestamp.
- **Channel**: Contains information about television channels such as name, category, and description.
- **Performer**: Stores details about performers including name, birth date, nationality, and occupation.
- **Episode**: Represents episodes of TV shows with attributes like name, episode number, and summary.
- **Show_Performer**: A many-to-many relationship table linking shows and performers.
- **Projection**: Records projections of shows on channels with air date and time.

## Installation

To set up the TV Schedule application locally, follow these steps:

1. Clone this repository to your local machine.
2. Import the SQL script provided into your MySQL database management tool.
3. Configure the database connection settings in the PHP files (`config.php`).
4. Run the PHP application on a local server environment.

## Database Initialization

The provided SQL script (`musorujsag.sql`) contains the necessary SQL statements to create the database schema, insert sample data, and create a user with appropriate permissions.
