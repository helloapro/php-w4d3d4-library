<?php
// ./vendor/bin/phpunit tests
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    // mysql.server start -uroot -proot.

    require_once "src/Author.php";
    require_once "src/Book.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown()
        {
            Author::deleteAll();
            Book::deleteAll();
        }

        function test_getId()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();

            $result = $test_book->getId();

            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();

            $result = Book::getAll();

            $this->assertEquals($test_book, $result[0]);
        }

        function test_getAll()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $title2 = "Sorcerer's Stone";
            $test_book2 = new Book($title);
            $test_book2->save();

            $result = Book::getAll();

            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function test_find()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $title2 = "Sorcerer's Stone";
            $test_book2 = new Book($title);
            $test_book2->save();

            $search_id = $test_book->getId();
            $result = Book::find($search_id);

            $this->assertEquals($test_book, $result);
        }

        function test_delete()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $title2 = "Sorcerer's Stone";
            $test_book2 = new Book($title);
            $test_book2->save();

            $test_book->delete();
            $result = Book::getAll();

            $this->assertEquals([$test_book2], $result);
        }

        function test_deleteAll()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $title2 = "Sorcerer's Stone";
            $test_book2 = new Book($title);
            $test_book2->save();

            Book::deleteAll();

            $result = Book::getAll();
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $new_title = "Order of the Phoenix";

            $test_book->update($new_title);
            $found_book = Book::find($test_book->getId());
            $result = $found_book->getTitle();

            $this->assertEquals($new_title, $result);
        }

        function test_addAuthor()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();

            $test_book->addAuthor($test_author->getId());

            $this->assertEquals([$test_author], $test_book->getAuthors());
        }

        function test_getAuthors()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();
            $name2 = "Shel Silverstein";
            $test_author2 = new Author($name2);
            $test_author2->save();

            $test_book->addAuthor($test_author->getId());
            $test_book->addAuthor($test_author2->getId());

            $this->assertEquals([$test_author, $test_author2], $test_book->getAuthors());
        }

        function test_searchBooks()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $title2 = "Chamber of Secrets";
            $test_book2 = new Book($title2);
            $test_book2->save();
            $title3 = "Prisma Colored Stone";
            $test_book3 = new Book($title3);
            $test_book3->save();
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();
            $test_book->addAuthor($test_author->getId());
            $test_book2->addAuthor($test_author->getId());

            $search_input = "of";
            $result = Book::searchBooks($search_input);

            $this->assertEquals([[$test_book, [$test_author]],[$test_book2, [$test_author]]], $result);
        }

        function test_searchAuthors()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $title2 = "Chamber of Secrets";
            $test_book2 = new Book($title2);
            $test_book2->save();
            $title3 = "Prisma Colored Stone";
            $test_book3 = new Book($title3);
            $test_book3->save();
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();
            $name2 = "Shel Silverstein";
            $test_author2 = new Author($name2);
            $test_author2->save();

            $test_book->addAuthor($test_author->getId());
            $test_book2->addAuthor($test_author->getId());
            $test_book3->addAuthor($test_author2->getId());

            $search_input = "rowl";
            $result = Book::searchBooks($search_input);

            $this->assertEquals([[$test_book, [$test_author]],[$test_book2, [$test_author]]], $result);
        }
    }
?>
