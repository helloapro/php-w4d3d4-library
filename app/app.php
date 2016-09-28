<?php
    date_default_timezone_set('America/Los_Angeles');

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app = new Silex\Application();

    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/authors", function() use ($app) {
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => null, 'authorBooks' => null, 'allBooks' => Book::getAll()));
    });

    $app->post("/authors", function() use ($app) {
        $new_author = new Author($_POST['name']);
        $new_author->save();
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => $new_author, 'authorBooks' => null, 'allBooks' => Book::getAll()));
    });

    $app->get("/authors/{id}", function($id) use ($app) {
        $found_author = Author::find($id);
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => $found_author, 'authorBooks' => $found_author->getBooks(), 'allBooks' => Book::getAll()));
    });

    $app->patch("/authors/{id}", function($id) use ($app) {
        $found_author = Author::find($id);
        $found_author->update($_POST['name']);
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => $found_author, 'authorBooks' => $found_author->getBooks(), 'allBooks' => Book::getAll()));
    });

    $app->delete("/authors/{id}", function($id) use ($app) {
        $found_author = Author::find($id);
        $found_author->delete();
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => null, 'authorBooks' => $found_author->getBooks(), 'allBooks' => Book::getAll()));
    });

    $app->post("/authors/add/{id}", function($id) use ($app) {
        $found_author = Author::find($id);
        $found_author->addBook($_POST['book_id']);
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll(), 'author' => $found_author, 'authorBooks' => $found_author->getBooks(), 'allBooks' => Book::getAll()));
    });

    $app->get("/books", function() use ($app) {
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => null));
    });

    $app->post("/books", function() use ($app) {
        $new_book = new Book($_POST['title']);
        $new_book->save();
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => $new_book));
    });

    $app->get("/books/{id}", function($id) use ($app) {
        $found_book = Book::find($id);
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => $found_book));
    });

    $app->patch("/books/{id}", function($id) use ($app) {
        $found_book = Book::find($id);
        $found_book->update($_POST['title']);
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll(), 'book' => $found_book));
    });

    $app->

    return $app;
?>
