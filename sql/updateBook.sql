UPDATE books
SET title = :title, author = :author, price = :price
WHERE isbn = :isbn;
