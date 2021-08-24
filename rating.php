<?php
define('IN_MEDIA',true);
include('includes/config.php');
include('includes/functions.php');
$isLoggedIn = m_checkLogin();
if (!$isLoggedIn && !$_POST['rating']) die("<center><b>Bạn chưa đăng nhập</b></center>");

if ($_POST['rating'] && $_POST['media_id'] && $_POST['star']) {
	$id = (int)$_POST['media_id'];
	$star = (int)$_POST['star'];
	if ($isLoggedIn || !(m_get_config('must_login_to_rate'))) {
		$mysql->query("UPDATE ".$tb_prefix."data SET m_rating = m_rating+$star, m_rating_total = m_rating_total+1 WHERE m_id = $id");
	}
	$q = $mysql->query("SELECT m_rating, m_rating_total FROM ".$tb_prefix."data WHERE m_id = $id");
	$q = $mysql->fetch_array($q);
	if ($q['m_rating_total'] =='0') $current_star = 0;
	else $rater_rating = $q['m_rating'] / $q['m_rating_total'];
	
	// Assign star image
	if ($rater_rating <= 0  ){$star1 = "none"; $star2 = "none"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 0.5){$star1 = "half"; $star2 = "none"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 1  ){$star1 = "full"; $star2 = "none"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 1.5){$star1 = "full"; $star2 = "half"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 2  ){$star1 = "full"; $star2 = "full"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 2.5){$star1 = "full"; $star2 = "full"; $star3 = "half"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 3  ){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 3.5){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "half"; $star5 = "none";}
	if ($rater_rating >= 4  ){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "full"; $star5 = "none";}
	if ($rater_rating >= 4.5){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "full"; $star5 = "half";}
	if ($rater_rating >= 5  ){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "full"; $star5 = "full";}

	echo " <img id=star1 src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star1.".gif\">"
		." <img id=star2 src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star2.".gif\">"
		." <img id=star3 src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star3.".gif\">"
		." <img id=star4 src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star4.".gif\">"
		." <img id=star5 src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star5.".gif\">"
		." ( ".$q['m_rating_total']." Rates )";
	if (!$isLoggedIn && m_get_config('must_login_to_rate')) echo "<br>&nbsp; &nbsp; Bạn chưa đăng nhập";
}
?>