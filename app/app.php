<?php
    date_default_timezone_set('America/Los_Angeles');

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    session_start();
    if (empty($_SESSION['current_user'])) {
        $_SESSION['current_user'] = null;
    }


    $app = new Silex\Application();

    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('valid' => false, 'user' => $_SESSION['current_user']));
    });

    $app->get("/authors", function() use ($app) {
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => null, 'authorBooks' => null, 'allBooks' => Book::getAll(), 'user' => $_SESSION['current_user']));
    });

    $app->post("/authors", function() use ($app) {
        $new_author = new Author($_POST['name']);
        $new_author->save();
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => $new_author, 'authorBooks' => null, 'allBooks' => Book::getAll(), 'user' => $_SESSION['current_user']));
    });

    $app->get("/authors/{id}", function($id) use ($app) {
        $found_author = Author::find($id);
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => $found_author, 'authorBooks' => $found_author->getBooks(), 'allBooks' => Book::getAll(), 'user' => $_SESSION['current_user']));
    });

    $app->patch("/authors/{id}", function($id) use ($app) {
        $found_author = Author::find($id);
        $found_author->update($_POST['name']);
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => $found_author, 'authorBooks' => $found_author->getBooks(), 'allBooks' => Book::getAll(), 'user' => $_SESSION['current_user']));
    });

    $app->delete("/authors/{id}", function($id) use ($app) {
        $found_author = Author::find($id);
        $found_author->delete();
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => null, 'authorBooks' => $found_author->getBooks(), 'allBooks' => Book::getAll(), 'user' => $_SESSION['current_user']));
    });

    $app->post("/authors/add/{id}", function($id) use ($app) {
        $found_author = Author::find($id);
        $found_author->addBook($_POST['book_id']);
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => $found_author, 'authorBooks' => $found_author->getBooks(), 'allBooks' => Book::getAll(), 'user' => $_SESSION['current_user']));
    });

    $app->delete("/authors/delete/{id}", function($id) use ($app) {
        $found_author = Author::find($id);
        $found_author->removeBook($_POST['book_id']);
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => $found_author, 'authorBooks' => $found_author->getBooks(), 'allBooks' => Book::getAll(), 'user' => $_SESSION['current_user']));
    });

    $app->get("/books", function() use ($app) {
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => null, 'bookAuthors' => null, 'allAuthors' => Author::getAll(), 'copies' => null, 'user' => $_SESSION['current_user']));
    });

    $app->post("/books", function() use ($app) {
        $new_book = new Book($_POST['title']);
        $new_book->save();
        $new_book->addCopies($_POST['copies']);
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => $new_book, 'bookAuthors' => null, 'allAuthors' => Author::getAll(), 'copies' => $new_book->countCopies(), 'user' => $_SESSION['current_user']));
    });

    $app->get("/books/{id}", function($id) use ($app) {
        $found_book = Book::find($id);
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => $found_book, 'bookAuthors' => $found_book->getAuthors(), 'allAuthors' => Author::getAll(), 'copies' => $found_book->countCopies(), 'user' => $_SESSION['current_user']));
    });

    $app->post("/books/{id}", function($id) use ($app) {
        $found_book = Book::find($id);
        $found_book->addCopies($_POST['copies']);
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => $found_book, 'bookAuthors' => $found_book->getAuthors(), 'allAuthors' => Author::getAll(), 'copies' => $found_book->countCopies(), 'user' => $_SESSION['current_user']));
    });

    $app->patch("/books/{id}", function($id) use ($app) {
        $found_book = Book::find($id);
        $found_book->update($_POST['title']);
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => $found_book, 'bookAuthors' => $found_book->getAuthors(), 'allAuthors' => Author::getAll(), 'copies' => $found_book->countCopies(), 'user' => $_SESSION['current_user']));
    });

    $app->delete("/books/{id}", function($id) use ($app) {
        $found_book = Book::find($id);
        $found_book->delete();
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => null, 'bookAuthors' => null, 'allAuthors' => Author::getAll(), 'copies' => null, 'user' => $_SESSION['current_user']));
    });

    $app->post("/books/add/{id}", function($id) use ($app) {
        $found_book = Book::find($id);
        $found_book->addAuthor($_POST['author_id']);
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => $found_book, 'bookAuthors' => $found_book->getAuthors(), 'allAuthors' => Author::getAll(), 'copies' => $found_book->countCopies(), 'user' => $_SESSION['current_user']));
    });

    $app->post('/search_books', function() use ($app) {
        $search_input = $_POST['search_input'];
        $search_results = Book::searchBooks($search_input);
        return $app['twig']->render('search-results.html.twig', array('books' => $search_results, 'user' => $_SESSION['current_user']));
    });

    $app->get('/patron', function() use ($app) {
        return $app['twig']->render('patron.html.twig', array('user' => $_SESSION['current_user']));
    });

    $app->post('/patron', function() use ($app) {
        $new_patron = new Patron($_POST['username']);
        $valid = $new_patron->save();
        if ($valid) {
            $_SESSION['current_user'] = $new_patron;
            return $app['twig']->render('patron.html.twig', array('user' => $_SESSION['current_user']));
        } else {
            return $app['twig']->render('index.html.twig', array('valid' => 'taken username'));
        }
    });

    $app->post('/patron/login', function() use ($app) {
        $found_patron = Patron::findByUsername($_POST['username']);
        if ($found_patron !== null) {
            $_SESSION['current_user'] = $found_patron;
            return $app['twig']->render('patron.html.twig', array('user' => $_SESSION['current_user']));
        } else {
            return $app['twig']->render('index.html.twig', array('valid' => 'wrong username'));
        }
    });

    $app->get("/log_out", function() use ($app) {
        $_SESSION['current_user'] = null;
        return $app['twig']->render('index.html.twig', array('valid' => false, 'user' => $_SESSION['current_user']));
    });


    // $app->delete("/authors/delete/{id}", function($id) use ($app) {
    //     $found_book = Book::find($id);
    //     $found_book->removeAuthor($_POST['author_id']);
    //     return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => $found_book, 'bookAuthors' => $found_book->getAuthors(), 'allAuthors' => Author::getAll()));
    // });

    return $app;
?>
