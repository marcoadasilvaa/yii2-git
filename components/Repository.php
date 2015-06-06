<?php
/**
 * Represents a git repository for interaction with git.
 */

namespace markmarco16\git\components;

use yii\base\Component;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;
use Yii;

class Repository extends Component {

	public $repository;
	
	protected $cotainerPath;

	protected $repositoryPath;
	
	//The list of project in cotainerPath
	protected $repositoriesList = array();

	//Longitud maxima de mensaje resumen
	protected $subjectMaxLength;

	//Formato de la fecha corta
	protected $datetimeFormat;
	
	//Prefijo de ejecutable GIT
	protected $gitPath = "git";

	/**
	 * Construct
	 */
	public function __construct($repository = null)	{
		$this->cotainerPath = Yii::$app->getModule('git')->gitDir;
		$this->datetimeFormat = Yii::$app->getModule('git')->datetimeFormat;
		$this->subjectMaxLength = Yii::$app->getModule('git')->subjectMaxLength;
		if ($repository == null) {
			$this->setRepositoryList();
		} else {
			$this->setRepositoryPath($repository);
		}
	}

	/**
	 * Search repositories on the specified path
	 */
	public function setRepositoryList() {
		if (is_dir($this->cotainerPath) && ($dir = opendir($this->cotainerPath))) {
        	while (($file = readdir($dir)) !== false) {
        		if (filetype($this->cotainerPath . $file) == "dir") {
        			if (file_exists($this->cotainerPath.$file."/HEAD")) {
        				$this->repositoriesList[] = array_merge(
        					array(
        						'dir'=> $file, 
        						'name' => substr($file, 0,-4),
        						'description'=> file_get_contents($this->cotainerPath.$file.'/description')
        					),
        					$this->getRevListHashDetail("--all",$file)
        				);
        			} elseif (file_exists($this->cotainerPath.$file."/.git/HEAD")) {
        				$this->repositoriesList[] = array_merge(
        					array(
        						'dir'=> $file, 
	        					'name' => $file,
        						'description'=> file_get_contents($this->cotainerPath.$file.'/.git/description')
        					),
        					$this->getRevListHashDetail("--all",$file."/.git")
        				);
        			} else {
        				continue;
        			}
        		}
        	}
        	closedir($dir);
		} else {
			throw new NotFoundHttpException("La ruta base para repositorios GIT: $this->cotainerPath, no es un directorio o no posee repositorios.");
		}
	}

	/**
	 * return project list
	 */
	public function getRepositoriesList() {
    	return $this->repositoriesList;
	}

	/**
	 * Sets the path to the git repository folder.
	 */
	public function setRepositoryPath($repository) {
		$realPath = realpath($this->cotainerPath.$repository); 
		if ((file_exists($realPath."/HEAD")) || (file_exists($realPath."/.git/HEAD"))) {
			if (file_exists($realPath."/.git/HEAD")) 
				$realPath .= "/.git";
			$this->repository=$repository;
			$this->repositoryPath = $realPath;
		} else {
			throw new NotFoundHttpException("La ruta especificada no existe o no es un repositorio git.");
		}
	}

	/**
	 * Lists commit objects in reverse chronological order
	 */
	public function getRevList($start = 'HEAD', $skip = 0, $max_count = null) {
		$cmd = "rev-list ";
		if ($skip != 0) {
			$cmd .= "--skip=$skip ";
		}
		if (!is_null($max_count)) {
			$cmd .= "--max-count=$max_count ";
		}
		$cmd .= $start;
		$result = array();
		$result = $this->run_git($cmd);
		$commitsList = array();
		foreach ($result as &$hash) {
 		   $commitsList[] = $this->getRevListHashDetail($hash);
		}
		return $commitsList;
	}

