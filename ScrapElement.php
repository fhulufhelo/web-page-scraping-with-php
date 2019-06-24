<?php

/**
 *  this class scans a website $url  for an Html tag $tag
 * return a multi dimensional array of values for these tags
 * -- The array key = the line where number found
 * -- The value = an associateve array
 * ---- Key/Value pairs where key = the attribute
 * ---- The key "content" what's between the open and close tags
 */

use DOMDocument;

class ScrapElement
{
	// contents of the website
	protected $content = null;

	/**
	 * populates $conents from $url
	 *
	 * @param string $url = website to scan
	 * @return DOMElement $content
	*/

	public function getContent($url)
	{
		if ( stripos($url, 'http') !==0) {
			$url = 'http://' . $url;
		}

		$this->content = new DOMDocument('1.0');
		$this->content->preserveWhiteSpace = false;
		// @ used to suppress warnings generated from improperly configured web pages
		@$this->content->loadHTMLFile($url);
		return $this->content;
	}

	/**
	 * Returns an array of values for $tag from $url
	 * Tags with content == <tag>content</tag>
	 *
	 * @param string $url = website to scan
	 * @param string $tag = tag to extract
	 * @return array $result
	*/

	public function getTags($url, $tag)
	{
		$count = 0;
		$result = array();
		$node_list = $this->getContent($url)->getElementsByTagName($tag);


		foreach ($node_list as $node) {

			
			$result[$count]['value'] = trim(preg_replace('/\s+/', '', $node->nodeValue));

			if ($node->hasAttributes()) {

				foreach ($node->attributes as $name => $attrNode) {
					$result[$count]['attributes'][$name] = $attrNode->value;
				}
			}

			$count++;
		}
		return $result;
	}


	/**
	 * Returns an array of values for $attr from $url
	 * Tags with content == <tag>content</tag>
	 *
	 * @param string $url = website to scan
	 * @param string $tag = tag to extract
	 * @param string $domain = [optional] Dns domain to include in the return array
	 * @return array $result
	*/

	public function getAttribute($url, $attr, $domain = null)
	{
		$result = array();
		$elements = $this->getContent($url)->getElementsByTagName('*');

		foreach ($elements as $node) {
			if ($node->hasAttributes($attr)) {
				$value = $node->getAttribute($attr);
			}

			if ($domain) {
				if (stripos($value, $domain)) {
					$result[] = trim($value);
				}
				else {
					$result[] = trim($value);
				}
			}
		}

		return $result;
	}


}


$hoover = new ScrapElement();

$getContent = $hoover->getTags('https://thebuyersguide.co.za', 'a');

print_r($getContent);


