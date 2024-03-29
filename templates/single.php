<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mon blog</title>
</head>
<body>
<div>
    <h1>Mon blog</h1>
    <p>En construction</p>
    <div>
        <h2><?= htmlspecialchars($article->getTitle());?></h2>
        <p><?= htmlspecialchars($article->getContent());?></p>
        <p><?= htmlspecialchars($article->getAuthor());?></p>
        <p>Créé le : <?= htmlspecialchars($article->getCreatedAt());?></p>
    </div>
    <br>
    <a href="../public/index.php">Retour à l'accueil</a>
    <div id="comments" class="text-left" style="margin-left: 50px">
        <h3>Commentaires</h3>
        <?php
        foreach ($comments as $comment)
        {
            ?>
            <h4><?= htmlspecialchars($comment->getPseudo());?></h4>
            <p><?= htmlspecialchars($comment->getContent());?></p>
            <p>Posté le <?= htmlspecialchars($comment->getCreatedAt());?></p>
            <?php
        }
        ?>
    </div>
    <form action="index.php?route=addComment&articleId=<?= $article->getId();?>" method="post">
        <label for="pseudo">Nom :</label><br>
        <input type="text" id="pseudo" name="pseudo" required><br><br>

        <label for="commentaire">Commentaire :</label><br>
        <textarea id="content" name="content" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Envoyer">
    </form>
</div>
</body>
</html>