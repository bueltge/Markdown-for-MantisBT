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

require_once __DIR__ . '/../Filter.php';

/**
 * Implements &lt;em&gt; and &lt;strong&gt;
 *
 * Definitions:
 * <ul>
 *   <li>text wrapped with one * or _ will be wrapped with an HTML &lt;em&gt; tag</li>
 *   <li>double *’s or _’s will be wrapped with an HTML &lt;strong&gt; tag</li>
 *   <li>the same character must be used to open and close an emphasis span</li>
 *   <li>emphasis can be used in the middle of a word</li>
 *   <li>if an * or _ is surrounded by spaces,
 *      it’ll be treated as a literal asterisk or an underscore</li>
 * </ul>
 *
 * @package Markdown
 * @subpackage Filter
 * @author Max Tsepkov <max@garygolden.me>
 * @version 1.0
 */
class Filter_Emphasis extends Filter
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
        foreach ($text as $no => &$line) {
            if ($text->lineflags($no) & Text::NOMARKDOWN) continue;

            // strong
            $line = preg_replace(
                '/(?<!\\\\)(\*\*|__)(?=\S)(.+?[*_]*)(?<=\S)(?<!\\\\)\1/u',
                '<strong>$2</strong>',
                $line
            );

            // emphasis
            $line = preg_replace(
                '/(?<!\\\\)([*_])(?!\s)(.+?)(?<![\\\\\s])\1/u',
                '<em>$2</em>',
                $line
            );
        }

        return $text;
    }
}
