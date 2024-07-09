<?php

namespace App\Service;

use App\Entity\Grant;
use App\Entity\GrantCategory;
use App\Entity\GrantLocation;
use App\Entity\GrantOpenTo;
use App\Entity\UserCompany;

class SuggestionService
{
    public function __construct(
        private readonly OpenApiClient $openApiClient,
    ) {}

    public function suggest(UserCompany $company, Grant $grant)
    {
        $locationsList = implode(', ', $grant->getLocations()->map(fn (GrantLocation $l) => $l->getLocation())->toArray());
        $openTo = implode(', ', $grant->getOpenTo()->map(fn (GrantOpenTo $l) => $l->getOpenTo())->toArray());
        $categories = implode(', ', $grant->getCategories()->map(fn (GrantCategory $l) => $l->getCategory())->toArray());

        return ($this->openApiClient)()->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Act as a Grant Project Suggester for our organization.
                    You will be provided with list grant description. I want the project you suggest to be focused only on that grant.
                    Based on it you will provide detailed suggestions for potential grant project that align with our mission and goals.
                    I don't need budget suggestions or other funding sources.
                    Your suggestions should include the project's title, brief description, targeted population. 
                    Please ensure that the ideas are original, innovative, and tailored to our specific needs.
                    Include answers to this:
                    - What the project is?
                    - Why the project is important?
                    - How it will be delivered?
                    - Delivery timeline. For example is it a 45 week programme consisting of 2 hour sessions per week? Or a one off event?
                    - Outcomes of the project.
                    Don't add note",
                ],
                [
                    'role' => 'user',
                    'content' => "Company description: {$company->getDescription()}",
                ],
                [
                    'role' => 'user',
                    'content' => "Grant description: {$grant->getDescription()}",
                ],
                [
                    'role' => 'user',
                    'content' => "Grant Locations: {$locationsList}",
                ],
                [
                    'role' => 'user',
                    'content' => "Grant open to: {$openTo}",
                ],
                [
                    'role' => 'user',
                    'content' => "Grant categories: {$categories}",
                ],
                [
                    'role' => 'user',
                    'content' => "Grant funding details: {$grant->getFundingDetails()}",
                ],
                [
                    'role' => 'user',
                    'content' => "How to apply to grant: {$grant->getHowToApply()}",
                ],
                [
                    'role' => 'user',
                    'content' => "Who to apply to grant: {$grant->getWhoCanApply()}",
                ],
                [
                    'role' => 'user',
                    'content' => 'Suggest grant project for our organization. Add as much details as possible.',
                ],
            ],
        ]);
    }
}
