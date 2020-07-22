<?php
session_start();
require('../dbconnect.php');

if(!empty($_POST)){
	if($_POST['name'] == "") {
		$error['name'] = "入力してください";
	}
	if($_POST['email'] == "") {
		$error['email'] = "入力してください";
	}
	if(strlen($_POST['password']) < 4) {
		$error['password'] = "4文字以上で入力してください";
	}
	if($_POST['password'] == ""){
		$error['password'] = "入力してください";
	}
	$fileName = $_FILES['image']['name'];
	if(!empty($fileName)) {
		$ext = substr($fileName, -3);
		if ($ext != 'jpg' && $ext != 'gif') {
			$error['image'] = 'jpgかgifの画像ファイルを選択してください。';
		}
	}

  if (empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = '指定されたメールアドレスは既に登録されています。';
		}
	}
	if (empty($error)) {
		$_SESSION['join'] = $_POST;
		if (isset($fileName)) {
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], './member_picture/' . $image);
		$_SESSION['join']['image'] = $image;
	  }
		header('Location: check.php');
		exit();
	}
	if ($_REQUEST['action'] == 'rewrite') {
		$_POST = $_SESSION['join'];
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

	<link rel="stylesheet" href="style.css"/>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>会員登録</h1>
  </div>
  <div id="content">
  <p>次のフォームに必要事項をご記入ください</p>
	<form action="" method="post" enctype="multipart/form-data">
		<dl>
			<dt>ニックネーム<span class="required">必須</span></dt>
			<dd><input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" />
			<?php if (isset($error['name'])): ?>
			<p class="error"><?php echo $error['name']; ?></p>
		  <?php endif; ?>
		  </dd>
			<dt>メールアドレス<span class="required">必須</span></dt>
			<dd><input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>"/>
			<?php if (isset($error['email'])): ?>
			<p class="error"><?php echo $error['email']; ?></p>
			<?php endif; ?>
			</dd>
			<dt>パスワード<span class="required">必須</span></dt>
			<dd><input type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>"/>
			<?php if (isset($error['password'])): ?>
			<p class="error"><?php echo $error['password']; ?></p>
			<?php endif; ?>
			</dd>
			<dt>写真など</dt>
			<dd><input type="file" name="image" size="35" />
      <?php if(isset($error['image'])): ?>
			<p class="error"><?php echo $error['image']; ?></p>
		  <?php endif; ?>
			<?php if (!empty($error) && isset($_POST['image'])) : ?>
			<p class="error">恐れ入りますが、もう一度画像を指定してください</p>
		  <?php endif; ?>
			</dd>
		</dl>
		<div><input type="submit" value="入力内容を確認する" /></div>
	</form>
  </div>

</div>
</body>
</html>
