<?php
    //DB接続設定
    $dsn = 'mysql:dbname=tb*******db;host=localhost';
    $user = 'tb-******';
    $password = 'PASSWORD';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."create_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,"
    ."pass TEXT"
    .");";
    $stmt = $pdo -> query($sql);
    
    //変数設定
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pass = $_POST["pass"];
    $del_num = $_POST["del_num"];
    $re_num = $_POST["re_num"];
    $post_num = $_POST["post_num"];
    
    //
    if($name != "" && $comment != ""){
        if($post_num != ""){
            //編集動作
            $sql = "SELECT * FROM mission5";
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach($results as $row){
                if($post_num == $row["id"]){
                    $id = $row["id"];
                    $sql = 'UPDATE mission5 SET name=:name, comment=:comment WHERE id=:id';
                    $stmt = $pdo -> prepare($sql);
                    $stmt -> bindParam(":name", $name, PDO::PARAM_STR);
                    $stmt -> bindParam(":comment", $comment, PDO::PARAM_STR);
                    $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
                    $stmt -> execute();
                    echo "編集完了<br>";
                    //変数リセット
                    $post_num = "";
                    $re_name = "";
                    $re_com = "";
                    $re_num = "";
                }
            }
            $sql = "SELECT * FROM mission5";
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach($results as $row){
                echo $row["id"]. " ". $row["name"]. " ". $row["comment"]. "<br>";
            }
            echo "<hr>";
        }else{
            //単純入力
            $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, pass) VALUES (:name, :comment, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql -> execute();
            
            $sql = "SELECT * FROM mission5";
            $stem = $pdo -> query($sql);
            $results = $stem -> fetchAll();
            foreach($results as $row){
                echo $row["id"]. " ". $row["name"]. " ". $row["comment"]. "<br>";
            }
            echo "<hr>";
        }
    }elseif($del_num != ""){
        //削除番号指定後
        $sql = "SELECT * FROM mission5";
        $stem = $pdo -> query($sql);
        $results = $stem -> fetchAll();
        foreach($results as $row){
            if($del_num == $row["id"] && $row["pass"] == $_POST["del_pass"]){
                $id = $del_num;
                $sql = 'delete from mission5 where id=:id';
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                $stmt -> execute();
                echo "削除完了<br>";
            }elseif($del_num == $row["id"] && $row["pass"] != $_POST["del_pass"]){
                echo "パスワードが違います";
            }elseif($del_num != $row["id"]){
                echo $row["id"]. $row["name"]. $row["comment"]. "<br>";
            }
        }
        echo "<hr>";
    }elseif($re_num != ""){
        //編集番号指定後
        $sql = "SELECT * FROM mission5";
        $stem = $pdo -> query($sql);
        $results = $stem -> fetchAll();
        foreach($results as $row){
            if($re_num == $row["id"] && $row["pass"] == $_POST["re_pass"]){
                $re_name = $row["name"];
                $re_com = $row["comment"];
            }elseif($re_num == $row["id"] && $row["pass"] != $_POST["re_pass"]){
                echo "パスワードが違います";
            }
        }
        
    }elseif($name == "" && $comment != ""){
        echo "名前を入力してください";
    }elseif($name != "" && $comment == ""){
        echo "コメントを入力してください";
    }
    
    
?>
<!doctype html>
<html lang="ja">
    <head>
        <mate charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <form action="" method="post">
            <label>編集番号指定フォーム</label>
            <br>
            <input type="number" name="re_num" placeholder="編集対象番号">
            <input type="password" name="re_pass" placeholder="パスワード">
            <input type="submit" name="submit3" value="編集">
            <br>
            <label>コメント・編集入力フォーム</label>
            <br>
            <input type="hidden" name="post_num" value="<?php if($re_num != ""){echo $re_num;}?>">
            <input type="text" name="name" placeholder="名前" value="<?php if($re_name != ""){echo $re_name;}?>">
            <input type="text" name="comment" placeholder="コメント" value="<?php if($re_com != ""){echo $re_com;}?>">
            <input type="password" name="pass" placeholder="パスワード">
            <input type="submit" name="submit1">
            <br>
            <label>削除フォーム</label>
            <br>
            <input type="number" name="del_num" placeholder="削除対象番号">
            <input type="password" name="del_pass" placeholder="パスワード">
            <input type="submit" name="submit2" value="削除">
            <br>
        </form>
    </body>
</html>