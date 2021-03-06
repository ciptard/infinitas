<?php
	class ReleaseShell extends Shell {
		public $tasks = array('Infinitas', 'InfinitasPlugin', 'InfinitasReleaseStatus');

		public function help(){
			$this->Infinitas->h1('Interactive Release Shell Help');
			$this->Infinitas->p(
				'The interactive shell is for generating a release for your Infinitas '.
				'powered plugin. It will allow you to enter the description and name '.
				'along with setting any dependencies. After you have answered some '.
				'questions all the necesery files will be generated to allow others '.
				'to use the plugin via the installer.'
			);
			$this->Infinitas->p(
				'This shell is also used for creating updates to your plugins by the' .
				'same method as creating the initial release. The release shell will ' .
				'provide you with update options if a previous release has been found.'
			);

			$this->Infinitas->h2('Available Options');

			$this->Infinitas->li(
				array(
					'cake release - For normal plugins',
					'cake release all - If you are creating a core plugin'
				)
			);

			$this->Infinitas->helpPause();
		}

		public function main() {
			do {
				$this->Infinitas->h1('Interactive Release Shell');
				$this->Infinitas->li(
					array(
						'[P]lugin',
						'[M]odule',
						'[T]heme',
						'Plugin [A]dd-on',
						'[S]tatus',
						'[H]elp',
						'[Q]uit'
					)
				);
				$this->Infinitas->br();
				$input = strtoupper($this->in('What do you wish to release?'));

				switch ($input) {
					case 'P':
						$this->InfinitasPlugin->execute();
						break;
					
					case 'M':
						break;

					case 'T':
						break;

					case 'A':
						break;

					case 'S':
						$this->InfinitasReleaseStatus->execute();
						break;

					case 'H':
						$this->help();
						break;

					case 'Q':
						$this->Infinitas->quit();
						break;

					default:
						$this->out('Invalid option');
						break;
				}
			} while($input != 'Q');
		}
	}