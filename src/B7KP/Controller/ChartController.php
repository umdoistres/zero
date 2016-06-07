<?php
namespace B7KP\Controller;

use B7KP\Model\Model;
use B7KP\Utils\UserSession;
use B7KP\Utils\Snippets;
use B7KP\Utils\Charts;
use B7KP\Entity\User;
use B7KP\Entity\Settings;
use B7KP\Entity\Week;
use B7KP\Core\Dao;
use LastFmApi\Main\LastFm;

class ChartController extends Controller
{
	
	function __construct(Model $factory)
	{
		parent::__construct($factory);
	}

	/**
	* @Route(name=chart_list|route=/user/{login}/charts)
	*/
	public function chartsIndex($login)
	{
		$user = $this->factory->findOneBy("B7KP\Entity\User", $login, "login");
		if($user instanceof User)
		{
			$numberones = array();
			$cond = array("iduser" => $user->id);
			$weeks = $this->factory->find("B7KP\Entity\Week", $cond, "week DESC", "0, 5");
			$i = 0;
			foreach ($weeks as $week) 
			{
				$numberones[$i]["week"] = $week->week;
				$from = new \DateTime($week->from_day);
				$numberones[$i]["from"] = $from->format("Y.m.d");
				$to = new \DateTime($week->to_day);
				$to->modify('-1 day');
				$numberones[$i]["to"] = $to->format("Y.m.d");
				$cond = array("idweek" => $week->id);
				$numberones[$i]["album"]  = $this->factory->find("B7KP\Entity\Album_charts", $cond, "updated DESC, rank ASC", "0, 1");
				$numberones[$i]["artist"] = $this->factory->find("B7KP\Entity\Artist_charts", $cond, "updated DESC, rank ASC", "0, 1");
				$numberones[$i]["music"]  = $this->factory->find("B7KP\Entity\Music_charts", $cond, "updated DESC, rank ASC", "0, 1");
				$i++;
			}
			$var = array
					(
						"weeks" => $numberones,
						"user" => $user
					);
			$this->render("mainchart.php", $var);
		}
		else
		{
			$this->redirectToRoute("registernotfound", array("entity" => "user"));
		}
	}

	/**
	* @Route(name=full_chart_list|route=/user/{login}/charts/list)
	*/
	public function fullCharts($login)
	{
		$user = $this->factory->findOneBy("B7KP\Entity\User", $login, "login");
		if($user instanceof User)
		{
			$numberones = array();
			$cond = array("iduser" => $user->id);
			$weeks = $this->factory->find("B7KP\Entity\Week", $cond, "week DESC");
			$i = 0;
			foreach ($weeks as $week) 
			{
				$numberones[$i]["week"] = $week->week;
				$from = new \DateTime($week->from_day);
				$numberones[$i]["from"] = $from->format("Y.m.d");
				$to = new \DateTime($week->to_day);
				$to->modify('-1 day');
				$numberones[$i]["to"] = $to->format("Y.m.d");
				$cond = array("idweek" => $week->id);
				$numberones[$i]["album"]  = $this->factory->find("B7KP\Entity\Album_charts", $cond, "updated DESC, rank ASC", "0, 1");
				$numberones[$i]["artist"] = $this->factory->find("B7KP\Entity\Artist_charts", $cond, "updated DESC, rank ASC", "0, 1");
				$numberones[$i]["music"]  = $this->factory->find("B7KP\Entity\Music_charts", $cond, "updated DESC, rank ASC", "0, 1");
				$i++;
			}
			$var = array
					(
						"weeks" => $numberones,
						"user" => $user
					);
			$this->render("chartlist.php", $var);
		}
		else
		{
			$this->redirectToRoute("registernotfound", array("entity" => "user"));
		}
	}

	/**
	* @Route(name=weekly_chart|route=/user/{login}/charts/{type}/week/{week})
	*/
	public function weeklyChart($login, $type, $week)
	{
		$user = $this->isValidUser($login);
		$this->isValidType($type, $user);
		if(is_numeric($week))
		{
			$settings = $this->factory->findOneBy("B7KP\Entity\Settings", $user->id, "iduser");
			$week = $this->factory->find("B7KP\Entity\Week", array("iduser" => $user->id, "week" => $week), "week DESC");
			if(is_array($week) && count($week) > 0)
			{
				$limit = 10;
				if($settings instanceof Settings)
				{
					$prop = substr($type, 0, 3) . "_limit";
					$limit = $settings->$prop;
				}
				$week = $week[0];
				$chart = new Charts($this->factory, $user);
				$list = $chart->getWeeklyCharts($week, $type, $limit, true, $settings);
				$vars = array('user' => $user, 'list' => $list, 'type' => $type, 'week' => $week);
				$this->render("chart.php", $vars);
			}
			else
			{
				$this->redirectToRoute("registernotfound", array("entity" => "Week"));
			}
		}
		else
		{
			$this->redirectToRoute("chart_list", array("login" => $user->login));
		}
	}