	/**
	 * Obtiene la informacion detallada de un commit
	 */
	public function getRevListHashDetail($hash = 'HEAD', $repository = null) {
		$pattern = '/^(author|committer) ([^<]+) <([^>]*)> ([0-9]+) (.*)$/';
		$info = array();
		$info['h'] = $hash;
		$info['subject'] = '(No Message)';
		$info['message'] = '';
		$info['author_datetime'] = null;
		$info['parents'] = array();
		$info['rev'] = $this->getNameRev($hash);
		$output = $this->run_git("rev-list --date=raw --pretty=format:'tree %T %nparent %P %nauthor %an <%ae> %ad %ncommitter %cn <%ce> %cd %nsubject %s %n%B ' --max-count=1 $hash", $repository);
		foreach ($output as $line) {
			if (substr($line, 0, 7)=='commit ') {
				$info['h'] = substr($line, 7);
			} elseif (substr($line, 0, 4) === 'tree') {
				$info['tree'] = substr($line, 5);
			} elseif (substr($line, 0, 6) === 'parent') {
				foreach (explode(" ", substr($line, 7)) as $item) {
					$info['parents'][] = Html::a('<span class="">'.$item.'</span>', 
							["commitview", 'id' => $this->repository, 'hash' => $item],
						    ['title' => Yii::t('app', 'Detail')]
						);
				}
			} elseif (preg_match($pattern, $line, $matches) > 0) {
				$info[$matches[1] .'_name'] = $matches[2];
				$info[$matches[1] .'_mail'] = $matches[3];
				$info[$matches[1] .'_stamp'] = $matches[4] + ((intval($matches[5]) / 100.0) * 3600);
				$info[$matches[1] .'_timezone'] = $matches[5];
				$info[$matches[1] .'_utcstamp'] = $matches[4];
				if (isset($conf['mail_filter'])) {
					$info[$matches[1] .'_mail'] = $conf['mail_filter']($info[$matches[1] .'_mail']);
				}
			} elseif (substr($line, 0, 7) == 'subject') {
				strlen($line)>=$this->subjectMaxLength?$info['subject'] = substr($line, 8, $this->subjectMaxLength).'...':$info['subject'] = substr($line, 8);
			} else {
				$info['message'] .= $line.' <br>';
			}
		}
		if (array_key_exists('author_stamp', $info)) {
			$info['author_datetime'] = strftime($this->datetimeFormat, $info['author_utcstamp']);
			$info['author_datetime_local'] = strftime($this->datetimeFormat, $info['author_stamp']) .' '. $info['author_timezone'];
		}
		if (array_key_exists('committer_stamp', $info)) {
			$info['committer_datetime'] = strftime($this->datetimeFormat, $info['committer_utcstamp']);
			$info['committer_datetime_local'] = strftime($this->datetimeFormat, $info['committer_stamp']) .' '. $info['committer_timezone'];
		}
		return $info;
	}

	/**
	 *  Obtiene los archivos modificados segun el hash ingresado
	 */
	public function getChangedPaths($hash) {
		$result = array();
		$affected_files = $this->run_git("diff --name-only $hash^ $hash");
		if (empty($affected_files)) {
			$affected_files = $this->run_git("show --pretty='format:' --name-only $hash");
		}
		foreach ($affected_files as $file) {
			if ($file == '') {
				continue;
			}
			$output = $this->run_git("ls-tree -l --full-tree $hash '$file'");
			if (empty($output)) {
				$output = $this->run_git("ls-tree -l --full-tree -r $hash^ '$file'");
			}
			foreach ($output as $line) {
				$parts = preg_split('/\s+/', $line, 5);
				$result[] = array(
					'name' => $parts[4], 
					'mode' => $parts[0], 
					'type' => $parts[1], 
					'hash_file' => $parts[2], 
					'size'=>$parts[3], 
					'link'=> array(
						Html::a('<span class="">View</span>', 
							[$parts[1], 'id' => $this->repository, 'hash' => $hash, "hash_file"=>$parts[2]],
						    ['title' => Yii::t('app', 'Detail')]
						),
						Html::a('<span class="">Compare</span>', 
							["commitview", 'id' => $this->repository, 'hash' => $hash, "hash_file"=>$parts[2]],
						    ['title' => Yii::t('app', 'Detail')]
						),
					),
				);
			}
		}
		return $result;
	}
	
