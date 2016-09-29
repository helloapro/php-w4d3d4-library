<?php
    class Patron
    {
        private $id;
        private $username;

        function __construct($username, $id = null)
        {
            $this->username = $username;
            $this->id = $id;
        }

        function getId()
        {
            return $this->id;
        }

        function getUsername()
        {
            return $this->username;
        }

        function setUsername($new_username)
        {
            $this->username = $new_username;
        }

        function save()
        {
            $patrons = Patron::getAll();
            $usernames = array();
            foreach ($patrons as $patron) {
                array_push($usernames, $patron->getUsername());
            }
            if(in_array($this->getUsername(), $usernames) == false) {
                $GLOBALS['DB']->exec("INSERT INTO patrons (username) VALUES ('{$this->getUsername()}');");
                $this->id = $GLOBALS['DB']->lastInsertId();
                return true;
            } else {
                return false;
            }
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id={$this->getid()};");
        }

        static function findById($search_id)
        {
            $returned_patrons = Patron::getAll();
            $found_patron = null;
            foreach($returned_patrons as $patron){
                $patron_id = $patron->getId();
                if($patron_id == $search_id){
                    $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        static function findByUsername($username)
        {
            $returned_patrons = Patron::getAll();
            $found_patron = null;
            foreach($returned_patrons as $patron){
                $patron_name = $patron->getUsername();
                if($patron_name == $username){
                    $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons");
            $patrons= array();
            foreach ($returned_patrons as $patron) {
                $id = $patron['id'];
                $username = $patron['username'];
                $new_patron = new Patron($username, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons;");
        }

        function update($username)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET username ='{$username}' WHERE id ='{$this->getId()}';");
            $this->setUsername($username);
        }

        function addCopy($copy_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (patron_id, copy_id) VALUES ({$this->getId()}, {$copy_id});");
        }

        function getCopies()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT copies.* FROM patrons
                JOIN checkouts ON(checkouts.patron_id = patrons.id)
                JOIN copies ON(copies.id = checkouts.copy_id)
                WHERE patrons.id = {$this->getId()};");
            $copies = array();
            foreach ($returned_copies as $copy) {
                $status = $copy['status'];
                $book_id = $copy['book_id'];
                $id = $copy['id'];
                $found_copy = new Copy($status, $book_id, $id);
                array_push($copies, $found_copy);
            }
            return $copies;
        }

        // function returnCopy($copy_id)
        // {
        //     // $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE author_id = {$author_id} AND patron_id = {$this->getId()};");
        // }

        // static function searchPatrons($search_input)
        // {
        //     $allPatronsArray = Patron::getAll();
        //     $searchResults = array();
        //     foreach ($allPatronsArray as $patron)
        //     {
        //         if (stripos($patron->getTitle(), $search_input) !== false) {
        //             $authors = $patron->getAuthors();
        //             array_push($searchResults, [$patron, $authors]);
        //         }
        //     }
        //
        //     $allAuthors = Author::getAll();
        //     foreach ($allAuthors as $author)
        //     {
        //         if (stripos($author->getName(), $search_input) !== false) {
        //             $patrons = $author->getPatrons();
        //             foreach ($patrons as $patron) {
        //                 $authors = $patron->getAuthors();
        //                 array_push($searchResults, [$patron, $authors]);
        //             }
        //         }
        //     }
        //     return $searchResults;
        // }
    }
?>
