<?
class Post
{


    /**
     * Статический метод get() выдает массив со всеми статьями или только со статьями авторизованного пользователя
     * аттрибуты: 
     * - $oConnectionDB - объект подключения к БД;
     * - $author - принимает значение true (будут выдан массив со статьями авторизованного пользователя) и false (будут выданы все статьи)
     * Возвращает массив с полями статей.
     */
    public static function get( object $oConnectionDB, bool $author = false, int $postId = 0)
    {
        if($postId !== 0){
            $query = "SELECT * FROM `posts` WHERE `id` = ?";
            $stmt = mysqli_prepare($oConnectionDB, $query);
            mysqli_stmt_bind_param($stmt, 'd', $postId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_assoc($result);
            return $data;
        }
        if($author)
        {
            $query = "SELECT `posts`.`id`, `posts`.`title`, `posts`.`preview_img`, `posts`.`create_date`, `posts`.`preview`, `posts`.`content`, `users`.`name`, `users`.`patronymic`, `users`.`surname`, `users`.`nickname` FROM `posts` JOIN `users` ON `posts`.`author_id` = `users`.`id` WHERE `posts`.`author_id` = ? ORDER BY `posts`.`create_date` DESC";
            $author_id = (int) $_SESSION['id'];
            $stmt = mysqli_prepare($oConnectionDB, $query); 
            mysqli_stmt_bind_param($stmt, 'd', $author_id);
            mysqli_stmt_execute($stmt); 
            $result = mysqli_stmt_get_result($stmt);
            for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
            return $data; 
        }
        else
        {
            $query = "SELECT `posts`.`id`, `posts`.`title`, `posts`.`preview_img`, `posts`.`create_date`, `posts`.`preview`, `posts`.`content`, `users`.`name`, `users`.`patronymic`, `users`.`surname`, `users`.`nickname`   FROM `posts` JOIN `users` ON `posts`.`author_id` = `users`.`id` ORDER BY `posts`.`create_date` DESC";
            $result = mysqli_query($oConnectionDB, $query);
            for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
            return $data;
        }
    }

    /**
     * Статический метод add() добавляет новость в базу данных
     * аттрибуты:
     * - postMessageFromForm - массив с данными полей формы;
     * - filesMessageFromForm - массив с данными файла;
     * - oConnectionDB - объект подключения к базе данных; 
     * возвращает json
     */
    public static function add(array $postMessageFromForm, array $filesMessageFromForm, object $oConnectionDB)
    {
        $arAnswer = [
            "error_status" => false,
            "title" => [
                "error" => false,
                "error_message" => 'Поле Заголовок не заполнено'
            ]
        ];
        
        if ($postMessageFromForm['title']) { 

            if ($filesMessageFromForm['preview_img']['name']) { 
                move_uploaded_file($filesMessageFromForm['preview_img']['tmp_name'], './upload/' . $filesMessageFromForm['preview_img']['name']);
            }  
             
            $title = htmlspecialchars(trim($postMessageFromForm['title']));  
            $fileAddres = '/upload/' . $filesMessageFromForm['preview_img']['name'];
            $preview = htmlspecialchars(trim($postMessageFromForm['preview']));   
            $createDate = date('Y-m-d H:i:s' ); 
            $author_id = (int) $_SESSION['id'];

            
            $query = "INSERT INTO `posts` (`title`, `preview_img`, `create_date`, `preview`, `content`, `author_id`) VALUES ( ?,?, ?, ?, ?, ? )";

            $stmt = mysqli_prepare($oConnectionDB, $query);

            mysqli_stmt_bind_param($stmt, 'sssssd', $title, $fileAddres, $createDate, $preview, $postMessageFromForm['content'], $author_id);
            
            mysqli_stmt_execute($stmt); 

            if ($arAnswer["error_status"]) {
                $arAnswer["error_status"] = false;
            }
            if ($arAnswer["title"]["error"]) {
                $arAnswer["title"]["error"] = false;
            }
            $arAnswer["title"]["error_message"] = 'Все ок, статья добавлена в БД'; 

        } else {
            $arAnswer["error_status"] = true;
            $arAnswer["title"]["error"] = true;
            $arAnswer["title"]["error_message"] = 'Поле Заголовок не заполнено'; 
        }

 
        $jsonToFront  = json_encode($arAnswer);
        return $jsonToFront;
    }

    public static function delete(int $postId, object $oConnectionDB)
    {
        $query = "SELECT * FROM `posts` WHERE `id` = ?";
        $stmt = mysqli_prepare($oConnectionDB, $query);
        mysqli_stmt_bind_param($stmt, 'd', $postId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt); 
        $data = mysqli_fetch_assoc($result);

        if($data['preview_img'] && file_exists('.'.$data['preview_img'])){
            unlink('.'.$data['preview_img']);
        }  
        $query = "DELETE FROM `posts` WHERE `id` = ?";
        $stmt = mysqli_prepare($oConnectionDB, $query);

        mysqli_stmt_bind_param($stmt, 'd', $postId);
        if(mysqli_stmt_execute($stmt)){
            return true;
        }else{
            return false;
        }
        return true;
    }
    public static function update(array $postMessageFromForm, array $filesMessageFromForm, object $oConnectionDB, int $postId)
    { 
        $oldPost = self::get($oConnectionDB, false, $postId);
        //  return $oldPost;
        if(!$filesMessageFromForm['no_files'])
        {
            
            if(!empty($filesMessageFromForm['preview_img']['name']))
            {
                
                if(!( $oldPost["preview_img"] === '/ulpoad/'.$filesMessageFromForm['preview_img']['name']))
                {
                    if(file_exists('.'.$oldPost["preview_img"]))
                    {
                        unlink('.'.$oldPost["preview_img"]);
                    }
                    move_uploaded_file($filesMessageFromForm['preview_img']['tmp_name'], './upload/' . $filesMessageFromForm['preview_img']['name']);
                }
                $query = "UPDATE `posts` SET 
                                `title` = ?,
                                `preview_img` = ?,
                                `create_date` = ?,
                                `preview` = ?,
                                `content` = ?
                                WHERE
                                `id` = ? ";

                $title = htmlspecialchars(trim($postMessageFromForm['title']));  
                $fileAddres = '/upload/' . $filesMessageFromForm['preview_img']['name'];
                $preview = htmlspecialchars(trim($postMessageFromForm['preview']));   
                $createDate = date('Y-m-d H:i:s');  

                $stmt = mysqli_prepare($oConnectionDB, $query);
                mysqli_stmt_bind_param($stmt, 'sssssd', $title, $fileAddres, $createDate, $preview, $postMessageFromForm['content'], $postId);
                mysqli_stmt_execute($stmt);
                $_SESSION['current_post_id'] = '';
                return true;
            }
            else
            {
                
                if(file_exists('.'.$oldPost["preview_img"]))
                {
                    unlink('.'.$oldPost["preview_img"]);
                }
                $query = "UPDATE `posts` SET 
                        `title` = ?, 
                        `preview_img` = ?,
                        `create_date` = ?,
                        `preview` = ?,
                        `content` = ?
                        WHERE
                        `id` = ? ";

                $title = htmlspecialchars(trim($postMessageFromForm['title']));  
                $fileAddres = ''; 
                $preview = htmlspecialchars(trim($postMessageFromForm['preview']));   
                $createDate = date('Y-m-d H:i:s');  

                $stmt = mysqli_prepare($oConnectionDB, $query);
                mysqli_stmt_bind_param($stmt, 'sssssd', $title, $fileAddres, $createDate, $preview, $postMessageFromForm['content'], $postId);
                mysqli_stmt_execute($stmt); 
                $_SESSION['current_post_id'] = '';
                return true;
            }

        }
        else
        {  
            $query = "UPDATE `posts` SET 
                        `title` = ?, 
                        `create_date` = ?,
                        `preview` = ?,
                        `content` = ?
                        WHERE
                        `id` = ? ";

            $title = htmlspecialchars(trim($postMessageFromForm['title']));   
            $preview = htmlspecialchars(trim($postMessageFromForm['preview']));   
            $createDate = date('Y-m-d H:i:s');  

            $stmt = mysqli_prepare($oConnectionDB, $query);
            mysqli_stmt_bind_param($stmt, 'ssssd', $title, $createDate, $preview, $postMessageFromForm['content'], $postId);
            mysqli_stmt_execute($stmt);
            $_SESSION['current_post_id'] = '';
            return true;
        } 
        
    }
}
