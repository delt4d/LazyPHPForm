<?php
require_once 'shared/db_connection.php';

session_start();

function getUser($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id=?");

    if (!$stmt) return null;
    if (!$stmt->execute([$id])) return null;

    $row = $stmt->fetch();

    if ($row) return $row;
    return null;
}


function getAllUsers($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM users");

    if (!$stmt) return null;
    if (!$stmt->execute()) return null;

    $rows = $stmt->fetchAll();

    if ($rows) return $rows;
    return null;
}

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
}

$user = getUser($pdo, $_SESSION['user_id']);

if (!$user) {
    header('location: login.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>

<body>
    <a href="login.php">Ir para login</a>
    <a href="register.php">Ir para o cadastro</a>

    <hr />

    <h1>Hello, <?php echo $user['name'] ?>.</h1>
    <h2>Email: <?php echo $user['email'] ?></h2>

    <hr />

    <h1>Usuários</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
        </tr>

        <?php
        foreach (getAllUsers($pdo) as $row) {
            $id = $row['id'];
            $name = $row['name'];
            $email = $row['email'];

            echo "<tr><td>$id</td><td>$name</td><td>$email</td></tr>";
        }
        ?>
    </table>
</body>

</html>
