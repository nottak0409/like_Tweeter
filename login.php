<?php
require('dbconnect.php');
require('function.php');

session_start();

if ($_COOKIE['email' != '']){
	$_POST['email'] = $_COOKIE['email'];
	$_POST['password'] = $_COOKIE['password'];
	$_POST['save'] = 'on';
}

if(!empty($_POST)) {
	if($_POST['email'] != '' && $_POST['password'] != '') {
		$login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
		$login->execute(array(
			$_POST['email'],
			sha1($_POST['password'])
		));
		$member = $login->fetch();

	if($member) {
		$_SESSION['id'] = $member['id'];
		$_SESSION['time'] = time();

		if ($_POST['save'] == 'on') {
			setcookie('email', $_POST['email'], time()+60*60*24*14);
			setcookie('password', $_POST['password'], time()+60*60*24*14);
		}

		header('Location: index.php');
		exit();
	} else {
		$error['login'] = "ログインに失敗しました";
	   }
  } else {
		$error['login'] = "入力してください";
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="join/style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ログインする</h1>
  </div>
  <div id="content">
  <div id="lead">
  <p>メールアドレスとパスワードを記入してログインしてください。</p>
	<p>入会手続きがまだの方はこちらからどうぞ。</p>
	<p>&raquo;<a href="join/">入会手続きをする</a></p>
	</div>
	<form action="" method="post">
		<dl>
			<dt>メールアドレス</dt>
			<dd>
			<input type="text" name="email" size="35" maxlength="255" value="<?php echo h($_POST['email']); ?>"/>
			<?php if (isset($error['login'])): ?>
			<p class="error"><?php echo $error['login']; ?></p>
   		<?php endif; ?>
			</dd>
			<dt>パスワード</dt>
			<dd>
			<input type="text" name="password" size="35" maxlength="255" value="<?php echo h($_POST['password']); ?>"/>
			<?php if (isset($error['login'])): ?>
			<p class="error"><?php echo $error['login']; ?></p>
			<?php endif; ?>
			</dd>
			<dt>ログイン情報の記録</dt>
			<dd>
			<input id="save" type="checkbox" name="save" value="on" /><lavel for="save">
			次回からは自動的にログインする</lavel>
			</dd>
		</dl>
		<div><input type="submit" value="ログインする"  /></div>
	</form>
  </div>

</div>
</body>
</html>
