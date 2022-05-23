<?php

namespace GlimeshClientBuilder\Resolver;

use GlimeshClientBuilder\Schema\Schema;
use GlimeshClientBuilder\Schema\SchemaField;
use GlimeshClientBuilder\Schema\SchemaObject;

/**
 * Resolves Connection objects in the Schema to their corresponding objects
 */
class SchemaConnectionNodeMapResolver
{
    /**
     * Resolves the schema to a connection node map
     *
     * @param Schema $schema The schema to resolve
     *
     * @return array
     */
    public static function resolveSchema(Schema $schema): array
    {
        $connectionEdgeMap = $nodeObjectMap = $connectionNodeMap = [];

        foreach ($schema->schemaObjects as $type) {
            $fields = $type->fields ?? [];
            if (self::isANode($type)) {
                $connectionEdgeMap[$type->name] = self::getEdgeFromConnectionFields($fields);
            }

            if (self::isAnEdge($type)) {
                $nodeObjectMap[$type->name] = self::getNodeFromEdgeFields($fields);
            }
        }

        foreach ($connectionEdgeMap as $connectionName => $edgeName) {
            if (!isset($nodeObjectMap[$edgeName])) {
                throw new \Exception("Edge {$edgeName} is not a node");
            }

            $connectionNodeMap[$connectionName] = $nodeObjectMap[$edgeName];
        }

        return $connectionNodeMap;
    }

    public static function isAnEdge(SchemaObject $field): bool
    {
        return str_ends_with($field->name ?? '', 'Edge');
    }

    public static function isANode(SchemaObject $field): bool
    {
        return str_ends_with($field->name ?? '', 'Connection');
    }

    /**
     * @param SchemaField[] $fields
     */
    protected static function getEdgeFromConnectionFields(array $fields): string
    {
        foreach ($fields as $field) {
            if ($field->name === 'edges') {
                return $field->type->ofType->name;
            }
        }

        throw new \Exception('No edges field found');
    }

    /**
     * @param SchemaField[] $fields
     */
    protected static function getNodeFromEdgeFields(array $fields): string
    {
        foreach ($fields as $field) {
            if ($field->name === 'node') {
                return $field->type->name;
            }
        }

        throw new \Exception('No node field found');
    }
}
