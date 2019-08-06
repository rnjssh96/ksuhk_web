<?php

	$env_page = "page_memberinfo";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 회원정보</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container mb-0 my-0 my-lg-5 mb-sm-3 p-0">
	<div class="row w-100 m-0">
		<div class="col-lg-3 offset-lg-1 p-0 p-sm-3">
			<div class="card">
				<h5 class="card-header">회원정보</h5>
				<div class="list-group list-group-flush">
					<a href="/memberinfo" class="list-group-item list-group-item-action active">회원정보 수정</a>
					<a href="/memberinfo/password" class="list-group-item list-group-item-action">비밀번호 변경</a>
					<a href="/memberinfo/deleteaccount" class="list-group-item list-group-item-action">계정탈퇴</a>
				</div>
			</div>
		</div>
		<div class="col-lg-7 mt-0 mt-sm-3 mt-lg-0 p-0 p-sm-3">
<?php
		include $WEB_ROOT.'/classes/UserDBManager.php';
		$user_db = UserDBManager::createUserDBManager();

		if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
			if( isset($_POST["submit"]) ) {
				$check = $user_db->check_user_login( $_SESSION["user_username"], hash("sha256", $_POST["UserPassword"]) );
				if( $check != UserDBManager::$PASSWORD_INCORRECT ) {
					if( $user_db->update_user_byusername( $_SESSION["user_username"], $_POST["UserSchool"], $_POST["UserGrade"], $_POST["UserMajor"],
								$_POST["UserPhoneNo"], $_POST["UserKakaoID"] ) === UserDBManager::$UPDATE_ERROR ) {
						include $WEB_ROOT.'/templates/popup-message.php';
						echo '<script>createAlert("회원정보 수정중 오류가 발생했습니다.", "알림!");</script>';
					} else {
						echo '<script>alert("회원정보 수정이 정상적으로 요청되었습니다.");</script>';
						echo "<script>document.location='/login/logout.php';</script>";
					}
				}
			}

			$user = $user_db->select_user_byusername( $_SESSION["user_username"] );
			if( $user ) {
				include $WEB_ROOT.'/templates/popup-message.php';
				include $WEB_ROOT.'/templates/memberinfoedit.php'; 
			} else {
				include $WEB_ROOT.'/templates/status.php';
				ERROR_PAGE( "현재 서버문제로 회원정보를 불러올 수 없습니다." );
			}

		} else {
			include $WEB_ROOT.'/templates/status.php';
			ERROR_PAGE( "현재 서버문제로 회원정보를 불러올 수 없습니다." );
		}
?>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
