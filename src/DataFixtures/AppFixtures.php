<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Author;
use App\Entity\Editor;
use App\Entity\Book;
use App\Entity\Comment;
use App\Enum\BookStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create Users
        $admin = new User();
        $admin->setEmail('admin@library.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('User');
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@library.com');
        $user->setFirstname('Regular');
        $user->setLastname('User');
        $user->setUsername('user');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $manager->persist($user);

        // Create Publishers/Editors
        $publishers = [
            ['name' => 'Penguin Random House'],
            ['name' => 'HarperCollins'],
            ['name' => 'Simon & Schuster'],
            ['name' => 'Hachette Book Group'],
            ['name' => 'Macmillan Publishers'],
        ];

        $editorObjects = [];
        foreach ($publishers as $pub) {
            $editor = new Editor();
            $editor->setName($pub['name']);
            $manager->persist($editor);
            $editorObjects[] = $editor;
        }

        // Create Authors
        $authors = [
            ['name' => 'J.K. Rowling', 'birth' => '1965-07-31', 'nationality' => 'British'],
            ['name' => 'Stephen King', 'birth' => '1947-09-21', 'nationality' => 'American'],
            ['name' => 'Agatha Christie', 'birth' => '1890-09-15', 'death' => '1976-01-12', 'nationality' => 'British'],
            ['name' => 'George Orwell', 'birth' => '1903-06-25', 'death' => '1950-01-21', 'nationality' => 'British'],
            ['name' => 'Jane Austen', 'birth' => '1775-12-16', 'death' => '1817-07-18', 'nationality' => 'British'],
            ['name' => 'Mark Twain', 'birth' => '1835-11-30', 'death' => '1910-04-21', 'nationality' => 'American'],
        ];

        $authorObjects = [];
        foreach ($authors as $auth) {
            $author = new Author();
            $author->setName($auth['name']);
            $author->setDateOfBirth(new \DateTime($auth['birth']));
            if (isset($auth['death'])) {
                $author->setDateOfDeath(new \DateTime($auth['death']));
            }
            $author->setNationality($auth['nationality']);
            $manager->persist($author);
            $authorObjects[] = $author;
        }

        // Create Books
        $books = [
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'isbn' => '9780747532699',
                'pages' => 223,
                'cover' => 'https://sixtrid.fr/wp-content/uploads/2019/11/B33M.jpg',
                'plot' => 'Harry Potter, a young wizard who discovers his magical heritage on his eleventh birthday.',
                'author_index' => 0,
                'editor_index' => 0,
                'status' => BookStatus::AVAILABLE
            ],
            [
                'title' => 'The Shining',
                'isbn' => '9780307743657',
                'pages' => 447,
                'cover' => 'https://images-na.ssl-images-amazon.com/images/I/91U8HRPV2PL.jpg',
                'plot' => 'A family heads to an isolated hotel for the winter where a sinister presence influences the father.',
                'author_index' => 1,
                'editor_index' => 1,
                'status' => BookStatus::BORROWED
            ],
            [
                'title' => 'Murder on the Orient Express',
                'isbn' => '9780062693662',
                'pages' => 256,
                'cover' => 'https://images-na.ssl-images-amazon.com/images/I/81QcWIAyeYL.jpg',
                'plot' => 'Detective Hercule Poirot investigates a murder aboard the famous Orient Express.',
                'author_index' => 2,
                'editor_index' => 2,
                'status' => BookStatus::AVAILABLE
            ],
            [
                'title' => '1984',
                'isbn' => '9780451524935',
                'pages' => 328,
                'cover' => 'https://images-na.ssl-images-amazon.com/images/I/71kxa1-0mfL.jpg',
                'plot' => 'A dystopian social science fiction novel about totalitarian control.',
                'author_index' => 3,
                'editor_index' => 3,
                'status' => BookStatus::AVAILABLE
            ],
            [
                'title' => 'Pride and Prejudice',
                'isbn' => '9780141439518',
                'pages' => 432,
                'cover' => 'https://images-na.ssl-images-amazon.com/images/I/81NLDvyAHrL.jpg',
                'plot' => 'A romantic novel about Elizabeth Bennet and her complex relationship with Mr. Darcy.',
                'author_index' => 4,
                'editor_index' => 4,
                'status' => BookStatus::AVAILABLE
            ],
            [
                'title' => 'The Adventures of Tom Sawyer',
                'isbn' => '9780486400778',
                'pages' => 224,
                'cover' => 'https://images-na.ssl-images-amazon.com/images/I/81QcWIAyeYL.jpg',
                'plot' => 'The adventures of a young boy growing up along the Mississippi River.',
                'author_index' => 5,
                'editor_index' => 0,
                'status' => BookStatus::UNAVAILABLE
            ]
        ];

        $bookObjects = [];
        foreach ($books as $bookData) {
            $book = new Book();
            $book->setTitle($bookData['title']);
            $book->setIsbn($bookData['isbn']);
            $book->setPageNumber($bookData['pages']);
            $book->setCover($bookData['cover']);
            $book->setPlot($bookData['plot']);
            $book->setEditedAt(new \DateTime());
            $book->setStatus($bookData['status']);
            $book->setEditor($editorObjects[$bookData['editor_index']]);
            $book->addAuthor($authorObjects[$bookData['author_index']]);
            $manager->persist($book);
            $bookObjects[] = $book;
        }

        // Create Comments
        $comments = [
            ['name' => 'Alice Johnson', 'content' => 'Amazing book! Couldn\'t put it down.', 'book_index' => 0],
            ['name' => 'Bob Smith', 'content' => 'A classic that everyone should read.', 'book_index' => 0],
            ['name' => 'Carol Davis', 'content' => 'Terrifying and brilliant. Stephen King at his best.', 'book_index' => 1],
            ['name' => 'David Wilson', 'content' => 'Agatha Christie\'s masterpiece. The plot twist is incredible.', 'book_index' => 2],
            ['name' => 'Eva Brown', 'content' => 'Dystopian perfection. More relevant today than ever.', 'book_index' => 3],
        ];

        foreach ($comments as $commentData) {
            $comment = new Comment();
            $comment->setName($commentData['name']);
            $comment->setContent($commentData['content']);
            $comment->setCreatedAt(new \DateTime());
            $comment->setBook($bookObjects[$commentData['book_index']]);
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
