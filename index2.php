<!DOCTYPE html>
<html>
<head>
	<title>E-Soma System</title>
	<style>
@import url('https://fonts.googleapis.com/css?family=Quicksand&display=swap');
</style> 
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<link rel="stylesheet" type="text/css" href="fontawesome-free-5.11.2-web/css/all.min.css">

  <script src="https://checkout.flutterwave.com/v3.js"></script>

    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="313160411823-16pkglrsi7qmh8r2qrik925cq4uckr9p.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>

</head>
<?php 
session_start();
unset($_SESSION['download']);
// error_reporting(0);
?>
<div id="modal"></div>
<div id="alert">
	<i class="fa fa-times" id="alertclose"></i>
	<h1>Ihangane!</h1><br>
	<p>Ifayiri wahisemo ntago iri mubwoko bw'amafayiri twakira. <br>
		Twakira: 
		<b>.text</b>,
		<b>.doc</b>,
		<b>.docx</b>,
		<b>.xls</b>,
		<b>.xlsx</b> na
		<b>.pdf</b>
	</p>
	<span></span>
</div>
<body>
	<?php 
		require'driver/config.php';
		require'driver/funcs.php';
	 ?>
	<div class="head">
		<div class="logo">
			<img src="img/logo.png"> <h1>Isomero</h1>
		</div>
		<?php
		if(isset($_SESSION['sound'])){
		$sound = getSounds($_SESSION['sound']);
		}
		?>
		<div id="audio_controls">
			<div class="controller">
	<?php if(!isset($_SESSION['user'])){ ?>
			<i class="fa fa-file" id="triDoc" title="Shyiramo ifayiro"></i>
		<?php } ?>
			<i class="fa fa-play" title="Tangira kumva" id="start"></i>
			<i class="fa fa-pause" id="playpausebtn" style="display: none;"></i>
			<i class="fa fa-redo-alt" title="Ongera wumve" id="check2"></i>
			<div id="audio_player">
  
    <input id="seekslider" style="display: none;" type="range" min="0" max="100" value="0" step="1">
    <div id="timebox" style="display: none;">
      <span id="curtimetext">00:00</span> / <span id="durtimetext">00:00</span>
    </div>
    <span class="fa fa-volume-up"></span><input id="volumeslider" type="range" min="0" max="100" value="100" step="1">
  </div>
</div>
<h2 id="playlist_status" style="color:#FFF; display: none; "></h2>
</div>
<div class="menu">
	<?php if(!isset($_SESSION['userId'])){ ?>
	<div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
	<?php }else{
		$id= $_SESSION['userId'];
		$sql="SELECT * from users where user_id=?";
		$data = $pdo->prepare($sql);
		$data->bindValue(1,$id);
		$data->execute();
		$user = $data->fetch();
		?>
		<div class="userP">
			<img src="<?= $user['user_image']; ?>">
			<span ><span id="pro"><?= $user['user_name']; ?></span> <b class="logout">(Sign Out)</b></span>
		</div>
	<?php } ?>
	</div>
	</div>
		
	</div>
	<div class="left">
		<div class="toph"><h1><i class="fa fa-book"></i> Ibitabo</h1>
			<input type="text" class="searchbook" placeholder="Shaka Igitabo" name=""></div>
		<ul class="book">
		<?php 
	$sql="SELECT * from book";
	$query=$pdo->prepare($sql);
	$query->execute();
	// <a href="driver/download.php?download='.$book['b_id'].'" >
	foreach ($query as $book) {
		echo'<li><i class="fa fa-book" data-book="'.$book['b_id'].'"></i> <span data-book="'.$book['b_id'].'" class="read">'.$book['b_name'].'</span><b class="fa fa-download" onClick="makePayment('.$book['b_price'].','.$book['b_id'].')"  data-amount="'.$book['b_price'].'"></b></li>';
	}
	?> </ul>
	</div>
<input type="file" id="document" style="display: none;" name="">
	<div class="middle">
	<div id="textAct">
		<button id="check"><i class="fa fa-volume-up"></i> Soma</button>
		<!-- <div id="waveform" style="width: 650px;height: 60px;float: left;padding-top: 5px;margin-left: 10px;"></div> -->
		<div class="wave">
			<img src="img/sound2.gif">
		</div>
		<div class="share">
			<i class="fab fa-facebook-f"><span>Sangiza kuri facebook</span></i>
			<i class="fab fa-twitter"><span>Sangiza kuri twitter</span></i>
			<i class="fa fa-envelope"><span>Ohereza Imeli</span></i>
		</div>
	</div>
	<textarea id="word" style="display: none;"></textarea>
	<textarea id="getString"><?php 
			if(isset($_SESSION['alltext'])){echo $_SESSION['alltext'];}?></textarea>
	</div>	
	<div id="popup">
		<h2><span></span>
	<i class="fa fa-times" id="popclose"></i>
		</h2>
		<p></p>
		<?php if(isset($_SESSION['userId'])){ ?>
		<form method="post" class="form">
			<textarea name="desc" placeholder="Andika ubusobanuro hano."></textarea>
			<button>Save</button>
		</form>
	<?php } ?>
	</div>

	<div id="profile">
		<h2>Profile<span></span>
	<i class="fa fa-times" id="proclose"></i>
		</h2>
		<div class="card">
			<img src="<?= $user['user_image']; ?>">
			<span>Names: <?= $user['user_name']; ?></span>
			<span>Email: <?= $user['user_email']; ?></span>
		</div>
		<div class="books">
			<ul>
		<?php 
	$sql="SELECT * from books";
	$query=$pdo->prepare($sql);
	$query->execute();
	// <a href="driver/download.php?download='.$book['b_id'].'" >
	foreach ($query as $book) {?>
				<li>
					<i class="fa fa-book"></i> 
					<span><?= $book['b_name']; ?></span>
					<br>
					<b>Price: <?= $book['b_price']; ?></b>
					<button>Delete</button>
				</li>
				<?php } ?>
			</ul>
			<form id="bookupload" method="post">
			<label for="bookfile" class="fa fa-file"></label>
			<input type="hidden" name="upload">
				<input type="file" style="display: none;" name="file" id="bookfile">
				<input type="text" required="" placeholder="Book Name" name="name">
				<input type="text" required="" placeholder="Price" class="price" name="price">
				<button>Upload</button>
			</form>
		</div>
	</div>	
	<div class="right">
		<div class="toph"><input type="text" class="search" placeholder="Shaka ijambo" name=""></div>
		<ul class="word">
			<?php 
	$sql="SELECT * from word order by w_string asc";
	$query=$pdo->prepare($sql);
	$query->execute();
	foreach ($query as $book) {
		echo'<li><i class="fa fa-volume-up listen" data-word="'.$book['w_string'].'"></i> <span class="details" data-desc="'.$book['w_details'].'">'.$book['w_string'].'</span></li>';
	}
	?>
		</ul>
	</div>	
	<div class="footer">
		<?php echo date('Y'); ?> Allright reserved.
	</div>
</body>
</html>
<script type="text/javascript" src="js/jquery.js"></script>

<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/play.js"></script>