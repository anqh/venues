<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Venues_Index
 *
 * @package    Venues
 * @author     Antti Qvickström
 * @copyright  (c) 2012 Antti Qvickström
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
class View_Venues_Index extends View_Section {

	/**
	 * @var  Model_Venue[]
	 */
	public $venues;


	/**
	 * Create new view.
	 *
	 * @param  Model_Venue[]  $venues
	 */
	public function __construct($venues) {
		parent::__construct();

		$this->venues = $venues;
	}


	/**
	 * Group venues by city.
	 *
	 * @return  array
	 */
	private function _group_by_city() {
		$cities = array();

		if ($this->venues && count($this->venues)) {
			foreach ($this->venues as $venue) {
				$city = Text::capitalize($venue->city_name);

				if (!isset($cities[$city])) {
					$cities[$city] = array();
				}

				$cities[$city][] = $venue;
			}

		}

		return $cities;
	}


	/**
	 * Render view.
	 *
	 * @return  string
	 */
	public function content() {
		ob_start();

		foreach ($this->_group_by_city() as $city => $venues):

?>

<article>
	<header>
		<h4><?= HTML::chars($city) ?></h4>
	</header>

	<ul class="unstyled block-grid two-up">
	<?php foreach ($venues as $venue): ?>

		<li><?= HTML::anchor(Route::model($venue), HTML::chars($venue->name)) ?></li>

	<?php endforeach; ?>
	</ul>

</article>

<?php

		endforeach;

		return ob_get_clean();
	}

}
