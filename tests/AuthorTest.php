<?php
// ./vendor/bin/phpunit tests
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    // mysql.server start -uroot -proot.

    require_once "src/Author.php";
    // require_once "src/Book.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown()
        {
            Book::deleteAll();
            Author::deleteAll();
        }

        function test_getId()
        {
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();

            $result = $test_author->getId();

            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();

            $result = Author::getAll();

            $this->assertEquals($test_author, $result[0]);
        }

        function test_getAll()
        {
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();
            $name2 = "Shel Silverstein";
            $test_author2 = new Author($name2);
            $test_author2->save();

            $result = Author::getAll();

            $this->assertEquals([$test_author, $test_author2], $result);
        }

        function test_find()
        {
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();
            $name2 = "Shel Silverstein";
            $test_author2 = new Author($name2);
            $test_author2->save();

            $search_id = $test_author->getId();
            $result = Author::find($search_id);

            $this->assertEquals($test_author, $result);
        }

        function test_delete()
        {
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();
            $name2 = "Shel Silverstein";
            $test_author2 = new Author($name2);
            $test_author2->save();

            $test_author->delete();
            $result = Author::getAll();

            $this->assertEquals([$test_author2], $result);
        }

        function test_deleteAll()
        {
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();
            $name2 = "Shel Silverstein";
            $test_author2 = new Author($name2);
            $test_author2->save();

            Author::deleteAll();

            $result = Author::getAll();
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();
            $new_name = "Jo Kat Rowling";

            $test_author->update($new_name);
            $found_author = Author::find($test_author->getId());
            $result = $found_author->getName();

            $this->assertEquals($new_name, $result);
        }

        function test_addBook()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();

            $test_author->addBook($test_book->getId());

            $this->assertEquals([$test_book], $test_author->getBooks());
        }

        function test_getBooks()
        {
            $title = "Prisoner of Azkaban";
            $test_book = new Book($title);
            $test_book->save();
            $title2 = "Sorcerer's Stone";
            $test_book2 = new Book($title);
            $test_book2->save();
            $name = "JK Rowling";
            $test_author = new Author($name);
            $test_author->save();

            $test_author->addBook($test_book->getId());
            $test_author->addBook($test_book2->getId());

            $this->assertEquals([$test_book, $test_book2], $test_author->getBooks());
        }
    }
?>
