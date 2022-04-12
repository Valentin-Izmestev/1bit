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
     * - $oConnectionDB - объект подключения к базе данных
     * возвращает json с данными о результате проверки логина и пароля
     */
    public static function login(array $postMessageFromForm, object $oConnectionDB)
    {
        //экспортируемый массив с информацией о прогрессе авторизации 
        $arAnswer = [
            "error" => [
                "errorStatus" => true,
                "loginError" => "Y",
                "loginErrorMessage" => "Неправильно указан логин",
                "passwordError" => "Y",
                "passwordErrorMessage" => "Неправильно указан пароль",
            ],
        ];

        if ($postMessageFromForm['login']) {
            $login = htmlspecialchars($postMessageFromForm['login']);
            $query = "SELECT * FROM `users` WHERE `login`=?";
            $stmt = mysqli_prepare($oConnectionDB, $query);

            mysqli_stmt_bind_param($stmt, 's', $login);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_assoc($result);

            if ($data) {
                $arAnswer['error']['loginError'] = 'N';
                $password = $postMessageFromForm['password'];

                if ($data['password'] === $password) {
                    $arAnswer['error']['errorStatus'] = false;
                    $arAnswer['error']['passwordError'] = 'N';

                    //заполняю сессию данными о пользователе.
                    $_SESSION['auth'] = true;
                    $_SESSION['id'] = $data["id"];
                    $_SESSION['login'] = $data["login"];
                    $_SESSION['name'] = $data["name"];
                    $_SESSION['patronymic'] = $data["patronymic"];
                    $_SESSION['surname'] = $data["surname"];
                    $_SESSION['email'] = $data["email"];
                    $_SESSION['tel'] = $data["tel"];
                    $_SESSION['gender'] = $data["gender"];
                    $_SESSION['date_of_birth'] = $data["date_of_birth"];
                    $_SESSION['last_auth_date'] = $data["last_auth_date"];
                    $_SESSION['nickname'] = $data["nickname"];
                }
                $currentDate = date('Y.m.d H:i:s');
                // записываю в БД информацию, что пользователь активен.
                mysqli_query($oConnectionDB, "UPDATE `users` SET `active`=1 WHERE `id`=" . $data["id"]);
                // записываю в БД последнюю дату авторизации
                mysqli_query($oConnectionDB, "UPDATE `users` SET `last_auth_date`='" . $currentDate . "' WHERE `id`=" . $data["id"]);
            }
        }
        $json = json_encode($arAnswer);
        echo $json;
    }

    /**
     * Статический метод logout() используется выхода пользователя из аккаунта 
     * Использует аттрибуты:
     * - $oConnectionDB - объект подключения к базе данных
     */
    public static function logout(object $oConnectionDB)
    {
        mysqli_query($oConnectionDB, "UPDATE `users` SET `active`=0 WHERE `id`=" . $_SESSION['id']);
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
     * - $oConnectionDB - объект подключения к базе данных
     * Возвращает массив с данными о проверке 
     */
    public static function validation(array $postMessageFromForm, object $oConnectionDB)
    {
        //экспортируемый массив с информацией о прогрессе регистрации 
        $arAnswer = [
            "error_status" => false,
            "name" => [
                "error" => false,
                "error_message" => ""
            ],
            "patronymic" => [
                "error" => false,
                "error_message" => ""
            ],
            "surname" => [
                "error" => false,
                "error_message" => ""
            ],
            "email" => [
                "error" => false,
                "error_message" => ""
            ],
            "login" => [
                "error" => false,
                "error_message" => ""
            ],
            "password" => [
                "error" => false,
                "error_message" => ""
            ],
            "tel" => [
                "error" => false,
                "error_message" => ""
            ],
            "gender" => [
                "error" => false,
                "error_message" => ""
            ],
            "date_of_birth" => [
                "error" => false,
                "error_message" => ""
            ],
            "nickname" => [
                "error" => false,
                "error_message" => ""
            ]
        ];
        //проверка заполнения поля Имя
        if ($postMessageFromForm['name'] !== '') {
            $arAnswer['name']['error'] = false;
            $arAnswer['name']['error_message'] = "";
        } else {
            $arAnswer['name']['error'] = true;
            $arAnswer['name']['error_message'] = "Поле Имя не заполнено";
            if ($arAnswer['error_status'] === false) {
                $arAnswer['error_status'] = true;
            }
        }

        //Проверка на заполнение поля Отчество
        if ($postMessageFromForm['patronymic'] !== '') {
            $arAnswer['patronymic']['error'] = false;
            $arAnswer['patronymic']['error_message'] = "";
        } else {
            $arAnswer['patronymic']['error'] = true;
            $arAnswer['patronymic']['error_message'] = "Поле Отчество не заполнено";
            if ($arAnswer['error_status'] === false) {
                $arAnswer['error_status'] = true;
            }
        }
        //Проверка на заполнение поля Фамилия
        if ($postMessageFromForm['surname'] !== '') {
            $arAnswer['surname']['error'] = false;
            $arAnswer['surname']['error_message'] = "";
        } else {
            $arAnswer['surname']['error'] = true;
            $arAnswer['surname']['error_message'] = "Поле Фамилия не заполнено";
            if ($arAnswer['error_status'] === false) {
                $arAnswer['error_status'] = true;
            }
        }

        //Валидация поля email 
        if (self::isAuth()) {
            // получить эл.почту пользователя
            $query = "SELECT `email` FROM `users` WHERE `id`=" . $_SESSION['id'];
            $result = mysqli_query($oConnectionDB, $query);
            $currentUserEmail = mysqli_fetch_assoc($result);

            if ($currentUserEmail['email'] !== $postMessageFromForm['email']) {
                if (filter_var($postMessageFromForm['email'], FILTER_VALIDATE_EMAIL)) {
                    if (self::isContaints('email', $postMessageFromForm['email'], $oConnectionDB)) {
                        $arAnswer['email']['error'] = true;
                        $arAnswer['email']['error_message'] = 'Такая почта уже используется';
                        if ($arAnswer['error_status'] === false) {
                            $arAnswer['error_status'] = true;
                        }
                    } else {
                        $arAnswer['email']['error'] = false;
                        $arAnswer['email']['error_message'] = '';
                    }
                } else {
                    $arAnswer['email']['error'] = true;
                    $arAnswer['email']['error_message'] = 'Почта введена некорректно';

                    if ($arAnswer['error_status'] === false) {
                        $arAnswer['error_status'] = true;
                    }
                }
            } else {
                $arAnswer['email']['error'] = false;
                $arAnswer['email']['error_message'] = '';
            }
        } else {
            if (filter_var($postMessageFromForm['email'], FILTER_VALIDATE_EMAIL)) {

                if (self::isContaints('email', $postMessageFromForm['email'], $oConnectionDB)) {
                    $arAnswer['email']['error'] = true;
                    $arAnswer['email']['error_message'] = 'Такая почта уже используется';
                    if ($arAnswer['error_status'] === false) {
                        $arAnswer['error_status'] = true;
                    }
                } else {
                    $arAnswer['email']['error'] = false;
                    $arAnswer['email']['error_message'] = '';
                }
            } else {
                $arAnswer['email']['error'] = true;
                $arAnswer['email']['error_message'] = 'Почта введена некорректно';
                if ($arAnswer['error_status'] === false) {
                    $arAnswer['error_status'] = true;
                }
            }
        }


        // Проверка на уникальность логина
        if (self::isAuth()) {
            // получить логин текущего пользователя
            $query = "SELECT `login` FROM `users` WHERE `id`=" . $_SESSION['id'];
            $result = mysqli_query($oConnectionDB, $query);
            $currentUserLogin = mysqli_fetch_assoc($result);

            if ($currentUserLogin['login'] !== $postMessageFromForm['login']) {
                if ($postMessageFromForm['login'] !== '') {
                    if (self::isContaints('login', $postMessageFromForm['login'], $oConnectionDB)) {
                        $arAnswer['login']['error'] = true;
                        $arAnswer['login']['error_message'] = 'Такой логин уже используется';
                        if ($arAnswer['error_status'] === false) {
                            $arAnswer['error_status'] = true;
                        }
                    } else {
                        $arAnswer['login']['error'] = false;
                        $arAnswer['login']['error_message'] = '';
                    }
                } else {
                    $arAnswer['login']['error'] = true;
                    $arAnswer['login']['error_message'] = 'Поле Логин не заполнено';
                    if ($arAnswer['error_status'] === false) {
                        $arAnswer['error_status'] = true;
                    }
                }
            }
        } else {
            if ($postMessageFromForm['login'] !== '') {
                if (self::isContaints('login', $postMessageFromForm['login'], $oConnectionDB)) {
                    $arAnswer['login']['error'] = true;
                    $arAnswer['login']['error_message'] = 'Такой логин уже используется';
                    if ($arAnswer['error_status'] === false) {
                        $arAnswer['error_status'] = true;
                    }
                } else {
                    $arAnswer['login']['error'] = false;
                    $arAnswer['login']['error_message'] = '';
                }
            } else {
                $arAnswer['login']['error'] = true;
                $arAnswer['login']['error_message'] = 'Поле Логин не заполнено';
                if ($arAnswer['error_status'] === false) {
                    $arAnswer['error_status'] = true;
                }
            }
        }


        //Валидация пароля
        if (isset($postMessageFromForm['password'])) {
            if ($postMessageFromForm['password'] !== '' && $postMessageFromForm['check_password'] !== '') {
                if ($postMessageFromForm['password'] === $postMessageFromForm['check_password']) {
                    $arAnswer['password']['error'] = false;
                    $arAnswer['password']['error_message'] = '';
                } else {
                    $arAnswer['password']['error'] = true;
                    $arAnswer['password']['error_message'] = 'Пароль и повторение пароля не совпадают';
                    if ($arAnswer['error_status'] === false) {
                        $arAnswer['error_status'] = true;
                    }
                }
            } else {
                $arAnswer['password']['error'] = true;
                $arAnswer['password']['error_message'] = 'Поле Пароль не заполнено';
                if ($arAnswer['error_status'] === false) {
                    $arAnswer['error_status'] = true;
                }
            }
        }


        //Проверка на заполнение поля Телефон
        if ($postMessageFromForm['tel'] !== '') {
            $arAnswer['tel']['error'] = false;
            $arAnswer['tel']['error_message'] = "";
        } else {
            $arAnswer['tel']['error'] = true;
            $arAnswer['tel']['error_message'] = "Поле Телефон не заполнено";
            if ($arAnswer['error_status'] === false) {
                $arAnswer['error_status'] = true;
            }
        }
        //Проверка на заполнение поля Пол
        if (isset($postMessageFromForm['gender'])) {
            $arAnswer['gender']['error'] = false;
            $arAnswer['gender']['error_message'] = "";
        } else {
            $arAnswer['gender']['error'] = true;
            $arAnswer['gender']['error_message'] = "Не указан пол";
            if ($arAnswer['error_status'] === false) {
                $arAnswer['error_status'] = true;
            }
        }
        //Проверка на заполнение поля Дата рождения
        if ($postMessageFromForm['date_of_birth'] !== '') {
            $arAnswer['date_of_birth']['error'] = false;
            $arAnswer['date_of_birth']['error_message'] = "";
        } else {
            $arAnswer['date_of_birth']['error'] = true;
            $arAnswer['date_of_birth']['error_message'] = "Ну указана дата рождения";
            if ($arAnswer['error_status'] === false) {
                $arAnswer['error_status'] = true;
            }
        }

        // Проверка на уникальность никнейма
        if (self::isAuth()) {
            // получить логин текущего пользователя
            $query = "SELECT `nickname` FROM `users` WHERE `id`=" . $_SESSION['id'];
            $result = mysqli_query($oConnectionDB, $query);
            $currentUserNickname = mysqli_fetch_assoc($result);

            if ($currentUserNickname['nickname'] !== $postMessageFromForm['nickname']) {
                if ($postMessageFromForm['nickname'] !== '') {
                    if (self::isContaints('nickname', $postMessageFromForm['nickname'], $oConnectionDB)) {
                        $arAnswer['nickname']['error'] = true;
                        $arAnswer['nickname']['error_message'] = 'Такой никнейм уже используется';
                        if ($arAnswer['error_status'] === false) {
                            $arAnswer['error_status'] = true;
                        }
                    } else {
                        $arAnswer['nickname']['error'] = false;
                        $arAnswer['nickname']['error_message'] = '';
                    }
                } else {
                    $arAnswer['nickname']['error'] = true;
                    $arAnswer['nickname']['error_message'] = 'Поле Никнейм не заполнено';
                    if ($arAnswer['error_status'] === false) {
                        $arAnswer['error_status'] = true;
                    }
                }
            } else {
                $arAnswer['nickname']['error'] = false;
                $arAnswer['nickname']['error_message'] = '';
            }
        } else {
            if ($postMessageFromForm['nickname'] !== '') {
                if (self::isContaints('nickname', $postMessageFromForm['nickname'], $oConnectionDB)) {
                    $arAnswer['nickname']['error'] = true;
                    $arAnswer['nickname']['error_message'] = 'Такой никнейм уже используется';
                    if ($arAnswer['error_status'] === false) {
                        $arAnswer['error_status'] = true;
                    }
                } else {
                    $arAnswer['nickname']['error'] = false;
                    $arAnswer['nickname']['error_message'] = '';
                }
            } else {
                $arAnswer['nickname']['error'] = true;
                $arAnswer['nickname']['error_message'] = 'Поле Никнейм не заполнено';
                if ($arAnswer['error_status'] === false) {
                    $arAnswer['error_status'] = true;
                }
            }
        }


        return $arAnswer;
    }
    /**
     * Статический метод registeration() используется для добавления в БД нового пользователя
     * принимает два атрибута:
     * - $postMessageFromForm - массив с новыми данными о пользователе
     * - $oConnectionDB - объект подключения к базе данных
     */
    public static function registeration(array $postMessageFromForm, object $oConnectionDB)
    {
        $arAnswer = self::validation($postMessageFromForm, $oConnectionDB);

        if (!$arAnswer['error_status']) {
            // Добавляю нового пользователя в базу данных
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
            $stmt = mysqli_prepare($oConnectionDB, $query);
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

            $arAnswer = [
                "error_status" => false
            ];
            //Записываю данные о пользователе в сессию.
            $_SESSION['auth'] = true;
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

            $jsonToFront = json_encode($arAnswer);
            echo $jsonToFront;
        } else {
            $jsonToFront = json_encode($arAnswer);
            echo $jsonToFront;
        }
    }

    /**
     * Статический метод update() обновляет данные о пользователе 
     * принимает два атрибута:
     * - $postMessageFromForm - массив с новыми данными о пользователе
     * - $oConnectionDB - объект подключения к базе данных
     */
    public static function update(array $postMessageFromForm, object $oConnectionDB)
    { 
        $arAnswer = self::validation($postMessageFromForm, $oConnectionDB);
   
        // написать запись данных в базу данных, а лучше проверять были ли изменения.
        if (!$arAnswer['error_status']) {
            // делаю запрос к БД по id из $_POST запихиваю все в массив и далее сопоставляю элементы $_POST и массив из БД
            $query = "SELECT * FROM `users` WHERE `id`=" . $_SESSION['id'];
            $result = mysqli_query($oConnectionDB, $query);
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
                        $stmt = mysqli_prepare($oConnectionDB, $query);  
                        mysqli_stmt_bind_param($stmt, 's', $value);
                        mysqli_stmt_execute($stmt);  
                        $_SESSION[$key] = $value;
                } 
            }
        }  
        $jsonToFront = json_encode($arAnswer);
        return $jsonToFront; 
    }

    /**
     * Статический метод isContaints() необходим для проверки на наличие в таблице поля с определенным значением.
     * Принимает три атрибута:
     * - $fieldName - имя поля в таблице User
     * - $desiredValue - искомое значение
     * - $oConnectionDB - объект подключения к базе данных
     * если есть указанное поле с указанным значением, вернет true, в противном случае false
     */
    public static function isContaints(string $fieldName, string $desiredValue, object $oConnectionDB)
    {
        $val = htmlspecialchars($desiredValue);
        $query = "SELECT * FROM `users` WHERE `" . $fieldName . "`=?";
        $stmt = mysqli_prepare($oConnectionDB, $query);
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
