# Library CRUD System

A simple library management system for maintaining a catalogue of books. It supports creating, viewing, updating, and deleting books, with details such as title, author, category, ISBN, publication year, and stock quantity.

## Versions

### V1.0

- The initial version of the library system.
- Uses PHP forms and server-side page reloads to add, edit, and delete books.
- Displays the book catalogue and supports searching by title, author, or ISBN.

### V2.0

- Adds AJAX requests so book actions and searches can update the catalogue without refreshing the page.
- Uses a PHP JSON API for listing, searching, creating, updating, and deleting books.
- Keeps the same core catalogue fields and CRUD functionality as V1.0.

## Technology

- PHP
- JavaScript (AJAX / Fetch API in V2.0)
- MySQL
- HTML and CSS

Each version is in its own folder (`V1.0/` and `V2.0/`). Configure the database connection for the version you want to run in that version's `db/conn.php`.
