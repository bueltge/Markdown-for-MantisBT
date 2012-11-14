<?php
/**
 * Copyright (C) 2011, Maxim S. Tsepkov
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Markdown;

/**
 * Superclass of all filters.
 *
 * Provides static methods to configure and use filtering system.
 *
 * @package Markdown
 * @subpackage Filter
 * @author Max Tsepkov <max@garygolden.me>
 * @version 1.0
 */
abstract class Filter
{
    protected static $_defaultFilters = null;

    protected static $_factoryDefaultFilters = array(
        'Hr',
        'ListBulleted',
        'ListNumbered',
        'Blockquote',
        'Code',
        'Emphasis',
        'Entities',
        'HeaderAtx',
        'HeaderSetext',
        'Img',
        'Linebreak',
        'Link',
        'Paragraph',
        'Unescape'
    );

    /**
     * List of characters which copies as is after \ char.
     *
     * @var array
     */
    protected static $_escapableChars = array(
        '\\', '`', '*', '_', '{', '}', '[', ']',
        '(' , ')', '#', '+', '-', '.', '!'
	);

    /**
     * Block-level HTML tags.
     *
     * @var array
     */
    protected static $_blockTags = array(
        'p', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'pre',
        'table', 'dl', 'ol', 'ul', 'script', 'noscript', 'form', 'fieldset',
        'iframe', 'math', 'ins', 'del', 'article', 'aside', 'header', 'hgroup',
        'footer', 'nav', 'section', 'figure', 'figcaption'
    );

    /**
     * Lookup Filter_{$filtername} class and return its instance.
     *
     * @throws InvalidArgumentException
     * @param string $filtername
     * @return Filter
     */
    public static function factory($filtername)
    {
        if (is_string($filtername) && ctype_alnum($filtername)) {
            $file  = __DIR__ . '/Filter/' . $filtername . '.php';
            $class = __NAMESPACE__ . '\\Filter_'   . $filtername;

            if (is_readable($file)) {
                require_once $file;

                if (class_exists($class)) {
                    return new $class;
                }
                else {
                    throw new \InvalidArgumentException(
                        'Could not find class ' . $class
                    );
                }
            }
            else {
                throw new \InvalidArgumentException($file . ' is not readable');
            }
        }
        else {
            throw new \InvalidArgumentException(sprintf(
                '$filtername must be an alphanumeric string, <%s> given.',
                gettype($filtername)
            ));
        }
    }

    public static function getFactoryDefaultFilters()
    {
        return self::$_factoryDefaultFilters;
    }

    /**
     * @return array
     */
    public static function getDefaultFilters()
    {
        if (!self::$_defaultFilters) {
            self::$_defaultFilters = self::getFactoryDefaultFilters();
        }

        return self::$_defaultFilters;
    }

    /**
     * @param array $filters
     * @return Filter
     */
    public static function setDefaultFilters(array $filters)
    {
        self::$_defaultFilters = $filters;
    }

    /**
     * Pass given $text through $filters chain and return result.
     * Use default filters in no $filters given.
     *
     * @param string $text
     * @param array $filters optional
     * @return string
     */
    public static function run($text, array $filters = null)
    {
        if(!$text instanceof Text) {
            $text = new Text($text);
        }

        if ($filters === null) {
            $filters = self::getDefaultFilters();
        }

        foreach ($filters as $filter) {
            if ($filter instanceof Filter) {
                // do nothing
            }
            elseif (is_string($filter)) {
                $filter = self::factory($filter);
            }
            else {
                throw new \InvalidArgumentException(
                    '$filters must be an array which elements ' .
                    'is either a string or Filter'
                );
            }

            $filter->preFilter($text);
            $filter->filter($text);
            $filter->postFilter($text);
        }

        return $text;
    }

    /**
     * Remove one level of indentation
     *
     * @static
     * @param string
     * @return string
     */
    protected static function outdent($text)
    {
        return preg_replace('/^(\t| {1,4})/m', '', $text);
    }

    protected static function isBlank($line)
    {
        return (empty($line) || preg_match('/^\s*$/uS', $line));
    }

    protected static function isIndented($line)
    {
        if (!is_string($line)) {
            return false;
        }
        if (isset($line[0]) && $line[0] == "\t") {
            return true;
        }
        if (substr($line, 0, 4) == '    ') {
            return true;
        }
        else {
            return false;
        }
    }

    abstract public function filter(Text $text);

    public function preFilter(Text $text) {}
    public function postFilter(Text $text) {}
}
