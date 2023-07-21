<?php

/**
 * Class Model
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class Model
{
    var $table_name = '';
    var $is_admin = false;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->setTableName($this->table_name);
    }

    /**
     * Returns the current table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * Sets the table name.
     *
     * @param string $table_name
     */
    public function setTableName($table_name = '')
    {
        $this->table_name = trim($table_name);
    }

    /**
     * Fetch records.
     *
     * @param array $where
     * @param array $order_by
     * @param array $limit
     * @param bool $return_count
     * @param array $just_these_attributes
     *
     * @return array|int
     */
    public function getRecords(array $where = [], array $order_by = [], array $limit = [], $return_count = false, $just_these_attributes = [])
    {
        $dump = false;
        if (array_key_exists('_dump', $where)) {
            $dump = true;
            unset($where['_dump']);
        }

        $sql = 'SELECT ';
        if (false === $return_count) {
            if (is_array($just_these_attributes) && count($just_these_attributes)) {
                $attributes_count = count($just_these_attributes);
                foreach ($just_these_attributes as $a_i => $attribute) {
                    $sql .= '`' . $this->getTableName() . '`.`' . $attribute . '`';
                    if (($a_i+1) < $attributes_count) {
                        $sql .= ', ';
                    } else {
                        $sql .= " ";
                    }
                }
            } else {
                $sql .= '`' . $this->getTableName() . '`.*';
            }

            if (array_key_exists('_select', $where)) {
                foreach ($where['_select'] AS $subselect) {
                    $sql .= $subselect;
                }
            }
        } else {
            $sql .= 'COUNT(*) AS `count` ';
        }
        unset($where['_select']);

        $sql .= ' FROM `' . $this->getTableName() . '` AS `' . $this->getTableName() . '`';

        $input_parameters = [];

        if (count($where)) {
            $i = 0;
            foreach ($where as $column => $clause) {
                if (0 === $i) {
                    $sql .= " WHERE ";
                } else {
                    if ('or_where' === $column) {
                        $sql .= " OR ";
                    } else {
                        $sql .= " AND ";
                    }
                }

                if("find_in_set" === $column) {
                    $c_i = 0;
                    foreach ($clause as $sub_column => $sub_clause) {
                        if (is_array($sub_clause) && count($sub_clause)) {
                            $limit = count($sub_clause);
                            foreach ($sub_clause as $k => $sub_sub_clause) {
                                if ($k && $k < $limit) {
                                    $sql .= ' AND';
                                }
                                $sql .= " FIND_IN_SET(" . $sub_sub_clause . ",`" . $sub_column . "`)";
                                $i++;
                            }
                        }

                        if (!is_array($sub_clause) && strlen($sub_clause)) {
                            if ($c_i) {
                                $sql .= " AND";
                            }
                            $sql .= " FIND_IN_SET(" . $sub_clause . ",`" . $sub_column . "`)";
                        }
                        $c_i++;
                    }
                } elseif('in' === $column) {
                    foreach ($clause as $sub_column => $sub_clause) {
                        if (strlen($sub_clause)) {
                            $sql .= "`" . $sub_column . "` IN(" . $sub_clause . ")";
                        }
                    }
                } elseif('or_where' === $column) {
                    $sub_clause_count = count($clause);
                    if (is_array($clause) && count($clause) > 1) {
                        $sql .= '(';
                        $i = 1;
                        foreach ($clause as $sub_column => $sub_clause) {
                            if (in_array(substr($sub_clause, 0, 1), ['>', '<'])) {
                                $sql .= "`" . $sub_column . "` " . substr($sub_clause, 0, 1) . " :ow_" . $sub_column;
                                $input_parameters['ow_' . $sub_column] = trim(substr($sub_clause, 1, (strlen($sub_clause)-1)));
                            } elseif(in_array(substr($sub_clause, 0, 1), ['%'])) {
                                $sub_clause = substr($sub_clause, 0, strlen($sub_clause));
                                $sub_clause = trim(str_replace('%','',$sub_clause));
                                $sql .= "`" . $sub_column . "` LIKE :ow_" . $sub_column;
                                $input_parameters['ow_' . $sub_column] = "%" . $sub_clause . "%";
                            } elseif(in_array(substr($sub_clause, 0, 1), ['~'])) {
                                $sub_clause = substr($sub_clause, 0, strlen($sub_clause));
                                $sub_clause = trim(str_replace('~','',$sub_clause));
                                $sql .= "`" . $sub_column . "` LIKE :ow_" . $sub_column;
                                $input_parameters['ow_' . $sub_column] = $sub_clause . "%";
                            } else {
                                if (in_array($sub_clause, ['NULL', '']) && $sub_clause !== 0) {
                                    $sql .= "`" . $sub_column . "` IS NULL";
                                } elseif(in_array($sub_clause, ['NOT NULL']) && $sub_clause !== 0) {
                                    $sql .= "`" . $sub_column . "` IS NOT NULL";
                                } else {
                                    $sql .= "`" . $sub_column . "` = :ow_" . $sub_column;
                                    $input_parameters['ow_' . $sub_column] = $sub_clause;
                                }
                            }
                            if ($i < $sub_clause_count) {
                                $sql .= ' OR ';
                            }

                            $i++;
                        }
                        $sql .= ') ';
                    }
                } else {
                    if (in_array(substr($clause, 0, 1), ['>', '<'])) {
                        $sql .= "`" . $column . "` " . substr($clause, 0, 1) . " :" . $column;
                        $input_parameters[$column] = trim(substr($clause, 1, (strlen($clause)-1)));
                    } elseif(in_array(substr($clause, 0, 1), ['%'])) {
                        $clause = substr($clause, 0, strlen($clause));
                        $clause = trim(str_replace('%','',$clause));
                        $sql .= "`" . $column . "` LIKE :" . $column;
                        $input_parameters[$column] = "%" . $clause . "%";
                    } elseif(in_array(substr($clause, 0, 1), ['~'])) {
                        $clause = substr($clause, 0, strlen($clause));
                        $clause = trim(str_replace('~','',$clause));
                        $sql .= "`" . $column . "` LIKE :" . $column;
                        $input_parameters[$column] = $clause . "%";
                    } else {
                        if (in_array($clause, ['NULL', '']) && $clause !== 0) {
                            $sql .= "`" . $column . "` IS NULL";
                        } elseif(in_array($clause, ['NOT NULL']) && $clause !== 0) {
                            $sql .= "`" . $column . "` IS NOT NULL";
                        } else {
                            $sql .= "`" . $column . "` = :" . $column;
                            $input_parameters[$column] = $clause;
                        }
                    }
                }

                $i++;
            }
        }

        if (false === $return_count) {
            if (count($order_by)) {
                if (
                    (
                        array_key_exists(0, $order_by) ||
                        array_key_exists(1, $order_by)
                    )
                ) {
                    if ("RAND()" === $order_by[0]) {
                        $sql .= ' ORDER BY RAND()';
                    } else {
                        $sql .= ' ORDER BY `' . $order_by[0] . '` ' . strtoupper($order_by[1]);
                    }
                } else {
                    $ak = array_keys($order_by);
                    $av = array_values($order_by);
                    $sql .= ' ORDER BY `' . $ak[0] . '` ' . strtoupper($av[0]);
                }
            } else {
                $sql .= ' ORDER BY `id` DESC';
            }

            if (is_array($limit) && count($limit)) {
                if (array_key_exists(0, $limit)) {
                    $limit['page'] = $limit[0];
                }
                if (array_key_exists(1, $limit)) {
                    $limit['results_per_page'] = $limit[1];
                }

                if (array_key_exists('page', $limit)) {
                    if ($limit['page'] <= 1) {
                        $record_limit = 0;
                    } else {
                        $record_limit = (($limit['page']-1)*$limit['results_per_page']);
                    }
                    $sql .= ' LIMIT ' . $record_limit . ',' . $limit['results_per_page'];
                } else {
                    $sql .= ' LIMIT ' . $limit['page'];
                }
            }
        }

        if (true === $dump) {
            Core::dd($sql);
        }

        $req = Database::getBDD()->prepare($sql);
        $req->execute($input_parameters);
        $results = $req->fetchAll(PDO::FETCH_ASSOC);

        if (true === $return_count) {
            if (array_key_exists(0, $results)) {
                return (int) $results[0]['count'];
            }

            return 0;
        }

        return $results;
    }

    /**
     * Returns record count.
     *
     * @return mixed
     */
    public function getCount()
    {
        $sql = 'SELECT COUNT(*) AS `count` FROM `' . $this->getTableName() . '`;';
        $req = Database::getBDD()->prepare($sql);
        $req->execute();

        $result = $req->fetch();

        return (int) $result['count'];
    }

    /**
     * Get entity by ID.
     *
     * @param $id
     * @param string $field
     * @param array $where
     *
     * @return array
     */
    public function get($id, $field = 'id', array $where = [])
    {
        $input_parameters = [];

        $sql = 'SELECT `' . $this->getTableName() . '`.*';

        if (array_key_exists('_select', $where)) {
            foreach ($where['_select'] AS $subselect) {
                $sql .= $subselect;
            }

            unset($where['_select']);
        }

        $sql .= ' FROM `' . $this->getTableName() . '` WHERE `' . $this->getTableName() . '`.`' . $field . '` = :id';

        $input_parameters['id'] = $id;

        $req = Database::getBDD()->prepare($sql);
        $req->execute($input_parameters);
        $result = $req->fetchAll();

        if (
            is_array($result) &&
            1 === count($result) &&
            array_key_exists(0, $result)
        ) {
            return $result[0];
        }

        return [];
    }

    /**
     * Fetch entity by slug.
     *
     * @param string $slug
     * @param array $where
     *
     * @return array|false
     */
    public function getBySlug($slug = '', array $where = [])
    {
        $input_parameters = [];

        $sql = 'SELECT `' . $this->getTableName() .'`.*';

        if (array_key_exists('_select', $where)) {
            foreach ($where['_select'] AS $subselect) {
                $sql .= $subselect;
            }

            unset($where['_select']);
        }

        $sql .= ' FROM `' . $this->getTableName() . '` AS `' . $this->getTableName() . '` WHERE `slug` = :slug';

        if (
            in_array($this->getTableName(), ['recipe', 'blog'])
        ) {
            $sql .= ' OR `old_slug` = :oldslug';
            $input_parameters['oldslug'] = trim($slug);
        }

        if (strlen($slug)) {
            $input_parameters['slug'] = trim($slug);

            if (count($where)) {
                foreach ($where as $column => $clause) {
                    $sql .= " AND ";

                    if (in_array($clause, ['NULL', '']) && $clause !== 0) {
                        $sql .= "`" . $column . "` IS NULL";
                    } else {
                        $sql .= "`" . $column . "` = :" . $column;
                        $input_parameters[$column] = $clause;
                    }
                }
            }

            $sql .= ' ORDER BY `id` ASC';

            $req = Database::getBDD()->prepare($sql);
            $req->execute($input_parameters);
            $result = $req->fetchAll();

            if (
                is_array($result) &&
                array_key_exists(0, $result)
            ) {
                return $result[0];
            }
        }

        return false;
    }

    /**
     * Update record values.
     *
     * @param $id
     * @param varchar $identifier
     * @param array $values
     *
     * @return bool
     */
    public function update($id, array $values = [], $identifier = 'id')
    {
        foreach ($values as $column => $value) {
            $sql = "UPDATE `" . $this->getTableName() . "` SET `" . $column . "` = ? WHERE `" . $identifier . "` = " . (int) $id;

            $req = Database::getBdd()->prepare($sql);
            try {
                $req->execute([$value]);
            } catch (\Exception $e) {
                if (SessionHelper::hasPermission('is_admin')) {
                    dd($e->getMessage());
                }
            }
        }

        return true;
    }

    /**
     * Create a new record.
     *
     * @param array $values
     *
     * @return bool
     */
    public function create(array $values = [])
    {
        $sql = "INSERT INTO `" . $this->getTableName() . "` (";

        $total_values = count($values);

        $i = 1;
        foreach ($values as $column => $value) {
            $sql .= '`' . $column . '`';

            if ($i < $total_values) {
                $sql .= ',';
            }

            $i++;
        }

        $sql .= ") VALUES (";

        $i = 1;
        foreach ($values as $column => $value) {
            $sql .= ':'. $column . '';

            if ($i < $total_values) {
                $sql .= ',';
            }

            $i++;
        }

        $sql .= ")";

        $req = Database::getBDD()->prepare($sql);

        $req->execute($values);

        return (int) Database::getBDD()->lastInsertId();
    }

    /**
     * Deletes a record.
     *
     * @param $id
     * @param string $identifier
     *
     * @return bool
     */
    public function delete($id, string $identifier = 'id'): bool
    {
        $sql = 'DELETE FROM `' . $this->getTableName() .'` WHERE `' . $identifier . '` = ?';
        $req = Database::getBDD()->prepare($sql);
        return $req->execute([(int) $id]);
    }

    /**
     * Increment entity views.
     *
     * @param array $entity
     * @return bool
     */
    public function incrementViews(array $entity = [])
    {
        if (!SessionHelper::hasPermission('is_admin')) {
            if (is_array($entity) && count($entity) && array_key_exists('id', $entity))
            {
                $input_parameters = ['id' => $entity['id']];

                $sql = "UPDATE `" . $this->getTableName() . "` SET `views` = `views` +1 WHERE `id` = :id";
                $req = Database::getBDD()->prepare($sql);
                $req->execute($input_parameters);

                return true;
            }
        }

        return false;
    }

    /**
     * Increment entity comment count.
     *
     * @param array $entity
     * @return bool
     */
    public function updateCommentCount(array $entity = [])
    {
        if (is_array($entity) && count($entity) && array_key_exists('id', $entity))
        {
            $page_type = $this->getTableName();
            $id = (int) $entity['id'];

            if (array_key_exists('page_type', $entity)) {
                $page_type = $entity['page_type'];
                $id = (int) $entity['entity_id'];
            }

            $input_parameters = [
                'id'        => $id,
                'page_type' => $page_type,
                'status'    => "approved"
            ];

            $sql = "UPDATE `" . $page_type . "` SET `total_comments` = (SELECT COUNT(*) AS `cnt` FROM `comment` WHERE `entity_id` = :id AND `page_type` = :page_type AND `status` = :status) WHERE `id` = :id";

            // dd($input_parameters, $sql);

            $req = Database::getBDD()->prepare($sql);
            $req->execute($input_parameters);

            // dd($req->debugDumpParams());

            return true;
        }

        return false;
    }
}