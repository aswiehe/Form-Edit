SELECT book_categories.isbn, categories.categoryid, categories.name
FROM book_categories
JOIN categories on book_categories.categoryid = categories.categoryid;