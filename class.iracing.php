<?PHP 
class iracing_reader {
	// iRacing Reader Class //
	// Coded by Juliano F. //
	// Version 20180316 //
	
	// Construct //
	public function __construct($email, $pass) {
		
		// Login String username and pass, because iracing has no webapi =( //
		$login_string = "username=".$email."&password=".$pass."&utcoffset=-120&todaysdate="; 
		
		$headers[] = "Accept: */*";
		$headers[] = "Connection: Keep-Alive";

		$cookie_file_path 	= "/tmp/cookies.txt";
		$login_url         	= "https://members.iracing.com/membersite/Login"; 
		global $custid;
		
		$ch = curl_init(); 
		
		// Set the intraclass variables //
		$this->cookie_file_path = $cookie_file_path;
		$this->ch = $ch;
		$this->custid = $custid;
				
		// CURL Options //
		curl_setopt($this->ch, CURLOPT_HTTPHEADER,  $headers);
		curl_setopt($this->ch, CURLOPT_HEADER,  0);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);         
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($this->ch, CURLOPT_COOKIEFILE,$this->cookie_file_path); 
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookie_file_path);
		curl_setopt($this->ch, CURLOPT_POST, 1); 
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $login_string); 

		curl_setopt($this->ch, CURLOPT_URL, $login_url); 
		$content = curl_exec($this->ch); // Execute Login //
	}
	
	private function webcrawl($url) { 
		// We will crawl the required url and return the raw data //
		curl_setopt($this->ch, CURLOPT_URL,$url);
		curl_setopt($this->ch, CURLOPT_POST, false);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, "");
		$answer = curl_exec($this->ch);
		return $answer;
	}
	
	public function getCareerStats() { 
		$url = 'http://members.iracing.com/memberstats/member/GetCareerStats?custid='.$this->custid;
		$careerstats = $this->webcrawl($url);
		$careerstats = json_decode($careerstats, true);
		return $careerstats;
	}
	
	public function getSR($type) {
		// 1 = Asphalt Oval //
		// 2 = Asphalt Road //
		// 3 = Dirt Oval //
		// 4 = Dirt Road / RX //
		
		$url = 'http://members.iracing.com/memberstats/member/GetChartData?custId='.$this->custid.'&catId='.$type.'&chartType=3';
		$data = $this->webcrawl($url);
		$data = json_decode($data, true);
		$data_end = end($data);
		$data_end_value = $data_end[1];
		$lic = substr($data_end_value, 0, 1);
		$sr = substr($data_end_value,1,3);
		$sr = $sr / 100;
		return array($sr, $lic);
	}
	
	public function getCarsDriven() { 
		$url = 'http://members.iracing.com/memberstats/member/GetCarsDriven?custid='.$this->custid;
		$carsdriven = $this->webcrawl($url);
		$carsdriven = json_decode($carsdriven, true);
		return $carsdriver;
	}
	
	public function getLastRacesStats() {
		$url = 'http://members.iracing.com/memberstats/member/GetLastRacesStats?custid='.$this->custid; 
		$getlastOfficialraces = $this->webcrawl($url);
		$getlastOfficialraces = json_decode($carsdriven, true);	
		return $getlastOfficialraces;
	}
	
	public function getMemberProfile() {
		$url = 'http://members.iracing.com/membersite/member/GetMemberProfile?custid='.$this->custid;
		$memberprofile = $this->webcrawl($url);
		$memberprofile = json_decode($memberprofile, true);
		return $memberprofile;
	}
	
	public function getAllRaces() {
		$url = 'http://members.iracing.com/memberstats/member/GetResults?custid='.$this->custid.'&showraces=1&showquals=0&showtts=0&showops=0&showofficial=1&showunofficial=1&showrookie=1&showclassd=1&showclassc=1&showclassb=1&showclassa=1&showpro=1&showprowc=1&lowerbound=0&upperbound=500&sort=start_time&order=desc&format=json&category%5B%5D=1&category%5B%5D=2&category%5B%5D=3&category%5B%5D=4&starttime_low=0&starttime_high=2492985600000';
		$allraces = $this->webcrawl($url);
		$allraces = json_decode($allraces, true);
		return $allraces;
	}
}
?>