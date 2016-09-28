<?php
    class Author
    {
        private $id;
        private $name;

        function __construct($name, $id=null)
        {
            $this->id = $id;
            $this->name = $name;
        }

        function getId()
        {
            return $this->id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name){
            $this->name = $new_name;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO authors (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id={$this->getid()};");
        }

        static function find($search_id)
        {
            $returned_authors = Author::getAll();
            foreach($returned_authors as $author){
                $author_id = $author->getId();
                if($author_id == $search_id){
                    $found_author = $author;
                }
            }
            return $found_author;
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors");
            $authors = array();
            foreach ($returned_authors as $author) {
                $id = $author['id'];
                $name = $author['name'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors;");
        }

        function update($name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET name ='{$name}' WHERE id ='{$this->getId()}';");
            $this->setName($name);
        }

        function addBook($book_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (book_id, author_id) VALUES ({$book_id}, {$this->getId()});");
        }

        function getBooks()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT books.* FROM authors
                JOIN authors_books ON(authors_books.author_id = authors.id)
                JOIN books ON(books.id = authors_books.book_id)
                WHERE authors.id = {$this->getId()};");
            $books = array();
            foreach ($returned_books as $book) {
                $id = $book['id'];
                $title = $book['title'];
                $found_book = new Book($title, $id);
                array_push($books, $found_book);
            }
            return $books;
        }

        function removeBook($book_id)
        {
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE book_id = {$book_id} AND author_id = {$this->getId()};");
        }
    }
?>
