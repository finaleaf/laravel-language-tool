<?php

	namespace App\Http\Controllers;

	use App\Http\Controllers\Controller;

	class LangsController extends Controller
	{
		private $depth = 0;
		private $string = "";
		private $default = "ko"; //change this

		public function langs()
		{
			$langs = [];
			foreach(\File::files(\App::langPath()."/".$this->default) as $lang_file)
			{
				$lang = pathinfo($lang_file, PATHINFO_FILENAME);
				$langs[$lang] = \Lang::get($lang, [], $this->default);
			}

			return view("langs", ["langs" => $langs]);
		}

		public function data()
		{
			$files = \Storage::files("lang");
			foreach($files as $file)
			{
				if(pathinfo($file, PATHINFO_EXTENSION) != "csv")
				{
					continue;
				}
				$this->string = "";
				$data = $this->convert($file);
				echo "<b style='font-size:14pt;background-color:#bbac3b'>";
				echo(pathinfo($file, PATHINFO_FILENAME).".php");
				echo "</b>";
				echo "<pre>\n return ";
				echo substr(trim($this->printArray($data)), 0, -1).";";
				echo "</pre><br/><br/>";
			}
		}

		/**
		 * csv 2 array
		 * @param $file
		 * @return array
		 */
		private function convert($file)
		{
			$csv = \Storage::get($file);
			$data = explode("\r\n", $csv);
			if(substr($data[0], 0, 4) == "idx1") //Remove if header row
			{
				unset($data[0]);
			}

			$return = [];
			foreach($data as $row)
			{
				$arr = explode(",", $row);
				if(!$arr[0] || empty($arr))
				{
					continue;
				}

				array_walk($arr, function (&$row)
				{
					$row = trim($row);
				});

				if(!$arr[1] && !$arr[2]) //only 1 depth
				{
					$return[$arr[0]] = $arr[3];
				}
				else if(!$arr[2] && $arr[1]) //2 depth
				{
					if(!array_key_exists($arr[0], $return))
					{
						$return[$arr[0]] = [];
					}
					$return[$arr[0]][$arr[1]] = $arr[3];
				}
				else  //all depth
				{
					if(!array_key_exists($arr[0], $return))
					{
						$return[$arr[0]] = [];
					}
					else if(!array_key_exists($arr[1], $return[$arr[0]]))
					{
						$return[$arr[0]][$arr[1]] = [];
					}
					$return[$arr[0]][$arr[1]][$arr[2]] = $arr[3];
				}
			}

			return $return;
		}

		private function printArray($array)
		{

			$this->string .= "[\r\n";
			$this->depth++;
			foreach($array as $key => $item)
			{

				if(empty($item))
				{
					continue;
				}

				for($i = 0; $i < $this->depth; $i++)
				{
					$this->string .= "\t";
				}
				if(!is_array($item))
				{
					$this->string .= "'".$key."' => '".addslashes($item)."',\r\n";
				}
				else
				{
					$this->string .= "'".$key."' => ";
					$this->printArray($item);
					$this->string .= "\r\n";
				}
			}
			for($i = 0; $i < $this->depth - 1; $i++)
			{
				$this->string .= "\t";
			}
			$this->depth--;
			$this->string .= "],";

			return $this->string;
		}

	}
