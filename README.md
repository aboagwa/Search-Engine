# Search-Engine

RSS Feed Search Application
Overview
This PHP web application fetches articles from multiple RSS feeds, stores them in a MySQL database, and allows users to search for articles by keywords in the title or description. It is designed for users who want to aggregate and search news or content from sources like NASA, The New York Times, TechCrunch, and CNN.
Features

Aggregates articles from predefined RSS feeds.
Stores article data (title, link, description) in a MySQL database.
Provides a simple search interface to find articles by keyword.
Displays search results with clickable links to original articles.

Prerequisites

PHP: Version 7.4+ with curl and mysqli extensions enabled.
MySQL: Version 5.6+ (InnoDB or MyISAM recommended).
Web Server: Apache or Nginx configured for PHP.
Internet access to fetch RSS feeds.

Installation

Copy Files:

Place header.php, db-connection.php, and new-file.php in your web server’s document root (e.g., /var/www/html).


Set Up MySQL Database:

Create a database named hamoksha:CREATE DATABASE hamoksha;


Create the rss table:USE do
USE hamoksha;
CREATE TABLE rss (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL
);




Configure Database Connection:

Open db-connection.php and update the MySQL credentials:$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "route"; // Your MySQL password
$dbname = "hamoksha";
$port = "3307"; // Your MySQL port (default: 3306)


For security, consider using a non-root user and storing credentials in a .env file.


Fix Form Action:

In header.php, ensure the form submits to new-file.php:<form method="POST" action="new-file.php">
    <input type="text" name="query" placeholder="Search" required>
    <button type="submit">Search</button>
</form>




Set Permissions:

Ensure the web server can read the files:chmod -R 755 /path/to/project
chown -R www-data:www-data /path/to/project




Access the Application:

Navigate to http://your-server/new-file.php in a browser.
The page will fetch RSS feeds and display a search form.



Usage

Fetching RSS Feeds:
On page load, the application fetches articles from:
NASA APOD: https://apod.nasa.gov/apod.rss
New York Times: https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml
TechCrunch: https://techcrunch.com/feed/
CNN Top Stories: http://rss.cnn.com/rss/cnn_topstories.rss


Articles are stored in the rss table, with unique links to prevent duplicates.


Searching:
Enter a keyword (e.g., "space" or "technology") in the search form.
Results show matching articles with titles (linked to the source) and descriptions.



File Structure

header.php: HTML form for searching.
db-connection.php: MySQL connection setup.
new-file.php: Core logic for fetching RSS feeds, storing data, and handling searches.

Database Schema
CREATE TABLE rss (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL
);

How It Works

RSS Fetching:
Uses cURL to retrieve RSS feeds.
Parses XML with simplexml_load_string.
Inserts articles into the rss table, escaping inputs for security.


Search:
Uses LIKE queries to match keywords in title or description.
Results are ordered by ID (newest first).
Outputs HTML-escaped data to prevent XSS.



Improvements

Performance:
Add a FULLTEXT index for faster searches:ALTER TABLE rss ADD FULLTEXT(title, description);


Cache RSS feeds to reduce server load.


Search:
Use prepared statements for better security:$stmt = $conn->prepare("SELECT * FROM rss WHERE title LIKE ? OR description LIKE ?");
$like_search = "%$search%";
$stmt->bind_param("ss", $like_search, $like_search);


Support multi-word queries by tokenizing input.


UI:
Add CSS (e.g., Bootstrap) for a better look.
Include pagination for large result sets.



Troubleshooting

No Results:
Ensure the rss table has data (check after loading new-file.php).
Verify search keywords match article content.


Database Errors:
Check db-connection.php credentials and MySQL port.
Confirm the hamoksha database exists.


RSS Fetching Issues:
Verify curl is enabled (php -m | grep curl).
Check internet connectivity and RSS URL validity.



Limitations

Slow searches for large datasets due to LIKE queries.
No advanced search features (e.g., filters, autocomplete).
Hardcoded credentials in db-connection.php (secure in production).

Future Enhancements

Schedule RSS fetching with a cron job:*/30 * * * * php /path/to/new-file.php


Add user authentication for restricted access.
Integrate Elasticsearch for advanced search capabilities.

License
This project is provided as-is for educational use. Feel free to modify and distribute.
