<?php
	class @@CLASSNAME@@ {
		public $name = "ShoutcastWelcome";
		
		public function requestHeadersReceived($name, $data) {
			$connection = $data[0];
			$request = $data[1];
			
			Logger::info("Welcoming new client at ".$connection->getHost()." (".$connection->getIP().")");
			Logger::debug($request);
			
			$config = ModuleManagement::getModuleByName("ShoutcastConfig")->getConfig();
			
			$welcome = array();
			$welcome[] = "ICY 200 OK";
			$welcome[] = "Content-Type: audio/mpeg";
			$welcome[] = "icy-notice1: ".$config['notice'];
			$welcome[] = "icy-notice2: Radio-PHP (Based off Modfwango-Server) https://github.com/clayfreeman/Modfwango-Server";
			$welcome[] = "icy-name: ".$config['name'];
			$welcome[] = "icy-genre: ".$config['genre'];
			$welcome[] = "icy-url: ".$config['url'];
			$welcome[] = "icy-pub: 0";
			$welcome[] = "icy-br: ".$config['bitrate'];
			$welcome[] = "icy-metaint: ".intval(((($config['bitrate'] / 8) + 1) * 1024) / (1000000 / __INTERVAL__));
			$welcome[] = null;
			
			foreach ($welcome as $line) {
				$connection->send($line);
			}
			
			ModuleManagement::getModuleByName("ShoutcastStream")->addClient($connection);
		}
		
		public function isInstantiated() {
			EventHandling::registerForEvent("requestHeadersReceivedEvent", $this, "requestHeadersReceived");
			return true;
		}
	}
?>