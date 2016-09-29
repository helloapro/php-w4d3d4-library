<?php
// ./vendor/bin/phpunit tests
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    // mysql.server start -uroot -proot.

    require_once "src/Author.php";
    require_once "src/Book.php";
    require_once "src/Copy.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CopyTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown()
        {
            Author::deleteAll();
            Book::deleteAll();
            Copy::deleteAll();
        }

        function test_getId()
        {
            $book_id = 2;
            $test_copy = new Copy($book_id);
            $test_copy->save();

            $result = $test_copy->getId();

            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            $book_id = 2;
            $test_copy = new Copy($book_id);
            $test_copy->save();

            $result = Copy::getAll();

            $this->assertEquals($test_copy, $result[0]);
        }

        function test_getAll()
        {
            $book_id = 2;
            $test_copy = new Copy($book_id);
            $test_copy->save();
            $book_id2 = 4;
            $test_copy2 = new Copy($book_id2);
            $test_copy2->save();

            $result = Copy::getAll();

            $this->assertEquals([$test_copy, $test_copy2], $result);
        }

        function test_find()
        {
            $book_id = 2;
            $test_copy = new Copy($book_id);
            $test_copy->save();
            $book_id2 = 4;
            $test_copy2 = new Copy($book_id2);
            $test_copy2->save();

            $search_id = $test_copy->getId();
            $result = Copy::find($search_id);

            $this->assertEquals($test_copy, $result);
        }

        function test_delete()
        {
            $book_id = 2;
            $test_copy = new Copy($book_id);
            $test_copy->save();
            $book_id2 = 4;
            $test_copy2 = new Copy($book_id2);
            $test_copy2->save();

            $test_copy->delete();
            $result = Copy::getAll();

            $this->assertEquals([$test_copy2], $result);
        }

        function test_deleteAll()
        {
            $book_id = 2;
            $test_copy = new Copy($book_id);
            $test_copy->save();
            $book_id2 = 4;
            $test_copy2 = new Copy($book_id2);
            $test_copy2->save();

            Copy::deleteAll();

            $result = Copy::getAll();
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $book_id = 2;
            $test_copy = new Copy($book_id);
            $test_copy->save();
            $new_status = "reserved";
            $new_due_date = "Jan 12, 2020";

            $test_copy->update($new_status, $new_due_date);
            $result = $test_copy->getStatus();

            $this->assertEquals($new_status, $result);
        }
    }
?>
