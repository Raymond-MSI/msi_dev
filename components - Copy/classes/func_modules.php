<?php
if((isset($property)) && ($property == 1)) {
	$version = '2.0';
	$author = 'Syamsul Arham';
} else {
	define("CR", chr(10));
	
	function LoadModule($position) {
		global $DB;
		
		//  $condition = "`position`='" . $position . "' AND `published`=1";
		$condition = "`position`='" . $position . "'";
		$order = "`ordering`";
		$gModule = $DB->get_data("cfg_modules", $condition, $order);
		$dModule = $gModule[0];
		$qModule = $gModule[1];
		if($gModule[2]>0) {
			do {
				if($dModule["published"]==1) {
					$params = get_params($dModule["params"]);
					include("components/modules/" . $dModule["module"] . ".php");
				} else {
					?>
					<div class="alert alert-danger" role="alert">
					<?php echo "Module " . $dModule["title"] . "  is disabled."; ?>
					</div>
					<?php
				}
			} while($dModule=$qModule->fetch_assoc());
		} else {
			?>
			<div class="alert alert-danger" role="alert">
			<?php echo "Position '" . $position . "'  has not been defined, please define its position."; ?>
			</div>
			<?php
		}
	}
	
	function get_params($params) {
		if($params!=NULL) {
			$params_exp = explode(CR, $params); 
			if(count($params_exp)>1) {
				if(trim($params_exp[0])=="version=3") {
					for($j=0;$j<count($params_exp);$j++) {
						$params_exp2 = explode(":", $params_exp[$j]);
						if(count($params_exp2)>1) {
							$params_exp3 = explode(";", $params_exp2[1]);
							if(count($params_exp3)>0) {
								for($k=0;$k<count($params_exp3);$k++) {
									$params_exp4 = explode("=", $params_exp3[$k]);
									$param_name[trim($params_exp2[0])][trim($params_exp4[0])] = trim($params_exp4[1]);
								}
							}
						}
					} 
				} else {
					?>
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<strong>Holy guacamole!</strong> Please convert parameters to version 3.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>
					<?php
				}
			} else {
				$param_name = $params;
			}
			return $param_name;
		}
	}
	
	function showCaption($dArticle, $params) {
		global $DB;
		if($params["caption"]["enable"]==1) {
			switch($params["caption"]["type"]) {
				case "data":
					if($params["category"]["id"]!=0) {
						$condition = "`id`=" . $params["category"]["id"];
						$gCategory = $DB->get_data("post_categories", $condition);
						$dCaption = $gCategory[0];
						if($gCategory[2]>0) {
							?>
							<div class="caption"><?php echo get_hyperlink("article_list", $dCaption["id"], $dCaption["name"]); ?></div>
							<?php
						} else {
							?>
							<div class="caption"><?php echo $dCaption["name"]; ?></div>
							<?php
						}
					}
					break;
				case "text":
					?>
					<div class="caption"><?php echo $params["caption"]["text"]; ?></div>
					<?php
				case "img":
					?>
					<?php /*?><div class="caption" style="background-image: url(media/images/banners/<?php echo $params["caption"]["img"]; ?>)"></div><?php */?>
					<?php
			}
		}
	}
				
	function showTitle ($dArticle, $params, $link="") {
		if($params["title"]["enable"]==1) {
			$title = $dArticle["post_title"];
			if($params["title"]["len"]!=0) {
				$title = substr($dArticle["post_title"], 0, $params["title"]["len"]);
			}
			if($link=="") {
				?>
				<div class="title">
				<?php
				if($params["content"]["type"]=="intro") {
					?>
					<?php echo get_hyperlink("article", $dArticle["post_id"], $dArticle["post_title"]); ?>
					<?php
				} else {
					echo $title; 
				}
				?>
				</div>
				<?php 
			} else {
				?>
				<div class="link">
				<li>
				<?php echo get_hyperlink("article", $dArticle["post_id"], $dArticle["post_title"]); ?>
				</li>
				</div>
				<?php
			}
		} 
	}
				
	function get_Article($dArticle, $params) {
		if($params["content"]["enable"]==1) {
			if($params["content"]["type"]=="intro") {
				$intro_exp = explode("<hr />", $dArticle["post_article"]);
				if($params["content"]["len"]!=0) {
					$txtArticle = strip_tags(substr($intro_exp[0], 0, $params["content"]["len"]));
				} else {
					$txtArticle = strip_tags($intro_exp[0]);
				}
			} else {
				$txtArticle = $dArticle["post_article"];
			}
			return $txtArticle;
		}
	}
	
	function showArticle($dArticle, $params) {
		?>
		<div class="post-article"><?php echo get_Article($dArticle, $params); ?></div>
		<?php
	}
	
	function showAuthor($dArticle, $params) {
		global $DB;
		if($params["author"]["enable"]==1) {
			$condition = "`email`='" . $dArticle["post_author"] . "'";
			$gAuthor = $DB->get_data("mst_users", $condition);
			$dAuthor = $gAuthor[0];
			?>
			<div class="author">
			<?php echo authorCaption; ?>
			<?php echo get_hyperlink("author", $dAuthor["email"], $dAuthor["name"]); ?>
			<?php /*?><a href="#"><?php echo $dAuthor["name"]; ?></a><?php */?>
			</div>
			<?php
		}
	}
	
	function showDateTime($dArticle, $params) {
		if($params["datetime"]["enable"]==1) {
			?>
			<div class="created"><?php echo datetimeCaption; ?><?php echo date(formatDate, strtotime($dArticle["post_date"])); ?></div>
			<?php
		}
	}
	
	function showHits($dArticle, $params) {
		if($params["hits"]["enable"]==1) {
			?>
			<div class="hits"><?php echo hitsCaption; ?><?php echo $dArticle["post_hits"]; ?></div>
			<?php
		}
	}
	
	function showCategory($dArticle, $params) {
		global $DB;
		if($params["category"]["enable"]) {
			$mysql = sprintf("SELECT `a1`.`id` AS `main_id`, `a1`.`name` AS `mainCategory`, `a2`.`id` AS `sub_id`, `a2`.`name` AS `subCategory` FROM `sa_post_categories` `a1` join `sa_post_categories` `a2` on `a1`.`id`=`a2`.`parent` WHERE `a2`.`id`=%s",
			GetSQLValueString($dArticle["post_category"], "int")
			);
			$gCategory = $DB->get_sql($mysql);
			$dCategory = $gCategory[0];
			?>
			<div class="category">
			<?php echo categoryCaption; ?>
			<?php echo get_hyperlink("article/category", $dCategory["main_id"], $dCategory["mainCategory"]) . " / "; ?>
			<?php echo get_hyperlink("article/category", $dCategory["sub_id"], $dCategory["subCategory"]); ?>
			<?php /*?><a href="#"><?php echo $dCategory["mainCategory"];?></a> / 
			<a href="#"><?php echo $dCategory["subCategory"]; ?></a><?php */?>
			</div>
			<?php
		}
	}
			
	function showTags($dArticle, $params) {
		if($params["tags"]["enable"]==1) {
			?>
			<div class="tags"><?php echo tagsCaption . " : "; ?><?php echo $dArticle["post_metakey"]; ?></div>
			<?php
		}
	}
	
	function get_hyperlink($category, $id, $title) {
		//	$link = '<a href="' . DOMAIN . 'index.php?mod=' . $category . '&id=' . $id . '">' . $title . '</a>';
		$titlex = htmlentities(str_replace(" ", ".", strtolower($title)), ENT_QUOTES);
		$link = '<a href="' . DOMAIN . $category . '/' . $id . '/' . $titlex . '">' . $title . '</a>';
		return $link;
	}
	
	function get_Image($dArticle, $params) {
		$intro = explode("<hr />", $dArticle["post_article"]);
		$findImage = explode("src=\"", $intro[0]);
		$srcImage = "";
		if(count($findImage)>1) {
			$findImage2 = explode("\"", $findImage[1]);
			$srcImage = $findImage2[0];
		}
		return $srcImage;
	}
	
	function useraccess($modulename) {
		global $DB;
		$username = $_SESSION['Microservices_UserLogin'];
		$condition = "username='" . $username . "' AND title='" . $modulename . "' AND user_level != 'Non Member'";
		$tblname = 'view_user_access';
		$user_level = $DB->get_data($tblname, $condition);
		$duserlevel = $user_level[0];
		if($_SESSION['Microservices_UserLevel']=="Administrator" || $_SESSION['Microservices_UserLevel']=="Super Admin") {
			define("USERPERMISSION", MD5("Administrator"));
		} elseif($user_level[2]>0) {
			define("USERPERMISSION", MD5($duserlevel['user_level']));
		} else {
			define("USERPERMISSION", "");
		}
	}
	
	function useraccess_v2($config_key) {
		global $DB;
		if($_SESSION['Microservices_UserLevel']=="Administrator" || $_SESSION['Microservices_UserLevel']=="Super Admin") {
			$condition = "config_key='MODULE_" . $config_key . "'";
			$tblname = "cfg_web";
			$module = $DB->get_data($tblname, $condition);
			define("USERPERMISSION_V2", MD5($module[0]['config_key']));
			$mdlname = $module[0]['config_key'];
			$userlevel = "Administrator";
			$mdltitle = $module[0]['title'];
		} else {
			$condition = "username='" . $_SESSION['Microservices_UserLogin'] . "' AND config_key='MODULE_" . $config_key . "' AND `status`=1";
			$tblname = 'view_user_access';
			$user_level = $DB->get_data($tblname, $condition);
			if($user_level[2]>0) {
				define("USERPERMISSION_V2", MD5($user_level[0]['config_key']));
				$userlevel = $user_level[0]['user_level'];
				$mdlname =  $user_level[0]['config_key'];
				$mdltitle = $user_level[0]['module_name'];
			} else {
				define("USERPERMISSION_V2", "");
				$userlevel = "";
				$mdlname = "";
				$mdltitle = "";
			}
		}
		return array("mdlname"=>$mdlname, "mdltitle"=>$mdltitle, "mdllevel"=>$userlevel);
	}
			
}
			