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

use Markdown\ListStack;

require_once __DIR__ . '/../Filter.php';

/**
 * Abstract class for all list's types
 *
 * Definitions:
 * <ul>
 *   <li>list items may consist of multiple paragraphs</li>
 *   <li>each subsequent paragraph in a list item
 *      must be indented by either 4 spaces or one tab</li>
 * </ul>
 *
 * @todo Readahead list lines and pass through blockquote and code filters.
 * @package Markdown
 * @subpackage Filter
 * @author Max Tsepkov <max@garygolden.me>
 * @version 1.0
 */
abstract class Filter_List extends Filter
{
    /**
     * Pass given text through the filter and return result.
     *
     * @see Filter::filter()
     * @param string $text
     * @return string $text
     */
    public function filter(Text $text)
    {
        require_once __DIR__ . '/List/Stack.php';
        $stack = new ListStack();

        foreach ($text as $no => $line)
        {
            $prevLine = isset($text[$no - 1]) ? $text[$no - 1] : null;
            $nextLine = isset($text[$no + 1]) ? $text[$no + 1] : null;

            // match list marker, add a new list item
            if (($marker = $this->matchMarker($line)) !== false)
            {
                if (!$stack->isEmpty() && $prevLine !== null && self::isBlank($prevLine)) {
                    $stack->paragraphize();
                }

                $stack->addItem(array($no => substr($line, strlen($marker))));

                continue;
            }

            // we are inside a list
            if (!$stack->isEmpty())
            {
                // a blank line
                if (self::isBlank($line)) {
                    // two blank lines in a row
                    if ($prevLine !== null && self::isBlank($prevLine)) {
                        // end of list
                        $stack->apply($text, static::TAG);
                    }
                }
                // not blank line
                else {
                    if (self::isIndented($line)) {
                        // blockquote
                        if (substr(ltrim($line), 0, 1) == '>') {
                            $line = substr(ltrim($line), 1);
                            if (substr(ltrim($prevLine), 0, 1) != '>') {
                                $line = '<blockquote>' . $line;
                            }
                            if (substr(ltrim($nextLine), 0, 1) != '>') {
                                $line .= '</blockquote>';
                            }
                        }
                        // codeblock
                        else if (substr($line, 0, 2) == "\t\t" || substr($line, 0, 8) == '        ') {
                            $line = ltrim(htmlspecialchars($line, ENT_NOQUOTES));
                            if (!(substr($prevLine, 0, 2) == "\t\t" || substr($prevLine, 0, 8) == '        ')) {
                                $line = '<pre><code>' . $line;
                            }
                            if (!(substr($nextLine, 0, 2) == "\t\t" || substr($nextLine, 0, 8) == '        ')) {
                                $line .= '</code></pre>';
                            }
                        }
                        else if (self::isBlank($prevLine)) {
                            // new paragraph inside a list item
                            $line = '</p><p>' . ltrim($line);
                        }
                        else {
                            $line = ltrim($line);
                        }
                    }
                    else if (self::isBlank($prevLine)) {
                        // end of list
                        $stack->apply($text, static::TAG);
                        continue;
                    }
                    // unbroken text inside a list item
                    else {
                        // add text to current list item
                        $line = ltrim($line);
                    }

                    $stack->appendLine(array($no => $line));
                }
            }
        }

        // if there is still stack, flush it
        if (!$stack->isEmpty()) {
            $stack->apply($text, static::TAG);
        }

        return $text;
    }

    abstract protected function matchMarker($line);
}
