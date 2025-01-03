<?php

namespace App\Service;

class Tokenizer
{
    public function preprocessText(string $text): string
    {
        // Lowercase the text
        $text = strtolower($text);

        // Remove punctuation
        $text = preg_replace(['#[[:punct:]]#', '/\s+/'], ['', ' '], $text);

        // Tokenize the text
        $tokens = explode(' ', $text);

        // Define stop words
        $stop_words = ['a', 'about', 'above', 'after', 'again', 'against', 'all', 'am', 'an', 'and', 'any', 'are', 'as', 'at', 'be', 'because', 'been', 'before', 'being', 'below', 'between', 'both', 'but', 'by', 'could', 'did', 'do', 'does', 'doing', 'down', 'during', 'each', 'few', 'for', 'from', 'further', 'had', 'has', 'have', 'having', 'he', "he'd", "he'll", "he's", 'her', 'here', "here's", 'hers', 'herself', 'him', 'himself', 'his', 'how', "how's", 'i', "i'd", "i'll", "i'm", "i've", 'if', 'in', 'into', 'is', 'it', "it's", 'its', 'itself', "let's", 'me', 'more', 'most', 'my', 'myself', 'nor', 'of', 'on', 'once', 'only', 'or', 'other', 'ought', 'our', 'ours', 'ourselves', 'out', 'over', 'own', 'same', 'she', "she'd", "she'll", "she's", 'should', 'so', 'some', 'such', 'than', 'that', "that's", 'the', 'their', 'theirs', 'them', 'themselves', 'then', 'there', "there's", 'these', 'they', "they'd", "they'll", "they're", "they've", 'this', 'those', 'through', 'to', 'too', 'under', 'until', 'up', 'very', 'was', 'we', "we'd", "we'll", "we're", "we've", 'were', 'what', "what's", 'when', "when's", 'where', "where's", 'which', 'while', 'who', "who's", 'whom', 'why', "why's", 'with', 'would', 'you', "you'd", "you'll", "you're", "you've", 'your', 'yours', 'yourself', 'yourselves'];

        // Remove stop words
        $tokens = array_diff($tokens, $stop_words);

        return implode(' ', $tokens);
    }

    public function tokenize(string $text, int $chunk = 200)
    {
        $normalizedText = preg_replace("/\n+/", "\n", $text);
        $words = array_filter(explode(' ', $normalizedText));

        return array_chunk($words, $chunk);
    }
}