	/**
	 * 
	 */
	public function getTree($treeish,$hash) {
		$command = "ls-tree -l --full-tree $treeish ";
		foreach ($this->run_git($command) as $list) 
		{
			$parts = preg_split('/\s+/', $list, 5);
			$result[] = array(
				'name' => $parts[4], 
				'mode' => $parts[0], 
				'type' => $parts[1], 
				'hash_file' => $parts[2], 
				'size'=>$parts[3],
				'link'=> array(
					Html::a('<span class="">View</span>', 
							[$parts[1], 'id' => $this->repository, 'hash' => $hash, $parts[1]=="tree"?"tree":"hash_file"=>$parts[2]],
						    ['title' => Yii::t('app', 'Detail')]
						),
					//'<a href="'.Yii::app()->createUrl("repositorio/".$parts[1],array("id"=>$this->repositoryPath, "hash"=>$hash, $parts[1]=="tree"?"tree":"hash_file"=>$parts[2])).'">Ver</a>',
				),

			);
		}
		return $result;
	}

	/**
	 * Funcion para mostrar todas las referencias de los commits
	 */
	public function getShowRef($project = null, $tags = true, $heads = true, $remotes = true) {
		$cmd = "show-ref --dereference";
		if (!$remotes) {
			if ($tags) 
				$cmd .= " --tags";
			if ($heads)
				$cmd .= " --heads";
		}
		$result = array();
		$output = $this->run_git($cmd,$project);
		foreach ($output as $line) {
			// <hash> <ref>
			$parts = explode(' ', $line, 2);
			$name = str_replace(array('refs/', '^{}'), array('', ''), $parts[1]);
			$result[]= array('h'=>$parts[0], 'ref' => $name);
		}
		return $result;
	}

	/**
	 * Obtiene las referencias a partir de un hash en formato html
	 */
	public function getNameRev($hash) {
		$output = $this->run_git("show-ref -d | grep $hash");
		$items=array();
		$result = '';
		$type='';
		foreach ($output as $line) {
			// <hash> <ref>
			$part = explode(' ', $line, 2);
			if ($part[0]==$hash) {
				$name = str_replace(array('refs/', '^{}'), array('', ''), $part[1]);
				if (substr($name, 0, 4)=="tags") {
					$type = "tags";
					$name = str_replace("/","",substr($name,4));
				} elseif (substr($name, 0, 7)=="remotes") {
					$type = "remote";
					$name = str_replace("/",": ",substr($name,8));
				}
				else {
					$type = "branch";
					$name = str_replace("/","",substr($name,5));
				}
				$items[]= array('h'=>$part[0], 'type' => $type, 'name'=>$name);
			}
		}
		foreach ($items as $item) {
			if (!empty($item['type'])) {
				$result .= "<span class='git-".$item['type']."'>".$item['name']."</span>";
			}
		}
		return $result;
	}

	/**
	 * 
	 */
	public function showDiffPath($hash,$hash_file) {
		$path = $this->showNameHashFile($hash_file);
		$output = $this->run_git("diff $hash^..$hash -- '$path'");
		if (empty($output)) {
			throw new NotFoundHttpException("No se puede mostrar los detalles del archivo: '$path' ($hash_file), en la versión: $hash.");
		} else {
			return $this->formatDiff($output);
		}
	}

	/**
	 * 
	 */
	public function showDiffCommit($hash_from,$hash_to) {
		$output = $this->run_git("diff \"hash_from..$hash_to\"");
		return $this->formatDiff($output);
	}	

