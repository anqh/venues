<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Venue Category model
 *
 * @package    Venues
 * @author     Antti Qvickström
 * @copyright  (c) 2010 Antti Qvickström
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
class Anqh_Model_Venue_Category extends Jelly_Model implements Permission_Interface {

	/**
	 * Permission to add new venue
	 */
	const PERMISSION_VENUE = 'venue';


	/**
	 * Create new model
	 *
	 * @param  Jelly_Meta  $meta
	 */
	public static function initialize(Jelly_Meta $meta) {
		$meta
			->sorting(array('name' => 'DESC'))
			->fields(array(
				'id' => new Field_Primary,
				'name' => new Field_String(array(
					'label'  => __('Category'),
					'unique' => true,
					'rules'  => array(
						'not_empty'  => null,
						'max_length' => array(32),
					),
				)),
				'description' => new Field_String(array(
					'label' => __('Description'),
					'rules' => array(
						'max_length' => array(250),
					),
				)),
				'author' => new Field_BelongsTo(array(
					'column'  => 'author_id',
					'foreign' => 'user',
				)),
				'created' => new Field_Timestamp(array(
					'auto_now_create' => true,
				)),

				'tag_group' => new Field_BelongsTo(array(
					'label' => __('Tag group'),
					'null'  => true,
				)),
				'venues'    => new Field_HasMany,
		));
	}


	/**
	 * Load venues grouped by city
	 *
	 * @return  array
	 */
	public function find_venues_by_city() {
		$venues = array();
		foreach ($this->venues as $venue) {
			$city = $venue->city_name;
			if (!isset($venues[$city])) {
				$venues[$city] = array($venue);
			} else {
				$venues[$city][] = $venue;
			}
		}

		return $venues;
	}


	/**
	 * Check permission
	 *
	 * @param   string      $permission
	 * @param   Model_User  $user
	 * @return  boolean
	 */
	public function has_permission($permission, $user) {
		$status = false;

		switch ($permission) {

			case self::PERMISSION_CREATE:
			case self::PERMISSION_DELETE:
			case self::PERMISSION_UPDATE:
		    $status = $user && $user->has_role('admin');
		    break;

			case self::PERMISSION_VENUE:
		    $status = $user && $user->loaded();
		    break;

			case self::PERMISSION_READ:
		    $status = true;

		}

		return $status;
	}

}