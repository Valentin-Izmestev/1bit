<?
class User
{


    /**
     * Статический метод isAuth() используется для проверки аторизован пользователь или нет.
     * Атрибутов не имеет
     */
    public static function isAuth()
    {
        if ($_SESSION['auth']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Статический метод login() необходим для авторизации пользователя.
     * Принимает два атрибута:
     * - $postMessageFromForm - массив $_POST получаемый от формы авторизации
     * возвращает json с данными о результате проверки логина и пароля
     */
    public static function login(array $arData)
    {
        global $connection;
       //заполняю сессию данными о пользователе.
       $_SESSION['auth'] = true;
       $_SESSION['id'] = $arData["id"];
       $_SESSION['login'] = $arData["login"];
       $_SESSION['name'] = $arData["name"];
       $_SESSION['patronymic'] = $arData["patronymic"];
       $_SESSION['surname'] = $arData["surname"];
       $_SESSION['email'] = $arData["email"];
       $_SESSION['tel'] = $arData["tel"];
       $_SESSION['gender'] = $arData["gender"];
       $_SESSION['date_of_birth'] = $arData["date_of_birth"];
       $_SESSION['last_auth_date'] = $arData["last_auth_date"];
       $_SESSION['nickname'] = $arData["nickname"];
       $currentDate = date('Y.m.d H:i:s');

       // записываю в БД информацию, что пользователь активен.
       mysqli_query($connection, "UPDATE `users` SET `active`=1 WHERE `id`=" . $arData["id"]);
       // записываю в БД последнюю дату авторизации
       mysqli_query($connection, "UPDATE `users` SET `last_auth_date`='" . $currentDate . "' WHERE `id`=" . $arData["id"]);

       return true;
    }

    /**
     * Статический метод logout() используется выхода пользователя из аккаунта 
     */
    public static function logout()
    {
        global $connection;
        mysqli_query($connection, "UPDATE `users` SET `active`=0 WHERE `id`=" . $_SESSION['id']);
        session_destroy();
    }

    /**
     * Статический метод getData() возвращает массив с данными пользователя
     */
    public static function getData()
    {
        if (self::isAuth()) {
            //преобразовываю пол пользоваетля в вид
            $gender = '';
            if ($_SESSION['gender'] === 'm') {
                $gender = 'мужской';
            } else {
                $gender = 'женский';
            }

            //преобразую дату в ДД.ММ.ГГГГ
            $date = date_create($_SESSION['date_of_birth']);
            $modDate = date_format($date, 'd.m.Y');

            return [
                "id" => $_SESSION['id'],
                "login" => $_SESSION['login'],
                "name" => $_SESSION['name'],
                "patronymic" => $_SESSION['patronymic'],
                "surname" => $_SESSION['surname'],
                "email" => $_SESSION['email'],
                "tel" => $_SESSION['tel'],
                "gender" => $gender,
                "date_of_birth" => $modDate,
                "nickname" => $_SESSION['nickname']
            ];
        }
    }
    /**
     * статический метод validation() используетя для вадидации полученных данных из формы 
     * принимает два атрибута:
     * - $postMessageFromForm - массив с новыми данными о пользователе
     * Возвращает массив с данными о проверке 
     */
    public static function validation(array $postMessageFromForm)
    {
        global $connection;
        //экспортируемый массив с информацией о прогрессе регистрации 
        global $arAnswer;
        $arAnswer['status'] = true;
        
        //проверка заполнения поля Имя
        if ($postMessageFromForm['name'] !== '') {
            $arAnswer['data']['name']['error'] = false;
            $arAnswer['data']['name']['error_message'] = "";
            
        } else {
            $arAnswer['data']['name']['error'] = true;
            $arAnswer['data']['name']['error_message'] = "Поле Имя не заполнено";
            if ($arAnswer['status'] === true) {
                $arAnswer['status'] = false;
            }
        }

        //Проверка на заполнение поля Отчество
        if ($postMessageFromForm['patronymic'] !== '') {
            $arAnswer['data']['patronymic']['error'] = false;
            $arAnswer['data']['patronymic']['error_message'] = "";
        } else {
            $arAnswer['data']['patronymic']['error'] = true;
            $arAnswer['data']['patronymic']['error_message'] = "Поле Отчество не заполнено";
            if ($arAnswer['status'] === true) {
                $arAnswer['status'] = false;
            }
        }
        // Проверка на заполнение поля Фамилия
        if ($postMessageFromForm['surname'] !== '') {
            $arAnswer['data']['surname']['error'] = false;
            $arAnswer['data']['surname']['error_message'] = "";
        } else {
            $arAnswer['data']['surname']['error'] = true;
            $arAnswer['data']['surname']['error_message'] = "Поле Фамилия не заполнено";
            if ($arAnswer['status'] === true) {
                $arAnswer['status'] = false;
            }
        }

        //Валидация поля email 
        if (self::isAuth()) {
            // получить эл.почту пользователя
            $query = "SELECT `email` FROM `users` WHERE `id`=" . $_SESSION['id'];
            $result = mysqli_query($connection, $query);
            $currentUserEmail = mysqli_fetch_assoc($result);

            if ($currentUserEmail['email'] !== $postMessageFromForm['email']) {
                if (filter_var($postMessageFromForm['email'], FILTER_VALIDATE_EMAIL)) {
                    if (self::isContaints('email', $postMessageFromForm['email'])) {
                        $arAnswer['data']['email']['error'] = true;
                        $arAnswer['data']['email']['error_message'] = 'Такая почта уже используется';
                        if ($arAnswer['status'] === true) {
                            $arAnswer['status'] = false;
                        }
                    } else {
                        $arAnswer['data']['email']['error'] = false;
                        $arAnswer['data']['email']['error_message'] = '';
                    }
                } else {
                    $arAnswer['data']['email']['error'] = true;
                    $arAnswer['data']['email']['error_message'] = 'Почта введена некорректно';

                    if ($arAnswer['status'] === true) {
                        $arAnswer['status'] = false;
                    }
                }
            } else {
                $arAnswer['data']['email']['error'] = false;
                $arAnswer['data']['email']['error_message'] = '';
            }
        } else {
            if (filter_var($postMessageFromForm['email'], FILTER_VALIDATE_EMAIL)) {

                if (self::isContaints('email', $postMessageFromForm['email'])) {
                    $arAnswer['data']['email']['error'] = true;
                    $arAnswer['data']['email']['error_message'] = 'Такая почта уже используется';
                    if ($arAnswer['status'] === true) {
                        $arAnswer['status'] = false;
                    }
                } else {
                    $arAnswer['data']['email']['error'] = false;
                    $arAnswer['data']['email']['error_message'] = '';
                }
            } else {
                $arAnswer['data']['email']['error'] = true;
                $arAnswer['data']['email']['error_message'] = 'Почта введена некорректно';
                if ($arAnswer['status'] === true) {
                    $arAnswer['status'] = false;
                }
            }
        }


        // Проверка на уникальность логина
        if (self::isAuth()) {
            // получить логин текущего пользователя
            $query = "SELECT `login` FROM `users` WHERE `id`=" . $_SESSION['id'];
            $result = mysqli_query($connection, $query);
            $currentUserLogin = mysqli_fetch_assoc($result);

            if ($currentUserLogin['login'] !== $postMessageFromForm['login']) {
                if ($postMessageFromForm['login'] !== '') {
                    if (self::isContaints('login', $postMessageFromForm['login'])) {
                        $arAnswer['data']['login']['error'] = true;
                        $arAnswer['data']['login']['error_message'] = 'Такой логин уже используется';
                        if ($arAnswer['status'] === true) {
                            $arAnswer['status'] = false;
                        }
                    } else {
                        $arAnswer['data']['login']['error'] = false;
                        $arAnswer['data']['login']['error_message'] = '';
                    }
                } else {
                    $arAnswer['data']['login']['error'] = true;
                    $arAnswer['data']['login']['error_message'] = 'Поле Логин не заполнено';
                    if ($arAnswer['status'] === true) {
                        $arAnswer['status'] = false;
                    }
                }
            }
        } else {
            if ($postMessageFromForm['login'] !== '') {
                if (self::isContaints('login', $postMessageFromForm['login'])) {
                    $arAnswer['data']['login']['error'] = true;
                    $arAnswer['data']['login']['error_message'] = 'Такой логин уже используется';
                    if ($arAnswer['status'] === true) {
                        $arAnswer['status'] = false;
                    }
                } else {
                    $arAnswer['data']['login']['error'] = false;
                    $arAnswer['data']['login']['error_message'] = '';
                }
            } else {
                $arAnswer['data']['login']['error'] = true;
                $arAnswer['data']['login']['error_message'] = 'Поле Логин не заполнено';
                if ($arAnswer['status'] === true) {
                    $arAnswer['status'] = false;
                }
            }
        }
 
        //Валидация пароля
        if (isset($postMessageFromForm['password'])) {
            if ($postMessageFromForm['password'] !== '' && $postMessageFromForm['check_password'] !== '') {
                if ($postMessageFromForm['password'] === $postMessageFromForm['check_password']) {
                    $arAnswer['data']['password']['error'] = false;
                    $arAnswer['data']['password']['error_message'] = '';
                } else {
                    $arAnswer['data']['password']['error'] = true;
                    $arAnswer['data']['password']['error_message'] = 'Пароль и повторение пароля не совпадают';
                    if ($arAnswer['status'] === true) {
                        $arAnswer['status'] = false;
                    }
                }
            } else {
                $arAnswer['data']['password']['error'] = true;
                $arAnswer['data']['password']['error_message'] = 'Поле Пароль не заполнено';
                if ($arAnswer['status'] === true) {
                    $arAnswer['status'] = false;
                }
            }
        }


        //Проверка на заполнение поля Телефон
        if ($postMessageFromForm['tel'] !== '') {
            $arAnswer['data']['tel']['error'] = false;
            $arAnswer['data']['tel']['error_message'] = "";
        } else {
            $arAnswer['data']['tel']['error'] = true;
            $arAnswer['data']['tel']['error_message'] = "Поле Телефон не заполнено";
            if ($arAnswer['status'] === true) {
                $arAnswer['status'] = false;
            }
        }
        //Проверка на заполнение поля Пол
        if (isset($postMessageFromForm['gender'])) {
            $arAnswer['data']['gender']['error'] = false;
            $arAnswer['data']['gender']['error_message'] = "";
        } else {
            $arAnswer['data']['gender']['error'] = true;
            $arAnswer['data']['gender']['error_message'] = "Не указан пол";
            if ($arAnswer['status'] === true) {
                $arAnswer['status'] = false;
            }
        }
        //Проверка на заполнение поля Дата рождения
        if ($postMessageFromForm['date_of_birth'] !== '') {
            $arAnswer['data']['date_of_birth']['error'] = false;
            $arAnswer['data']['date_of_birth']['error_message'] = "";
        } else {
            $arAnswer['data']['date_of_birth']['error'] = true;
            $arAnswer['data']['date_of_birth']['error_message'] = "Ну указана дата рождения";
            if ($arAnswer['status'] === true) {
                $arAnswer['status'] = false;
            }
        }

        // // Проверка на уникальность никнейма
        if (self::isAuth()) {
            // получить логин текущего пользователя
            $query = "SELECT `nickname` FROM `users` WHERE `id`=" . $_SESSION['id'];
            $result = mysqli_query($connection, $query);
            $currentUserNickname = mysqli_fetch_assoc($result);

            if ($currentUserNickname['nickname'] !== $postMessageFromForm['nickname']) {
                if ($postMessageFromForm['nickname'] !== '') {
                    if (self::isContaints('nickname', $postMessageFromForm['nickname'])) {
                        $arAnswer['data']['nickname']['error'] = true;
                        $arAnswer['data']['nickname']['error_message'] = 'Такой никнейм уже используется';
                        if ($arAnswer['status'] === true) {
                            $arAnswer['status'] = false;
                        }
                    } else {
                        $arAnswer['data']['nickname']['error'] = false;
                        $arAnswer['data']['nickname']['error_message'] = '';
                    }
                } else {
                    $arAnswer['data']['nickname']['error'] = true;
                    $arAnswer['data']['nickname']['error_message'] = 'Поле Никнейм не заполнено';
                    if ($arAnswer['status'] === true) {
                        $arAnswer['status'] = false;
                    }
                }
            } else {
                $arAnswer['data']['nickname']['error'] = false;
                $arAnswer['data']['nickname']['error_message'] = '';
            }
        } else {
            if ($postMessageFromForm['nickname'] !== '') {
                if (self::isContaints('nickname', $postMessageFromForm['nickname'])) {
                    $arAnswer['data']['nickname']['error'] = true;
                    $arAnswer['data']['nickname']['error_message'] = 'Такой никнейм уже используется';
                    if ($arAnswer['status'] === true) {
                        $arAnswer['status'] = false;
                    }
                } else {
                    $arAnswer['data']['nickname']['error'] = false;
                    $arAnswer['data']['nickname']['error_message'] = '';
                }
            } else {
                $arAnswer['data']['nickname']['error'] = true;
                $arAnswer['data']['nickname']['error_message'] = 'Поле Никнейм не заполнено';
                if ($arAnswer['status'] === true) {
                    $arAnswer['status'] = false;
                }
            }
        } 
        return $arAnswer;
    }
    /**
     * Статический метод registeration() используется для добавления в БД нового пользователя
     * принимает два атрибута:
     * - $postMessageFromForm - массив с новыми данными о пользователе
     */
    public static function registeration(array $postMessageFromForm)
    {
        global $connection;
        
        $query = "INSERT INTO `users`  
        (`name`, `patronymic`, `surname`, `email`, `login`, `password`, `tel`, `gender`, `date_of_birth`, `active`, `last_auth_date`, `register_date`, `nickname`) 
        VALUES (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'sssssssssisss', $name, $patronymic, $surname,  $email, $login, $password, $tel, $gender, $dateOfBirth, $active, $lastAuthDate, $registerDate, $nickname);

        $name = htmlspecialchars(trim($postMessageFromForm['name']));
        $patronymic = htmlspecialchars(trim($postMessageFromForm['patronymic']));
        $surname = htmlspecialchars(trim($postMessageFromForm['surname']));
        $email = $postMessageFromForm['email'];
        $login = htmlspecialchars(trim($postMessageFromForm['login']));
        $password = $postMessageFromForm['password'];
        $tel = trim($postMessageFromForm['tel']);
        $gender = $postMessageFromForm['gender'];
        $dateOfBirth = $postMessageFromForm['date_of_birth'];
        $active = 1;
        $lastAuthDate = date('Y-m-d H:i:s');
        $registerDate = date('Y-m-d');
        $nickname = htmlspecialchars(trim($postMessageFromForm['nickname']));

        mysqli_stmt_execute($stmt);

        $query = "SELECT `id` FROM `users` WHERE `login`=?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $login);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
       

        //Записываю данные о пользователе в сессию.
        $_SESSION['auth'] = true; 
        $_SESSION['id'] = $data['id']; 
        $_SESSION['login'] = $login;
        $_SESSION['name'] =  $name;
        $_SESSION['patronymic'] = $patronymic;
        $_SESSION['surname'] = $surname;
        $_SESSION['email'] = $email;
        $_SESSION['tel'] = $tel;
        $_SESSION['gender'] = $gender;
        $_SESSION['date_of_birth'] = $dateOfBirth;
        $_SESSION['active'] = $active;
        $_SESSION['last_auth_date'] = $lastAuthDate;
        $_SESSION['nickname'] = $nickname;

        if ($data) {
            return "Пользователь успешно добавлен";
        } else {
            return "Ошибка добавления пользователя";
        }
    }

    /**
     * Статический метод update() обновляет данные о пользователе 
     * принимает два атрибута:
     * - $postMessageFromForm - массив с новыми данными о пользователе
     */
    public static function update(array $postMessageFromForm)
    { 
        global $connection;
        global $arAnswer;
        // делаю запрос к БД по id из $_POST запихиваю все в массив и далее сопоставляю элементы $_POST и массив из БД
        $query = "SELECT * FROM `users` WHERE `id`=" . $_SESSION['id'];
        $result = mysqli_query($connection, $query);
        $data = mysqli_fetch_assoc($result); 
        
        foreach ($postMessageFromForm as $key => $value) {   
            if (isset($data[$key])) {  
                    if ($key === 'id') {
                        continue;
                    }
                    if ($value === $data[$key]) {
                        continue;
                    }
                    $query = "UPDATE `users` SET ".$key."=? WHERE `id`=" . $_SESSION['id'];
                    $stmt = mysqli_prepare($connection, $query);  
                    mysqli_stmt_bind_param($stmt, 's', $value);
                    mysqli_stmt_execute($stmt);  
                    $_SESSION[$key] = $value;
            } 
        }
        $arAnswer["status"] = true;
        $arAnswer["message"] = 'Изменения успешно внесены';
         return $arAnswer;
    }

    /**
     * Статический метод isContaints() необходим для проверки на наличие в таблице поля с определенным значением.
     * Принимает три атрибута:
     * - $fieldName - имя поля в таблице User
     * - $desiredValue - искомое значение
     * если есть указанное поле с указанным значением, вернет true, в противном случае false
     */
    public static function isContaints(string $fieldName, string $desiredValue)
    {
        global $connection;
 
        $val = htmlspecialchars($desiredValue);
        
        $query = "SELECT * FROM `users` WHERE `" . $fieldName . "`=?";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $val);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
       
        if ($data) {
            return true;
        } else {
            return false;
        }
    }
}
