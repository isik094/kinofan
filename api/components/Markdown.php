<?php
namespace api\components;


use League\HTMLToMarkdown\HtmlConverter;

class Markdown
{
    public static function convert($content)
    {
        $content = preg_replace('~<(span)[^>]*>(.+?)</\1>~x', '$2', $content);
        $content = preg_replace('~<(span)[^>]*></\1>~x', '$2', $content);
        $content = preg_replace('~<(div)[^>]*>(.+?)</\1>~x', '$2', $content);
        $converter = new HtmlConverter();
        $content = $converter->convert($content);
        $content = preg_replace('/\\\\_/', '_', $content);

        return $content;
    }
}