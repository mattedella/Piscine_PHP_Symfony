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
		$html .= "</{$this->element}>\n";
		return $html;
	}

	public function validPage() {
		// Helper for recursive validation
		$validate = function($elem, $isRoot = false) use (&$validate) {
			// Rule 1: Root html node
			if ($isRoot) {
				if ($elem->element !== 'html') return false;
				if (count($elem->content) !== 2) return false;
				if (!($elem->content[0] instanceof Elem && $elem->content[0]->element === 'head')) return false;
				if (!($elem->content[1] instanceof Elem && $elem->content[1]->element === 'body')) return false;
				// Validate head and body
				if (!$validate($elem->content[0])) return false;
				if (!$validate($elem->content[1])) return false;
				return true;
			}
			// Rule 2: head must have one title and one meta charset
			if ($elem->element === 'head') {
				$titleCount = 0;
				$metaCharsetCount = 0;
				foreach ($elem->content as $c) {
					if ($c instanceof Elem) {
						if ($c->element === 'title') $titleCount++;
						if ($c->element === 'meta' && isset($c->attributes['charset'])) $metaCharsetCount++;
						if (!in_array($c->element, ['title', 'meta'])) return false;
					} else {
						return false;
					}
				}
				if ($titleCount !== 1 || $metaCharsetCount !== 1) return false;
				return true;
			}
			// Rule 3: p can only contain text
			if ($elem->element === 'p') {
				foreach ($elem->content as $c) {
					if ($c instanceof Elem) return false;
				}
				return true;
			}
			// Rule 4: table only tr, tr only th/td
			if ($elem->element === 'table') {
				foreach ($elem->content as $c) {
					if (!($c instanceof Elem && $c->element === 'tr')) return false;
					if (!$validate($c)) return false;
				}
				return true;
			}
			if ($elem->element === 'tr') {
				foreach ($elem->content as $c) {
					if (!($c instanceof Elem && in_array($c->element, ['th', 'td']))) return false;
				}
				return true;
			}
			// Rule 5: ul/ol only li
			if (in_array($elem->element, ['ul', 'ol'])) {
				foreach ($elem->content as $c) {
					if (!($c instanceof Elem && $c->element === 'li')) return false;
				}
				return true;
			}
			// Recursively validate children
			foreach ($elem->content as $c) {
				if ($c instanceof Elem && !$validate($c)) return false;
			}
			return true;
		};
		return $validate($this, true);
	}
}

?>