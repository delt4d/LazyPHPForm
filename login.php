<?php
require_once 'shared/db_connection.php';

session_start();

$error_msg = "";

function checkFields($email, $password)
{
    $error_msg = empty($email) ? "Você precisa fornecer seu email" : (empty($password) ? "A senha é obrigatória" : "");

    if ($error_msg) return false;

    if (strlen($password) < 8) {
        $error_msg = "A senha tem que ter no mínimo 8 caracteres";
        return false;
    }

    return true;
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $fieldsValid = checkFields($email, $password);

    if ($fieldsValid) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email=? and password=md5(?)");

        if (!$stmt) $error_msg = $pdo->errorInfo();
        if (!$stmt->execute([$email, $password])) $error_msg = $pdo->errorInfo();

        $row = $stmt->fetch();

        if (!$error_msg && $row) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: profile.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Form</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
</head>

<body>
    <div id="wrapper" class="h-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6 col-sm-8 col-xs-12">
                    <form method="post" id="form" class="needs-validation" novalidate>
                        <h1 class="text-center mb-4">Formulário de Cadastro</h1>

                        <div class="form-group mb-2">
                            <label class="form-label" for="email">E-mail</label>
                            <input class="form-control" type="email" id="email" name="email" required />
                            <div class="valid-feedback">Parece bom!</div>
                            <div class="invalid-feedback">Verifique o preenchimento do campo e-mail.</div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control" type="password" id="password" name="password" minlength="8" required />
                            <div class="valid-feedback">Parece bom!</div>

                            <div class="invalid-feedback">O campo senha deve conter no mínimo 8 caracteres com letras maiúsculas e minúsculas, números, caracteres especiais e não conter sequências.</div>
                        </div>

                        <div class="mt-2">
                            <div class="row">
                                <div class="col-12">
                                    <button name='submit' class="btn btn-primary" type="submit">
                                        Entrar
                                    </button>

                                    <a style="margin-left:10px;" href="./register.php">Não tenho conta</a>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($error_msg)) : ?>
                            <p style="color: red;">
                                <?php echo $error_msg; ?>
                            </p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="assets/js/login.js"></script>
</body>

</html>
