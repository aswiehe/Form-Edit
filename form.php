<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions for application
include('functions.php');

// Get type of form either add or edit from the URL (ex. form.php?action=add) using the newly written get function
$action = get('action');

// Get the book isbn from the URL if it exists using the newly written get function
$isbn = get('isbn');

// Initially set $book to null;
$book = null;

// Initially set $book_categories to an empty array;
$book_categories = array();

// If book isbn is not empty,
if(!empty($isbn)) {
	//     Get book record into $books variable from the database
	$sql = file_get_contents('sql/getBook.sql');
  $params = array(
 	 'isbn' => $isbn
  );
  $statement = $database->prepare($sql);
  $statement->execute($params);
  $books = $statement->fetchAll(PDO::FETCH_ASSOC);
	//     Set $book equal to the first book in $books
	$book = $books[0];
	// 	   Set $book_categories equal to a list of category ids associated to a book from the database. Note you may need to
	//     get book categories from the database and create a secondary array.
	$sql = file_get_contents('sql/getBookCategories.sql');
	$params = array(
		'isbn' => $isbn
	);
  $statement = $database->prepare($sql);
  $statement->execute($params);
  $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

	foreach($categories as $selectedCategory) {
	  $book_categories[] = $selectedCategory['categoryid'];
	}

	// Set action = edit (an isbn has been passed in)
}
	// Else (no isbn passed in) set action = add
else {

}

// Get an associative array of categories
$sql = file_get_contents('sql/getCategories.sql');
$statement = $database->prepare($sql);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

// If form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$isbn = $_POST['isbn'];
	$title = $_POST['book-title'];
	$book_categories = $_POST['book-category'];
	$author = $_POST['book-author'];
	$price = $_POST['book-price'];

	if($action == 'add') {
		// Insert book
		$sql = file_get_contents('sql/insertBook.sql');
		$params = array(
			'isbn' => $isbn,
			'title' => $title,
			'author' => $author,
			'price' => $price
		);

		$statement = $database->prepare($sql);
		$statement->execute($params);

		// Set categories for book
		$sql = file_get_contents('sql/insertBookCategory.sql');
		$statement = $database->prepare($sql);

		foreach($book_categories as $category) {
			$params = array(
				'isbn' => $isbn,
				'categoryid' => $categoryid
			);
			$statement->execute($params);
		}
	}

	elseif ($action == 'edit') {
		// Update general information about book
		$sql = file_get_contents('sql/updateBook.sql');
		$statement = $database->prepare($sql);
		$params = array(
			'title' => $title,
			'author' => $author,
			'price' => $price,
			'isbn' => $isbn
		);
		$statement->execute($params);

		// Update categories by deleting and inserting
		// delete current categories
		$sql = file_get_contents('sql/deleteBookCategories.sql');
		$statement = $database->prepare($sql);
		$params = array(
			'isbn' => $isbn
		);
		$statement->execute($params);

		// insert new categories
		$sql = file_get_contents('sql/insertBookCategory.sql');
		$statement = $database->prepare($sql);
		foreach($book_categories as $category) {
			$params = array(
				'isbn' => $isbn,
				'categoryid' => $category
			);
			$statement->execute($params);
		}
	}

	// Redirect to book listing page
	header('location: index.php');
	die();
}

// In the HTML, if an edit form:
	// Populate textboxes with current data of selected book stored in $book variable
	// Print the checkbox with the book's current categories already checked (selected)
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

  	<title>Manage Book</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="page">
		<h1>Manage Book</h1>
		<form action="" method="POST">
			<div class="form-element">
				<label>ISBN:</label>
				<?php if($action == 'add') : ?>
					<input type="text" name="isbn" class="textbox" value="<?php echo $book['isbn'] ?>" />
				<?php else : ?>
					<input readonly type="text" name="isbn" class="textbox" value="<?php echo $book['isbn'] ?>" />
				<?php endif; ?>
			</div>
			<div class="form-element">
				<label>Title:</label>
				<input type="text" name="book-title" class="textbox" value="<?php echo $book['title'] ?>" />
			</div>
			<div class="form-element">
				<label>Category:</label>
				<?php foreach($categories as $category) : ?>
						<?php if(in_array($category['categoryid'], $book_categories)) : ?>
							<input class="radio" type="checkbox" name="book-category[]" value="<?php echo $category['categoryid'] ?>" checked /><span class="radio-label"><?php echo $category['name'] ?></span><br />
						<?php else : ?>
				    	<input class="radio" type="checkbox" name="book-category[]" value="<?php echo $category['categoryid'] ?>" /><span class="radio-label"><?php echo $category['name'] ?></span><br />
						<?php endif ?>
				<?php endforeach; ?>
			</div>
			<div class="form-element">
				<label>Author</label>
				<input type="text" name="book-author" class="textbox" value="<?php echo $book['author'] ?>" />
			</div>
			<div class="form-element">
				<label>Price:</label>
				<input type="number" step="any" name="book-price" class="textbox" value="<?php echo $book['price'] ?>" />
			</div>
			<div class="form-element">
				<input type="submit" class="button" />&nbsp;
				<input type="reset" class="button" />
			</div>
		</form>
	</div>
</body>
</html>