	/**
	 * 
	 */
	public function formatDiff($text) {
        $output = array();
        $output['info'] = '';
        /*
			old mode <mode>
       		new mode <mode>
       		new file mode <mode>
       		deleted file mode <mode>
       		copy from <path>
       		copy to <path>
       		rename from <path>
       		rename to <path>
       		similarity index <number>
       		dissimilarity index <number>
       		index <hash>..<hash> <mode>
        */
		foreach ($text as $item) {
            if ('diff' === substr($item, 0, 4)) {
				preg_match('#^diff --git a/(.*) b/(.*)$#', $item, $matches);
				$output['name'] = $matches[1];
				$output['info'] .= "<pre>".$item."</pre>";
			} elseif (('new file'===substr($item,0,8)) || ('old mode'===substr($item,0,8)) || 
					('new mode'===substr($item,0,8)) || ('deleted file'===substr($item,0,12)) || 
					('index' === substr($item, 0, 5))) {
				$output['info'] .= "<pre>".$item."</pre>";
			} elseif ('Binary files'===substr($item,0,12)) {
                $output['contents'][] = array('lineNumOld'=> "-", 'lineNumNew'=> "-", 'lineCode' => "<pre class='chunk'>".$item."</pre>",);
			} elseif ('@@' === substr($item, 0, 2)) {
                preg_match('/@@ -([0-9]+)/', $item, $matches);
                $lineNumOld = $matches[1] - 1;
                $lineNumNew = $matches[1] - 1;
                $output['contents'][] = array('lineNumOld'=> "-", 'lineNumNew'=> "-", 'lineCode' => "<pre class='chunk'>".$item."</pre>",);
			} elseif ('---' === substr($item, 0, 3)) {
				$output['info'] .= "<pre class='old'>".$item."</pre>";
			} elseif ('+++' === substr($item, 0, 3)) {
				$output['info'] .= "<pre class='new'>".$item."</pre>";
			} elseif ('\ ' === substr($item, 0, 2)) {
                $output['contents'][] = array('lineNumOld'=> "-", 'lineNumNew'=> "-", 'lineCode'=> "<pre>".htmlspecialchars($item)."</pre>",);
			} elseif ('-' === substr($item, 0, 1)) {
                $lineNumOld++;
                $output['contents'][] = array('lineNumOld'=> $lineNumOld, 'lineNumNew'=> "-", 'lineCode'=> "<pre class='old'>".htmlspecialchars($item)."</pre>",);
            } elseif ('+' === substr($item, 0, 1)) {
                $lineNumNew++;
                $output['contents'][] = array('lineNumOld'=> "-", 'lineNumNew'=> $lineNumNew, 'lineCode'=> "<pre class='new'>".htmlspecialchars($item)."</pre>",);
            } else {
                $lineNumNew++;
                $lineNumOld++;
                $output['contents'][] = array('lineNumOld'=> $lineNumOld, 'lineNumNew'=> $lineNumNew, 'lineCode'=> "<pre>".htmlspecialchars($item)."</pre>",);
			}
		}
		return $output;
	}

	/**
	 * Show blob file
	 */
	public function showBlobFile($hash_file) {
		$output = array();
		$output['name'] = $this->showNameHashFile($hash_file);
		$command = "cat-file blob $hash_file";
		$output['contents'] = $this->run_git($command);
     	return $output;
	}

	/**
	 * Show name of hash file
	 */
	public function showNameHashFile($hash_file) {
		$command = "rev-list --objects --all | grep $hash_file";
		$name = '';
		foreach (($this->run_git($command)) as $item) {
			$output = explode(' ', $item,2);
			if ($output['0']==$hash_file) {
				$name = $output['1'];
			}
		}
		return $name;		
	}

	/**
	 * Get blade file, opon para los view tree
	 */
	//public function getBlame($file) {
    //    $blame = array();
    //    $logs = $this->getClient()->run($this, "blame -s $file");
    //    $logs = explode("\n", $logs);
	//
    //    $i = 0;
    //    $previousCommit = '';
    //    foreach ($logs as $log) {
    //        if ($log == '') {
    //            continue;
    //        }
	//
  	//          preg_match_all("/([a-zA-Z0-9^]{8})\s+.*?([0-9]+)\)(.+)/", $log, $match);
	//
  	//          $currentCommit = $match[1][0];
    //        if ($currentCommit != $previousCommit) {
    //          ++$i;
    //        $blame[$i] = array('line' => '', 'commit' => $currentCommit);
    //  }
	//
  	//          $blame[$i]['line'] .= PHP_EOL . $match[3][0];
    //        $previousCommit = $currentCommit;
    //  }
	//
  	//      return $blame;
    //}

	/**
	 * Gets a list of tags in this branch
	 */
	public function getTags() {
		$tag = array();
		foreach($this->run_git("tag -l") as $item) {
			$tag[]= $this->showtag($item);
		}
		return $tag;
	}

