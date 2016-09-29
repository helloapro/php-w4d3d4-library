<?php
    class Copy
    {
        private $status; // available or patron_id
        private $book_id;
        private $id;

        function __construct($book_id, $status = "available", $id = null)
        {
            $this->book_id = $book_id;
            $this->status = $status;
            $this->id = $id;
        }

        function getStatus()
        {
            return $this->status;
        }

        function setStatus($new_status)
        {
            $this->status = $new_status;
        }

        function getBookId()
        {
            return $this->book_id;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO copies (status, book_id) VALUES ('{$this->getStatus()}', {$this->getBookId()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id={$this->getid()};");
        }

        static function find($search_id)
        {
            $returned_copies = Copy::getAll();
            foreach($returned_copies as $copy){
                $copy_id = $copy->getId();
                if($copy_id == $search_id){
                    $found_copy = $copy;
                }
            }
            return $found_copy;
        }

        static function getAll()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies");
            $copies = array();
            foreach ($returned_copies as $copy) {
                $id = $copy['id'];
                $status = $copy['status'];
                $book_id = $copy['book_id'];
                $new_copy = new Copy($book_id, $status, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies;");
        }

        function update($status)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET status ='{$status}' WHERE id ='{$this->getId()}';");
            $this->setStatus($status);
        }
    }
?>
