<?php

function searchBooks($term, $database) {
	// Get list of books
	$term = $term . '%';
	$sql = file_get_contents('sql/getBooks.sql');
	$params = array(
		'term' => $term
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$books = $statement->fetchAll(PDO::FETCH_ASSOC);
	return $books;
}

/*
- Create a function named get() that:
	- takes a parameter holding a $_GET key as a string
	- if the GET variable isset, return the GET variable
	- else return an empty string
*/

function get($key) {
	if(isset($_GET[$key])) {
		return $_GET[$key];
	}
	else {
		return "";
	}
}
