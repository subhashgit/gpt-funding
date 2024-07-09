<?php

namespace App\Model\Scraper\Contractsfinder;

class ListItem
{
    public string $id;
    public string $title;
    public string $link;
    public string $description;
    public string $subheader;
    public Entry $entry;
}
