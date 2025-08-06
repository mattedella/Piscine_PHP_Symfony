<?php

class Elem {
	private $element;
	private $content = [];

	public function __construct($element, $content = "") {
		$validElements = ["meta", "img", "hr", "br", "html", "head", "body", "title", "h1", "h2", "h3", "h4", "h5", "h6", "p", "span", "div"];
		if (!in_array($element, $validElements)) {
			throw new InvalidArgumentException("Invalid HTML element: " . $element);
		}
		$this->element = $element;
		if ($content !== "") {
			$this->content[] = $content;
		}
	}

	public function pushElement($element) {
		$this->content[] = $element;
	}

	public function getHTML() {
		$selfClosing = ["meta", "img", "hr", "br"];
		$html = "<{$this->element}>";
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