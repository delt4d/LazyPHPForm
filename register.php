<?php
require_once 'shared/db_connection.php';

session_start();

$error_msg = "";

function generateUuidV4()
{
    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


function checkFields($name, $email, $password, $repeat_password)
{
    $error_msg = empty($name) ? "O campo nome é obrigatório" : (empty($email) ? "Você precisa fornecer seu email" : (empty($password) ? "A senha é obrigatória" : (empty($repeat_password) ? "Você precisa confirmar a senha" : "")));

    if ($error_msg) return false;

    if (strlen($name) < 3 || strlen($name) > 50) {
        $error_msg = "O campo nome tem que ter entre 3 e 50 caracteres";
        return false;
    }

    if (strlen($password) < 8) {
        $error_msg = "A senha tem que ter no mínimo 8 caracteres";
        return false;
    }

    if ($password != $repeat_password) {
        $error_msg = "As senhas devem ser iguais";
        return false;
    }

    return true;
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat-password'];

    $fieldsValid = checkFields($name, $email, $password, $repeat_password);

    $id = generateUuidV4();

    if ($fieldsValid) {
        $stmt = $pdo->prepare("INSERT INTO users (id, name, email, password) VALUES (?, ?, ?, md5(?))");

        if (!$stmt) $error_msg = $pdo->errorInfo();
        if (!$stmt->execute([$id, $name, $email, $password])) $error_msg = $pdo->errorInfo();

        if (!$error_msg) {
            $_SESSION['user_id'] = $id;
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
                            <label class="form-label" for="name">Nome*</label>
                            <input class="form-control" type="text" id="name" name="name" minlength="3" maxlength="50" required />
                            <div class="valid-feedback">Parece bom!</div>
                            <div class="invalid-feedback">O campo nome deve conter entre 3 e 50 caracteres.</div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label" for="email">E-mail*</label>
                            <input class="form-control" type="email" id="email" name="email" required />
                            <div class="valid-feedback">Parece bom!</div>
                            <div class="invalid-feedback">Verifique o preenchimento do campo e-mail.</div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label" for="password">Password*</label>
                            <input class="form-control" type="password" id="password" name="password" minlength="8" required />
                            <div class="valid-feedback">Parece bom!</div>
                            <div class="invalid-feedback">O campo senha deve conter no mínimo 8 caracteres com letras maiúsculas e minúsculas, números, caracteres especiais e não conter sequências.</div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label" for="repeat-password">Password confirmation*</label>
                            <input class="form-control" type="password" id="repeat-password" name="repeat-password" disabled required />
                            <div class="valid-feedback">Parece bom!</div>
                            <div class="invalid-feedback">Campo confirmação de senha e o campo senha não conferem.</div>
                        </div>

                        <div class="mt-2">
                            <div class="row">
                                <div class="col-12">
                                    <button name='submit' class="btn btn-primary" type="submit">
                                        Cadastrar
                                    </button>

                                    <a style="margin-left:10px;" href="./login.php">Já sou cadastrado</a>
                                </div>

                                <div class="col-12">
                                    <span>* Campos obrigatórios</span>
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
    <script src="assets/js/register.js"></script>
</body>

</html>
