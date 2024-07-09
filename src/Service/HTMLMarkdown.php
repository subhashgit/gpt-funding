<?php

namespace App\Service;

use League\HTMLToMarkdown\Converter\ConverterInterface;
use League\HTMLToMarkdown\ElementInterface;
use League\HTMLToMarkdown\HtmlConverter;

class HTMLMarkdown
{
    public $title;
    private $converter;

    public function __construct()
    {
        $this->converter = new HtmlConverter([
            'strip_tags' => true,
            'strip_placeholder_links' => true,
        ]);
    }

    public function handle($htmlContent)
    {
        $cleanHtml = $this->cleanHtml($htmlContent);

        $this->converter->getEnvironment()->addConverter(new PreTagConverter());
        $markdownContent = $this->converter->convert($cleanHtml);

        return $this->reverseLTGT($markdownContent);
        // Usefull for debugging.
        // Log::info($cleanHtml);
        // Log::info($markdownContent);
        //        try {
        //            $dom = new Crawler($htmlContent);
        //            $this->title = $dom->filter('title')->first()->text();
        //        } catch (\Exception $e) {
        //            $this->title = substr($markdownContent, 0, strpos($markdownContent, "\n"));
        //        }
    }

    private function removeHrefAttribute($htmlString)
    {
        $pattern = '/<a\b[^>]*\bhref\s*=\s*"[^"]*"[^>]*>/i';
        $replacement = '<a>';

        return preg_replace($pattern, $replacement, $htmlString);
    }

    private function cleanHtml($htmlContent)
    {
        // The content inside this tag is usually is not useful
        // Clean tags: <style> <script> <span> <footer> <aside> <nav> <picture> <svg> <form>
        $cleanHtml = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $htmlContent);
        $cleanHtml = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<svg\b[^>]*>(.*?)<\/svg>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<picture\b[^>]*>(.*?)<\/picture>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<form\b[^>]*>(.*?)<\/form>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<footer\b[^>]*>(.*?)<\/footer>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<nav\b[^>]*>(.*?)<\/nav>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<span[^>]*>(.*?)<\/span>/is', '$1', $cleanHtml);
        $cleanHtml = $this->removeHrefAttribute($cleanHtml);

        return trim($cleanHtml);
    }

    private function reverseLTGT($input)
    {
        $output = str_replace('&lt;', '<', $input);

        return str_replace('&gt;', '>', $output);
    }
}

class PreTagConverter implements ConverterInterface
{
    public function convert(ElementInterface $element): string
    {
        $preContent = \html_entity_decode($element->getChildrenAsString());
        $preContent = strip_tags($preContent, 'pre');
        $preContent = \str_replace(['<pre>', '</pre>'], '', $preContent);

        /*
         * Checking for the code tag.
         * Usually pre tags are used along with code tags. This conditional will check for already converted code tags,
         * which use backticks, and if those backticks are at the beginning and at the end of the string it means
         * there's no more information to convert.
         */

        $firstBacktick = \strpos(\trim($preContent), '`');
        $lastBacktick = \strrpos(\trim($preContent), '`');
        if (0 === $firstBacktick && $lastBacktick === \strlen(\trim($preContent)) - 1) {
            return $preContent."\n\n";
        }

        // If the execution reaches this point it means it's just a pre tag, with no code tag nested

        // Empty lines are a special case
        if ('' === $preContent) {
            return "```\n```\n\n";
        }

        // Normalizing new lines
        $preContent = \preg_replace('/\r\n|\r|\n/', "\n", $preContent);
        \assert(\is_string($preContent));

        // Ensure there's a newline at the end
        if (\strrpos($preContent, "\n") !== \strlen($preContent) - \strlen("\n")) {
            $preContent .= "\n";
        }

        // Use three backticks
        return "```\n".$preContent."```\n\n";
    }

    /**
     * @return string[]
     */
    public function getSupportedTags(): array
    {
        return ['pre'];
    }
}
