<?php

require_once "myException.php";

class Elem {
	private $element;
	private $content = [];
	private $attributes = [];


	public function __construct($element, $content = "", $attributes = []) {
		$validElements = ["meta", "img", "hr", "br", "html", "head", "body", "title", "h1", "h2", "h3", "h4", "h5", "h6", "p", "span", "div", "tr", "td", "th", "ul", "ol", "li", "table"];
		if (!in_array($element, $validElements)) {
			throw new myException("Invalid HTML element: " . $element);
		}
		$this->element = $element;
		if ($content !== "") {
			$this->content[] = $content;
		}
		if (!empty($attributes) && is_array($attributes)) {
			$this->attributes = $attributes;
		}
		elseif (!empty($attributes) && !is_array($attributes)) {
			throw new myException("Attributes must be an associative array.");
		}
	}

	public function pushElement($element) {
		$this->content[] = $element;
	}

	private function renderAttributes() {
		$attrString = "";
		foreach ($this->attributes as $key => $value) {
			$attrString .= " " . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
		}
		return $attrString;
	}

	public function getHTML() {
		$selfClosing = ["meta", "img", "hr", "br"];
		$attrString = $this->renderAttributes();
		$html = "<{$this->element}{$attrString}>";
		if (in_array($this->element, $selfClosing)) {
			return $html;
		}
		foreach ($this->content as $item) {
			if ($item instanceof Elem) {
				$html .= $item->getHTML();
			}
			else {
				$html .= htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
			}
		}
		$html .= "</{$this->element}>";
		return $html;
	}
}

?>