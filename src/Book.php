<?php
    class Book
    {
        private $id;
        private $title;

        function __construct($title, $id=null){
            $this->id = $id;
            $this->title = $title;
        }

        function getId()
        {
            return $this->id;
        }

        function getTitle()
        {
            return $this->title;
        }

        function setTitle($new_title){
            $this->title = $new_title;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ('{$this->getTitle()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id={$this->getid()};");
        }

        static function find($search_id)
        {
            $returned_books = Book::getAll();
            $found_book = null;
            foreach($returned_books as $book){
                $book_id = $book->getId();
                if($book_id == $search_id){
                    $found_book = $book;
                }
            }
            return $found_book;
        }

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books");
            $books= array();
            foreach ($returned_books as $book) {
                $id = $book['id'];
                $title = $book['title'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books;");
        }

        function update($title)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title ='{$title}' WHERE id ='{$this->getId()}';");
            $this->setTitle($title);
        }

        function addCopies($number)
        {
            for ($i=0; $i < $number; $i++) {
                $new_copy = new Copy($this->getId());
                $new_copy->save();
            }
        }

        function countCopies()
        {
            $allCopies = Copy::getAll();
            $count = 0;
            foreach ($allCopies as $copy) {
                if ($copy->getBookId() == $this->getId() && $copy->getStatus() == "available") {
                    $count++;
                }
            }
            return $count;
        }

        function getCopies()
        {
            $allCopies = Copy::getAll();
            $bookCopies = array();
            foreach ($allCopies as $copy) {
                if ($copy->getBookId() == $this->getId() && $copy->getStatus() == "available") {
                    array_push($bookCopies, $copy);
                }
            }
            return $bookCopies;
        }

        function addAuthor($author_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (book_id, author_id) VALUES ({$this->getId()}, {$author_id});");
        }

        function getAuthors()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT authors.* FROM books
                JOIN authors_books ON(authors_books.book_id = books.id)
                JOIN authors ON(authors.id = authors_books.author_id)
                WHERE books.id = {$this->getId()};");
            $authors = array();
            foreach ($returned_authors as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $found_author = new Author($name, $id);
                array_push($authors, $found_author);
            }
            return $authors;
        }

        function removeAuthor($author_id)
        {
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE author_id = {$author_id} AND book_id = {$this->getId()};");
        }

        static function searchBooks($search_input)
        {
            $allBooksArray = Book::getAll();
            $searchResults = array();
            foreach ($allBooksArray as $book)
            {
                if (stripos($book->getTitle(), $search_input) !== false) {
                    $authors = $book->getAuthors();
                    array_push($searchResults, [$book, $authors]);
                }
            }

            $allAuthors = Author::getAll();
            foreach ($allAuthors as $author)
            {
                if (stripos($author->getName(), $search_input) !== false) {
                    $books = $author->getBooks();
                    foreach ($books as $book) {
                        $authors = $book->getAuthors();
                        array_push($searchResults, [$book, $authors]);
                    }
                }
            }
            return $searchResults;
        }
    }
?>
