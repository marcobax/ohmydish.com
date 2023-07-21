<?php

/**
 * Class AdminStatsModel
 *
 * @author Marco Bax <marco@ohmydish.nl?
 */
class AdminStatsModel extends Model
{
    var $table_name = 'admin_stats';

    public function increment($identifier, $section)
    {
        $records = $this->getRecords(['identifier' => $identifier, 'section' => $section]);

        if (is_array($records) && count($records) && array_key_exists(0, $records)) {
            $record = $records[0];
            $this->update($record['id'], [
                'value' => ((int) $record['value'] + 1)
            ]);

            return true;
        } else {
            $this->create([
                'identifier' => $identifier,
                'section' => $section,
                'value' => 1
            ]);

            return true;
        }
    }

    public function clear($identifier, $section)
    {
        $records = $this->getRecords(['identifier' => $identifier, 'section' => $section]);

        if (is_array($records) && count($records) && array_key_exists(0, $records)) {
            $record = $records[0];
            $this->update($record['id'], [
                'value' => 0
            ]);

            return true;
        }

        return false;
    }
}