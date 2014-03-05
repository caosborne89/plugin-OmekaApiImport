<?php

class Table_ApiRecordIdMap extends Omeka_Db_Table
{
    public function localRecord($recordType, $externalId, $endpointUri)
    {
        $select = $this->getSelect();
        $recordTable = $this->getDb()->getTable($recordType);
        $recordTableAlias = $recordTable->getTableAlias();
        $select = $recordTable->getSelect();

        $select->join(
                    array('api_record_id_maps' => $this->getTableName()),
                    "api_record_id_maps.local_id = $recordTableAlias.id",
                    null
                );
        $select->where('record_type = ?', $recordType);
        $select->where('external_id = ?', $externalId);

        $select->where('endpoint_uri = ?', $endpointUri);
        return $recordTable->fetchObject($select);
    }
    
    public function getSelectForExternalIds()
    {
        $select = new Omeka_Db_Select($this->getDb()->getAdapter());
        $alias = $this->getTableAlias();
        $select->from(array($alias=>$this->getTableName()), "$alias.external_id");
        return $select;
    }    
    
    public function findExternalIdsByParams($params = array())
    {
        $select = $this->getSelectForExternalIds($params);
        $this->applySearchFilters($select, $params);
        $data = $this->getDb()->fetchAssoc($select);
        return $data;
    }
}