<?php
/**
 * @package   CleverStyle CMS
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2011-2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs;

use
	JsonSerializable;

/**
 * Provides next events:
 *  System/general/languages/load
 *  [
 *   'clanguage'        => clanguage
 *   'clang'            => clang
 *   'cregion'          => cregion
 *   'clanguage_en'     => clanguage_en
 *  ]
 *
 * @method static Language instance($check = false)
 */
class Language implements JsonSerializable {
	use Singleton;
	/**
	 * Current language
	 *
	 * @var string
	 */
	public $clanguage;
	/**
	 * callable for time processing
	 *
	 * @var callable
	 */
	public $time;
	/**
	 * For single initialization
	 *
	 * @var bool
	 */
	protected $init = false;
	/**
	 * Local cache of translations
	 *
	 * @var array
	 */
	protected $translation = [];
	/**
	 * Cache to optimize frequent calls
	 *
	 * @var array
	 */
	protected $localized_url = [];
	/**
	 * Set basic language
	 */
	protected function construct () {
		$Core = Core::instance();
		$this->change($Core->language);
	}
	/**
	 * Initialization
	 *
	 * Is called from Config class by system. Usually there is no need to call it manually.
	 */
	function init () {
		$Config = Config::instance(true);
		/**
		 * We need Config for initialization
		 */
		if (!$Config) {
			return;
		}
		/**
		 * @var _SERVER $_SERVER
		 */
		/**
		 * Highest priority - `-Locale` header
		 */
		$language = $this->check_locale_header($Config->core['active_languages']);
		/**
		 * Second priority - URL
		 */
		$language = $language ?: $this->url_language($_SERVER->request_uri);
		/**
		 * Third - `Accept-Language` header
		 */
		$language = $language ?: $this->check_accept_header($Config->core['active_languages']);
		$this->change($language ?: '');
	}
	/**
	 * Does URL have language prefix
	 *
	 * @param false|string $url Relative url, `$_SERVER->request_uri` by default
	 *
	 * @return false|string If there is language prefix - language will be returned, `false` otherwise
	 */
	function url_language ($url = false) {
		$url = $url ?: $_SERVER->request_uri;
		if (isset($this->localized_url[$url])) {
			return $this->localized_url[$url];
		}
		$aliases = $this->get_aliases();
		$clang   = explode('?', $url, 2)[0];
		$clang   = explode('/', trim($clang, '/'), 2)[0];
		if (isset($aliases[$clang])) {
			return $this->localized_url[$url] = $aliases[$clang];
		}
		return false;
	}
	/**
	 * Checking Accept-Language header for languages that exists in configuration
	 *
	 * @param array $active_languages
	 *
	 * @return false|string
	 */
	protected function check_accept_header ($active_languages) {
		/**
		 * @var _SERVER $_SERVER
		 */
		$aliases          = $this->get_aliases();
		$accept_languages = array_filter(
			explode(
				',',
				strtolower(
					strtr($_SERVER->language, '-', '_')
				)
			)
		);
		foreach ($accept_languages as $language) {
			$language = explode(';', $language, 2)[0];
			if (@in_array($aliases[$language], $active_languages)) {
				return $aliases[$language];
			}
		}
		return false;
	}
	/**
	 * Check `*-Locale` header (for instance, `X-Facebook-Locale`) that exists in configuration
	 *
	 * @param array $active_languages
	 *
	 * @return false|string
	 */
	protected function check_locale_header ($active_languages) {
		/**
		 * @var _SERVER $_SERVER
		 */
		$aliases = $this->get_aliases();
		/**
		 * For `X-Facebook-Locale` and other similar
		 */
		foreach ($_SERVER as $i => $v) {
			if (preg_match('/.*_LOCALE$/i', $i)) {
				$language = strtolower($v);
				if (@in_array($aliases[$language], $active_languages)) {
					return $aliases[$language];
				}
				return false;
			}
		}
		return false;
	}
	/**
	 * Get languages aliases
	 *
	 * @return array|false
	 */
	protected function get_aliases () {
		return Cache::instance()->get(
			'languages/aliases',
			function () {
				$aliases      = [];
				$aliases_list = _strtolower(get_files_list(LANGUAGES.'/aliases'));
				foreach ($aliases_list as $alias) {
					$aliases[$alias] = file_get_contents(LANGUAGES."/aliases/$alias");
				}
				return $aliases;
			}
		);
	}
	/**
	 * Get translation
	 *
	 * @param bool|string  $item
	 * @param false|string $language If specified - translation for specified language will be returned, otherwise for current
	 *
	 * @return string
	 */
	function get ($item, $language = false) {
		$language = $language ?: $this->clanguage;
		if (isset($this->translation[$language])) {
			return @$this->translation[$language][$item] ?: ucfirst(str_replace('_', ' ', $item));
		}
		$current_language = $this->clanguage;
		$this->change($language);
		$return = $this->get($item);
		$this->change($current_language);
		return $return;
	}
	/**
	 * Set translation
	 *
	 * @param array|string $item Item string, or key-value array
	 * @param null|string  $value
	 *
	 * @return void
	 */
	function set ($item, $value = null) {
		$translate = &$this->translation[$this->clanguage];
		if (is_array($item)) {
			$translate = $item + ($translate ?: []);
		} else {
			$translate[$item] = $value;
		}
	}
	/**
	 * Get translation
	 *
	 * @param string $item
	 *
	 * @return string
	 */
	function __get ($item) {
		return $this->get($item);
	}
	/**
	 * Set translation
	 *
	 * @param array|string $item
	 * @param null|string  $value
	 *
	 * @return string
	 */
	function __set ($item, $value = null) {
		$this->set($item, $value);
	}
	/**
	 * Change language
	 *
	 * @param string $language
	 *
	 * @return bool
	 */
	function change ($language) {
		/**
		 * Already set to specified language
		 */
		if ($language == $this->clanguage) {
			return true;
		}
		$Config   = Config::instance(true);
		$language = $language ?: $Config->core['language'];
		if (
			!$language ||
			!$this->can_be_changed_to($Config, $language)
		) {
			return false;
		}
		if (!isset($this->translation[$language])) {
			$Cache       = Cache::instance();
			$translation = $Cache->{"languages/$language"};
			if ($translation) {
				$this->translation[$language] = $translation;
			} else {
				/**
				 * `$this->get_translation()` will implicitly change `$this->translation`, so we do not need to assign new translation there manually
				 */
				$Cache->{"languages/$language"} = $this->get_translation($language);
			}
		}
		/**
		 * Change current language to `$language`
		 */
		$this->clanguage = $language;
		_include(LANGUAGES."/$language.php", false, false);
		_header("Content-Language: $this->content_language");
		return true;
	}
	/**
	 * Check whether it is allowed to change to specified language according to configuration
	 *
	 * @param Config $Config
	 * @param string $language
	 *
	 * @return bool
	 */
	protected function can_be_changed_to ($Config, $language) {
		return
			//Config not loaded yet
			!$Config->core ||
			//Set to language that is configured on system level
			$language == $Config->core['language'] ||
			//Set to active language
			(
				$Config->core['multilingual'] &&
				in_array($language, $Config->core['active_languages'])
			);
	}
	/**
	 * Load translation from all over the system, set `$this->translation[$language]` and return it
	 *
	 * @param $language
	 *
	 * @return string[]
	 */
	protected function get_translation ($language) {
		/**
		 * Get current system translations
		 */
		$translation = &$this->translation[$language];
		$translation = file_get_json_nocomments(LANGUAGES."/$language.json");
		$translation = $this->fill_required_translation_keys($translation, $language);
		/**
		 * Set modules' translations
		 */
		foreach (get_files_list(MODULES, false, 'd', true) as $module_dir) {
			if (file_exists("$module_dir/languages/$language.json")) {
				$translation = file_get_json_nocomments("$module_dir/languages/$language.json") + $translation;
			}
		}
		/**
		 * Set plugins' translations
		 */
		foreach (get_files_list(PLUGINS, false, 'd', true) as $plugin_dir) {
			if (file_exists("$plugin_dir/languages/$language.json")) {
				$translation = file_get_json_nocomments("$plugin_dir/languages/$language.json") + $translation;
			}
		}
		Event::instance()->fire(
			'System/general/languages/load',
			[
				'clanguage'    => $language,
				'clang'        => $translation['clang'],
				'cregion'      => $translation['cregion'],
				'clanguage_en' => $translation['clanguage_en']
			]
		);
		/**
		 * If current language was set - append its translation to fill potentially missing keys
		 */
		if ($this->clanguage) {
			$translation = $translation + $this->translation[$this->clanguage];
		}
		return $translation;
	}
	/**
	 * Some required keys might be missing in translation, this functions tries to guess and fill them automatically
	 *
	 * @param string[] $translation
	 * @param string   $language
	 *
	 * @return string[]
	 */
	protected function fill_required_translation_keys ($translation, $language) {
		$translation['clanguage'] = $language;
		if (!isset($translation['clang'])) {
			$translation['clang'] = mb_strtolower(mb_substr($language, 0, 2));
		}
		if (!isset($translation['content_language'])) {
			$translation['content_language'] = $translation['clang'];
		}
		if (!isset($translation['cregion'])) {
			$translation['cregion'] = $translation['clang'];
		}
		if (!isset($translation['clanguage_en'])) {
			$translation['clanguage_en'] = $language;
		}
		$translation['clocale'] = $translation['clang'].'_'.mb_strtoupper($translation['cregion']);
		return $translation;
	}
	/**
	 * Time formatting according to the current language (adding correct endings)
	 *
	 * @param int    $in          time (number)
	 * @param string $type        Type of formatting<br>
	 *                            s - seconds<br>m - minutes<br>h - hours<br>d - days<br>M - months<br>y - years
	 *
	 * @return string
	 */
	function time ($in, $type) {
		if (is_callable($this->time)) {
			$time = $this->time;
			return $time($in, $type);
		} else {
			switch ($type) {
				case 's':
					return "$in $this->seconds";
				case 'm':
					return "$in $this->minutes";
				case 'h':
					return "$in $this->hours";
				case 'd':
					return "$in $this->days";
				case 'M':
					return "$in $this->months";
				case 'y':
					return "$in $this->years";
			}
		}
		return $in;
	}
	/**
	 * Allows to use formatted strings in translations
	 *
	 * @see format()
	 *
	 * @param string $item
	 * @param array  $arguments
	 *
	 * @return string
	 */
	function __call ($item, $arguments) {
		return $this->format($item, $arguments);
	}
	/**
	 * Allows to use formatted strings in translations
	 *
	 * @param string   $item
	 * @param string[] $arguments
	 *
	 * @return string
	 */
	function format ($item, $arguments) {
		return vsprintf($this->get($item), $arguments);
	}
	/**
	 * Formatting date according to language locale (translating months names, days of week, etc.)
	 *
	 * @param string|string[] $data
	 * @param bool            $short_may When in date() or similar functions "M" format option is used, third month "May" have the same short textual
	 *                                   representation as full, so, this option allows to specify, which exactly form of representation do you want
	 *
	 * @return string|string[]
	 */
	function to_locale ($data, $short_may = false) {
		if (is_array($data)) {
			foreach ($data as &$item) {
				$item = $this->to_locale($item, $short_may);
			}
			return $data;
		}
		if ($short_may) {
			$data = str_replace('May', 'May_short', $data);
		}
		$from = [
			'January',
			'February',
			'March',
			'April',
			'May_short',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December',
			'Jan',
			'Feb',
			'Mar',
			'Apr',
			'May',
			'Jun',
			'Jul',
			'Aug',
			'Sep',
			'Oct',
			'Nov',
			'Dec',
			'Sunday',
			'Monday',
			'Tuesday',
			'Wednesday',
			'Thursday',
			'Friday',
			'Saturday',
			'Sun',
			'Mon',
			'Tue',
			'Wed',
			'Thu',
			'Fri',
			'Sat'
		];
		foreach ($from as $f) {
			$data = str_replace($f, $this->get("l_$f"), $data);
		}
		return $data;
	}
	/**
	 * Implementation of JsonSerializable interface
	 *
	 * @return string[]
	 */
	function jsonSerialize () {
		return $this->translation[$this->clanguage];
	}
}