	/**
	* @Route(name=bwp|route=/user/{login}/charts/{type}/overall/bwp)
	*/
	public function biggestWeeklyPlaycounts($login, $type)
	{
		$user = $this->isValidUser($login);
		$this->isValidType($type, $user);
		$entity = "B7KP\Entity\\".ucfirst($type)."_charts";
		$table  = $type."_charts";
		$biggest = $this->factory->findSql($entity, "SELECT t.* FROM ".$table." t, week w, user u WHERE t.idweek = w.id AND w.iduser = u.id AND u.id = ".$user->id." ORDER BY t.playcount DESC, w.week ASC LIMIT 0, 100");
		$vars = array("user" => $user, "list" => $biggest, "type" => $type);
		$this->render("bwp.php", $vars);
	}

	/**
	* @Route(name=mwa|route=/user/{login}/charts/{type}/overall/mwa/top/{rank})
	*/
	public function moreWeeksAt($login, $type, $rank)
	{
		$user = $this->isValidUser($login);
		$this->isValidType($type, $user);
		$settings = $this->factory->findOneBy("B7KP\Entity\Settings", $user->id, "iduser");
		$rank = intval($rank) > 1 ? intval($rank) : 1;
		$limit = substr($type,0,3)."_limit";
		if($rank > $settings->$limit)
		{
			$rank = $settings->$limit;
		}
		$table  = $type."_charts";
		$dao = Dao::getConn();
		$group = "";
		if($type != "artist"): $group .= ", t.".$type; endif;
		$biggest = $dao->run("SELECT t.*, count(w.week) as total FROM ".$table." t, week w, user u WHERE t.idweek = w.id AND w.iduser = u.id AND u.id = ".$user->id." AND t.rank <= ".$rank." GROUP BY t.artist".$group." ORDER BY total DESC, w.week ASC");
		$vars = array("user" => $user, "list" => $biggest, "type" => $type, "rank" => $rank, "settings" => $settings);
		$this->render("mwa.php", $vars);
	}

	/**
	* @Route(name=mia|route=/user/{login}/charts/artist/overall/more/{type}/at/{rank})
	*/
	public function moreItemsAt($login, $type, $rank)
	{
		$user = $this->isValidUser($login);
		$this->isValidType($type, $user);
		$settings = $this->factory->findOneBy("B7KP\Entity\Settings", $user->id, "iduser");
		if($type == "artist")
		{
			$this->redirectToRoute("chart_list", array("login" => $user->login));
		}
		$rank = intval($rank) > 1 ? intval($rank) : 1;
		$limit = substr($type,0,3)."_limit";
		if($rank > $settings->$limit)
		{
			$rank = $settings->$limit;
		}
		$table  = $type."_charts";
		$dao = Dao::getConn();

		$col = "t.".$type;
		$biggest = $dao->run("SELECT t.*, count(".$col.") as total, COUNT(DISTINCT ".$col.") AS uniques FROM ".$table." t, week w, user u WHERE t.idweek = w.id AND w.iduser = u.id AND u.id = ".$user->id." AND t.rank <= ".$rank." GROUP BY t.artist ORDER BY uniques DESC, total DESC");
		$vars = array("user" => $user, "list" => $biggest, "type" => $type, "rank" => $rank, "settings" => $settings);
		$this->render("mia.php", $vars);
	}

	protected function checkAccess()
	{
		if(UserSession::getUser($this->factory) == false)
		{
			$this->redirectToRoute("login");
		}
	}

	protected function isValidType($type, $user)
	{
		if($type != "artist" && $type != "music" && $type != "album")
		{
			$this->redirectToRoute("chart_list", array("login" => $user->login));
		}
	}

	protected function isValidUser($login)
	{
		$user = $this->factory->findOneBy("B7KP\Entity\User", $login, "login");
		if($user instanceof User)
		{
			return $user;
		}
		else
		{
			$this->redirectToRoute("404");
		}
	}
}
?>