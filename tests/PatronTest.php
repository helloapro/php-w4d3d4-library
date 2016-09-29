<?php
// ./vendor/bin/phpunit tests
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    // mysql.server start -uroot -proot.

    require_once "src/Author.php";
    require_once "src/Book.php";
    require_once "src/Patron.php";
    require_once "src/Copy.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown()
        {
            Author::deleteAll();
            Book::deleteAll();
            Patron::deleteAll();
            Copy::deleteAll();
        }

        function test_getId()
        {
            $username = "mlawson3691";
            $test_patron = new Patron($username);
            $test_patron->save();

            $result = $test_patron->getId();

            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            $username = "mlawson3691";
            $test_patron = new Patron($username);
            $test_patron->save();

            $result = Patron::getAll();

            $this->assertEquals($test_patron, $result[0]);
        }

        function test_saveNoDuplicates()
        {
            $username = "mlawson3691";
            $test_patron = new Patron($username);
            $test_patron->save();
            $username2 = "mlawson3691";
            $test_patron2 = new Patron($username2);
            $test_patron2->save();
            $username3 = "helloapro";
            $test_patron3 = new Patron($username3);
            $test_patron3->save();

            $result = Patron::getAll();

            $this->assertEquals([$test_patron, $test_patron3], $result);
        }

        function test_getAll()
        {
            $username = "mlawson3691";
            $test_patron = new Patron($username);
            $test_patron->save();
            $username2 = "helloapro";
            $test_patron2 = new Patron($username2);
            $test_patron2->save();

            $result = Patron::getAll();

            $this->assertEquals([$test_patron, $test_patron2], $result);
        }

        function test_findById()
        {
            $username = "mlawson3691";
            $test_patron = new Patron($username);
            $test_patron->save();
            $username2 = "helloapro";
            $test_patron2 = new Patron($username2);
            $test_patron2->save();

            $search_id = $test_patron->getId();
            $result = Patron::findById($search_id);

            $this->assertEquals($test_patron, $result);
        }

        function test_findByUsername()
        {
            $username = "mlawson3691";
            $test_patron = new Patron($username);
            $test_patron->save();
            $username2 = "helloapro";
            $test_patron2 = new Patron($username2);
            $test_patron2->save();

            $search_name = $test_patron->getUsername();
            $result = Patron::findByUsername($search_name);

            $this->assertEquals($test_patron, $result);
        }

        function test_delete()
        {
            $username = "mlawson3691";
            $test_patron = new Patron($username);
            $test_patron->save();
            $username2 = "helloapro";
            $test_patron2 = new Patron($username2);
            $test_patron2->save();

            $test_patron->delete();
            $result = Patron::getAll();

            $this->assertEquals([$test_patron2], $result);
        }

        function test_deleteAll()
        {
            $username = "mlawson3691";
            $test_patron = new Patron($username);
            $test_patron->save();
            $username2 = "helloapro";
            $test_patron2 = new Patron($username2);
            $test_patron2->save();

            Patron::deleteAll();
            $result = Patron::getAll();

            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $username = "mlawson3691";
            $test_patron = new Patron($username);
            $test_patron->save();
            $new_username = "100pts4gryffindor";

            $test_patron->update($new_username);
            $result = $test_patron->getUsername();

            $this->assertEquals($new_username, $result);
        }

        // function test_addCopy()
        // {
        //     $username = "mlawson3691";
        //     $test_patron = new Patron($username);
        //     $test_patron->save();
        //     $status = ;
        //     $due_date = ;
        //     $book_id = "2";
        //     $test_copy = new Author($name);
        //     $test_author->save();
        //
        //     $test_book->addAuthor($test_author->getId());
        //
        //     $this->assertEquals([$test_author], $test_book->getAuthors());
        // }

        // function test_getAuthors()
        // {
        //     $username = "Prisoner of Azkaban";
        //     $test_book = new Book($username);
        //     $test_book->save();
        //     $name = "JK Rowling";
        //     $test_author = new Author($name);
        //     $test_author->save();
        //     $name2 = "Shel Silverstein";
        //     $test_author2 = new Author($name2);
        //     $test_author2->save();
        //
        //     $test_book->addAuthor($test_author->getId());
        //     $test_book->addAuthor($test_author2->getId());
        //
        //     $this->assertEquals([$test_author, $test_author2], $test_book->getAuthors());
        // }
        //
        // function test_searchBooks()
        // {
        //     $username = "Prisoner of Azkaban";
        //     $test_book = new Book($username);
        //     $test_book->save();
        //     $username2 = "Chamber of Secrets";
        //     $test_book2 = new Book($username2);
        //     $test_book2->save();
        //     $username3 = "Prisma Colored Stone";
        //     $test_book3 = new Book($username3);
        //     $test_book3->save();
        //     $name = "JK Rowling";
        //     $test_author = new Author($name);
        //     $test_author->save();
        //     $test_book->addAuthor($test_author->getId());
        //     $test_book2->addAuthor($test_author->getId());
        //
        //     $search_input = "of";
        //     $result = Book::searchBooks($search_input);
        //
        //     $this->assertEquals([[$test_book, [$test_author]],[$test_book2, [$test_author]]], $result);
        // }
        //
        // function test_searchAuthors()
        // {
        //     $username = "Prisoner of Azkaban";
        //     $test_book = new Book($username);
        //     $test_book->save();
        //     $username2 = "Chamber of Secrets";
        //     $test_book2 = new Book($username2);
        //     $test_book2->save();
        //     $username3 = "Prisma Colored Stone";
        //     $test_book3 = new Book($username3);
        //     $test_book3->save();
        //     $name = "JK Rowling";
        //     $test_author = new Author($name);
        //     $test_author->save();
        //     $name2 = "Shel Silverstein";
        //     $test_author2 = new Author($name2);
        //     $test_author2->save();
        //
        //     $test_book->addAuthor($test_author->getId());
        //     $test_book2->addAuthor($test_author->getId());
        //     $test_book3->addAuthor($test_author2->getId());
        //
        //     $search_input = "rowl";
        //     $result = Book::searchBooks($search_input);
        //
        //     $this->assertEquals([[$test_book, [$test_author]],[$test_book2, [$test_author]]], $result);
        // }
    }
?>
