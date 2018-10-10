<?php

include('config.php');
include('functions.php');

// Call the newly defined get function to find to get the search term
$term = get('search-term');

$books = searchBooks($term, $database);

// For each book add links to view the book on book.php an d a link to edit the book.
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

  	<title>Books</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1>Books</h1>
		<form method="GET">
			<input type="text" name="search-term" placeholder="Search..." />
			<input type="submit" />
		</form>
		<br>
        <?php foreach($books as $book) : ?>
			<p>
				<?php echo $book['title']; ?><br />
				<?php echo $book['author']; ?> <br />
				<?php echo $book['price']; ?> <br />
				<?php echo $book['isbn']; ?> <br />
				<br>
				<a href="book.php?isbn=<?php echo $book['isbn'] ?>">See book profile (book.php)</a><br />
				<a href="form.php?isbn=<?php echo $book['isbn'] ?>&action=edit">Go to form to edit book info (form.php)</a><br />
				<!-- For each book add links to view the book on book.php an d a link to edit the book.
			</p>
		<?php endforeach; ?>
	</div>
</body>
</html>
