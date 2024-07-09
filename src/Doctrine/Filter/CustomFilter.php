<?php
namespace App\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class CustomFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // Add logic to filter the query
        if ($targetEntity->name != 'App\Entity\Request\GrantRequest') {
            return '';
        }

        $field = $this->getParameter('status');
        return sprintf('%s.status = %s', $targetTableAlias, $field);
    }
}
?>