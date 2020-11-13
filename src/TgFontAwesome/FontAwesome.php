<?php

namespace TgFontAwesome;

/**
 * Provides the free FontAwesome library.
 */
class FontAwesome {

    public const CSS = "css";

    public const JS = "js";

	/**
	 * Returns the version of this library.
	 * @return string the version number
	 */
	public static function getVersion() {
		$changes = file_get_contents(__DIR__.'/../../../../fortawesome/font-awesome/CHANGELOG.md');
		if ($changes !== FALSE) {
			$matches = array();
			if (preg_match_all('/\\[([\\d\\.]+)\\]/', $changes, $matches)) {
				return $matches[1][0];
			}
		}
		throw new FontAwesomeException('Cannot detect Font Awesome version');
	}

    /**
     * Returns the URI from where the font library can be downloaded.
     *
     * @param string $module
     *            - module to return (optional, default is all.min)
     * @param string $type
     *            - type of link, can be CSS or JS (optional, default is CSS)
     * @return string the URI to the FontAwesome library
     */
    public static function getUri($module = 'all.min', $type = self::CSS) {
        $localPath = realpath(__DIR__.'/../../../../fortawesome/font-awesome');
        
        if ($type == self::CSS) {
            $localPath .= '/css/'.$module.'.css';
        } else if ($type == self::JS) {
            $localPath .= '/js/'.$module.'.js';
        } else {
            throw new FontAwesomeException('Unknown link type', $type);
        }
        
        if (!file_exists($localPath)) {
            throw new FontAwesomeException('Unknown FontAwesome version', $localPath);
        }
        
        // Contextualize
		$docRoot = $_SERVER['CONTEXT_DOCUMENT_ROOT'] ? $_SERVER['CONTEXT_DOCUMENT_ROOT'] : $_SERVER['_DOCUMENT_ROOT'];
		$local   = '/';
		if (strpos($localPath, $docRoot) === 0) $local = substr($localPath, strlen($docRoot));
		else throw new FontAwesomeException('Cannot compute local path', $localPath);
		
		return $local;
    }

    /**
     * Returns the HTML tag for a web page.
     *
     * @param string $module
     *            - module to return (optional, default is all.min)
     * @param string $type
     *            - type of link, can be CSS or JS (optional, default is CSS)
     * @return string HTML link to the FontAwesome library as a HTML tag
     */
    public static function getLink($module = 'all.min', $type = self::CSS) {
        $uri = self::getUri($module, $type);
        
        $rc = NULL;
        if ($type == self::CSS) {
            $rc = '<link rel="stylesheet" href="'.$uri.'">';
        } else if ($type == self::JS) {
            $rc = '<script type="text/javascript src="'.$uri.'"></script>';
        }
        return $rc;
    }
}
