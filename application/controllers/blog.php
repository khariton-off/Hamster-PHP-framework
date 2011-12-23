<?php
class blogController extends Hamster {
	function index() {
		echo 'Blog';
	}
	function hello($url) {
		switch (@$url[0]) {
			case '':
				echo 'Hello';
			break;
			case 'hi':
				switch (@$url[1]) {
					case '':
						echo 'Hi';
					break;
					case 'good':
						echo 'Good';
					break;
					default:
						echo 'Yarr hi!';
					break;
				}
			break;
			default:
				echo 'Yarr!';
			break;
		}
	}
}