	/**
	 * Verificar un Tag
	 */
	public function showtag($tag) {
		$tags = array();
		$tags['message'] = '';
		$pattern = '/^(tagger) ([^<]+) <([^>]*)> ([0-9]+) (.*)$/';
		foreach($this->run_git("tag -v ".$tag) as $line) {
			if (substr($line, 0, 6) === 'object') {
				$tags['h'] = substr($line, 7);
			} elseif (substr($line, 0, 4) === 'type') {
				$tags['type'] = substr($line, 5);
			} elseif (substr($line, 0, 6) === 'tagger') {
				preg_match($pattern, $line, $matches);
				$tags['name'] = $matches[2];
				$tags['mail'] = $matches[3];
				$tags['stamp'] = $matches[4] + ((intval($matches[5]) / 100.0) * 3600);
				$tags['timezone'] = $matches[5];
				$tags['utcstamp'] = $matches[4];
				$tags['datetime'] = strftime($this->datetimeFormat, $tags['utcstamp']);
				$tags['datetime_local'] = strftime($this->datetimeFormat, $tags['stamp']) .' '. $tags['timezone'];
			} elseif (substr($line, 0, 3) === 'tag') {
				$tags['tag'] = substr($line, 4);
			} elseif (!empty($line)) {
				$tags['message'] .= $line.'<br>';
			}
		}
		$tags['message_short'] =  strlen($tags['message'])>=$this->subjectMaxLength?substr($tags['message'],0,$this->subjectMaxLength).'...':$tags['message'];
		return $tags;
	}
	
	/**
	 * Verifica si un hash existe en rev list
	 */
	public function verifyHash($hash) {
		$command = "rev-list --all | grep $hash";
		return $this->run_git($command);
	}

	/**
	 * Gets a list of git branches
	 */
	public function getBranches() {
		$branches = array();
		foreach($this->run_git("branch") as $branchName) {
			$branches[] = array('branch' => substr($branchName, 2), 'active' => substr($branchName,0,2)=="* "?true:false);
		}
		return $branches;
	}

	/**
	 * Graficar el log de un repositorio comands git
	 */
	public function showGraphLog() {
		$command = "log --graph --abbrev-commit --decorate --format=format:'<a href=?hash=%H><b>%h</b></a> - <font color=blue>%aD (%ar)</font> <span class=GITref>%d</span>%n''          <i>%s</i> - <b>%an</b>' --all";
		$output = $this->run_git($command);
		return implode("\n", $output);
	}

	/*
	 *
	 */
	public function getTotalCommits() {
	 	$command = "rev-list --all $file | wc -l";
		return	$this->run_git($command);
	}

	/**
     * Obtener los disparadores disponibles en el repsoitorio con su contenido
	 */
	public function getHooks() {
		$Hooks = array();
		if ($dir = opendir($this->cotainerPath.$this->project."/hooks/")) {
        	while (($file = readdir($dir)) !== false) {
        		if (($file !=='.') && ($file !=='..')) {
        			$Hooks[] = array('name' => $file, 'contents'=> file_get_contents($this->cotainerPath.$this->repositoryPath.'/hooks/'.$file));
        		}
        	}
        	closedir($dir);
    	}
        return $Hooks;
	}

	/**
     * Get all, git configuration variable
	 */
    public function getConfigAll($global = false) {
		$config = array();
    	if ($global) {
	        $output = $this->run_git("config --global -l");
    	} else {
	        $output = $this->run_git("config --local -l");
    	}
		if (!empty($output)) {
        	foreach ($output as $item) {
        		$matches = explode("=", $item);
        		$config[] =  array('key'=>$matches[0], 'value'=>$matches[1], );
        	}
		}
        return $config;
	}

	/**
     * Get a git configuration variable
     */
    public function getConfig($key, $global = false) {
    	if ($global)
	        $key = $this->run_git("config --global $key");
		else
	        $key = $this->run_git("config $key");
        return $key;
    }

	/**
	 * Execute comands git
	 */
	protected function run_git($command, $repository = null) {
		$output = array();
		if ($repository == null) {
			$cmd = $this->gitPath." --git-dir=".escapeshellarg($this->repositoryPath)." $command";
		} else {
			$cmd = $this->gitPath." --git-dir=". escapeshellarg($this->cotainerPath . $repository) ." $command";
		}
		$ret = 0;
		exec($cmd, $output, $ret);
		return $output;
	}
}