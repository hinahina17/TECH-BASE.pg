<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>mission5-01</title>
</head>
<body>
    <?php
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


        //送信が押されたとき
        if(isset($_POST["submit"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){     //送信ボタンを押す＆名前とコメント＆パスワードを記入しているとき
            $password = $_POST["pass"];   //パスワード
            $name = $_POST["name"];       //名前
            $comment = $_POST["comment"]; //コメント
            $date = date("Y/m/d H:i:s");  //日付

            //編集のとき(隠しフォームに番号があるとき)
            if(!empty($_POST["enum"])){ 
                $id = $_POST["enum"]; //変更する投稿番号
                $sql = 'UPDATE board SET name=:name,comment=:comment,password=:password,date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->execute();
            }else{ //新規投稿のとき
                $sql = $pdo -> prepare("INSERT INTO board (name,comment,password,date) VALUES (:name,:comment,:password,:date)" );
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                $sql -> execute();
            }

        }

        //削除のとき
        if(isset($_POST["reset"]) && !empty($_POST["del"]) && !empty($_POST["delpass"])){      //削除ボタンを押す＆削除番号＆パスワードを記入しているとき
            $id = $_POST["del"];  //削除する投稿番号
            $password = $_POST["delpass"];  //パスワード
            $sql = 'delete from board where id=:id and password=:password'; //idとpasswordが一致するものをboardから削除
            $stmt = $pdo->prepare($sql);                                     //sql発行
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);                     //id変数をsplに代入？
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);         //password変数をsplに代入？
            $stmt->execute();                                               //phpで受け取る？
        }

        //編集のとき
        if(isset($_POST["edit"]) && !empty($_POST["editnum"]) && !empty($_POST["editpass"])){   //編集ボタンを押す＆編集番号＆パスワードを記入しているとき
            $id = $_POST["editnum"]; //変更する投稿番号
            $password = $_POST["editpass"];  //パスワード
            $sql = 'SELECT * FROM board WHERE id=:id AND password=:password';  //idとpasswordが一致するものをboardから選択
            $stmt = $pdo->prepare($sql);              //sql発行
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);     //id変数をsplに代入？
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);    //password変数をsplに代入？
            $stmt->execute();                                             //phpで受け取る？
            $results = $stmt->fetchAll();      //resultに実行内容を全て代入
            foreach ($results as $row){
                $enum = $id;                    //編集番号→隠しフォームに送る
                $ename = $row['name'];          //編集する名前→フォームに送る
                $ecome = $row['comment'];      //編集するコメント→フォームに送る
            }
        }
    ?>

    <div align="center">
        <img src="https://img.imageimg.net/artist/genic/img/member_1002097.jpg" alt="宇井優良梨" width="10%" height="10%">
    </div>
    <form action="" method="post">
        <input type="text" placeholder="名前" name="name" value="<?php if(isset($ename)) {echo $ename;} ?>"><br>          <!--名前フォーム作成-->
        <input type="text" placeholder="コメント" name="comment" value="<?php if(isset($ecome)) {echo $ecome;} ?>"><br>       <!--コメントフォーム作成-->
        <input type="text" placeholder="パスワード" name="pass">                                                           <!--パスワード-->
        <input type="submit" value="送信する" name="submit"><br><br>   
        
        <input type="number" placeholder="削除番号" name="del"><br>                                                         <!--削除番号フォーム作成-->
        <input type="text" placeholder="パスワード" name="delpass">                                                           <!--パスワード-->
        <input type="submit" value="削除する" name="reset"><br><br>                                                         <!--削除ボタン--><!--送信ボタン-->
    
        <input type="number" placeholder="編集番号" name="editnum"><br>                                                     <!--編集番号フォーム作成-->
        <input type="text" placeholder="パスワード" name="editpass">                                                           <!--パスワード-->
        <input type="submit" value="編集する" name="edit"><br>                                                           <!--編集ボタン-->

        <input type="hidden" name="enum" value="<?php if(isset($enum)) {echo $enum;} ?>"><br>                         <!--編集している番号（編集モードか判断）-->
    </form>   
    <p>好きな曲を教えて下さい! そして可能であれば推しを見てほしい...<br></p>

    <?php
        $sql = 'SELECT * FROM board';          //テーブル(board)を選択
        $stmt = $pdo->query($sql);             //sql発行
        $results = $stmt->fetchAll();         //resultに実行内容を全て代入
        foreach ($results as $row){           //繰り返して表示
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            echo "<hr>";
        }
    ?>
    
</body>
</html>