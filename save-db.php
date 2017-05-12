<?php

	/*
			DB-Toolkit v 0.1
			Written by kod0kk
			fb.me/reyvand.cz
	*/

	error_reporting(0);
	class Kit {

		function __construct() {
			if(PHP_OS !== 'Linux') {
				die("This script must be run in GNU/Linux Operation System, sorry about that\n");
			}	else {
				echo $this->colorize('green',"[!] DB-Toolkit beta 0.1 [!]\n\n");
				if(function_exists('mysqli_init') && extension_loaded('mysqli')) {
					($_SERVER['argc'] == 1) ? $this->getHelp() : $this->main();
				} else {
					die("No extension loaded for php-mysql, please install it first by using\n".$this->colorize('yellow',"sudo apt-get install php-mysql")." \n\n");
				}
			}
		}
		function getParamValue($param) {
			$search = array_search($param, $_SERVER['argv']);
			return $_SERVER['argv'][$search+1];
		}
		function main() {
			if(array_search('-help', $_SERVER['argv'])) {
				$this->helpInfo();
			} elseif(array_search('-show', $_SERVER['argv'])) {
				$this->printAllDB();
			} elseif(array_search('-save', $_SERVER['argv']) || array_search('-save-all', $_SERVER['argv'])) {
				$this->backup();
			} else {
				$this->getHelp();
			}
		}
		function colorize($color,$text) {
			$avl_color = array('green' => "\e[92m", 'red' => "\e[91m", 'yellow' => "\e[93m", 'blue' => "\e[94m", 'cyan' => "\e[96m");
			return $avl_color[''.$color.''].$text."\e[39m";
		}
		function getHelp() {
			echo $this->colorize('red',"[x] invalid options, use -help for help\n\n");
		}
		function helpInfo() {
			echo $this->colorize('yellow',"\n[?] usage : php ".basename(__FILE__)." <options>\n\n")."\n".$this->colorize('yellow','[?] available options :')." \n\n  ".$this->colorize('yellow','-help')."\t display this message\n  ".$this->colorize('yellow','-h')."\t set MySQL host\n  ".$this->colorize('yellow','-u')."\t set MySQL user\n  ".$this->colorize('yellow','-p')."\t set MySQL pass\n  ".$this->colorize('yellow','-show')."\t show all available database in server\n  ".$this->colorize('yellow','-save <db_name>')."  save selected database [separated by comma]\n  ".$this->colorize('yellow','-save-all')."  save all available database in server\n  ".$this->colorize('yellow','-zip')."\t compress saved database into zip file [coming soon]\n\n".$this->colorize('yellow',"[?] example : \n\n")." ".$this->colorize('yellow',"$ php ".basename(__FILE__)." -h host -u user -p pass -show")."  [check all database]\n ".$this->colorize('yellow',"$ php ".basename(__FILE__)." -h host -u user -p pass -save my_db")."  [save 1 database]\n ".$this->colorize('yellow',"$ php ".basename(__FILE__)." -h host -u user -p pass -save my_db1,my_db2")."  [multiple db]\n ".$this->colorize('yellow',"$ php ".basename(__FILE__)." -h host -u user -p pass -save-all")."  [all available database]\n\n";
		}
		function login($host,$user,$pass) {
			$link = new mysqli($host,$user,$pass) or die($this->colorize('red',"Cannot connect to database server, please check your credentials\n"));
			if($link->connect_error) {
				echo $this->colorize('red',"Cannot connect to database server, please check your credentials\n\n");
			} else {
				$z = $link;
			} return $z;
		}
		function getAllDB() {
			$link = $this->login($this->getParamValue('-h'),$this->getParamValue('-u'),$this->getParamValue('-p'));
			$query = $link->query("SHOW DATABASES");
			$all_db = array();
			if($query->num_rows > 0) {
				while($r = $query->fetch_assoc()) {
					array_push($all_db, $r['Database']);
				}
			} return $all_db;
		}
		function printAllDB() {
			$vardb = $this->getAllDB();
			if(!empty($vardb)) {
				echo $this->colorize('green',"[+] Founded Database(s) on this server : \n\n");
				foreach ($vardb as $db) {
					echo $this->colorize('blue',"  [-] ".$db."\n");
				} echo "\n";
			} else {
				echo $this->colorize('red',"[x] No database found on this server\n\n");
			}
		}
		function backup() {
			$path = (empty(exec('which mysqldump'))) ? '/opt/lampp/bin/' : '';
			if(array_search('-save', $_SERVER['argv'])) {
				if(strpos($this->getParamValue('-save'), ',') != false) {
					$av = explode(',', $this->getParamValue('-save'));
				} else {
					$av = $this->getParamValue('-save');
				} 
				$filename = $av;
				if(!is_array($av)) {
					$backup = exec($path."mysqldump -h ".$this->getParamValue('-h')." -u ".$this->getParamValue('-u')." -p'".$this->getParamValue('-p')."' ".$av." > ".$av.".sql");
					echo $this->colorize('green',"[+] Database ".$db." has been saved into ".$db.".sql\n\n");
				} else {
					foreach ($av as $db) {
						$backup =  exec($path."mysqldump -h ".$this->getParamValue('-h')." -u ".$this->getParamValue('-u')." -p'".$this->getParamValue('-p')."' ".$db." > ".$db.".sql");
						echo $this->colorize('green',"[+] Database ".$db." has been saved into ".$db.".sql\n\n");
					}
				}
			} elseif(array_search('-save-all', $_SERVER['argv'])) {
					$all = $this->getAllDB();
					$filename = $all;
					foreach ($all as $db) {
						$backup =  exec($path."mysqldump -h ".$this->getParamValue('-h')." -u ".$this->getParamValue('-u')." -p'".$this->getParamValue('-p')."' ".$db." > ".$db.".sql");
						echo $this->colorize('green',"[+] Database ".$db." has been saved into ".$db.".sql\n\n");
				}
			}
		}
	} $db = new Kit;
