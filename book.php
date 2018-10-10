<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions
include('functions.php');

// Get the book isbn from the url
$isbn = get('isbn');

// Get a list of books from the database with the isbn passed in the URL
$sql = file_get_contents('sql/getBook.sql');
$params = array(
 'isbn' => $isbn
);
$statement = $database->prepare($sql);
$statement->execute($params);
$books = $statement->fetchAll(PDO::FETCH_ASSOC);

// Set $book equal to the first book in $books
$book = $books[0];

// Get categories of book from the database
$sql = file_get_contents('sql/getBookCategories.sql');
$statement = $database->prepare($sql);
$params = array(
	'isbn' => $isbn
);
$statement->execute($params);
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

/* In the HTML:
	- Print the book title, author, price
	- List the categories associated with this book
*/
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

  	<title>Book</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1>General Information</h1>
		<strong>Title: </strong><?php echo $book['title'] ?>
		<br><br>
		<strong>Author: </strong><?php echo $book['author'] ?>
		<br><br>
		<strong>Price: </strong><?php echo $book['price'] ?>
	</div>
	<div class="page">
		<h1>Subject Categories</h1>
		<ul>
		<?php foreach($categories as $category) : ?>
			<li><?php echo $category['name'] ?>
		<?php endforeach ?>
	</div>
</body>
</html>
