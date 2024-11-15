<?php
// home.php
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>Friender</title>
    <link rel="stylesheet" href="home.css">
</head>

<body>

<!-- ////////ヘッダー（メニューバー）/////////////// -->
    <div class="header">    
         
        <a><img src="home.png"  alt="ロゴ"  class="header-logo" ></a>
            
        <nav class="header-list">
            <ul>
            <li><a href="registar.php" class="home" >アカウント作成</a></li>
            <li><a href="login.php" class="home">ログイン</a></li>
            </ul>
        </nav>
    </div>
    

<!-- ////////////<1>////////////////// -->


<img src="home-1.jpg" alt="最初の写真"  class="home-1">

<!-- ///////////特徴>////////////// -->
<h1>Frienderの特徴</h1>
<p class="subt">Features</p>
<img src="tokutyo.png" alt="特徴" class="tokutyo">
<img src="tokutyo-1.png" alt="" class="media-tokutyo">
<img src="tokutyo-2.png" alt="" class="media-tokutyo">
<img src="tokutyo-3.png" alt="" class="media-tokutyo">



<!-- ///////////安心・安全>////////////// -->

<h1 >安心・安全ガイド</h1>
<p class="subt">Safety and security</p>

    <div class="safe">
        <div>
            <img src="kojinn.png" alt="個人情報"  class="safe_img">
        </div>

            <div class="safe_txt">
                <h2>ー個人情報に注意ー</h2>
                <p>細かい個人情報は教えない！</br>
                    住所やクレジットカード番号などの詳細な個人情報を聞いてくる人には注意をする。 <br>
                    信頼できる人と分かるまでは、SNSを交換しない。
                </p>
            </div>
    </div>

    <div class="safe">
        <div>
            <img src="hanzai.png" alt="犯罪"  class="safe_img">
        </div>
            <div class="safe_txt">
                <h2>ー犯罪に注意ー</h2>
                <p>マルチ商法や、勧誘に気を付けましょう！</br>
                    商品の紹介やアルバイトの勧誘など、<br>
                    不信に思われるメッセージにはサイトの運営に報告する。
                </p>
            </div>
    </div>

    <div class="safe">
        <div>
            <img src="missitu.png" alt="密室"  class="safe_img">
        </div>
            <div class="safe_txt">
                <h2>ー密室で会わないー</h2>
                <p>信頼できるまでは人の多いところで会いましょう!</br>
                    カラオケ、ネットカフェ、車内などは避け、<br>
                    人の多い場所で会う。
                </p>
            </div>
    </div>
        
    <div class="safe">
        <div>
            <img src="honnin.png" alt="本人確認"  class="safe_img">
        <div>
            <div class="safe_txt">
                <h2>ー本人確認が必須ですー</h2>
                <p>安心・安全にご利用いただくために学生証による</br>
                    本人確認が必須です。
                    
                </p>
            </div>
    </div>



<!-- /////////////<Q＆A/////// -->

<h1>よくある質問</h1>

    <p class="subt">Quession & Answer<p>

    <div class="QandA">

        <div class="QA_txt">
            <h2 class="QA">Q何歳から利用できますか？</h2>
                <p class="QA">A18歳からご利用いただけます。
                </p>
        </div>

        <div class="QA_txt">
            <h2 class="QA">Q誰でも登録できますか？</h2>
                <p class="QA">A日本工学院八王子専門学校の生徒のみ登録いただけます。
                    <br><span>(先生はご利用できません)</span>
                </p>
        </div>

        <div class="QA_txt">
            <h2 class="QA">Q無料で利用できますか？</h2>
                <p class="QA">A本サイトは無料でご利用いただけます。
                </p>
        </div>

        <div class="QA_txt">
            <h2 class="QA">Qスマホでも利用できますか？</h2>
                <p class="QA">
                    Aしたいです。
                </p>
        </div>


    </div>



<!-- /////////////<フッダー>/////////// -->




  <footer class="p-footer">

    <div class="p-footer__inner">

      <div class="p-footer__logo">
        <a><img src="home.png"  alt="ロゴ"  class="header-logo" ></a>
      </div>

      <div class="p-footer__nav">
      <ul class="p-footer__navList">
          <li class="p-footer__navItem">
           Friender
          </li>
          <li class="p-footer__navItem">
            ガイドライン
          </li>
          <li class="p-footer__navItem">
            プライバシーポリシー
          </li>
        </ul>
        <div class="aa">
        <ul class="p-footer__navList" >
          <li class="p-footer__navItem">
           ヘルプ
          </li>
          <li class="p-footer__navItem">
          お問い合わせ
          </li>
        </ul>
        </div>
       </div>
    </div>
    <small class="p-footer__copyLight">© 2024 All Rights Reserved.</small>
  </footer>












</body>
</html>