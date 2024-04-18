<?php
namespace App\src\controller;
use App\src\DAO\ArticleDAO;
// Import de la classe CommentDAO
use App\src\DAO\CommentDAO;
use App\src\model\View;
class FrontController
{
    private $articleDAO;
    private $commentDAO;
    private $View;
    public function __construct()
    {
        $this->articleDAO = new ArticleDAO();
        $this->commentDAO = new CommentDAO();
        $this->view = new View();
    }
    public function home()
    {
        /* $articles = $this->articleDAO->getArticles();
        require '../templates/home.php';*/
        $articles = $this->articleDAO->getArticles();
        return $this->view->render('home', [
            'articles' => $articles
        ]);
    }
    public function article($articleId)
    {
        $article = $this->articleDAO->getArticle($articleId);
        $comments = $this->commentDAO->getCommentsFromArticle($articleId);
// require '../templates/single.php';
        return $this->view->render('single', [
            'article' => $article,
            'comments' => $comments,

        ]);
    }
    public function addComment($articleId, $pseudo, $content) {
        if (!$this->validateCommentData($articleId, $pseudo, $content)) {
            // Gestion de l'erreur si le token CSRF est invalide
            // Par exemple, rediriger vers une page d'erreur CSRF
            header('Location: ../templates/error_csrf.html');
            exit;
        }

        $this->commentDAO->addComment($articleId, $pseudo, $content)|| !$this->validateCsrfToken();
        // Rediriger vers l'article pour voir le commentaire ajouté
        header('Location: index.php?route=article&articleId=' . $articleId);
        exit;
    }

// Exemple validation à améliorer
    private function validateCommentData($articleId, $pseudo, $content) {
        return !empty($pseudo) && !empty($content) && !empty($articleId) && $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function generateCsrfToken() {
        // Générer un token CSRF aléatoire
        $token = bin2hex(random_bytes(32)); // Génère un token aléatoire de 32 caractères

        // Stocker le token CSRF dans la session utilisateur
        $_SESSION['csrf_token'] = $token;

        // Retourner le token CSRF
        return $token;
    }
    public function validateCsrfToken() {
        if (!empty($_POST['csrf_token'])) {
            // Récupère le token CSRF stocké dans la session utilisateur
            $storedCsrfToken = $_SESSION['csrf_token'];

            // Récupère le token CSRF soumis dans le formulaire
            $submittedCsrfToken = $_POST['csrf_token'];

            // Compare les tokens pour vérifier s'ils correspondent
            if ($storedCsrfToken === $submittedCsrfToken) {
                // Les tokens correspondent, le formulaire est valide
                return true;
            } else {
                // Les tokens ne correspondent pas, probable attaque CSRF
                return false;
            }
        } else {
            // Le token CSRF est manquant dans les données POST
            return false;
        }
    }

}