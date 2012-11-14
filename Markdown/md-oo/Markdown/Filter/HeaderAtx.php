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
 * Translates ### style headers.
 *
 * Definitions:
 * <ul>
 *   <li>use 1-6 hash characters at the start of the line</li>
 *   <li>number of opening hashes determines the header level</li>
 *   <li>closing hashes don’t need to match the number of hashes used to open</li>
 * </ul>
 *
 * @package Markdown
 * @subpackage Filter
 * @author Max Tsepkov <max@garygolden.me>
 * @version 1.0
 */
class Filter_HeaderAtx extends Filter
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
        foreach($text as $no => &$line) {
            if ($text->lineflags($no) & Text::NOMARKDOWN) continue;

            if (@$line[0] == '#') {
                $line = rtrim($line, '#');
                $level = substr_count($line, '#', 0, min(6, strlen($line)));
                $line = "<h$level>" . trim(substr($line, $level)) . "</h$level>";
            }
        }
    }
}
