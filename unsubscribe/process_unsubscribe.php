<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("E-mail inválido.");
    }

    $db = new SQLite3('unsubscribed.db'); // cria ou abre o banco
    $db->exec("CREATE TABLE IF NOT EXISTS unsubscribed_emails (
        id INTEGER PRIMARY KEY,
        email TEXT UNIQUE,
        unsubscribed_at TEXT
    )");

    $stmt = $db->prepare("INSERT OR IGNORE INTO unsubscribed_emails (email, unsubscribed_at) VALUES (:email, datetime('now'))");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->execute();

    echo "<h2>Você foi descadastrado com sucesso.</h2>";
} else {
    echo "<h2>Erro ao processar o pedido.</h2>";
}
?>
